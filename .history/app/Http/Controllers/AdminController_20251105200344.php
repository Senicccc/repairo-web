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
        $data = [
            'users' => User::all(),
            'repairs' => Repair::with(['user', 'payment'])->get(),
            'payments' => Payment::with(['repair.user'])->get(),
            'rewards' => LoyaltyReward::with('user')->get(),
            'spareparts' => Sparepart::paginate(20),
        ];

        return view('admin.index', $data);
    }

    public function adminRepairs()
    {
        $repairs = Repair::with(['user', 'payment'])->get();
        return view('admin.repairs', compact('repairs'));
    }

    // Delete Repair
    public function deleteRepair($id)
    {
        $repair = Repair::findOrFail($id);
        $repair->delete();
        return response()->json(['message'=>'Repair deleted successfully']);
    }

    // Update Repair
    public function updateRepair(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);
        $data = $request->validate([
            'status' => 'nullable|string|in:pending,in_progress,finished,cancelled',
            'technician' => 'nullable|string|max:255',
            'cost' => 'nullable|numeric|min:0',
        ]);
        $repair->update($data);
        return response()->json(['message'=>'Repair updated successfully','repair'=>$repair]);
    }
}