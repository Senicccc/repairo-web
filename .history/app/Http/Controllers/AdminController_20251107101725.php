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
            // Handle both normal request and AJAX pagination
            $data['spareparts'] = Sparepart::paginate(20);
            
            // Jika request AJAX, return hanya section spareparts
            if (request()->ajax()) {
                return view('admin.spareparts', $data)->render();
            }
            
            return view('admin.spareparts', $data);

        default:
            abort(404);
    }
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