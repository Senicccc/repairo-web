<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Tampilkan form create user (khusus cashier)
    public function create()
    {
        if (Auth::user()->role !== 'cashier') {
            abort(403, 'Unauthorized access.');
        }

        return view('cashier.users.create');
    }

    // Store user baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'phone'    => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'required|string|in:user,customer,cashier,technician,admin',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        // Auto-relate repairs by phone (jaga biar konsisten)
        \App\Models\Repair::where('phone', $user->phone)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id]);

        $role = Auth::user()->role ?? 'guest';

        // ADMIN → balik JSON (buat AJAX di admin/users)
        if ($role === 'admin') {
            return response()->json([
                'message' => 'User created successfully!',
                'user' => $user
            ]);
        }

        // CASHIER → redirect ke dashboard
        if ($role === 'cashier') {
            return redirect()
                ->route('cashier.dashboard')
                ->with('success', 'Customer account created successfully!');
        }

        // Default fallback
        return redirect('/')
            ->with('info', 'User created, but no specific redirect was set for your role.');
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'phone'    => 'required|string|max:20|unique:users,phone,' . $id,
            'role'     => 'required|string|in:user,customer,cashier,technician,admin',
            'password' => 'nullable|string|min:6',
        ]);

        $user->update([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            'role'     => $data['role'],
            'password' => $data['password'] ? Hash::make($data['password']) : $user->password,
        ]);

        return response()->json([
            'message' => 'User updated successfully!',
            'user' => $user
        ]);
    }

    // Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully!']);
    }

    // Tampilkan 1 user (buat modal edit di admin)
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }
}