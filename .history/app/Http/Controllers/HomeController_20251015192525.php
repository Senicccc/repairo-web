<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home'); 
    }

    public function userDashboard()
    {
        return view('dashboard.user');
    }

    public function cashierDashboard()
    {
        return view('dashboard.cashier');
    }

    public function technicianDashboard()
    {
        return view('dashboard.technician');
    }

    public function adminDashboard()
    {
        return view('dashboard.admin');
    }
}