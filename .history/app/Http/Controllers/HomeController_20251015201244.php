use Illuminate\Support\Facades\Auth;
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('staff.cashier');
    }

    public function technicianDashboard()
    {
        return view('staff.technician');
    }

    public function adminDashboard()
    {
        return view('staff.admin');
    }

    public function dashboardRedirect()
{
    $user = Auth::user();

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