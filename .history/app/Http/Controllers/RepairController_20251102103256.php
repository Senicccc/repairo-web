<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Repair;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use App\Models\RepairSparepart;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RepairController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'technician') {
            $repairs = Repair::with(['user', 'payment'])->get();
            
            // Redirect ke admin jika role admin
            if ($user->role === 'admin') {
                return view('admin.repairs', compact('repairs'));
            }
            
            return view('staff.cashier', compact('repairs'));
        }

        abort(403, "Technicians should use technicianDashboard().");
    }

    public function show($id)
    {
        $repair = Repair::with(['user', 'payment', 'repairSpareparts'])->findOrFail($id);
        return view('cashier.repairs.show', compact('repair'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'phone_brand' => 'required|string|max:255',
            'phone_model' => 'required|string|max:255',
            'imei' => 'nullable|string|max:255',
            'complaint' => 'required|string',
            'cost' => 'nullable|numeric',
        ]);

        $lastRepair = Repair::orderBy('id', 'desc')->first();
        $nextNumber = $lastRepair ? str_pad($lastRepair->id + 1, 4, '0', STR_PAD_LEFT) : '0001';
        $trackingId = 'SRV' . now()->format('Ymd') . '-' . $nextNumber;

        $user = User::firstOrCreate(
            ['phone' => $validated['phone']],
            [
                'name' => $validated['name'] ?? 'Customer',
                'email' => strtolower(str_replace(' ', '', $validated['phone'])) . '@repairo.local',
                'password' => bcrypt('password123'),
                'role' => 'user'
            ]
        );

        $repair = new Repair();
        $repair->tracking_id = $trackingId;
        $repair->user_id = $user->id;
        $repair->phone = $validated['phone'];
        $repair->customer_name = $validated['name'] ?? null;
        $repair->phone_brand = $validated['phone_brand'];
        $repair->phone_model = $validated['phone_model'];
        $repair->imei = $validated['imei'] ?? null;
        $repair->complaint = $validated['complaint'];
        $repair->status = 'pending';
        $repair->technician = null;
        $repair->cost = $validated['cost'] ?? null;
        $repair->save();

        // Redirect berdasarkan role
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.repairs.index')->with('success', 'Repair record created successfully.');
        }
        
        return redirect()->route('cashier.dashboard')->with('success', 'Repair record created successfully.');
    }

// Di RepairController, pastikan status flow-nya konsisten:
public function update(Request $request, $id)
{
    $repair = Repair::findOrFail($id);
    $user = Auth::user();

    if ($user->role === 'technician') {
        $data = $request->validate([
            'diagnosis' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,in_progress,diagnosed,waiting_parts,finished,cancelled', // technician bisa set finished
        ]);

        $repair->update([
            'diagnosis' => $data['diagnosis'] ?? $repair->diagnosis,
            'cost' => $data['cost'] ?? $repair->cost,
            'status' => $data['status'], 
            'technician_id' => $user->id,
            'technician' => $user->name,
        ]);

        return response()->json(['success' => true, 'message' => 'Job updated successfully.']);
    }

    if ($user->role === 'cashier') {
        $data = $request->validate([
            'status' => 'required|string|in:finished,paid,cancelled', // cashier bisa set paid
        ]);

        $repair->update($data);
        
        // Jika status diubah jadi paid, buat payment otomatis
        if ($data['status'] === 'paid' && $repair->cost > 0) {
            Payment::create([
                'repair_id' => $repair->id,
                'amount' => $repair->cost,
                'payment_method' => 'cash', // default
                'status' => 'paid',
            ]);

            // Tambah poin loyalitas
            if ($repair->user_id) {
                $loyalty = new LoyaltyController();
                $loyalty->addPoints($repair->user_id, $repair->cost);
            }
        }
        
        return redirect()->back()->with('success', 'Repair status updated.');
    }

    abort(403, 'Unauthorized');
}

    public function claim(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);
        $user = Auth::user();

        if ($repair->technician_id) {
            return redirect()->back()->with('error', 'This job has already been claimed.');
        }

        $activeCount = Repair::where('technician_id', $user->id)
            ->whereIn('status', ['in_progress', 'diagnosed', 'waiting_parts'])
            ->count();

        if ($activeCount >= 6) {
            return redirect()->back()->with('error', "You already have {$activeCount} active jobs.");
        }

        $repair->update([
            'technician_id' => $user->id,
            'technician' => $user->name,
            'status' => 'in_progress',
        ]);

        return redirect()->back()->with('success', 'You have taken the job.');
    }

    public function addSparepart(Request $request, $id)
    {
        Log::info('ADD SPAREPART METHOD CALLED');
        Log::info('Request Data:', $request->all());

        try {
            $repair = Repair::findOrFail($id);

            $validated = $request->validate([
                'sparepart_id' => 'nullable|exists:spareparts,id',
                'name' => 'required|string|max:255',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'source' => 'required|in:in_store,customer_owned,external_purchase',
                'category' => 'nullable|string|max:255',
            ]);

            if ($validated['sparepart_id']) {
                $existing = RepairSparepart::where('repair_id', $repair->id)
                    ->where('sparepart_id', $validated['sparepart_id'])
                    ->where('source', $validated['source'])
                    ->first();
                    
                if ($existing) {
                    Log::warning('Duplicate sparepart detected, updating quantity instead');
                    
                    $existing->quantity += $validated['quantity'];
                    $existing->save();
                    
                    if ($validated['source'] === 'in_store') {
                        $sparepart = Sparepart::find($validated['sparepart_id']);
                        if ($sparepart) {
                            $sparepart->decrement('stock', $validated['quantity']);
                        }
                    }
                    
                    $this->updateRepairSparepartField($repair);
                    
                    return response()->json([
                        'success' => true, 
                        'message' => 'Sparepart quantity updated!',
                        'action' => 'updated',
                        'sparepart_id' => $existing->id
                    ]);
                }
            }

            $repairSparepart = RepairSparepart::create([
                'repair_id' => $repair->id,
                'sparepart_id' => $validated['sparepart_id'],
                'name' => $validated['name'],
                'category' => $validated['category'],
                'quantity' => $validated['quantity'],
                'price' => $validated['price'],
                'source' => $validated['source'],
            ]);

            Log::info('RepairSparepart Saved:', ['id' => $repairSparepart->id]);

            if ($validated['source'] === 'in_store' && $validated['sparepart_id']) {
                $sparepart = Sparepart::find($validated['sparepart_id']);
                if ($sparepart) {
                    $sparepart->decrement('stock', $validated['quantity']);
                    Log::info('📉 Stock decreased:', ['sparepart_id' => $sparepart->id, 'new_stock' => $sparepart->stock]);
                }
            }

            $this->updateRepairSparepartField($repair);

            return response()->json([
                'success' => true, 
                'message' => 'Sparepart added successfully!',
                'sparepart_id' => $repairSparepart->id,
                'action' => 'created'
            ]);

        } catch (\Exception $e) {
            Log::error('Error in addSparepart:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeSparepart($repairId, $sparepartId)
    {
        Log::info('REMOVE SPAREPART METHOD CALLED');
        Log::info('Removing sparepart:', ['repair_id' => $repairId, 'sparepart_id' => $sparepartId]);

        try {
            $repairSparepart = RepairSparepart::where('repair_id', $repairId)
                ->where('id', $sparepartId)
                ->firstOrFail();

            Log::info('Found repair_sparepart:', [
                'id' => $repairSparepart->id,
                'name' => $repairSparepart->name,
                'quantity' => $repairSparepart->quantity,
                'source' => $repairSparepart->source,
                'sparepart_id' => $repairSparepart->sparepart_id
            ]);

            if ($repairSparepart->source === 'in_store' && $repairSparepart->sparepart_id) {
                $sparepart = Sparepart::find($repairSparepart->sparepart_id);
                if ($sparepart) {
                    Log::info('📈 Returning stock:', [
                        'sparepart_id' => $sparepart->id,
                        'current_stock' => $sparepart->stock,
                        'quantity_to_return' => $repairSparepart->quantity
                    ]);
                    
                    $sparepart->increment('stock', $repairSparepart->quantity);
                    
                    Log::info('📊 Stock after return:', [
                        'new_stock' => $sparepart->stock
                    ]);
                }
            }

            $repairSparepart->delete();
            Log::info('RepairSparepart deleted successfully');

            $repair = Repair::find($repairId);
            $this->updateRepairSparepartField($repair);

            return response()->json([
                'success' => true, 
                'message' => 'Sparepart removed successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error in removeSparepart:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function updateRepairSparepartField(Repair $repair)
    {
        $sparepartsList = RepairSparepart::where('repair_id', $repair->id)
            ->pluck('name')
            ->toArray();
        
        $repair->sparepart = implode(', ', $sparepartsList);
        $repair->save();

        Log::info('Repair Updated:', ['sparepart_field' => $repair->sparepart]);
    }

    public function destroy($id)
    {
        $repair = Repair::findOrFail($id);
        $repair->delete();

        return response()->json(['message' => 'Repair deleted successfully.']);
    }

    public function technicianDashboard()
    {
        $user = Auth::user();

        $currentJobs = Repair::where('technician_id', $user->id)
            ->whereIn('status', ['in_progress', 'diagnosed', 'waiting_parts'])
            ->with(['user', 'payment', 'repairSpareparts'])
            ->get();

        $otherJobs = Repair::where('technician_id', $user->id)
            ->whereIn('status', ['finished', 'cancelled'])
            ->with(['user', 'payment', 'repairSpareparts'])
            ->get();

        $availableJobs = Repair::where('status', 'pending')
            ->with(['user', 'payment'])
            ->get();

        return view('staff.technician', compact('currentJobs', 'availableJobs', 'otherJobs'));
    }

    /**
     * Method khusus untuk admin - list repairs
     */
    public function adminIndex()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        
        $repairs = Repair::with(['user', 'payment', 'repairSpareparts'])->get();
        return view('admin.repairs', compact('repairs'));
    }
}