<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\LoyaltyReward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get repair statistics
        $totalRepairs = Repair::where('user_id', $user->id)->count();
        $completedRepairs = Repair::where('user_id', $user->id)->where('status', 'finished')->count();
        $inProgressRepairs = Repair::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'in_progress', 'diagnosed', 'waiting_parts'])
            ->count();
        $cancelledRepairs = Repair::where('user_id', $user->id)->where('status', 'cancelled')->count();
        
        // Get recent repairs
        $recentRepairs = Repair::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Get user points from users.loyalty_points column (migration adds this)
        $userPoints = $user->loyalty_points ?? 0;

        // Recent loyalty rewards/points activity (last 10)
        $recentPoints = \App\Models\LoyaltyReward::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('users.dashboard', compact(
            'totalRepairs',
            'completedRepairs',
            'inProgressRepairs',
            'cancelledRepairs',
            'recentRepairs',
            'userPoints',
            'recentPoints'
        ));
    }

    public function repairHistory()
    {
        $user = Auth::user();
        $repairs = Repair::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('users.repair-history', compact('repairs'));
    }

    public function showRepair($id)
    {
        $user = Auth::user();
        $repair = Repair::where('user_id', $user->id)
            ->with(['repairSpareparts', 'payment'])
            ->findOrFail($id);

        return view('users.repair-detail', compact('repair'));
    }
}