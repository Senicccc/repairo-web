<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Repair;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\LoyaltyReward;
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
        // Redirect ke dashboard users yang baru
        return redirect()->route('users.dashboard');
    }

    public function cashierDashboard()
    {
        $repairs = Repair::with('user', 'payment')->orderBy('created_at', 'desc')->get();
        $users = User::orderBy('name')->get();
        return view('cashier.index', compact('repairs', 'users'));
    }

    public function technicianDashboard()
    {
        $user = Auth::user();

        // If the DB has technician_id column, use it; otherwise fall back to the legacy 'technician' string
        if (Schema::hasColumn('repairs', 'technician_id')) {
            // Current jobs assigned to this technician (in_progress, diagnosed, waiting_parts)
            $currentJobs = Repair::with('user','payment','technicianUser','repairSpareparts')
                ->where('technician_id', $user->id)
                ->whereIn('status', ['in_progress', 'diagnosed', 'waiting_parts'])
                ->get();

            // Available jobs not yet claimed
            $availableJobs = Repair::with('user','payment')
                ->whereNull('technician_id')
                ->where('status', 'pending')
                ->get();

            // Completed or other jobs for history
            $otherJobs = Repair::with('user','payment','technicianUser','repairSpareparts')
                ->where('technician_id', $user->id)
                ->whereIn('status', ['finished', 'cancelled'])
                ->orderByDesc('updated_at')
                ->get();
        } else {
            // Legacy fallback: technician stored as name in 'technician' column
            $currentJobs = Repair::with('user','payment','repairSpareparts')
                ->where('technician', $user->name)
                ->whereIn('status', ['in_progress', 'diagnosed', 'waiting_parts'])
                ->get();

            $availableJobs = Repair::with('user','payment')
                ->where(function ($q) {
                    $q->whereNull('technician')->orWhere('technician', '');
                })
                ->where('status', 'pending')
                ->get();

            $otherJobs = Repair::with('user','payment','repairSpareparts')
                ->where('technician', $user->name)
                ->whereIn('status', ['finished', 'cancelled'])
                ->orderByDesc('updated_at')
                ->get();
        }

        return view('technician.dashboard', compact('currentJobs', 'availableJobs', 'otherJobs'));
    }

    public function adminDashboard()
    {
        return redirect()->route('admin.dashboard');
    }

    public function dashboardRedirect()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'user':
                return redirect()->route('users.dashboard');
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