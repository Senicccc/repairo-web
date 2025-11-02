<section id="loyalty" class="tab-section">
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