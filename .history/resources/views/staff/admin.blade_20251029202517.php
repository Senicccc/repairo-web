@extends('layouts.app')

@section('content')
<div class="flex">
    <!-- Sidebar -->
    <aside class="w-1/5 bg-gray-900 text-white min-h-screen p-4">
        <h2 class="text-xl font-bold mb-6 text-center">Admin Panel</h2>
        <ul class="space-y-1">
            <li><button onclick="showSection('users')" class="tab-btn w-full text-left py-2 px-3 rounded hover:bg-gray-700">👤 Users</button></li>
            <li><button onclick="showSection('repairs')" class="tab-btn w-full text-left py-2 px-3 rounded hover:bg-gray-700">🔧 Repairs</button></li>
            <li><button onclick="showSection('payments')" class="tab-btn w-full text-left py-2 px-3 rounded hover:bg-gray-700">💳 Payments</button></li>
            <li><button onclick="showSection('loyalty')" class="tab-btn w-full text-left py-2 px-3 rounded hover:bg-gray-700">🎁 Loyalty</button></li>
        </ul>
    </aside>

    <!-- Main -->
    <main class="w-4/5 bg-white p-6 overflow-y-auto">
        <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

        <!-- USERS -->
        <section id="users" class="tab-section hidden">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-xl font-semibold">Users</h2>
                <button class="bg-green-600 text-white px-3 py-1 rounded">+ Add User</button>
            </div>
            <input type="text" placeholder="Cari user..." class="border px-3 py-1 mb-2 w-1/2 rounded search">
            <table class="min-w-full border text-sm">
                <thead class="bg-gray-200">
                    <tr><th>ID</th><th>Nama</th><th>Email</th><th>Role</th></tr>
                </thead>
                <tbody>
                    @foreach ($users as $u)
                    <tr><td>{{ $u->id }}</td><td>{{ $u->name }}</td><td>{{ $u->email }}</td><td>{{ $u->role }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <!-- REPAIRS -->
        <section id="repairs" class="tab-section hidden">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-xl font-semibold">Repairs</h2>
                <button class="bg-blue-600 text-white px-3 py-1 rounded">+ Add Repair</button>
            </div>
            <input type="text" placeholder="Cari repair..." class="border px-3 py-1 mb-2 w-1/2 rounded search">
            <table class="min-w-full border text-sm">
                <thead class="bg-gray-200">
                    <tr><th>ID</th><th>User</th><th>Device</th><th>Status</th><th>Biaya</th></tr>
                </thead>
                <tbody>
                    @foreach ($repairs as $r)
                    <tr><td>{{ $r->id }}</td><td>{{ $r->user->name ?? '-' }}</td><td>{{ $r->device ?? '-' }}</td><td>{{ $r->status }}</td><td>{{ $r->cost ?? '-' }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <!-- PAYMENTS -->
        <section id="payments" class="tab-section hidden">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-xl font-semibold">Payments</h2>
                <button class="bg-yellow-500 text-white px-3 py-1 rounded">+ Add Payment</button>
            </div>
            <input type="text" placeholder="Cari payment..." class="border px-3 py-1 mb-2 w-1/2 rounded search">
            <table class="min-w-full border text-sm">
                <thead class="bg-gray-200">
                    <tr><th>ID</th><th>User</th><th>Repair ID</th><th>Metode</th><th>Status</th><th>Jumlah</th></tr>
                </thead>
                <tbody>
                    @foreach ($payments as $p)
                    <tr><td>{{ $p->id }}</td><td>{{ $p->repair->user->name ?? '-' }}</td><td>{{ $p->repair_id }}</td><td>{{ $p->payment_method }}</td><td>{{ $p->status }}</td><td>{{ $p->amount }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <!-- LOYALTY -->
        <section id="loyalty" class="tab-section hidden">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-xl font-semibold">Loyalty Rewards</h2>
                <button class="bg-purple-600 text-white px-3 py-1 rounded">+ Add Reward</button>
            </div>
            <input type="text" placeholder="Cari reward..." class="border px-3 py-1 mb-2 w-1/2 rounded search">
            <table class="min-w-full border text-sm">
                <thead class="bg-gray-200">
                    <tr><th>ID</th><th>User</th><th>Reward</th><th>Points</th><th>Code</th></tr>
                </thead>
                <tbody>
                    @foreach ($rewards as $rw)
                    <tr><td>{{ $rw->id }}</td><td>{{ $rw->user->name ?? '-' }}</td><td>{{ $rw->reward_name ?? '-' }}</td><td>{{ $rw->points_required ?? '-' }}</td><td>{{ $rw->code ?? '-' }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </main>
</div>

<script>
function showSection(id){
    document.querySelectorAll('.tab-section').forEach(s=>s.classList.add('hidden'));
    document.getElementById(id).classList.remove('hidden');
    document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('bg-gray-700'));
    event.target.classList.add('bg-gray-700');
}
document.addEventListener('DOMContentLoaded',()=>showSection('users'));

document.querySelectorAll('.search').forEach(input=>{
    input.addEventListener('keyup', function(){
        const val=this.value.toLowerCase();
        this.nextElementSibling.querySelectorAll('tbody tr').forEach(row=>{
            row.style.display=row.textContent.toLowerCase().includes(val)?'':'none';
        });
    });
});
</script>
@endsection
