<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LoyaltyReward;
use Illuminate\Support\Facades\Auth;

class LoyaltyController extends Controller
{
    // add points per transaction
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

    // show available rewards
    public function showRewards()
    {
        $user = Auth::user();
        $points = $user->loyalty_points;

        $rewardOptions = [
            ['points' => 50, 'discount' => 20, 'gift1' => 'Charging Cable', 'gift2' => 'Cleaning Kit'],
            ['points' => 100, 'discount' => 50, 'gift1' => 'Earphones', 'gift2' => 'Mini Powerbank'],
            ['points' => 150, 'discount' => 90, 'gift1' => 'Smartphone Stand', 'gift2' => 'Wireless Mouse'],
            ['points' => 200, 'discount' => 130, 'gift1' => 'Bluetooth Speaker', 'gift2' => 'Headset'],
        ];

        return view('loyalty.rewards', compact('points', 'rewardOptions'));
    }

    // claim reward
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
    public function claimReward(Request $request)
    {
        $user = Auth::user();
        $pointsNeeded = (int) $request->points;
        $rewardType = $request->reward_type; // 'discount' atau 'gift'
        $rewardValue = $request->reward_value;

        if ($user->loyalty_points < $pointsNeeded) {
            return back()->with('error', 'Insufficient points.');
        }

        // kurangi poin dulu
        $user->loyalty_points -= $pointsNeeded;
        $user->save();

        // generate redeem code unik (8-12 chars)
        do {
            $code = strtoupper(Str::random(8)); // ex: 'A1B2C3D4'
        } while (DB::table('loyalty_rewards')->where('redeem_code', $code)->exists());

        $reward = LoyaltyReward::create([
            'user_id' => $user->id,
            'reward_type' => $rewardType,
            'reward_value' => $rewardValue,
            'points_used' => $pointsNeeded,
            'status' => 'claimed',
            'redeem_code' => $code
        ]);

        // flash code supaya user lihat langsung
        return back()->with('success', 'Reward claimed successfully!')
                    ->with('redeem_code', $code)
                    ->with('claimed_reward', $reward);
    }

}