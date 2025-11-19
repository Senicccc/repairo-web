<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Repair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class RepairController extends Controller
{
    /**
     * Display all repairs for cashier dashboard
     */
    public function index()
    {
        $repairs = Repair::with(['user', 'payment'])->get();
        return view('cashier.index', compact('repairs'));
    }

    /**
     * Show the form for creating a new repair (cashier)
     */
    public function create()
    {
        return view('cashier.repairs.create');
    }

    /**
     * Store new repair record (linked by phone number)
     */
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

        $repair = new Repair();
        $repair->tracking_id = $trackingId;
        $repair->phone = $validated['phone'];
        // store customer_name if cashier provided a name
        if (!empty($validated['name'])) {
            $repair->customer_name = $validated['name'];
        }
        // Attach to existing user by phone if present
        $user = User::where('phone', $validated['phone'])->first();
        if ($user) {
            $repair->user_id = $user->id;
        }
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



    /**
     * Display a single repair for cashier view
     */
    public function show($id)
    {
        $repair = Repair::with(['user', 'payment'])->findOrFail($id);
        return view('cashier.repairs.show', compact('repair'));
    }

    /**
     * Update repair details (status, diagnosis, cost)
     */
    public function update(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);

        $data = $request->validate([
            'status' => 'nullable|in:pending,in_progress,finished,cancelled',
            'diagnosis' => 'nullable|string',
            'cost' => 'nullable|numeric',
        ]);

        $repair->update($data);

        return redirect()->back()->with('success', 'Repair updated successfully.');
    }

    /**
     * Technician claims a repair (assigns themselves) or updates job details.
     */
    public function claim(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);

        $user = Auth::user();

        // If DB has technician_id column use it; otherwise fall back to 'technician' string
        if (Schema::hasColumn('repairs', 'technician_id')) {
            // Prevent claiming if already assigned
            if ($repair->technician_id) {
                return redirect()->back()->with('error', 'This job has already been claimed by another technician.');
            }

            // Allow up to 6 in-progress jobs per technician
            $activeCount = Repair::where('technician_id', $user->id)->where('status', 'in_progress')->count();
            if ($activeCount >= 6) {
                return redirect()->back()->with('error', "You already have {$activeCount} active jobs (limit 6). Finish some before claiming another.");
            }

            // Assign the currently authenticated technician
            $repair->technician_id = $user->id;
            $repair->technician = $user->name; // keep readable name as well
            $repair->status = 'in_progress';
        } else {
            // Legacy flow using technician string
            if ($repair->technician) {
                return redirect()->back()->with('error', 'This job has already been claimed by another technician.');
            }

            $activeCount = Repair::where('technician', $user->name)->where('status', 'in_progress')->count();
            if ($activeCount >= 6) {
                return redirect()->back()->with('error', "You already have {$activeCount} active jobs (limit 6). Finish some before claiming another.");
            }

            $repair->technician = $user->name;
            $repair->status = 'in_progress';
        }

        // optional fields from technician
        if ($request->filled('sparepart')) {
            $repair->sparepart = $request->input('sparepart');
        }
        if ($request->filled('diagnosis')) {
            $repair->diagnosis = $request->input('diagnosis');
        }
        if ($request->filled('status')) {
            $repair->status = $request->input('status');
        }

        $repair->save();

        return redirect()->back()->with('success', 'You have taken the job.');
    }

    /**
     * Delete a repair record
     */
    public function destroy($id)
    {
        $repair = Repair::findOrFail($id);
        $repair->delete();

        return response()->json(['message' => 'Repair deleted successfully.']);
    }

    /**
     * Tambah sparepart ke repair (AJAX)
     */
    public function addSparepart(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $repair = Repair::findOrFail($id);
            
            Log::info('=== START ADD SPAREPART ===');
            Log::info('Repair ID: ' . $id);
            Log::info('Request data:', $request->all());

            // Validasi request
            $validated = $request->validate([
                'sparepart_id' => 'nullable|exists:spareparts,id',
                'name' => 'required|string|max:255',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'source' => 'required|in:in_store,customer_owned,external_purchase',
                'category' => 'nullable|string|max:255',
            ]);

            Log::info('Validated data:', $validated);

            // Cek duplikat sparepart (in_store + sparepart_id + source)
            if (!empty($validated['sparepart_id'])) {
                $existing = \App\Models\RepairSparepart::where('repair_id', $repair->id)
                    ->where('sparepart_id', $validated['sparepart_id'])
                    ->where('source', $validated['source'])
                    ->first();
                    
                if ($existing) {
                    Log::info('Existing sparepart found, updating quantity');
                    $existing->quantity += $validated['quantity'];
                    $existing->save();
                    
                    // Update stok jika in_store
                    if ($validated['source'] === 'in_store') {
                        $sparepart = \App\Models\Sparepart::find($validated['sparepart_id']);
                        if ($sparepart) {
                            $sparepart->decrement('stock', $validated['quantity']);
                            Log::info('Stock decremented for sparepart: ' . $validated['sparepart_id']);
                        }
                    }
                    
                    DB::commit();
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Sparepart quantity updated!',
                        'action' => 'updated',
                        'sparepart_id' => $existing->id
                    ]);
                }
            }

            // Buat sparepart baru
            $repairSparepart = \App\Models\RepairSparepart::create([
                'repair_id' => $repair->id,
                'sparepart_id' => $validated['sparepart_id'],
                'name' => $validated['name'],
                'category' => $validated['category'] ?? 'Unknown',
                'quantity' => $validated['quantity'],
                'price' => $validated['price'],
                'source' => $validated['source'],
            ]);

            Log::info('New repair sparepart created:', [
                'id' => $repairSparepart->id,
                'name' => $repairSparepart->name,
                'repair_id' => $repairSparepart->repair_id
            ]);

            // Update stok jika in_store
            if ($validated['source'] === 'in_store' && !empty($validated['sparepart_id'])) {
                $sparepart = \App\Models\Sparepart::find($validated['sparepart_id']);
                if ($sparepart) {
                    $sparepart->decrement('stock', $validated['quantity']);
                    Log::info('Stock decremented for new sparepart: ' . $validated['sparepart_id']);
                }
            }

            DB::commit();

            Log::info('=== SPAREPART ADDED SUCCESSFULLY ===');

            return response()->json([
                'success' => true,
                'message' => 'Sparepart added successfully!',
                'sparepart_id' => $repairSparepart->id,
                'action' => 'created'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('=== ERROR ADDING SPAREPART ===');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            Log::error('Request data: ', $request->all());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save sparepart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove sparepart from repair
     */

    public function adminIndex()
    {
        $repairs = Repair::with(['user', 'payment'])->get();
        return view('admin.repairs', compact('repairs'));
    }

    public function removeSparepart($repairId, $sparepartId)
    {
        DB::beginTransaction();
        try {
            $repairSparepart = \App\Models\RepairSparepart::where('repair_id', $repairId)
                ->where('id', $sparepartId)
                ->firstOrFail();

            // Kembalikan stok jika in_store
            if ($repairSparepart->source === 'in_store' && $repairSparepart->sparepart_id) {
                $sparepart = \App\Models\Sparepart::find($repairSparepart->sparepart_id);
                if ($sparepart) {
                    $sparepart->increment('stock', $repairSparepart->quantity);
                }
            }

            $repairSparepart->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sparepart removed successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error removing sparepart: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove sparepart: ' . $e->getMessage()
            ], 500);
        }
    }
    }