<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Repair;
use App\Models\User;
use App\Models\Payment;

class CashierController extends Controller
{
    /**
     * Display the cashier dashboard.
     */
    public function dashboard()
    {
        // Ambil data repairs + relasi user & payment
        // Gunakan paginate agar bisa dipakai ->total() & ->links()
        $repairs = Repair::with(['user', 'payment'])
            ->orderByDesc('created_at')
            ->paginate(10);

        // Hitung statistik status
        $pendingCount = Repair::where('status', 'pending')->count();
        $finishedCount = Repair::where('status', 'finished')->count();
        $paidCount = Repair::where('status', 'paid')->count();

        return view('cashier.index', compact('repairs', 'pendingCount', 'finishedCount', 'paidCount'));
    }

    /**
     * Show the form to create a new repair.
     */
    public function createRepair()
    {
        $users = User::orderBy('name')->get();
        return view('cashier.repairs.create', compact('users'));
    }

    /**
     * Store a new repair record.
     */
    public function storeRepair(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'phone_brand' => 'required|string|max:255',
            'phone_model' => 'nullable|string|max:255',
            'complaint' => 'required|string|max:500',
        ]);

        $repair = Repair::create([
            'customer_name' => $validated['customer_name'],
            'phone' => $validated['phone'],
            'phone_brand' => $validated['phone_brand'],
            'phone_model' => $validated['phone_model'],
            'complaint' => $validated['complaint'],
            'status' => 'pending',
            'tracking_id' => strtoupper(uniqid('TRK')),
        ]);

        return redirect()->route('cashier.dashboard')->with('success', 'Repair successfully created!');
    }

    /**
     * Show repair details.
     */
    public function showRepair($id)
    {
        $repair = Repair::with(['user', 'payment'])->findOrFail($id);
        return view('cashier.repairs.show', compact('repair'));
    }
}