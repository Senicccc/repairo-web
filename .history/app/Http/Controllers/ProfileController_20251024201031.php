<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Repair;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        if ($user->role === 'technician') {
            $repairs = Repair::where('technician_id', $user->id)->get();
            return view('profile.technician', compact('user', 'repairs'));
        }

        $repairs = Repair::where('user_id', $user->id)->get();
        return view('profile.show', compact('user', 'repairs'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }
}