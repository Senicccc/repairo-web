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
        return view('cashier', compact('repairs'));
    }

    /**
     * Store new repair record (linked by phone number)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:30',
            'phone_brand' => 'required|string|max:255',
            'phone_model' => 'required|string|max:255',
            'imei' => 'nullable|string|max:255',
            'complaint' => 'required|string',
            'technician' => 'nullable|string|max:255',
            'cost' => 'nullable|numeric',
        ]);

        // Find or create a user based on phone number
        $user = User::firstWhere('phone', $data['phone']);
        if (!$user) {
            $user = User::create([
                'name' => $data['name'] ?? 'Guest',
                'email' => $data['phone'] . '@local.invalid',
                'password' => bcrypt(str()->random(12)),
                'phone' => $data['phone'],
                'role' => 'user',
            ]);
        }

        // Create new repair record
        Repair::create([
            'phone' => $data['phone'],
            'user_id' => $user->id,
            'phone_brand' => $data['phone_brand'],
            'phone_model' => $data['phone_model'],
            'imei' => $data['imei'] ?? null,
            'complaint' => $data['complaint'],
            'status' => 'pending',
            'technician' => $data['technician'] ?? null,
            'cost' => $data['cost'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Repair created successfully.');
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