@extends('layouts.app')

@section('content')
<div class="flex">
    <aside class="w-1/5 bg-gray-900 text-white min-h-screen p-5">
        <h2 class="text-2xl font-bold mb-8 text-center">Admin Panel</h2>
        <ul class="space-y-3">
            <li><a href="{{ route('admin.users') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Users</a></li>
            <li><a href="{{ route('admin.repairs') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Repairs</a></li>
            <li><a href="{{ route('admin.payments') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Payments</a></li>
            <li><a href="{{ route('admin.loyalty') }}" class="block py-2 px-4 rounded bg-gray-700">Loyalty Rewards</a></li>
        </ul>
    </aside>

    <main class="w-4/5 bg-white p-6">
        <div class="flex justify-between items-center mb-5">
            <h1 class="text-2xl font-bold">Loyalty Rewards</h1>
            <input type="text" id="searchInput" placeholder="Cari reward..." class="border px-3 py-1 rounded w-1/3">
        </div>

        <table class="min-w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">User</th>
                    <th class="px-4 py-2 border">Reward</th>
                    <th class="px-4 py-2 border">Points Required</th>
                    <th class="px-4 py-2 border">Code</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody id="dataTable">
                @foreach ($rewards as $reward)
                <tr>
                    <td class="border px-4 py-2">{{ $reward->id }}</td>
                    <td class="border px-4 py-2">{{ $reward->user->name ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ $reward->reward_name }}</td>
                    <td class="border px-4 py-2">{{ $reward->points_required }}</td>
                    <td class="border px-4 py-2">{{ $reward->code }}</td>
                    <td class="border px-4 py-2 text-center">
                        <button onclick="openEditModal({{ $reward->id }}, '{{ $reward->reward_name }}', '{{ $reward->points_required }}', '{{ $reward->code }}')" class="bg-blue-600 text-white px-3 py-1 rounded">Edit</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $rewards->links() }}</div>
    </main>
</div>

<div id="editModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white p-6 rounded shadow-lg w-96">
        <h2 class="text-xl font-bold mb-4">Edit Reward</h2>
        <form id="editForm" method="POST" action="">
            @csrf @method('PUT')
            <input type="hidden" id="rewardId" name="id">
            <div class="mb-3">
                <label class="block mb-1">Reward Name</label>
                <input type="text" id="rewardName" name="reward_name" class="border px-3 py-1 w-full rounded">
            </div>
            <div class="mb-3">
                <label class="block mb-1">Points Required</label>
                <input type="number" id="rewardPoints" name="points_required" class="border px-3 py-1 w-full rounded">
            </div>
            <div class="mb-3">
                <label class="block mb-1">Code</label>
                <input type="text" id="rewardCode" name="code" class="border px-3 py-1 w-full rounded">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeEditModal()" class="bg-gray-400 px-3 py-1 rounded">Batal</button>
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, name, points, code) {
    document.getElementById('editModal').classList.remove('hidden')
    document.getElementById('rewardId').value = id
    document.getElementById('rewardName').value = name
    document.getElementById('rewardPoints').value = points
    document.getElementById('rewardCode').value = code
    document.getElementById('editForm').action = `/admin/loyalty/${id}`
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
