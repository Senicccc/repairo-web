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
        ];

        return view('loyalty.rewards', compact('points', 'rewardOptions'));
    }

    // claim reward
    public function claimReward(Request $request)
    {
        $user = Auth::user();
        $pointsNeeded = (int) $request->points;
        $rewardType = $request->reward_type;
        $rewardValue = $request->reward_value;

        if ($user->loyalty_points < $pointsNeeded) {
            return back()->with('error', 'Insufficient points.');
        }

        $user->loyalty_points -= $pointsNeeded;
        $user->save();

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

        return back()->with('success', 'Reward claimed successfully!')
                    ->with('redeem_code', $code)
                    ->with('claimed_reward', $reward);
    }

    public function redeemCode(Request $request)
{
    $request->validate([
        'redeem_code' => 'required|string',
        'repair_id' => 'required|integer'
    ]);

    $code = strtoupper($request->redeem_code);
    $reward = LoyaltyReward::where('redeem_code', $code)
                ->where('status', 'claimed')
                ->first();

    if (!$reward) {
        return back()->with('error', 'Invalid or already used code.');
    }

    // ambil repair yang akan dipakai diskon (pastikan repair ada)
    $repair = \App\Models\Repair::find($request->repair_id);
    if (!$repair) {
        return back()->with('error', 'Repair transaction not found.');
    }

    // jika reward adalah discount, apply discount; jika gift, tandai sebagai 'used' dan proses pengambilan gift
    if ($reward->reward_type === 'discount') {
        // reward_value di DB mungkin disimpan sebagai angka (20 untuk 20k) atau string "20000", pastikan format
        // contoh kita simpan diskon dalam rupiah; jika semula diskon disimpan sebagai '20' (meaning 20k),
        // kamu bisa ubah sesuai implementasimu. Berikut anggap reward_value berisi angka rupiah langsung.
        $discountAmount = (int) $reward->reward_value; // ex: 20000

        // terapkan diskon ke repair (contoh sederhana: kurangi repair_cost)
        $repair->repair_cost = max(0, $repair->repair_cost - $discountAmount);
        $repair->save();

        // tandai reward sudah dipakai
        $reward->status = 'used';
        $reward->save();

        return back()->with('success', 'Redeem successful. Discount applied: Rp' . number_format($discountAmount));
    }

    // jika gift
    if ($reward->reward_type === 'gift') {
        // tandai sudah diambil / di-proses oleh kasir. Implementasi: bisa set status 'used' dan simpan who_redeemed, redeemed_at
        $reward->status = 'used';
        $reward->save();

        // optionally simpan relasi ke repair_id atau transaksi pengambilan
        \DB::table('loyalty_reward_redemptions')->insert([
            'loyalty_reward_id' => $reward->id,
            'repair_id' => $repair->id,
            'redeemed_by' => auth()->id(),
            'created_at' => now(),
        ]);

        return back()->with('success', 'Gift redemption recorded: ' . $reward->reward_value);
    }

    return back()->with('error', 'Unhandled reward type.');
}


}