@extends('layouts.app')

@section('content')
<div class="flex">
    <!-- Sidebar -->
    <aside class="w-1/5 bg-gray-900 text-white min-h-screen p-4">
        <h2 class="text-xl font-bold mb-6 text-center">Admin Panel</h2>
        <ul>
            <li><a href="#users" class="block py-2 px-3 hover:bg-gray-700 rounded">Users</a></li>
            <li><a href="#repairs" class="block py-2 px-3 hover:bg-gray-700 rounded">Repairs</a></li>
            <li><a href="#payments" class="block py-2 px-3 hover:bg-gray-700 rounded">Payments</a></li>
            <li><a href="#loyalty" class="block py-2 px-3 hover:bg-gray-700 rounded">Loyalty Rewards</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="w-4/5 bg-white p-6 overflow-y-auto">
        <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

        <!-- Search + Per Page -->
        <div class="flex justify-between mb-4">
            <input type="text" id="searchInput" placeholder="Cari data..." class="border px-3 py-1 w-1/2 rounded">
            <select id="perPageSelect" class="border px-3 py-1 rounded">
                <option value="5">5 / page</option>
                <option value="10" selected>10 / page</option>
                <option value="25">25 / page</option>
                <option value="50">50 / page</option>
            </select>
        </div>

        <!-- Users -->
        <section id="users" class="mb-10">
            <h2 class="text-xl font-semibold mb-2">Daftar Pengguna</h2>
            <table class="min-w-full border mb-6 table-section">
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
        </section>

        <!-- Repairs -->
        <section id="repairs" class="mb-10">
            <h2 class="text-xl font-semibold mb-2">Daftar Servis (Repairs)</h2>
            <table class="min-w-full border mb-6 table-section">
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
        </section>

        <!-- Payments -->
        <section id="payments" class="mb-10">
            <h2 class="text-xl font-semibold mb-2">Pembayaran (Payments)</h2>
            <table class="min-w-full border mb-6 table-section">
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
        </section>

        <!-- Loyalty Rewards -->
        <section id="loyalty" class="mb-10">
            <h2 class="text-xl font-semibold mb-2">Loyalty Rewards</h2>
            <table class="min-w-full border mb-6 table-section">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">User</th>
                        <th class="px-4 py-2">Reward Name</th>
                        <th class="px-4 py-2">Points Required</th>
                        <th class="px-4 py-2">Code</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rewards as $reward)
                    <tr>
                        <td class="border px-4 py-2">{{ $reward->id }}</td>
                        <td class="border px-4 py-2">{{ $reward->user->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $reward->reward_name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $reward->points_required ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $reward->code ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </main>
</div>

<!-- Script untuk filter pencarian -->
<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    const value = this.value.toLowerCase();
    document.querySelectorAll('.table-section tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
    });
});
</script>
@endsection
