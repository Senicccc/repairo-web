@extends('layouts.app')

@section('content')
<div class="flex">
    <aside class="w-1/5 bg-gray-900 text-white min-h-screen p-5">
        <h2 class="text-2xl font-bold mb-8 text-center">Admin Panel</h2>
        <ul class="space-y-3">
            <li><a href="{{ route('admin.users') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Users</a></li>
            <li><a href="{{ route('repairs') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Repairs</a></li>
            <li><a href="{{ route('admin.payments') }}" class="block py-2 px-4 rounded bg-gray-700">Payments</a></li>
            <li><a href="{{ route('admin.loyalty') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Loyalty Rewards</a></li>
        </ul>
    </aside>

    <main class="w-4/5 bg-white p-6">
        <div class="flex justify-between items-center mb-5">
            <h1 class="text-2xl font-bold">Data Pembayaran</h1>
            <input type="text" id="searchInput" placeholder="Cari pembayaran..." class="border px-3 py-1 rounded w-1/3">
        </div>

        <table class="min-w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">User</th>
                    <th class="px-4 py-2 border">Metode</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Jumlah</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody id="dataTable">
                @foreach ($payments as $payment)
                <tr>
                    <td class="border px-4 py-2">{{ $payment->id }}</td>
                    <td class="border px-4 py-2">{{ $payment->repair->user->name ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ $payment->payment_method }}</td>
                    <td class="border px-4 py-2">{{ $payment->status }}</td>
                    <td class="border px-4 py-2">{{ $payment->amount }}</td>
                    <td class="border px-4 py-2 text-center">
                        <button onclick="openEditModal({{ $payment->id }}, '{{ $payment->payment_method }}', '{{ $payment->status }}', '{{ $payment->amount }}')" class="bg-blue-600 text-white px-3 py-1 rounded">Edit</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $payments->links() }}</div>
    </main>
</div>

<div id="editModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white p-6 rounded shadow-lg w-96">
        <h2 class="text-xl font-bold mb-4">Edit Pembayaran</h2>
        <form id="editForm" method="POST" action="">
            @csrf @method('PUT')
            <input type="hidden" id="paymentId" name="id">
            <div class="mb-3">
                <label class="block mb-1">Metode Pembayaran</label>
                <input type="text" id="paymentMethod" name="payment_method" class="border px-3 py-1 w-full rounded">
            </div>
            <div class="mb-3">
                <label class="block mb-1">Status</label>
                <select id="paymentStatus" name="status" class="border px-3 py-1 w-full rounded">
                    <option>Pending</option>
                    <option>Paid</option>
                    <option>Failed</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="block mb-1">Jumlah</label>
                <input type="number" id="paymentAmount" name="amount" class="border px-3 py-1 w-full rounded">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeEditModal()" class="bg-gray-400 px-3 py-1 rounded">Batal</button>
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, method, status, amount) {
    document.getElementById('editModal').classList.remove('hidden')
    document.getElementById('paymentId').value = id
    document.getElementById('paymentMethod').value = method
    document.getElementById('paymentStatus').value = status
    document.getElementById('paymentAmount').value = amount
    document.getElementById('editForm').action = `/admin/payments/${id}`
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden')
}
document.getElementById('searchInput').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase()
    document.querySelectorAll('#dataTable tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none'
    })
})
</script>
@endsection
