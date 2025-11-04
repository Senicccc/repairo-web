@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-50">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-blue-900 text-white flex flex-col p-4 shadow-lg">
        <div class="text-2xl font-bold mb-6 tracking-wide border-b border-blue-700 pb-3">
            Repairo
        </div>

        <nav class="flex-1 space-y-2">
            <a href="{{ route('cashier.dashboard') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-blue-800 transition {{ request()->routeIs('cashier.dashboard') ? 'bg-blue-800' : '' }}">
                🏠 <span class="ml-2">Dashboard</span>
            </a>
            <a href="{{ route('cashier.users.create') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                👤 <span class="ml-2">Create User</span>
            </a>
            <a href="{{ route('cashier.repairs.create') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                🔧 <span class="ml-2">New Repair</span>
            </a>
            <a href="{{ route('cashier.loyalty.redeem') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                🎁 <span class="ml-2">Loyalty</span>
            </a>
        </nav>

        <div class="mt-auto pt-4 border-t border-blue-700">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                    🚪 Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-8">
        <!-- HEADER -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Cashier Dashboard</h2>
            <div class="flex gap-2">
                <a href="{{ route('cashier.loyalty.redeem') }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                    Redeem Loyalty
                </a>
                <a href="{{ route('cashier.users.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    + Create Account
                </a>
                <a href="{{ route('cashier.repairs.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    + Add New Repair
                </a>
            </div>
        </div>

        <!-- STATS -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <x-dashboard-card color="blue" icon="📋" title="Total Repairs" :value="$repairs->total()" />
            <x-dashboard-card color="yellow" icon="⏳" title="Pending" :value="$repairs->where('status', 'pending')->count()" />
            <x-dashboard-card color="green" icon="✅" title="Finished" :value="$repairs->where('status', 'finished')->count()" />
            <x-dashboard-card color="purple" icon="💰" title="Paid" :value="$repairs->where('status', 'paid')->count()" />
        </div>

        <!-- SEARCH -->
        <div class="mb-4">
            <input type="text" id="searchBar" placeholder="Search repairs by customer, device, or tracking ID..." 
                class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring focus:ring-blue-200 focus:outline-none"
                onkeyup="filterTable()">
        </div>

        <!-- TABLE -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto max-h-[540px] overflow-y-auto">
                <table id="repairsTable" class="min-w-full text-sm text-gray-800">
                    <thead class="bg-blue-100 text-gray-700 sticky top-0 shadow-sm">
                        <tr class="text-center uppercase text-xs font-semibold">
                            <th class="px-4 py-3 border">Tracking ID</th>
                            <th class="px-4 py-3 border">Customer</th>
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
                        @forelse ($repairs as $repair)
                        <tr class="text-center hover:bg-gray-50 transition border-b">
                            <td class="border px-3 py-2 font-mono text-blue-700">{{ $repair->tracking_id ?? 'N/A' }}</td>
                            <td class="border px-3 py-2">{{ $repair->user->name ?? $repair->customer_name ?? '-' }}</td>
                            <td class="border px-3 py-2">{{ $repair->phone_brand }} {{ $repair->phone_model }}</td>
                            <td class="border px-3 py-2 text-left max-w-xs">{{ Str::limit($repair->complaint, 80) }}</td>
                            <td class="border px-3 py-2">
                                @if($repair->technician)
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">{{ $repair->technician }}</span>
                                @else
                                    <span class="text-gray-400 text-xs">Not assigned</span>
                                @endif
                            </td>
                            <td class="border px-3 py-2">
                                <span class="{{ \App\Models\Repair::getStatusColor($repair->status) }} px-2 py-1 rounded text-xs font-medium">
                                    {{ ucfirst($repair->status) }}
                                </span>
                            </td>
                            <td class="border px-3 py-2">
                                @if ($repair->payment && $repair->payment->status == 'paid')
                                    <span class="text-green-600 font-semibold text-sm">Paid</span>
                                @elseif ($repair->status === 'finished')
                                    <a href="{{ route('cashier.payments.create', $repair->id) }}" 
                                        class="bg-emerald-600 text-white px-3 py-1 rounded hover:bg-emerald-700 transition text-xs inline-block">
                                        Process Payment
                                    </a>
                                @else
                                    <span class="text-gray-500 italic text-sm">Not Ready</span>
                                @endif
                            </td>
                            <td class="border px-3 py-2 font-semibold text-green-600">
                                @if($repair->cost)
                                    Rp {{ number_format($repair->cost, 0, ',', '.') }}
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
                        @empty
                        <tr>
                            <td colspan="9" class="py-6 text-center text-gray-500 italic">
                                No repair data found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PAGINATION -->
        <div class="mt-4">
            {{ $repairs->links('pagination::tailwind') }}
        </div>
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

@endsection
