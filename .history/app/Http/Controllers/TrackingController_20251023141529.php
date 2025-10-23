<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Repair;

class TrackingController extends Controller
{
    public function index()
    {
        return view('tracking.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'tracking_id' => 'required|string'
        ]);

        $repair = Repair::with('user', 'payment')
                    ->where('tracking_id', $request->tracking_id)
                    ->first();

        return view('tracking.index', compact('repair'));
    }
}