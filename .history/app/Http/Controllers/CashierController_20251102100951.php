<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Repair;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{
    public function index()
    {
        $repairs = Repair::with(['user', 'payment'])->get();
        $users = User::orderBy('name')->get();
        return view('cashier.index', compact('repairs', 'users'));
    }

    public function repairsIndex()
    {
        $repairs = Repair::with(['user', 'payment'])->get();
        return view('cashier.repairs.index', compact('repairs'));
    }

    public function paymentsIndex()
    {
        $payments = Payment::with(['repair.user'])->get();
        return view('cashier.payments.index', compact('payments'));
    }
}