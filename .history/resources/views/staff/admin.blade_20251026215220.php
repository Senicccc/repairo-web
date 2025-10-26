@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

    <h2 class="text-xl font-semibold mb-2">Daftar Pengguna</h2>
    <table class="min-w-full border mb-6">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Nama</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Role</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td class="border px-4 py-2">{{ $user->id }}</td>
                <td class="border px-4 py-2">{{ $user->name }}</td>
                <td class="border px-4 py-2">{{ $user->email }}</td>
                <td class="border px-4 py-2">{{ $user->role }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="text-xl font-semibold mb-2">Daftar Servis (Repairs)</h2>
    <table class="min-w-full border mb-6">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">User</th>
                <th class="px-4 py-2">Device</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Biaya</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($repairs as $repair)
            <tr>
                <td class="border px-4 py-2">{{ $repair->id }}</td>
                <td class="border px-4 py-2">{{ $repair->user->name ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $repair->device ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $repair->status }}</td>
                <td class="border px-4 py-2">{{ $repair->cost ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="text-xl font-semibold mb-2">Pembayaran (Payments)</h2>
    <table class="min-w-full border">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">User</th>
                <th class="px-4 py-2">Repair ID</th>
                <th class="px-4 py-2">Metode</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
            <tr>
                <td class="border px-4 py-2">{{ $payment->id }}</td>
                <td class="border px-4 py-2">{{ $payment->repair->user->name ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $payment->repair_id }}</td>
                <td class="border px-4 py-2">{{ $payment->payment_method }}</td>
                <td class="border px-4 py-2">{{ $payment->status }}</td>
                <td class="border px-4 py-2">{{ $payment->amount }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
