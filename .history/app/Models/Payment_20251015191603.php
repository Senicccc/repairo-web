<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use Illuminate\Http\Request;
use App\Http\Controllers\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('repair.user')->get();
        return response()->json($payments);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'repair_id' => 'required|exists:repairs,id',
            'amount' => 'required|numeric',
            'method' => 'required|string',
        ]);

        $data['status'] = 'pending';
        $payment = Payment::create($data);

        return response()->json($payment);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $payment->update([
            'status' => $request->status ?? $payment->status,
        ]);

        // kalau status udah paid, update juga status repair jadi 
        if ($payment->status === 'paid') {
            $repair = Repair::find($payment->repair_id);
            if ($repair) {
                $repair->update(['status' => 'finished']);
            }
        }

        return response()->json($payment);
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully']);
    }
}