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
    $allowedSources = ['in_store','customer_owned','external_purchase'];

    // LOG UNTUK DEBUG
    \Log::info('=== ADD SPAREPART REQUEST ===');
    \Log::info('All request data:', $request->all());

    $data = $request->validate([
        'sparepart_id' => 'nullable|exists:spareparts,id',
        'name' => 'required|string|max:255', // PASTIKAN NAME ADA
        'quantity' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0',
        'source' => 'required|in:' . implode(',', $allowedSources),
        'category' => 'nullable|string|max:255',
    ]);

    Log::info('Validated data:', $data);

    $repair = Repair::findOrFail($id);

    // PREPARE DATA DENGAN SEMUA KOLOM YANG DIBUTUHKAN
    $sparepartData = [
        'repair_id' => $repair->id,
        'sparepart_id' => $data['sparepart_id'],
        'name' => $data['name'], // INI YANG PENTING
        'category' => $data['category'] ?? null,
        'quantity' => $data['quantity'],
        'price' => $data['price'],
        'source' => $data['source'],
    ];

    Log::info('Data to save:', $sparepartData);

    try {
        // CREATE RECORD
        RepairSparepart::create($sparepartData);
        \Log::info('Sparepart created successfully');

    } catch (\Exception $e) {
        Log::error('Error creating sparepart: ' . $e->getMessage());
        return response()->json([
            'success' => false, 
            'message' => 'Database error: ' . $e->getMessage()
        ], 500);
    }

    // Update stock if in_store
    if ($data['source'] === 'in_store' && $data['sparepart_id']) {
        $sparepart = Sparepart::find($data['sparepart_id']);
        if ($sparepart) {
            $sparepart->decrement('stock', $data['quantity']);
            \Log::info('Stock updated for sparepart ID: ' . $data['sparepart_id']);
        }
    }

    // Update repair's sparepart field
    $sparepartsList = RepairSparepart::where('repair_id', $repair->id)
        ->pluck('name')
        ->toArray();
    
    $repair->sparepart = implode(', ', $sparepartsList);
    $repair->save();

    \Log::info('Repair updated with spareparts: ' . $repair->sparepart);

    return response()->json([
        'success' => true, 
        'message' => 'Sparepart added successfully.'
    ]);
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