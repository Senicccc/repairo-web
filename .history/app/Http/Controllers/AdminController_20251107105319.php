<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Repair;
use App\Models\Payment;
use App\Models\LoyaltyReward;
use App\Models\Sparepart;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    // GET SECTION CONTENT
    public function getSection($section)
    {
        try {
            switch ($section) {
                case 'users':
                    $users = User::with('roles')->latest()->paginate(10);
                    return view('admin.users', compact('users'));
                
                case 'repairs':
                    $repairs = Repair::with(['user', 'technician'])->latest()->paginate(10);
                    return view('admin.repairs', compact('repairs'));
                
                case 'payments':
                    $payments = Payment::with(['user', 'repair'])->latest()->paginate(10);
                    return view('admin.payments', compact('payments'));
                
                case 'loyalty':
                    $loyalties = LoyaltyReward::with('user')->latest()->paginate(10);
                    return view('admin.loyalty', compact('loyalties'));
                
                case 'spareparts':
                    $spareparts = Sparepart::latest()->paginate(10);
                    return view('admin.spareparts', compact('spareparts'));
                
                default:
                    return response()->json(['error' => 'Section not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error loading section: ' . $e->getMessage()], 500);
        }
    }

    // SEARCH FUNCTION
    public function search($section, Request $request)
    {
        try {
            $query = $request->get('q');
            
            switch ($section) {
                case 'users':
                    $users = User::with('roles')
                        ->where('name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")
                        ->orWhere('phone', 'like', "%{$query}%")
                        ->latest()
                        ->paginate(10);
                    return view('admin.users', compact('users'));
                
                case 'repairs':
                    $repairs = Repair::with(['user', 'technician'])
                        ->whereHas('user', function($q) use ($query) {
                            $q->where('name', 'like', "%{$query}%");
                        })
                        ->orWhere('device_type', 'like', "%{$query}%")
                        ->orWhere('issue_description', 'like', "%{$query}%")
                        ->orWhere('status', 'like', "%{$query}%")
                        ->latest()
                        ->paginate(10);
                    return view('admin.repairs', compact('repairs'));
                
                case 'payments':
                    $payments = Payment::with(['user', 'repair'])
                        ->whereHas('user', function($q) use ($query) {
                            $q->where('name', 'like', "%{$query}%");
                        })
                        ->orWhere('status', 'like', "%{$query}%")
                        ->orWhere('payment_method', 'like', "%{$query}%")
                        ->latest()
                        ->paginate(10);
                    return view('admin.payments', compact('payments'));
                
                case 'loyalty':
                    $loyalties = LoyaltyReward::with('user')
                        ->whereHas('user', function($q) use ($query) {
                            $q->where('name', 'like', "%{$query}%");
                        })
                        ->orWhere('reward_type', 'like', "%{$query}%")
                        ->orWhere('status', 'like', "%{$query}%")
                        ->latest()
                        ->paginate(10);
                    return view('admin.loyalty', compact('loyalties'));
                
                case 'spareparts':
                    $spareparts = Sparepart::where('name', 'like', "%{$query}%")
                        ->orWhere('brand', 'like', "%{$query}%")
                        ->orWhere('model', 'like', "%{$query}%")
                        ->orWhere('category', 'like', "%{$query}%")
                        ->latest()
                        ->paginate(10);
                    return view('admin.spareparts', compact('spareparts'));
                
                default:
                    return response()->json(['error' => 'Section not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Search error: ' . $e->getMessage()], 500);
        }
    }

    // REPAIR MANAGEMENT
    public function adminRepairs()
    {
        $repairs = Repair::with(['user', 'technician'])->latest()->paginate(10);
        return view('admin.repairs', compact('repairs'));
    }

    public function updateRepair(Request $request, $id)
    {
        try {
            $repair = Repair::findOrFail($id);
            
            $validated = $request->validate([
                'status' => 'required|in:pending,in_progress,finished,cancelled',
                'technician' => 'nullable|string|max:255',
                'cost' => 'nullable|numeric|min:0'
            ]);

            $repair->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Repair updated successfully',
                'repair' => $repair
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating repair: ' . $e->getMessage()
            ], 500);
        }
    }

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
                'message' => 'Error deleting repair: ' . $e->getMessage()
            ], 500);
        }
    }

    // PAYMENT MANAGEMENT
    public function adminPayments()
    {
        $payments = Payment::with(['user', 'repair'])->latest()->paginate(10);
        return view('admin.payments', compact('payments'));
    }

    public function updatePayment(Request $request, $id)
    {
        try {
            $payment = Payment::findOrFail($id);
            
            $validated = $request->validate([
                'status' => 'required|in:unpaid,paid',
                'payment_method' => 'required|in:cash,transfer,ewallet',
                'amount' => 'required|numeric|min:0'
            ]);

            $payment->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Payment updated successfully',
                'payment' => $payment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating payment: ' . $e->getMessage()
            ], 500);
        }
    }

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
                'message' => 'Error deleting payment: ' . $e->getMessage()
            ], 500);
        }
    }

    // LOYALTY MANAGEMENT
    public function adminLoyalty()
    {
        $loyalties = LoyaltyReward::with('user')->latest()->paginate(10);
        return view('admin.loyalty', compact('loyalties'));
    }

    public function updateLoyalty(Request $request, $id)
    {
        try {
            $loyalty = LoyaltyReward::findOrFail($id);
            
            $validated = $request->validate([
                'status' => 'required|in:claimed,used',
                'reward_type' => 'required|in:discount,gift',
                'points_used' => 'required|integer|min:0',
                'reward_value' => 'required|string|max:255'
            ]);

            $loyalty->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Loyalty reward updated successfully',
                'loyalty' => $loyalty
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating loyalty reward: ' . $e->getMessage()
            ], 500);
        }
    }

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
            return response()->json([
                'success' => false,
                'message' => 'Error deleting loyalty reward: ' . $e->getMessage()
            ], 500);
        }
    }

    // SPAREPARTS MANAGEMENT
    public function adminSpareparts()
    {
        $spareparts = Sparepart::latest()->paginate(10);
        return view('admin.spareparts', compact('spareparts'));
    }

    public function storeSparepart(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'brand' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'category' => 'required|in:Original,OEM,Aftermarket,Replica',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0'
            ]);

            $sparepart = Sparepart::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Sparepart created successfully',
                'sparepart' => $sparepart
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating sparepart: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateSparepart(Request $request, $id)
    {
        try {
            $sparepart = Sparepart::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'brand' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'category' => 'required|in:Original,OEM,Aftermarket,Replica',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0'
            ]);

            $sparepart->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Sparepart updated successfully',
                'sparepart' => $sparepart
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating sparepart: ' . $e->getMessage()
            ], 500);
        }
    }

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
            return response()->json([
                'success' => false,
                'message' => 'Error deleting sparepart: ' . $e->getMessage()
            ], 500);
        }
    }
}