<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use App\Models\RepairSparepart;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class TechnicianController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $activeSection = $request->get('section', 'active');

        if (Schema::hasColumn('repairs', 'technician_id')) {
            $currentJobs = Repair::with('user', 'payment', 'technicianUser', 'repairSpareparts')
                ->where('technician_id', $user->id)
                ->whereIn('status', ['in_progress', 'diagnosed', 'waiting_parts'])
                ->get();

            $availableJobs = Repair::with('user', 'payment')
                ->whereNull('technician_id')
                ->where('status', 'pending')
                ->get();

            $otherJobs = Repair::with('user', 'payment', 'technicianUser', 'repairSpareparts')
                ->where('technician_id', $user->id)
                ->whereIn('status', ['finished', 'cancelled'])
                ->orderByDesc('updated_at')
                ->get();
        } else {
            $currentJobs = Repair::with('user', 'payment', 'repairSpareparts')
                ->where('technician', $user->name)
                ->whereIn('status', ['in_progress', 'diagnosed', 'waiting_parts'])
                ->get();

            $availableJobs = Repair::with('user', 'payment')
                ->where(function ($q) {
                    $q->whereNull('technician')->orWhere('technician', '');
                })
                ->where('status', 'pending')
                ->get();

            $otherJobs = Repair::with('user', 'payment', 'repairSpareparts')
                ->where('technician', $user->name)
                ->whereIn('status', ['finished', 'cancelled'])
                ->orderByDesc('updated_at')
                ->get();
        }

        return view('technician.index', compact('currentJobs', 'availableJobs', 'otherJobs', 'activeSection'));
    }

    public function claimJob($id)
    {
        $repair = Repair::findOrFail($id);
        $user = Auth::user();

        if ($repair->technician_id) {
            return redirect()->back()->with('error', 'This job has already been claimed.');
        }

        $activeCount = Repair::where('technician_id', $user->id)
            ->whereIn('status', ['in_progress', 'diagnosed', 'waiting_parts'])
            ->count();

        if ($activeCount >= 6) {
            return redirect()->back()->with('error', "You already have {$activeCount} active jobs.");
        }

        $repair->update([
            'technician_id' => $user->id,
            'technician' => $user->name,
            'status' => 'in_progress',
        ]);

        return redirect()->back()->with('success', 'You have taken the job.');
    }

    public function updateJob(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);
        $user = Auth::user();

        if ($repair->technician_id !== $user->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        DB::transaction(function () use ($request, $repair) {
            $repair->update([
                'diagnosis' => $request->diagnosis,
                'cost' => $request->cost,
                'status' => $request->status,
            ]);

            $this->updateRepairSparepartField($repair);
        });

        return back()->with('success', '✅ Job updated successfully!');
    }

    private function updateRepairSparepartField(Repair $repair)
    {
        $sparepartsList = RepairSparepart::where('repair_id', $repair->id)
            ->pluck('name')
            ->toArray();
        
        $repair->sparepart = implode(', ', $sparepartsList);
        $repair->save();
    }

    public function updateSparepartField($id)
    {
        $repair = Repair::findOrFail($id);
        $this->updateRepairSparepartField($repair);

        return response()->json(['success' => true]);
    }

    public function searchSpareparts(Request $request)
    {
        $query = $request->get('q');
        $spareparts = Sparepart::where('name', 'like', "%{$query}%")
            ->orWhere('brand', 'like', "%{$query}%")
            ->orWhere('model', 'like', "%{$query}%")
            ->where('stock', '>', 0)
            ->limit(10)
            ->get(['id', 'name', 'brand', 'model', 'category', 'price', 'stock']);

        return response()->json($spareparts);
    }

    public function getRepairSpareparts($id)
    {
        try {
            $spareparts = RepairSparepart::where('repair_id', $id)->get();
            
            return response()->json($spareparts);
            
        } catch (\Exception $e) {
            Log::error('Error getting repair spareparts: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to load spareparts'
            ], 500);
        }
    }
}