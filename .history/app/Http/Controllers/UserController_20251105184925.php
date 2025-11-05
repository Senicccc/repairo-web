<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Repair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function create()
    {
        return view('admin.users-create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:admin,technician,cashier,user,customer',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        Repair::where('phone', $user->phone)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id]);

        return redirect()
            ->route('admin.index')
            ->with('success', 'User account created successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}