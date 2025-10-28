<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class RepairController extends Controller
{
    /**
     * Display all repairs (for admin/cashier)
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'technician') {
            $repairs = Repair::with(['user', 'payment'])->get();
            return view('staff.cashier', compact('repairs'));
        }

        abort(403, "Technicians should use technicianDashboard()");
    }

    /**
     * Store new repair
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'phone_brand' => 'required|string|max:255',
            'phone_model' => 'required|string|max:255',
            'imei' => 'nullable|string|max:255',
            'complaint' => 'required|string',
            'cost' => 'nullable|numeric',
        ]);

        $lastRepair = Repair::orderBy('id', 'desc')->first();
        $nextNumber = $lastRepair ? str_pad($lastRepair->id + 1, 4, '0', STR_PAD_LEFT) : '0001';
        $trackingId = 'SRV' . now()->format('Ymd') . '-' . $nextNumber;

        $user = User::firstOrCreate(
            ['phone' => $validated['phone']],
            [
                'name' => $validated['name'] ?? 'Customer',
                'email' => strtolower(str_replace(' ', '', $validated['phone'])) . '@repairo.local',
                'password' => bcrypt('password123'),
                'role' => 'user'
            ]
        );

        $repair = new Repair();
        $repair->tracking_id = $trackingId;
        $repair->user_id = $user->id;
        $repair->phone = $validated['phone'];
        $repair->customer_name = $validated['name'] ?? null;
        $repair->phone_brand = $validated['phone_brand'];
        $repair->phone_model = $validated['phone_model'];
        $repair->imei = $validated['imei'] ?? null;
        $repair->complaint = $validated['complaint'];
        $repair->status = 'pending';
        $repair->technician = null;
        $repair->cost = $validated['cost'] ?? null;
        $repair->save();

        return redirect()->route('cashier.dashboard')->with('success', 'Repair record created successfully.');
    }

    /**
     * Update repair
     */
    public function update(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);

        $data = $request->validate([
            'status' => 'nullable|in:pending,in_progress,finished,cancelled',
            'diagnosis' => 'nullable|string',
            'cost' => 'nullable|numeric',
        ]);

        $repair->update($data);
        return redirect()->back()->with('success', 'Repair updated successfully.');
    }

    /**
     * Technician claims a job
     */
    public function claim(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);
        $user = Auth::user();

        if (Schema::hasColumn('repairs', 'technician_id')) {
            if ($repair->technician_id) {
                return redirect()->back()->with('error', 'This job has already been claimed.');
            }

            $activeCount = Repair::where('technician_id', $user->id)
                                ->where('status', 'in_progress')
                                ->count();
            if ($activeCount >= 6) {
                return redirect()->back()->with('error', "You already have {$activeCount} active jobs.");
            }

            $repair->technician_id = $user->id;
            $repair->technician = $user->name;
            $repair->status = 'in_progress';
        } else {
            if ($repair->technician) {
                return redirect()->back()->with('error', 'This job has already been claimed.');
            }

            $activeCount = Repair::where('technician', $user->name)
                                ->where('status', 'in_progress')
                                ->count();
            if ($activeCount >= 6) {
                return redirect()->back()->with('error', "You already have {$activeCount} active jobs.");
            }

            $repair->technician = $user->name;
            $repair->status = 'in_progress';
        }

        if ($request->filled('sparepart')) $repair->sparepart = $request->input('sparepart');
        if ($request->filled('diagnosis')) $repair->diagnosis = $request->input('diagnosis');
        if ($request->filled('status')) $repair->status = $request->input('status');

        $repair->save();

        return redirect()->back()->with('success', 'You have taken the job.');
    }


    
    /**
     * Delete repair
     */
    public function destroy($id)
    {
        $repair = Repair::findOrFail($id);
        $repair->delete();

        return response()->json(['message' => 'Repair deleted successfully.']);
    }

    /**
     * Technician dashboard
     */
    public function technicianDashboard()
    {
        $user = Auth::user();

        $currentJobs = Repair::where('technician_id', $user->id)
                            ->where('status', 'in_progress')
                            ->with(['user', 'payment'])
                            ->get();

        $otherJobs = Repair::where('technician_id', $user->id)
                        ->where('status', 'finished')
                        ->with(['user', 'payment'])
                        ->get();

        $availableJobs = Repair::where('status', 'pending')
                            ->with(['user', 'payment'])
                            ->get();

        return view('staff.technician', compact('currentJobs', 'availableJobs', 'otherJobs'));
    }
}