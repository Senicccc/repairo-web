<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Repair;
use App\Models\Payment;
use App\Models\LoyaltyReward;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        $repairs = Repair::all();
        $payments = Payment::all();
        $rewards = LoyaltyReward::all();

        return view('admin', compact('users', 'repairs', 'payments', 'rewards'));
    }
}