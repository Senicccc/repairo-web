<?php

nuse App\Models\Repair;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
        'phone' => ['required', 'string', 'max:20', 'unique:'.User::class],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'password' => Hash::make($request->password),
        'role' => 'user',
    ]);

    // 🔥 Update semua repair yang punya nomor telepon sama
    Repair::where('phone', $user->phone)
        ->whereNull('user_id')
        ->update(['user_id' => $user->id]);

    event(new Registered($user));

    Auth::login($user);

    return redirect()->route('dashboard');
}