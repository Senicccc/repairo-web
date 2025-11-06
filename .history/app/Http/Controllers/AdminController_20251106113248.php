<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Repair;
use App\Models\Payment;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use App\Models\LoyaltyReward;

class AdminController extends Controller
{
    // Admin Dashboard
    public function index()
    {
        $data = [
            'users' => User::all(),
            'repairs' => Repair::with(['user', 'payment'])->get(),
            'payments' => Payment::with(['repair.user'])->get(),
            'rewards' => LoyaltyReward::with('user')->get(),
            'spareparts' => Sparepart::paginate(20),
        ];

        return view('admin.index', $data);
    }

    // Load sections dynamically
    public function getSection($section)
    {
        switch ($section) {
            case 'users':
                $data['users'] = User::all();
                return view('admin.users', $data);

            case 'repairs':
                $data['repairs'] = Repair::with(['user', 'payment'])->get();
                return view('admin.repairs', $data);

            case 'payments':
                $data['payments'] = Payment::with(['repair.user'])->get();
                return view('admin.payments', $data);

            case 'loyalty':
                $data['rewards'] = LoyaltyReward::with('user')->get();
                return view('admin.loyalty', $data);

            case 'spareparts':
                $data['spareparts'] = Sparepart::paginate(20);
                return view('admin.spareparts', $data);

            default:
                abort(404);
        }
    }

    // Admin Repairs List
    public function adminRepairs()
    {
        $repairs = Repair::with(['user', 'payment'])->get();
        return view('admin.repairs', compact('repairs'));
    }

    // Admin Payments List
    public function adminPayments()
    {
        $payments = Payment::with(['repair.user'])->get();
        return view('admin.payments', compact('payments'));
    }

    // Admin Loyalty List
    public function adminLoyalty()
    {
        $rewards = LoyaltyReward::with('user')->get();
        return view('admin.loyalty', compact('rewards'));
    }

    // Admin Spareparts List
    public function adminSpareparts()
    {
        $spareparts = Sparepart::paginate(20);
        return view('admin.spareparts', compact('spareparts'));
    }

    // Delete Repair
    public function deleteRepair($id)
    {
        try {
            $repair = Repair::findOrFail($id);
            $repair->delete();

            return response()->json([
                'success' => true,
                'message' => 'Repair deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete repair: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update Repair
    public function updateRepair(Request $request, $id)
    {
        try {
            $repair = Repair::findOrFail($id);

            $data = $request->validate([
                'status' => 'required|string|in:pending,in_progress,finished,cancelled',
                'technician' => 'nullable|string|max:255',
                'cost' => 'nullable|numeric|min:0',
            ]);

            $repair->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Repair updated successfully',
                'repair' => $repair
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update repair: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete Payment
    public function deletePayment($id)
    {
        try {
            $payment = Payment::findOrFail($id);
            $payment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Payment deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete payment: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update Payment
    public function updatePayment(Request $request, $id)
    {
        try {
            $payment = Payment::findOrFail($id);

            $data = $request->validate([
                'status' => 'required|string|in:unpaid,paid',
                'payment_method' => 'required|string|in:cash,transfer,ewallet',
                'amount' => 'required|numeric|min:0',
            ]);

            // Debug data sebelum update
            Log::info('Updating payment:', $data);
            
            $payment->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Payment updated successfully',
                'payment' => $payment
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating payment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment: ' . $e->getMessage()
            ], 500);
        }
    }

    
}