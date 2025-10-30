<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function dashboardRedirect()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        if ($user->role === 'cashier') {
            return redirect()->route('cashier.dashboard');
        }
        
        if ($user->role === 'technician') {
            return redirect()->route('technician.dashboard');
        }
        
        // FIX: Ganti dari 'user.dashboard' ke 'users.dashboard'
        return redirect()->route('users.dashboard');
    }

    // Hapus method userDashboard() yang lama karena udah diganti sama UsersDashboardController
    public function userDashboard()
    {
        // Method ini udah ga dipake, redirect ke yang baru
        return redirect()->route('users.dashboard');
    }

    public function cashierDashboard()
    {
        $repairs = Repair::with(['user', 'payment'])->get();
        return view('staff.cashier', compact('repairs'));
    }

    public function adminDashboard()
    {
        $totalRepairs = Repair::count();
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');
        $pendingRepairs = Repair::where('status', 'pending')->count();
        
        return view('admin.dashboard', compact('totalRepairs', 'totalRevenue', 'pendingRepairs'));
    }
}