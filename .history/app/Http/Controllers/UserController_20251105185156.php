<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Repair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show($id)
    {
        return response()->json(User::findOrFail($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:admin,technician,cashier,user,customer',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        Repair::where('phone', $user->phone)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id]);

        return response()->json(['message' => 'User created successfully', 'user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50|unique:users,phone,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|string|in:admin,technician,cashier,user,customer',
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $user->password,
        ]);

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}