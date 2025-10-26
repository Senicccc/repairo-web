@extends('layouts.app')

@section('content')
<div class="flex">
    <aside class="w-1/5 bg-gray-900 text-white min-h-screen p-5">
        <h2 class="text-2xl font-bold mb-8 text-center">Admin Panel</h2>
        <ul class="space-y-3">
            <li><a href="{{ route('admin.users') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Users</a></li>
            <li><a href="{{ route('admin.repairs') }}" class="block py-2 px-4 rounded bg-gray-700">Repairs</a></li>
            <li><a href="{{ route('admin.payments') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Payments</a></li>
            <li><a href="{{ route('admin.loyalty') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Loyalty Rewards</a></li>
        </ul>
    </aside>

    <main class="w-4/5 bg-white p-6">
        <div class="flex justify-between items-center mb-5">
            <h1 class="text-2xl font-bold">Daftar Servis (Repairs)</h1>
            <input type="text" id="searchInput" placeholder="Cari servis..." class="border px-3 py-1 rounded w-1/3">
        </div>

        <table class="min-w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">User</th>
                    <th class="px-4 py-2 border">Device</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Cost</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody id="dataTable">
                @foreach ($repairs as $repair)
                <tr>
                    <td class="border px-4 py-2">{{ $repair->id }}</td>
                    <td class="border px-4 py-2">{{ $repair->user->name ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ $repair->device }}</td>
                    <td class="border px-4 py-2">{{ $repair->status }}</td>
                    <td class="border px-4 py-2">{{ $repair->cost }}</td>
                    <td class="border px-4 py-2 text-center">
                        <button onclick="openEditModal({{ $repair->id }}, '{{ $repair->device }}', '{{ $repair->status }}', '{{ $repair->cost }}')" class="bg-blue-600 text-white px-3 py-1 rounded">Edit</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $repairs->links() }}</div>
    </main>
</div>

<!-- Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white p-6 rounded shadow-lg w-96">
        <h2 class="text-xl font-bold mb-4">Edit Servis</h2>
        <form id="editForm" method="POST" action="">
            @csrf @method('PUT')
            <input type="hidden" id="repairId" name="id">
            <div class="mb-3">
                <label class="block mb-1">Device</label>
                <input type="text" id="repairDevice" name="device" class="border px-3 py-1 w-full rounded">
            </div>
            <div class="mb-3">
                <label class="block mb-1">Status</label>
                <select id="repairStatus" name="status" class="border px-3 py-1 w-full rounded">
                    <option>Pending</option>
                    <option>In Progress</option>
                    <option>Finished</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="block mb-1">Cost</label>
                <input type="number" id="repairCost" name="cost" class="border px-3 py-1 w-full rounded">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeEditModal()" class="bg-gray-400 px-3 py-1 rounded">Batal</button>
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, device, status, cost) {
    document.getElementById('editModal').classList.remove('hidden')
    document.getElementById('repairId').value = id
    document.getElementById('repairDevice').value = device
    document.getElementById('repairStatus').value = status
    document.getElementById('repairCost').value = cost
    document.getElementById('editForm').action = `/admin/repairs/${id}`
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
