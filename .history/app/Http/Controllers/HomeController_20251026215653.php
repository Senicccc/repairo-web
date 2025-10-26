<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Repair;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

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
        $user = Auth::user();

        // If the DB has technician_id column, use it; otherwise fall back to the legacy 'technician' string
        if (Schema::hasColumn('repairs', 'technician_id')) {
            // Current jobs assigned to this technician (in_progress)
            $currentJobs = \App\Models\Repair::with('user','payment','technicianUser')
                ->where('technician_id', $user->id)
                ->where('status', 'in_progress')
                ->get();

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
        } else {
            // Legacy fallback: technician stored as name in 'technician' column
            $currentJobs = \App\Models\Repair::with('user','payment')
                ->where('technician', $user->name)
                ->where('status', 'in_progress')
                ->get();

            $availableJobs = \App\Models\Repair::with('user','payment')
                ->where(function ($q) {
                    $q->whereNull('technician')->orWhere('technician', '');
                })
                ->where('status', 'pending')
                ->get();

            $otherJobs = \App\Models\Repair::with('user','payment')
                ->where(function ($q) use ($user) {
                    $q->where('status', 'finished')
                      ->orWhere('technician', $user->name);
                })
                ->orderByDesc('updated_at')
                ->get();
        }

        return view('staff.technician', compact('currentJobs', 'availableJobs', 'otherJobs'));
    }

    public function adminDashboard()
    {
        $users = User::all();
        $repairs = Repair::with('user')->get();
        $payments = Payment::with('repair.user')->get();
        $rewards = LoyaltyReward::with('user')->get();
        return view('staff.admin', compact('users', 'repairs', 'payments', 'rewards'));
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