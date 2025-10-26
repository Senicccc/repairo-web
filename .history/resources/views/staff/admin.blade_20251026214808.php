@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>
    <p class="mb-6 text-gray-600">Kelola semua data pengguna, servis, pembayaran, dan reward loyalty.</p>

    {{-- USERS TABLE --}}
    <div class="mb-10">
        <h2 class="text-xl font-semibold mb-3">Daftar Pengguna</h2>
        <table class="min-w-full border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2">ID</th>
                    <th class="border px-3 py-2">Nama</th>
                    <th class="border px-3 py-2">Email</th>
                    <th class="border px-3 py-2">Telepon</th>
                    <th class="border px-3 py-2">Role</th>
                    <th class="border px-3 py-2">Poin</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td class="border px-3 py-2">{{ $u->id }}</td>
                    <td class="border px-3 py-2">{{ $u->name }}</td>
                    <td class="border px-3 py-2">{{ $u->email }}</td>
                    <td class="border px-3 py-2">{{ $u->phone }}</td>
                    <td class="border px-3 py-2">{{ ucfirst($u->role) }}</td>
                    <td class="border px-3 py-2">{{ $u->loyalty_points }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- REPAIRS TABLE --}}
    <div class="mb-10">
        <h2 class="text-xl font-semibold mb-3">Data Perbaikan (Repairs)</h2>
        <table class="min-w-full border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2">ID</th>
                    <th class="border px-3 py-2">Customer</th>
                    <th class="border px-3 py-2">Perangkat</th>
                    <th class="border px-3 py-2">Keluhan</th>
                    <th class="border px-3 py-2">Teknisi</th>
                    <th class="border px-3 py-2">Biaya</th>
                    <th class="border px-3 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($repairs as $r)
                <tr>
                    <td class="border px-3 py-2">{{ $r->id }}</td>
                    <td class="border px-3 py-2">{{ $r->customer_name }}</td>
                    <td class="border px-3 py-2">{{ $r->phone_brand }} {{ $r->phone_model }}</td>
                    <td class="border px-3 py-2">{{ $r->complaint }}</td>
                    <td class="border px-3 py-2">{{ $r->technician }}</td>
                    <td class="border px-3 py-2">Rp{{ number_format($r->cost, 0, ',', '.') }}</td>
                    <td class="border px-3 py-2">{{ ucfirst($r->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- PAYMENTS TABLE --}}
    <div class="mb-10">
        <h2 class="text-xl font-semibold mb-3">Data Pembayaran</h2>
        <table class="min-w-full border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2">ID</th>
                    <th class="border px-3 py-2">Repair ID</th>
                    <th class="border px-3 py-2">Jumlah</th>
                    <th class="border px-3 py-2">Metode</th>
                    <th class="border px-3 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $p)
                <tr>
                    <td class="border px-3 py-2">{{ $p->id }}</td>
                    <td class="border px-3 py-2">{{ $p->repair_id }}</td>
                    <td class="border px-3 py-2">Rp{{ number_format($p->amount, 0, ',', '.') }}</td>
                    <td class="border px-3 py-2">{{ $p->payment_method }}</td>
                    <td class="border px-3 py-2">{{ ucfirst($p->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- LOYALTY REWARDS TABLE --}}
    <div>
        <h2 class="text-xl font-semibold mb-3">Data Loyalty Rewards</h2>
        <table class="min-w-full border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2">ID</th>
                    <th class="border px-3 py-2">User ID</th>
                    <th class="border px-3 py-2">Tipe Reward</th>
                    <th class="border px-3 py-2">Nilai Reward</th>
                    <th class="border px-3 py-2">Poin Digunakan</th>
                    <th class="border px-3 py-2">Kode Redeem</th>
                    <th class="border px-3 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rewards as $rw)
                <tr>
                    <td class="border px-3 py-2">{{ $rw->id }}</td>
                    <td class="border px-3 py-2">{{ $rw->user_id }}</td>
                    <td class="border px-3 py-2">{{ ucfirst($rw->reward_type) }}</td>
                    <td class="border px-3 py-2">{{ $rw->reward_value }}</td>
                    <td class="border px-3 py-2">{{ $rw->points_used }}</td>
                    <td class="border px-3 py-2">{{ $rw->redeem_code }}</td>
                    <td class="border px-3 py-2">{{ ucfirst($rw->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
