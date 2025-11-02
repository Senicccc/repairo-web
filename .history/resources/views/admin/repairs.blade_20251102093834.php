<section id="repairs" class="tab-section">
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