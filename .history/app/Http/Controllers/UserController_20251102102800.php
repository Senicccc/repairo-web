<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

    class UserController extends Controller
    {
        public function index()
        {
            $users = User::all();
            return response()->json($users);
        }

        /**
         * Create a new customer user (used by cashier modal).
         */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50|unique:users,phone',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:customer',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        // Auto-connect existing repairs by phone number
        \App\Models\Repair::where('phone', $user->phone)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id]);

        return redirect()->back()->with('success', 'Customer account created successfully.');
    }

    public function create()
    {
        return view('cashier.users.create');
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}