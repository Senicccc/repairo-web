<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\User;
use Illuminate\Http\Request;

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
            'user_id' => 'nullable|exists:users,id',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'phone_brand' => 'required|string|max:255',
            'phone_model' => 'required|string|max:255',
            'imei' => 'nullable|string|max:255',
            'complaint' => 'required|string',
            'cost' => 'nullable|numeric',
        ]);

        // build tracking id (use latest id to generate sequence)
        $lastRepair = Repair::orderBy('id', 'desc')->first();
        $nextNumber = $lastRepair ? str_pad($lastRepair->id + 1, 4, '0', STR_PAD_LEFT) : '0001';
        $trackingId = 'SRV' . now()->format('Ymd') . '-' . $nextNumber;

        // determine user association: prefer explicit user_id, then phone lookup/create, otherwise null
        $userId = null;
        if (!empty($validated['user_id'])) {
            $userId = $validated['user_id'];
        } elseif (!empty($validated['phone'])) {
            $user = User::firstWhere('phone', $validated['phone']);
            if (!$user && !empty($validated['name'])) {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['phone'].'@local.invalid',
                    'password' => bcrypt(str()->random(12)),
                    'phone' => $validated['phone'],
                    'role' => 'user',
                ]);
            }

            if ($user) {
                $userId = $user->id;
            }
        }

        $repair = new Repair();
        $repair->tracking_id = $trackingId;
        $repair->user_id = $userId; // may be null
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
     * Delete a repair record
     */
    public function destroy($id)
    {
        $repair = Repair::findOrFail($id);
        $repair->delete();

        return response()->json(['message' => 'Repair deleted successfully.']);
    }
}