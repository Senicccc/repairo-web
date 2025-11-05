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

    // Admin Repairs List (optional, if ingin route khusus)
    public function adminRepairs()
    {
        $repairs = Repair::with(['user', 'payment'])->get();
        return view('admin.repairs', compact('repairs'));
    }

    // Delete Repair
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
}