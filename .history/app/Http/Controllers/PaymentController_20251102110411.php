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
            'payment_method' => 'required|in:cash,transfer,ewallet',
            'status' => 'required|in:unpaid,paid',
        ]);

        $payment = Payment::create([
            'repair_id' => $data['repair_id'],
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'],
            'status' => $data['status'],
        ]);

        $repair = Repair::find($data['repair_id']);
        
        // HANYA update cost di repairs jika perlu, JANGAN update status
        if ($repair && $payment->status === 'paid') {
            $repair->update([
                'cost' => $payment->amount
                // HAPUS: 'status' => 'paid' - karena status paid hanya untuk payments
            ]);

            // === Tambah poin otomatis ===
            if ($repair->user_id) {
                $loyalty = new LoyaltyController();
                $loyalty->addPoints($repair->user_id, $repair->cost);
            }
        }

        return redirect()->route('cashier.dashboard')->with('success', 'Payment recorded successfully!');
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
                // HANYA tambah poin, JANGAN update repair status
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

    public function adminIndex()
    {
        $payments = Payment::with('repair.user')->orderBy('created_at', 'desc')->get();
        return view('admin.payments', compact('payments'));
    }
}