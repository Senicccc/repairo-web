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
        return view('admin.index');
    }

    // Load sections dynamically - FIXED
    public function getSection($section)
    {
        try {
            $perPage = 10;
            
            switch ($section) {
                case 'users':
                    $users = User::with('roles')->latest()->paginate($perPage);
                    return view('admin.users', compact('users'));

                case 'repairs':
                    $repairs = Repair::with(['user', 'technician'])->latest()->paginate($perPage);
                    return view('admin.repairs', compact('repairs'));

                case 'payments':
                    $payments = Payment::with(['user', 'repair'])->latest()->paginate($perPage);
                    return view('admin.payments', compact('payments'));

                case 'loyalty':
                    $loyalties = LoyaltyReward::with('user')->latest()->paginate($perPage);
                    return view('admin.loyalty', compact('loyalties'));

                case 'spareparts':
                    $spareparts = Sparepart::latest()->paginate($perPage);
                    return view('admin.spareparts', compact('spareparts'));

                default:
                    return response()->json(['error' => 'Section not found'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error in getSection: ' . $e->getMessage());
            return response()->json(['error' => 'Error loading section: ' . $e->getMessage()], 500);
        }
    }

    // Search function untuk semua section - FIXED
    public function search($section, Request $request)
    {
        try {
            $query = $request->get('q');
            $perPage = 10;
            
            Log::info("Searching {$section} for: {$query}");
            
            // Jika query kosong, redirect ke section normal
            if (empty($query)) {
                return $this->getSection($section);
            }
            
            switch ($section) {
                case 'users':
                    $users = User::with('roles')
                                ->where('name', 'like', "%{$query}%")
                                ->orWhere('email', 'like', "%{$query}%")
                                ->orWhere('phone', 'like', "%{$query}%")
                                ->latest()
                                ->paginate($perPage);
                    return view('admin.users', compact('users'));

                case 'repairs':
                    $repairs = Repair::with(['user', 'technician'])
                                    ->where('device_type', 'like', "%{$query}%")
                                    ->orWhere('issue_description', 'like', "%{$query}%")
                                    ->orWhere('status', 'like', "%{$query}%")
                                    ->orWhereHas('user', function($q) use ($query) {
                                        $q->where('name', 'like', "%{$query}%");
                                    })
                                    ->latest()
                                    ->paginate($perPage);
                    return view('admin.repairs', compact('repairs'));

                case 'payments':
                    $payments = Payment::with(['user', 'repair'])
                                    ->where('status', 'like', "%{$query}%")
                                    ->orWhere('payment_method', 'like', "%{$query}%")
                                    ->orWhereHas('user', function($q) use ($query) {
                                        $q->where('name', 'like', "%{$query}%");
                                    })
                                    ->orWhereHas('repair', function($q) use ($query) {
                                        $q->where('id', 'like', "%{$query}%");
                                    })
                                    ->latest()
                                    ->paginate($perPage);
                    return view('admin.payments', compact('payments'));

                case 'loyalty':
                    $loyalties = LoyaltyReward::with('user')
                                    ->where('reward_type', 'like', "%{$query}%")
                                    ->orWhere('reward_value', 'like', "%{$query}%")
                                    ->orWhere('status', 'like', "%{$query}%")
                                    ->orWhereHas('user', function($q) use ($query) {
                                        $q->where('name', 'like', "%{$query}%");
                                    })
                                    ->latest()
                                    ->paginate($perPage);
                    return view('admin.loyalty', compact('loyalties'));

                case 'spareparts':
                    $spareparts = Sparepart::where('name', 'like', "%{$query}%")
                                        ->orWhere('brand', 'like', "%{$query}%")
                                        ->orWhere('model', 'like', "%{$query}%")
                                        ->orWhere('category', 'like', "%{$query}%")
                                        ->latest()
                                        ->paginate($perPage);
                    return view('admin.spareparts', compact('spareparts'));

                default:
                    return response()->json(['error' => 'Section not found'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error in search: ' . $e->getMessage());
            return response()->json(['error' => 'Search error: ' . $e->getMessage()], 500);
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