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

    <!-- MAIN -->
    <main class="flex-1 p-8">
        @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Cashier Dashboard</h2>
        </div>

        <!-- STATS -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">📋</div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Repairs</p>
                        <p class="text-2xl font-bold">{{ $repairs->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">⏳</div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Pending</p>
                        <p class="text-2xl font-bold">{{ $repairs->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">✅</div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Finished</p>
                        <p class="text-2xl font-bold">{{ $repairs->where('status', 'finished')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">💰</div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Paid</p>
                        <p class="text-2xl font-bold">{{ $repairs->where('status', 'paid')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEARCH -->
        <div class="mb-4">
            <input id="searchBar" type="text" placeholder="Search repairs by customer, device, or tracking ID..." 
                class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring focus:ring-blue-200 focus:outline-none"
                onkeyup="filterTable()">
        </div>

        <!-- TABLE -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto max-h-[540px] overflow-y-auto">
                <table id="repairsTable" class="min-w-full text-sm text-gray-800">
                    <thead class="bg-gray-100 text-gray-700 sticky top-0">
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
                        <tr class="text-center hover:bg-gray-50 border-b">
                            <td class="border px-3 py-2 font-mono text-blue-700">
                                {{ $repair->tracking_id ?? 'N/A' }}
                            </td>
                            <td class="border px-3 py-2">{{ $repair->user->name ?? '-' }}</td>
                            <td class="border px-3 py-2">{{ $repair->phone ?? '-' }}</td>
                            <td class="border px-3 py-2">{{ $repair->phone_brand }}</td>
                            <td class="border px-3 py-2">{{ Str::limit($repair->complaint, 50) }}</td>
                            <td class="border px-3 py-2">{{ $repair->technician ?? 'Not assigned' }}</td>
                            <td class="border px-3 py-2">{{ ucfirst($repair->status) }}</td>
                            <td class="border px-3 py-2">
                                @if ($repair->payment && $repair->payment->status === 'paid')
                                    <span class="text-green-600 font-semibold">Paid</span>
                                @elseif ($repair->status === 'finished')
                                    <a href="{{ route('cashier.payments.create', $repair->id) }}" class="text-blue-600 underline">Pay</a>
                                @else
                                    <span class="text-gray-500 italic">Not ready</span>
                                @endif
                            </td>
                            <td class="border px-3 py-2 font-semibold text-green-600">
                                Rp {{ number_format($repair->cost ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="border px-3 py-2">
                                <a href="{{ route('cashier.repairs.show', $repair->id) }}" 
                                   class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">View</a>
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
    </main>
</div>

<script>
function filterTable() {
    const input = document.getElementById('searchBar').value.toLowerCase();
    const rows = document.querySelectorAll('#repairsTable tbody tr');
    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(input) ? '' : 'none';
    });
}
</script>
@endsection
