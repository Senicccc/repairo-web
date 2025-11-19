@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">All Repairs</h2>
        <div class="flex gap-2">
            <a href="{{ route('cashier.dashboard') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                ← Dashboard
            </a>
            <a href="{{ route('cashier.repairs.create') }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                + New Repair
            </a>
        </div>
    </div>

    <!-- SEARCH -->
    <div class="mb-4">
        <input type="text" id="searchBar" placeholder="Search repairs..." 
               class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring focus:ring-blue-200 focus:outline-none"
               onkeyup="filterTable()">
    </div>

    <!-- REPAIRS TABLE -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table id="repairsTable" class="min-w-full text-sm text-gray-800">
                <thead class="bg-gray-100 text-gray-700">
                    <tr class="text-center uppercase text-xs font-semibold">
                        <th class="px-4 py-3 border">Tracking ID</th>
                        <th class="px-4 py-3 border">Customer</th>
                        <th class="px-4 py-3 border">Phone</th>
                        <th class="px-4 py-3 border">Device</th>
                        <th class="px-4 py-3 border">Status</th>
                        <th class="px-4 py-3 border">Cost</th>
                        <th class="px-4 py-3 border">Date</th>
                        <th class="px-4 py-3 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($repairs as $repair)
                    <tr class="text-center hover:bg-gray-50 transition border-b">
                        <td class="border px-3 py-2 font-medium text-blue-700">{{ $repair->tracking_id }}</td>
                        <td class="border px-3 py-2">{{ $repair->user->name ?? $repair->customer_name }}</td>
                        <td class="border px-3 py-2">{{ $repair->phone }}</td>
                        <td class="border px-3 py-2">{{ $repair->phone_brand }} {{ $repair->phone_model }}</td>
                        <td class="border px-3 py-2">
                            <span class="{{ \App\Models\Repair::getStatusColor($repair->status) }} px-2 py-1 rounded text-xs font-medium">
                                {{ \App\Models\Repair::getStatuses()[$repair->status] ?? ucfirst($repair->status) }}
                            </span>
                        </td>
                        <td class="border px-3 py-2 font-semibold">
                            {{ $repair->cost ? 'Rp ' . number_format($repair->cost, 0, ',', '.') : '-' }}
                        </td>
                        <td class="border px-3 py-2 text-xs">{{ $repair->created_at->format('M d, Y') }}</td>
                        <td class="border px-3 py-2">
                            <div class="flex justify-center gap-1">
                                <a href="{{ route('cashier.repairs.show', $repair->id) }}" 
                                   class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition">
                                    View
                                </a>
                                @if($repair->status === 'finished' && (!$repair->payment || $repair->payment->status !== 'paid'))
                                <a href="{{ route('cashier.payments.create', $repair->id) }}" 
                                   class="bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600 transition">
                                    Payment
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION -->
    <div class="mt-4">
        {{ $repairs->links() }}
    </div>
</div>

<script>
function filterTable() {
    const input = document.getElementById('searchBar').value.toLowerCase();
    const rows = document.querySelectorAll('#repairsTable tbody tr');
    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? '' : 'none';
    });
}
</script>
@endsection