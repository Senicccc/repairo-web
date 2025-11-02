<section id="users" class="tab-section">
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