<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LoyaltyReward;
use Illuminate\Support\Facades\DB;
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
            ['points' => 250, 'discount' => 180, 'gift1' => 'Wireless Charger', 'gift2' => 'Portable Speaker'],
            ['points' => 300, 'discount' => 230, 'gift1' => 'Smartwatch', 'gift2' => 'Noise Cancelling Headphones'],
            ['points' => 400, 'discount' => 300, 'gift1' => 'Tablet Stand', 'gift2' => 'Mini Projector'],
            ['points' => 500, 'discount' => 400, 'gift1' => 'VR Glasses', 'gift2' => 'Bluetooth Keyboard'],
        ];

        return view('loyalty.rewards', compact('points', 'rewardOptions'));
    }

    // claim reward
    public function claimReward(Request $request)
    {
        $user = Auth::user();
        $pointsNeeded = (int) $request->points;

        if ($user->loyalty_points < $pointsNeeded) {
            return back()->with('error', 'Insufficient points.');
        }

        $user->loyalty_points -= $pointsNeeded;
        $user->save();

        do {
            $code = strtoupper(Str::random(8));
        } while (DB::table('loyalty_rewards')->where('redeem_code', $code)->exists());

        // tentukan reward_type & reward_value dari input
        if ($request->has('discount')) {
            $rewardType = 'discount';
            $rewardValue = (int) $request->discount;
        } else {
            $rewardType = 'gift';
            $rewardValue = $request->gift; // gift1 atau gift2 dari view
        }

        $reward = LoyaltyReward::create([
            'user_id' => $user->id,
            'reward_type' => $rewardType,
            'reward_value' => $rewardValue,
            'points_used' => $pointsNeeded,
            'status' => 'claimed',
            'redeem_code' => $code
        ]);

        return back()->with('success', 'Reward claimed successfully!')
                     ->with('redeem_code', $code)
                     ->with('claimed_reward', $reward);
    }

    // redeem code for discount or gift
    public function redeemCode(Request $request)
    {
        $request->validate([
            'redeem_code' => 'required|string',
            'repair_id' => 'required|integer'
        ]);

        $reward = LoyaltyReward::where('redeem_code', strtoupper($request->redeem_code))
                    ->where('status', 'claimed')
                    ->first();

        if (!$reward) {
            return back()->with('error', 'Invalid or already used code.');
        }

        $repair = \App\Models\Repair::find($request->repair_id);
        if (!$repair) {
            return back()->with('error', 'Repair transaction not found.');
        }

        if ($reward->reward_type === 'discount') {
            $repair->repair_cost = max(0, $repair->repair_cost - (int)$reward->reward_value);
            $repair->save();
        }

        $reward->status = 'used';
        $reward->save();

        if ($reward->reward_type === 'gift') {
            DB::table('loyalty_reward_redemptions')->insert([
                'loyalty_reward_id' => $reward->id,
                'repair_id' => $repair->id,
                'redeemed_by' => Auth::id(),
                'created_at' => now(),
            ]);
        }


        return back()->with('success', $reward->reward_type === 'discount' 
            ? 'Discount applied: Rp' . number_format($reward->reward_value) 
            : 'Gift redemption recorded: ' . $reward->reward_value);
    }

    // LoyaltyController.php
public function check(Request $request)
{
    $code = $request->redeem_code;

    $redeem = Loyalty::where('code', $code)->first(); // cek kode di DB

    if($redeem) {
        return response()->json([
            'valid' => true,
            'claim' => $redeem->claim_name
        ]);
    } else {
        return response()->json(['valid' => false]);
    }
}

}