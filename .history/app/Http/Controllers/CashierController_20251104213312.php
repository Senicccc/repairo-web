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
        // gunakan pagination agar bisa pakai ->total() dan ->links()
        $repairs = Repair::with(['user', 'payment'])
            ->orderByDesc('created_at')
            ->paginate(10);

        $users = User::orderBy('name')->get();

        return view('cashier.index', compact('repairs', 'users'));
    }

    public function repairsIndex()
    {
        $repairs = Repair::with(['user', 'payment', 'repairSpareparts'])
            ->orderByDesc('created_at')
            ->paginate(10);
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
            ->orderByDesc('created_at')
            ->paginate(10);
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

        $existingPayment = Payment::where('repair_id', $validated['repair_id'])->first();

        if ($existingPayment) {
            $existingPayment->update($validated);
        } else {
            Payment::create($validated);
        }

        if ($validated['status'] === 'paid') {
            Repair::where('id', $validated['repair_id'])->update(['status' => 'paid']);
        }

        return redirect()->route('cashier.dashboard')->with('success', 'Payment processed successfully!');
    }
}