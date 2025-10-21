<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepairController extends Controller
{
    /**
     * Display all repairs for cashier dashboard
     */
    public function index()
    {
        $repairs = Repair::with(['user', 'payment'])->get();
        return view('staff.cashier', compact('repairs'));
    }

    /**
     * Store new repair record (linked by phone number)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
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

        // Assign the currently authenticated technician
    $user = Auth::user();
    $repair->technician = $user->name;
        $repair->status = 'in_progress';

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
}