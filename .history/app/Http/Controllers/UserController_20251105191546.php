<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Show single user (for edit modal)
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // Create user form (cashier only)
    public function create()
    {
        return view('cashier.users.create');
    }

    // Store new user
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:user,customer,cashier,technician,admin',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        $redirectRoute = match (Auth::user()->role ?? 'guest') {
            'admin'   => 'admin.users',
            'cashier' => 'cashier.index',
            default   => 'home',
        };

        return redirect()->route($redirectRoute)->with('success', 'User created successfully!');
    }

    // Update existing user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $id,
            'role' => 'required|string|in:user,customer,cashier,technician,admin',
            'password' => 'nullable|string|min:6',
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'role' => $data['role'],
            'password' => $data['password'] ? Hash::make($data['password']) : $user->password,
        ]);

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}