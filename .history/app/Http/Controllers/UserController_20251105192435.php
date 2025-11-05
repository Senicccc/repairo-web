<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Repair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /** Show create form (cashier only) */
    public function create()
    {
        if (Auth::user()->role !== 'cashier') {
            abort(403, 'Unauthorized access.');
        }

        return view('cashier.users.create');
    }

    /** Store new user (works for both Admin AJAX & Cashier form) */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'phone'    => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'required|string|in:user,cashier,technician,admin',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        // Hubungkan repair lama (berdasarkan phone)
        Repair::where('phone', $user->phone)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id]);

        $authRole = Auth::user()->role ?? 'guest';

        // ADMIN (AJAX)
        if ($authRole === 'admin' || $request->expectsJson()) {
            return response()->json([
                'message' => 'User created successfully!',
                'user' => $user
            ]);
        }

        // CASHIER (redirect)
        if ($authRole === 'cashier') {
            return redirect()
                ->route('cashier.dashboard')
                ->with('success', 'Customer account created successfully!');
        }

        // Fallback (misal belum login)
        return redirect('/')
            ->with('info', 'User created successfully.');
    }

    /** Update user (Admin only - AJAX) */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'phone'    => 'required|string|max:20|unique:users,phone,' . $id,
            'role'     => 'required|string|in:user,cashier,technician,admin',
            'password' => 'nullable|string|min:6',
        ]);

        $user->update([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            'role'     => $data['role'],
            'password' => $data['password']
                ? Hash::make($data['password'])
                : $user->password,
        ]);

        return response()->json([
            'message' => 'User updated successfully!',
            'user' => $user
        ]);
    }

    /** Delete user */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully!']);
    }

    /** Show single user (for admin modal edit) */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }
}