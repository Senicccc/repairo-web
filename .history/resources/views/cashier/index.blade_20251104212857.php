@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- SIDEBAR -->
    <aside class="w-64 bg-blue-900 text-white flex flex-col p-5 shadow-lg">
        <h2 class="text-xl font-bold mb-6 tracking-wide">Cashier Menu</h2>
        <nav class="space-y-3">
            <a href="{{ route('cashier.dashboard') }}" 
               class="block px-4 py-2 rounded-lg hover:bg-blue-800 transition {{ request()->routeIs('cashier.dashboard') ? 'bg-blue-800' : '' }}">
               🏠 Dashboard
            </a>
            <a href="{{ route('cashier.loyalty.redeem') }}" 
               class="block px-4 py-2 rounded-lg hover:bg-blue-800 transition">
               🎁 Redeem Loyalty
            </a>
            <a href="{{ route('cashier.users.create') }}" 
               class="block px-4 py-2 rounded-lg hover:bg-blue-800 transition">
               👤 Create Account
            </a>
            <a href="{{ route('cashier.repairs.create') }}" 
               class="block px-4 py-2 rounded-lg hover:bg-blue-800 transition">
               🔧 Add New Repair
            </a>
        </nav>
        <div class="mt-auto pt-6 border-t border-blue-700 text-sm text-blue-200">
            <p>Repairo System</p>
            <p class="text-xs">Cashier Panel</p>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-8">
        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Cashier Dashboard</h2>
        </div>

        <!-- STATS -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">📋</div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Repairs</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $repairs->total() }}</p>
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
            <input type="text" id="searchBar" placeholder="Search repairs by customer, device, or tracking ID..." 
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
                            <th class="px-4 py-3 border">Device</th>
                            <th class="px-4 py-3 border">Complaint</th>
                            <th class="px-4 py-3 border">Technician</th>
                            <th class="px-4 py-3 border">Status</th>
                            <th class="px-4 py-3 border">Payment</th>
                            <th class="px-4 py-3 border">Cost</th>
                            <th class="px-4 py-3 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($repairs as $repair)
                        <tr class="text-center hover:bg-gray-50 transition border-b"
                            data-repair-id="{{ $repair->id }}" data-cost="{{ $repair->cost ?? 0 }}">
                            <td class="border px-3 py-2 font-medium text-blue-700">
                                <div class="font-mono text-sm">{{ $repair->tracking_id ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $repair->created_at->format('M d') }}</div>
                            </td>
                            <td class="border px-3 py-2">
                                <div class="font-medium">{{ $repair->user->name ?? $repair->customer_name ?? '-' }}</div>
                                @if($repair->user)
                                <div class="text-xs text-gray-500">{{ $repair->user->email }}</div>
                                @endif
                            </td>
                            <td class="border px-3 py-2 font-mono text-sm">{{ $repair->phone ?? '-' }}</td>
                            <td class="border px-3 py-2">
                                <div class="font-medium">{{ $repair->phone_brand }}</div>
                                <div class="text-xs text-gray-500">{{ $repair->phone_model }}</div>
                            </td>
                            <td class="border px-3 py-2 text-left max-w-xs">
                                <div class="text-sm line-clamp-2">{{ Str::limit($repair->complaint, 80) }}</div>
                            </td>
                            <td class="border px-3 py-2">
                                @if($repair->technician)
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">{{ $repair->technician }}</span>
                                @else
                                <span class="text-gray-400 text-xs">Not assigned</span>
                                @endif
                            </td>
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
                                    <div class="flex flex-col items-center">
                                        <span class="text-green-600 font-semibold text-sm">Paid</span>
                                        <span class="text-xs text-gray-500 capitalize">{{ $repair->payment->payment_method }}</span>
                                    </div>
                                @elseif ($repair->status === 'cancelled')
                                    <span class="text-red-500 italic text-sm">Cancelled</span>
                                @else
                                    <span class="text-gray-500 italic text-sm">Not Ready</span>
                                @endif
                            </td>
                            <td class="border px-3 py-2 font-semibold text-green-600">
                                @if($repair->cost)
                                <div>Rp {{ number_format($repair->cost, 0, ',', '.') }}</div>
                                @if($repair->payment && $repair->payment->status == 'paid')
                                <div class="text-xs text-gray-500">Paid</div>
                                @endif
                                @else
                                <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="border px-3 py-2">
                                <div class="flex flex-col gap-1 items-center">
                                    <a href="{{ route('cashier.repairs.show', $repair->id) }}" 
                                       class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition text-xs w-full text-center">
                                        View
                                    </a>
                                    <a href="{{ route('invoice.show', $repair->id) }}" 
                                       class="bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-700 transition text-xs w-full text-center">
                                        Invoice
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PAGINATION -->
        <div class="mt-6">
            {{ $repairs->links('pagination::tailwind') }}
        </div>

        <!-- EMPTY STATE -->
        @if($repairs->count() === 0)
        <div class="text-center py-12">
            <div class="text-gray-400 text-6xl mb-4">🔧</div>
            <h3 class="text-lg font-semibold text-gray-600">No repairs found</h3>
            <p class="text-gray-500 mt-2">Get started by creating a new repair request</p>
            <a href="{{ route('cashier.repairs.create') }}" 
               class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                + Add New Repair
            </a>
        </div>
        @endif
    </main>
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

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
