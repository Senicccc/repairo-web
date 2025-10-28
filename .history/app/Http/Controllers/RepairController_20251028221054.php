<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Repair;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use App\Models\RepairSparepart;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class RepairController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'technician') {
            $repairs = Repair::with(['user', 'payment'])->get();
            return view('staff.cashier', compact('repairs'));
        }

        abort(403, "Technicians should use technicianDashboard().");
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

        return redirect()->route('cashier.dashboard')->with('success', 'Repair record created successfully.');
    }

    public function update(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'technician') {
            $data = $request->validate([
                'diagnosis' => 'nullable|string',
                'cost' => 'nullable|numeric|min:0',
                'status' => 'required|string|in:in_progress,finished',
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
                'status' => 'required|string|in:finished,cancelled,paid',
            ]);

            $repair->update($data);
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
            ->where('status', 'in_progress')
            ->count();

        if ($activeCount >= 6) {
            return redirect()->back()->with('error', "You already have {$activeCount} active jobs.");
        }

        $repair->update([
            'technician_id' => $user->id,
            'technician' => $user->name,
            'status' => 'in_progress',
        ]);

        if ($request->filled('sparepart')) $repair->sparepart = $request->input('sparepart');
        if ($request->filled('diagnosis')) $repair->diagnosis = $request->input('diagnosis');

        $repair->save();

        return redirect()->back()->with('success', 'You have taken the job.');
    }

public function addSparepart(Request $request, $id)
{
    \Log::info('🎯 ADD SPAREPART METHOD CALLED');
    \Log::info('📦 Request Data:', $request->all());

    try {
        $repair = Repair::findOrFail($id);
        Log::info('🔧 Repair Found:', ['id' => $repair->id, 'tracking' => $repair->tracking_id]);

        // Validasi data
        $validated = $request->validate([
            'sparepart_id' => 'nullable|exists:spareparts,id',
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'source' => 'required|in:in_store,customer_owned,external_purchase',
            'category' => 'nullable|string|max:255',
        ]);

        Log::info('✅ Data Validated:', $validated);

        // 1. SIMPAN KE repair_spareparts
        $repairSparepart = new RepairSparepart();
        $repairSparepart->repair_id = $repair->id;
        $repairSparepart->sparepart_id = $validated['sparepart_id'];
        $repairSparepart->name = $validated['name'];
        $repairSparepart->category = $validated['category'];
        $repairSparepart->quantity = $validated['quantity'];
        $repairSparepart->price = $validated['price'];
        $repairSparepart->source = $validated['source'];
        $repairSparepart->save();

        Log::info('💾 RepairSparepart Saved:', ['id' => $repairSparepart->id]);

        // 2. KURANGI STOCK JIKA IN_STORE
        if ($validated['source'] === 'in_store' && $validated['sparepart_id']) {
            $sparepart = Sparepart::find($validated['sparepart_id']);
            if ($sparepart) {
                Log::info('📦 Before Stock Update:', [
                    'sparepart_id' => $sparepart->id,
                    'current_stock' => $sparepart->stock,
                    'quantity_to_decrement' => $validated['quantity']
                ]);
                
                $sparepart->decrement('stock', $validated['quantity']);
                
                Log::info('📉 After Stock Update:', [
                    'new_stock' => $sparepart->stock
                ]);
            } else {
                Log::warning('⚠️ Sparepart not found for stock update:', ['sparepart_id' => $validated['sparepart_id']]);
            }
        }

        // 3. UPDATE REPAIR'S SPAREPART FIELD
        $sparepartsList = RepairSparepart::where('repair_id', $repair->id)
            ->pluck('name')
            ->toArray();
        
        $repair->sparepart = implode(', ', $sparepartsList);
        $repair->save();

        Log::info('🔄 Repair Updated:', ['sparepart_field' => $repair->sparepart]);

        return response()->json([
            'success' => true, 
            'message' => 'Sparepart added successfully!',
            'sparepart_id' => $repairSparepart->id
        ]);

    } catch (\Exception $e) {
        Log::error('❌ Error in addSparepart:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false, 
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
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
            ->where('status', 'in_progress')
            ->with(['user', 'payment'])
            ->get();

        $otherJobs = Repair::where('technician_id', $user->id)
            ->where('status', 'finished')
            ->with(['user', 'payment'])
            ->get();

        $availableJobs = Repair::where('status', 'pending')
            ->with(['user', 'payment'])
            ->get();

        return view('staff.technician', compact('currentJobs', 'availableJobs', 'otherJobs'));
    }
}