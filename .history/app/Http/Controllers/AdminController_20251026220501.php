<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Repair;
use App\Models\Payment;
use App\Models\Loyalty;
use App\Models\LoyaltyReward;

class AdminController extends Controller
{
    public function users() {
        $users = User::paginate(10);
        return view('admin.users', compact('users'));
    }

    public function repairs() {
        $repairs = Repair::with('user')->paginate(10);
        return view('admin.repairs', compact('repairs'));
    }

    public function payments() {
        $payments = Payment::with('repair.user')->paginate(10);
        return view('admin.payments', compact('payments'));
    }

    public function loyalty() {
        $loyalties = Loyalty::with('user')->paginate(10);
        return view('admin.loyalty', compact('loyalties'));
    }

    public function rewards() {
        $rewards = LoyaltyReward::paginate(10);
        return view('admin.rewards', compact('rewards'));
    }
}