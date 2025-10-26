@extends('layouts.app')

@section('content')
<div class="flex">
    <aside class="w-1/5 bg-gray-900 text-white min-h-screen p-5">
        <h2 class="text-2xl font-bold mb-8 text-center">Admin Panel</h2>
        <ul class="space-y-3">
            <li><a href="{{ route('admin.users') }}" class="block py-2 px-4 rounded bg-gray-700">Users</a></li>
            <li><a href="{{ route('admin.repairs') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Repairs</a></li>
            <li><a href="{{ route('admin.payments') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Payments</a></li>
            <li><a href="{{ route('admin.loyalty') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Loyalty Rewards</a></li>
        </ul>
    </aside>

    <main class="w-4/5 bg-white p-6">
        <div class="flex justify-between items-center mb-5">
            <h1 class="text-2xl font-bold">Daftar Pengguna</h1>
            <input type="text" id="searchInput" placeholder="Cari pengguna..." class="border px-3 py-1 rounded w-1/3">
        </div>

        <table class="min-w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Role</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody id="dataTable">
                @foreach ($users as $user)
                <tr>
                    <td class="border px-4 py-2">{{ $user->id }}</td>
                    <td class="border px-4 py-2">{{ $user->name }}</td>
                    <td class="border px-4 py-2">{{ $user->email }}</td>
                    <td class="border px-4 py-2">{{ $user->role }}</td>
                    <td class="border px-4 py-2 text-center">
                        <button onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')" class="bg-blue-600 text-white px-3 py-1 rounded">Edit</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">{{ $users->links() }}</div>
    </main>
</div>

<!-- Modal Edit -->
<div id="editModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white p-6 rounded shadow-lg w-96">
        <h2 class="text-xl font-bold mb-4">Edit Pengguna</h2>
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')
            <input type="hidden" id="userId" name="id">
            <div class="mb-3">
                <label class="block mb-1">Nama</label>
                <input type="text" id="userName" name="name" class="border px-3 py-1 w-full rounded">
            </div>
            <div class="mb-3">
                <label class="block mb-1">Email</label>
                <input type="email" id="userEmail" name="email" class="border px-3 py-1 w-full rounded">
            </div>
            <div class="mb-3">
                <label class="block mb-1">Role</label>
                <select id="userRole" name="role" class="border px-3 py-1 w-full rounded">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeEditModal()" class="bg-gray-400 px-3 py-1 rounded">Batal</button>
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, name, email, role) {
    document.getElementById('editModal').classList.remove('hidden')
    document.getElementById('userId').value = id
    document.getElementById('userName').value = name
    document.getElementById('userEmail').value = email
    document.getElementById('userRole').value = role
    document.getElementById('editForm').action = `/admin/users/${id}`
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
