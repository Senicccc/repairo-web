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
        $user = \Illuminate\Support\Facades\Auth::user();

        // Current job assigned to this technician (in_progress)
        $currentJob = \App\Models\Repair::with('user','payment','technicianUser')
            ->where('technician_id', $user->id)
            ->where('status', 'in_progress')
            ->first();

        // Available jobs not yet claimed
        $availableJobs = \App\Models\Repair::with('user','payment')
            ->whereNull('technician_id')
            ->where('status', 'pending')
            ->get();

        // Completed or other jobs for history
        $otherJobs = \App\Models\Repair::with('user','payment','technicianUser')
            ->where(function ($q) use ($user) {
                $q->where('status', 'finished')
                  ->orWhere('technician_id', $user->id);
            })
            ->orderByDesc('updated_at')
            ->get();

        return view('staff.technician', compact('currentJob', 'availableJobs', 'otherJobs'));
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