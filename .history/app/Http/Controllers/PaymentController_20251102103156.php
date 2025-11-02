<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Repair;
use Illuminate\Http\Request;
use App\Http\Controllers\LoyaltyController;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('repair.user')->get();
        return view('payments.index', compact('payments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'repair_id' => 'required|exists:repairs,id',
            'amount' => 'required|numeric',
            'method' => 'required|string',
            'status' => 'nullable|in:unpaid,paid',
        ]);

        $payment = Payment::create([
            'repair_id' => $data['repair_id'],
            'amount' => $data['amount'],
            'payment_method' => $data['method'],
            'status' => $data['status'] ?? 'paid',
        ]);

        $repair = Repair::find($data['repair_id']);
        if ($repair && $payment->status === 'paid') {
            $repair->update(['status' => 'finished', 'cost' => $payment->amount]);

            // === Tambah poin otomatis ===
            if ($repair->user_id) {
                $loyalty = new LoyaltyController();
                $loyalty->addPoints($repair->user_id, $repair->cost);
            }
        }

        return redirect()->route('cashier.dashboard')->with('success', 'Payment recorded');
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update([
            'status' => $request->status ?? $payment->status,
        ]);

        if ($payment->status === 'paid') {
            $repair = Repair::find($payment->repair_id);
            if ($repair) {
                $repair->update(['status' => 'finished']);

                // add points after paid
                if ($repair->user_id) {
                    $loyalty = new LoyaltyController();
                    $loyalty->addPoints($repair->user_id, $repair->cost);
                }
            }
        }

        return response()->json($payment);
    }

    public function create($repair_id)
    {
        $repair = Repair::with('user')->findOrFail($repair_id);
        return view('cashier.payments.create', compact('repair'));
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully']);
    }
}