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
}