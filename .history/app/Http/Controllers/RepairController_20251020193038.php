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
            'name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:30',
            'device_type' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'damage_description' => 'required|string',
        ]);

        $user = \App\Models\User::firstWhere('phone', $data['phone']);
        if (!$user) {
            $user = \App\Models\User::create([
                'name' => $data['name'] ?? 'Guest',
                'email' => $data['phone'].'@local.invalid',
                'password' => bcrypt(str()->random(12)),
                'phone' => $data['phone'],
                'role' => 'user',
            ]);
        }

        $repair = Repair::create([
            'user_id' => $user->id,
            'phone_brand' => $data['device_type'],
            'phone_model' => $data['brand'].' '.$data['model'],
            'imei' => null,
            'complaint' => $data['damage_description'],
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Repair created successfully');
    }

    public function update(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);

        $repair->update($request->only([
            'status',
            'diagnosis',
        ]));

        return response()->json($repair);
    }

    public function destroy($id)
    {
        $repair = Repair::findOrFail($id);
        $repair->delete();

        return response()->json(['message' => 'Repair deleted successfully']);
    }
}