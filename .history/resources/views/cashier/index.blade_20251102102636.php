@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Cashier Dashboard</h2>
        <div class="flex gap-2">
            <a href="{{ route('cashier.loyalty.redeem') }}" 
               class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                Redeem Loyalty
            </a>
            <a href="{{ route('cashier.users.create') }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                + Create Account
            </a>
            <a href="{{ route('cashier.repairs.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                + Add New Repair
            </a>
        </div>
    </div>

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">📋</div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Repairs</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $repairs->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">⏳</div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $repairs->where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">✅</div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Finished</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $repairs->where('status', 'finished')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">💰</div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Paid</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $repairs->where('status', 'paid')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SEARCH -->
    <div class="mb-4">
        <input type="text" id="searchBar" placeholder="Search anything..." 
               class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring focus:ring-blue-200 focus:outline-none"
               onkeyup="filterTable()">
    </div>

    <!-- MAIN TABLE -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto max-h-[540px] overflow-y-auto">
            <table id="repairsTable" class="min-w-full text-sm text-gray-800">
                <thead class="bg-gray-100 text-gray-700 sticky top-0 shadow-sm">
                    <tr class="text-center uppercase text-xs font-semibold">
                        <th class="px-4 py-3 border">Tracking ID</th>
                        <th class="px-4 py-3 border">Customer</th>
                        <th class="px-4 py-3 border">Phone</th>
                        <th class="px-4 py-3 border">Brand</th>
                        <th class="px-4 py-3 border">Model</th>
                        <th class="px-4 py-3 border">Complaint</th>
                        <th class="px-4 py-3 border">Technician</th>
                        <th class="px-4 py-3 border">Status</th>
                        <th class="px-4 py-3 border">Payment</th>
                        <th class="px-4 py-3 border">Cost</th>
                        <th class="px-4 py-3 border">Invoice</th>
                        <th class="px-4 py-3 border">Redeem</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($repairs->sortByDesc('created_at') as $repair)
                    <tr class="text-center hover:bg-gray-50 transition border-b"
                        data-repair-id="{{ $repair->id }}" data-cost="{{ $repair->cost ?? 0 }}">
                        <td class="border px-3 py-2 font-medium text-blue-700">{{ $repair->tracking_id ?? 'N/A' }}</td>
                        <td class="border px-3 py-2">{{ $repair->user->name ?? $repair->customer_name ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $repair->phone ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $repair->phone_brand }}</td>
                        <td class="border px-3 py-2">{{ $repair->phone_model }}</td>
                        <td class="border px-3 py-2 text-left">{{ $repair->complaint }}</td>
                        <td class="border px-3 py-2">{{ $repair->technician ?? '-' }}</td>
                        
                        <td class="border px-3 py-2">
                            <span class="{{ \App\Models\Repair::getStatusColor($repair->status) }} px-2 py-1 rounded text-xs font-medium">
                                {{ \App\Models\Repair::getStatuses()[$repair->status] ?? ucfirst($repair->status) }}
                            </span>
                        </td>
                        
                        <td class="border px-3 py-2">
                            @if ($repair->status === 'finished' && (!$repair->payment || $repair->payment->status == 'unpaid'))
                                <a href="{{ route('cashier.payments.create', $repair->id) }}"
                                    class="bg-emerald-600 text-white px-3 py-1 rounded hover:bg-emerald-700 transition text-sm inline-block">
                                    Process Payment
                                </a>
                            @elseif ($repair->payment && $repair->payment->status == 'paid')
                                <span class="text-green-600 font-semibold">Paid</span>
                            @elseif ($repair->status === 'cancelled')
                                <span class="text-red-500 italic">Cancelled</span>
                            @else
                                <span class="text-gray-500 italic">Not Ready</span>
                            @endif
                        </td>
                        
                        <td class="border px-3 py-2 font-semibold">
                            {{ $repair->cost ? 'Rp ' . number_format($repair->cost, 0, ',', '.') : '-' }}
                        </td>
                        <td class="border px-3 py-2">
                            <a href="{{ route('invoice.show', $repair->id) }}" 
                               class="bg-gray-700 text-white px-3 py-1 rounded hover:bg-gray-800 transition text-sm inline-block">
                                Invoice
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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