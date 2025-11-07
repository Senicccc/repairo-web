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
            'users' => User::paginate(10), 
            'repairs' => Repair::with(['user', 'payment'])->paginate(10), // TAMBAH PAGINATION
            'payments' => Payment::with(['repair.user'])->paginate(10),
            'rewards' => LoyaltyReward::with('user')->paginate(10), 
            'spareparts' => Sparepart::paginate(10),
        ];

        return view('admin.index', $data);
    }

    // Load sections dynamically
    public function getSection($section)
    {
        $perPage = 10;
        
        switch ($section) {
            case 'users':
                $data['users'] = User::paginate($perPage);
                $view = 'admin.users';
                break;

            case 'repairs':
                $data['repairs'] = Repair::with(['user', 'payment'])->paginate($perPage);
                $view = 'admin.repairs';
                break;

            case 'payments':
                $data['payments'] = Payment::with(['repair.user'])->paginate($perPage);
                $view = 'admin.payments';
                break;

            case 'loyalty':
                $data['rewards'] = LoyaltyReward::with('user')->paginate($perPage);
                $view = 'admin.loyalty';
                break;

            case 'spareparts':
                $data['spareparts'] = Sparepart::paginate($perPage);
                $view = 'admin.spareparts';
                break;

            default:
                abort(404);
        }

        if (request()->ajax()) {
            return view($view, $data)->render();
        }

        return view($view, $data);
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
            Log::error('Error deleting repair: ' . $e->getMessage());
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

            $validated = $request->validate([
                'status' => 'required|string|in:pending,in_progress,finished,cancelled',
                'technician' => 'nullable|string|max:255',
                'cost' => 'nullable|numeric|min:0',
            ]);

            $repair->update($validated);

            Log::info('Repair updated successfully:', ['repair_id' => $id, 'data' => $validated]);

            return response()->json([
                'success' => true,
                'message' => 'Repair updated successfully',
                'repair' => $repair->load('user', 'payment')
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating repair: ' . $e->getMessage());
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
            Log::error('Error deleting payment: ' . $e->getMessage());
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

            $validated = $request->validate([
                'status' => 'required|string|in:unpaid,paid',
                'payment_method' => 'required|string|in:cash,transfer,ewallet',
                'amount' => 'required|numeric|min:0',
            ]);

            Log::info('Updating payment:', $validated);
            
            $payment->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Payment updated successfully',
                'payment' => $payment->load('repair.user')
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating payment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete Loyalty
    public function deleteLoyalty($id)
    {
        try {
            $loyalty = LoyaltyReward::findOrFail($id);
            $loyalty->delete();

            return response()->json([
                'success' => true,
                'message' => 'Loyalty reward deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error deleting loyalty: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete loyalty reward: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update Loyalty
    public function updateLoyalty(Request $request, $id)
    {
        try {
            $loyalty = LoyaltyReward::findOrFail($id);

            $validated = $request->validate([
                'status' => 'required|string|in:claimed,used',
                'reward_type' => 'required|string|in:discount,gift',
                'points_used' => 'required|integer|min:0',
                'reward_value' => 'required|string|max:255',
            ]);

            Log::info('Updating loyalty reward:', $validated);
            
            $loyalty->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Loyalty reward updated successfully',
                'loyalty' => $loyalty->load('user')
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating loyalty reward: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update loyalty reward: ' . $e->getMessage()
            ], 500);
        }
    }

    // SPAREPARTS MANAGEMENT
    public function adminSpareparts()
    {
        $spareparts = Sparepart::paginate(20);
        return view('admin.spareparts', compact('spareparts'));
    }

    // Delete Sparepart
    public function deleteSparepart($id)
    {
        try {
            $sparepart = Sparepart::findOrFail($id);
            $sparepart->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sparepart deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error deleting sparepart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete sparepart: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update Sparepart
    public function updateSparepart(Request $request, $id)
    {
        try {
            $sparepart = Sparepart::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'brand' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'category' => 'required|string|in:Original,OEM,Aftermarket,Replica',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
            ]);


            Log::info('Updating sparepart:', $validated);
            
            $sparepart->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Sparepart updated successfully',
                'sparepart' => $sparepart
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating sparepart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update sparepart: ' . $e->getMessage()
            ], 500);
        }
    }

    // Store Sparepart (CREATE) 
    public function storeSparepart(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'brand' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'category' => 'required|string|in:Original,OEM,Aftermarket,Replica', 
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
            ]);

            $sparepart = Sparepart::create($validated);

            Log::info('Sparepart created successfully:', $validated);

            return response()->json([
                'success' => true,
                'message' => 'Sparepart created successfully',
                'sparepart' => $sparepart
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error creating sparepart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create sparepart: ' . $e->getMessage()
            ], 500);
        }
    }
}