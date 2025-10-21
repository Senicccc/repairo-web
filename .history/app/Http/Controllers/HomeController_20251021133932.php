<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index');
    }

    public function userDashboard()
    {
        return view('users.dashboard');
    }

    public function cashierDashboard()
    {
        $repairs = \App\Models\Repair::with('user', 'payment')->get();
        $users = \App\Models\User::orderBy('name')->get();
        return view('staff.cashier', compact('repairs', 'users'));
    }

    public function technicianDashboard()
    {
        $repairs = \App\Models\Repair::with('user','payment')->get();
        return view('staff.technician', compact('repairs'));
    }

    public function adminDashboard()
    {
        return view('staff.admin');
    }

    public function dashboardRedirect()
{
    $user = \Illuminate\Support\Facades\Auth::user();

    switch ($user->role) {
        case 'user':
            return redirect()->route('user.dashboard');
        case 'cashier':
            return redirect()->route('cashier.dashboard');
        case 'technician':
            return redirect()->route('technician.dashboard');
        case 'admin':
            return redirect()->route('admin.dashboard');
        default:
            return redirect('/'); 
    }
}

}