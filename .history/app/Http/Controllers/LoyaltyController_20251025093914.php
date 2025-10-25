<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LoyaltyReward;
use Illuminate\Support\Facades\Auth;

class LoyaltyController extends Controller
{
    // add point per transaction
    public function addPoints($userId, $totalCost)
    {
        $user = User::find($userId);

        if ($totalCost < 500000) $points = 20;
        elseif ($totalCost < 1000000) $points = 30;
        elseif ($totalCost < 1500000) $points = 40;
        elseif ($totalCost < 2000000) $points = 50;
        else $points = 50 + floor(($totalCost - 2000000) / 500000) * 10;

        $user->loyalty_points += $points;
        $user->save();

        return $points;
    }

    // show rewards
    public function showRewards()
    {
        $user = Auth::user();
        $points = $user->loyalty_points;

        $rewardOptions = [
            ['points' => 50, 'diskon' => 20000, 'aks1' => 'Kabel Charger', 'aks2' => 'Cleaning Kit'],
            ['points' => 100, 'diskon' => 50000, 'aks1' => 'Earphone', 'aks2' => 'Power Bank Mini'],
            ['points' => 150, 'diskon' => 90000, 'aks1' => 'Voucher Rp50.000', 'aks2' => 'Case HP'],
            ['points' => 200, 'diskon' => 130000, 'aks1' => 'Voucher Rp100.000', 'aks2' => 'Headset/ Speaker'],
        ];

        return view('loyalty.rewards', compact('points', 'rewardOptions'));
    }

    // claim reward
    public function claimReward(Request $request)
    {
        $user = Auth::user();
        $pointsNeeded = $request->points;
        $rewardType = $request->reward_type;
        $rewardValue = $request->reward_value;

        if ($user->loyalty_points < $pointsNeeded) {
            return back()->with('error', 'Point');
        }

        $user->loyalty_points -= $pointsNeeded;
        $user->save();

        LoyaltyReward::create([
            'user_id' => $user->id,
            'reward_type' => $rewardType,
            'reward_value' => $rewardValue,
            'points_used' => $pointsNeeded,
            'status' => 'claimed'
        ]);

        return back()->with('success', 'Reward berhasil diklaim!');
    }
}