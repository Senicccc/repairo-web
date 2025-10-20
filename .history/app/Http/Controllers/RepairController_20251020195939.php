<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    public function index()
    {
        $repairs = Repair::with('user', 'payment')->get();
        return view('repairs.index', compact('repairs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'phone_brand' => 'required|string|max:255',
            'phone_model' => 'required|string|max:255',
            'imei' => 'nullable|string|max:255',
            'complaint' => 'required|string',
            'technician' => 'nullable|string|max:255',
            'cost' => 'nullable|numeric',
        ]);

        $userId = null;
        // if user selected from dropdown
        if (!empty($data['user_id'])) {
            $userId = $data['user_id'];
        } elseif (!empty($data['phone'])) {
            // find or create user by phone
            $user = \App\Models\User::firstWhere('phone', $data['phone']);
            if (!$user) {
                $user = \App\Models\User::create([
                    'name' => $data['name'] ?? 'Guest',
                    'email' => ($data['phone'] ?? uniqid()) . '@local.invalid',
                    'password' => bcrypt(str()->random(12)),
                    'phone' => $data['phone'],
                    'role' => 'user',
                ]);
            }
            $userId = $user->id;
        }

        $repair = Repair::create([
            'user_id' => $userId,
            'phone_brand' => $data['phone_brand'],
            'phone_model' => $data['phone_model'],
            'imei' => $data['imei'] ?? null,
            'complaint' => $data['complaint'],
            'status' => 'pending',
            'technician' => $data['technician'] ?? null,
            'cost' => $data['cost'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Repair created successfully');
    }

    public function update(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);

        $data = $request->validate([
            'status' => 'nullable|in:pending,in_progress,finished,cancelled',
            'diagnosis' => 'nullable|string',
            'cost' => 'nullable|numeric',
        ]);

        $repair->update($data);

        return redirect()->back()->with('success', 'Repair updated');
    }

    public function destroy($id)
    {
        $repair = Repair::findOrFail($id);
        $repair->delete();

        return response()->json(['message' => 'Repair deleted successfully']);
    }
}