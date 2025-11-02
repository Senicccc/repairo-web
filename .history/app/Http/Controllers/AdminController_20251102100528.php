<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Repair;
use App\Models\Payment;
use App\Models\LoyaltyReward;

class AdminController extends Controller
{
    public function index()
    {
        $data = [
            'users' => User::all(),
            'repairs' => Repair::with(['user', 'payment'])->get(),
            'payments' => Payment::with(['repair.user'])->get(),
            'rewards' => LoyaltyReward::with('user')->get()
        ];
        
        return view('admin.index', $data);
    }
    
    public function getSection($section)
    {
        switch($section) {
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
                $data['spareparts'] = \App\Models\Sparepart::paginate(20);
                return view('admin.spareparts', $data);
                
            default:
                abort(404);
        }
    }
}