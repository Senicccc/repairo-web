<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index');
    }

    public function userDashboard()
    {
        return view('users.dashboard');
    }

    public function cashierDashboard()
    {
        $repairs = \App\Models\Repair::with('user', 'payment')->get();
        $users = \App\Models\User::orderBy('name')->get();
        return view('staff.cashier', compact('repairs', 'users'));
    }

    public function technicianDashboard()
    {
        $user = Auth::user();

        // If the DB has technician_id column, use it; otherwise fall back to the legacy 'technician' string
        if (Schema::hasColumn('repairs', 'technician_id')) {
            // Current jobs assigned to this technician (in_progress) - maksimal 6 job
            $currentJobs = \App\Models\Repair::with('user','payment','technicianUser')
                ->where('technician_id', $user->id)
                ->where('status', 'in_progress')
                ->orderBy('updated_at', 'desc')
                ->limit(6) // Batasi tampilan untuk konsistensi
                ->get();

            // Count current active jobs
            $currentJobCount = \App\Models\Repair::where('technician_id', $user->id)
                ->where('status', 'in_progress')
                ->count();

            // Available jobs not yet claimed
            $availableJobs = \App\Models\Repair::with('user','payment')
                ->whereNull('technician_id')
                ->where('status', 'pending')
                ->get();

            // Completed or other jobs for history
            $otherJobs = \App\Models\Repair::with('user','payment','technicianUser')
                ->where(function ($q) use ($user) {
                    $q->where('status', 'finished')
                      ->orWhere('technician_id', $user->id);
                })
                ->orderByDesc('updated_at')
                ->get();
        } else {
            // Legacy fallback: technician stored as name in 'technician' column
            $currentJobs = \App\Models\Repair::with('user','payment')
                ->where('technician', $user->name)
                ->where('status', 'in_progress')
                ->orderBy('updated_at', 'desc')
                ->limit(6) // Batasi tampilan untuk konsistensi
                ->get();

            // Count current active jobs for legacy system
            $currentJobCount = \App\Models\Repair::where('technician', $user->name)
                ->where('status', 'in_progress')
                ->count();

            $availableJobs = \App\Models\Repair::with('user','payment')
                ->where(function ($q) {
                    $q->whereNull('technician')->orWhere('technician', '');
                })
                ->where('status', 'pending')
                ->get();

            $otherJobs = \App\Models\Repair::with('user','payment')
                ->where(function ($q) use ($user) {
                    $q->where('status', 'finished')
                      ->orWhere('technician', $user->name);
                })
                ->orderByDesc('updated_at')
                ->get();
        }

        return view('staff.technician', compact('currentJobs', 'currentJobCount', 'availableJobs', 'otherJobs'));
    }

    public function adminDashboard()
    {
        return view('staff.admin');
    }

    public function dashboardRedirect()
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        switch ($user->role) {
            case 'user':
                return redirect()->route('user.dashboard');
            case 'cashier':
                return redirect()->route('cashier.dashboard');
            case 'technician':
                return redirect()->route('technician.dashboard');
            case 'admin':
                return redirect()->route('admin.dashboard');
            default:
                return redirect('/'); 
        }
    }

    /**
     * Method untuk mengambil job baru oleh teknisi
     */
    public function takeJob(Request $request, $repairId)
    {
        $user = Auth::user();
        
        if ($user->role !== 'technician') {
            return redirect()->back()->with('error', 'Hanya teknisi yang dapat mengambil job.');
        }

        try {
            DB::beginTransaction();

            // Cek jumlah job aktif teknisi
            if (Schema::hasColumn('repairs', 'technician_id')) {
                $currentJobCount = \App\Models\Repair::where('technician_id', $user->id)
                    ->where('status', 'in_progress')
                    ->count();
            } else {
                $currentJobCount = \App\Models\Repair::where('technician', $user->name)
                    ->where('status', 'in_progress')
                    ->count();
            }

            // Validasi maksimal 6 job
            if ($currentJobCount >= 6) {
                return redirect()->back()->with('error', 'Anda sudah mencapai batas maksimal 6 job aktif. Selesaikan beberapa job sebelum mengambil yang baru.');
            }

            $repair = \App\Models\Repair::findOrFail($repairId);

            // Validasi job tersedia
            if ($repair->status !== 'pending') {
                return redirect()->back()->with('error', 'Job tidak tersedia untuk diambil.');
            }

            // Update repair
            if (Schema::hasColumn('repairs', 'technician_id')) {
                $repair->technician_id = $user->id;
            } else {
                $repair->technician = $user->name;
            }
            
            $repair->status = 'in_progress';
            $repair->save();

            DB::commit();

            return redirect()->back()->with('success', 'Job berhasil diambil!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Method untuk menyelesaikan job
     */
    public function completeJob(Request $request, $repairId)
    {
        $user = Auth::user();
        
        if ($user->role !== 'technician') {
            return redirect()->back()->with('error', 'Hanya teknisi yang dapat menyelesaikan job.');
        }

        try {
            DB::beginTransaction();

            $repair = \App\Models\Repair::findOrFail($repairId);

            // Validasi kepemilikan job
            if (Schema::hasColumn('repairs', 'technician_id')) {
                if ($repair->technician_id !== $user->id) {
                    return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk job ini.');
                }
            } else {
                if ($repair->technician !== $user->name) {
                    return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk job ini.');
                }
            }

            // Update repair status
            $repair->status = 'finished';
            $repair->save();

            DB::commit();

            return redirect()->back()->with('success', 'Job berhasil diselesaikan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}