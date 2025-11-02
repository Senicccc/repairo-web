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
        $repairs = Repair::with(['user', 'payment'])->orderBy('created_at', 'desc')->get();
        $users = User::orderBy('name')->get();
        return view('cashier.index', compact('repairs', 'users'));
    }

    public function repairsIndex()
    {
        $repairs = Repair::with(['user', 'payment', 'repairSpareparts'])
                        ->orderBy('created_at', 'desc')
                        ->get();
        return view('cashier.repairs.index', compact('repairs'));
    }

    public function repairShow($id)
    {
        $repair = Repair::with(['user', 'payment', 'repairSpareparts.sparepart'])
                       ->findOrFail($id);
        return view('cashier.repairs.show', compact('repair'));
    }

    public function paymentsIndex()
    {
        $payments = Payment::with(['repair.user'])
                          ->orderBy('created_at', 'desc')
                          ->get();
        return view('cashier.payments.index', compact('payments'));
    }

    public function storePayment(Request $request)
    {
        $validated = $request->validate([
            'repair_id' => 'required|exists:repairs,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,ewallet',
            'status' => 'required|in:paid,unpaid'
        ]);

        // Cek apakah payment sudah ada
        $existingPayment = Payment::where('repair_id', $validated['repair_id'])->first();
        
        if ($existingPayment) {
            $existingPayment->update($validated);
        } else {
            Payment::create($validated);
        }

        // Update repair status jika paid
        if ($validated['status'] === 'paid') {
            Repair::where('id', $validated['repair_id'])->update(['status' => 'paid']);
        }

        return redirect()->route('cashier.payments.index')->with('success', 'Payment processed successfully!');
    }
}