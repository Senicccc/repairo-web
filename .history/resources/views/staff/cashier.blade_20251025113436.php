@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Cashier Dashboard</h2>
        <div class="flex gap-2">
            <button onclick="document.getElementById('createUserModal').showModal()" 
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                + Create Account
            </button>
            <button onclick="document.getElementById('addRepairModal').showModal()" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                + Add New Repair
            </button>
            <button onclick="document.getElementById('redeemModal').showModal()" 
                    class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                + Redeem Loyalty Code
            </button>
        </div>
    </div>

    <!-- Table Repairs -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto max-h-[540px] overflow-y-auto">
            <table class="min-w-full text-sm text-gray-800">
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
                        <th class="px-4 py-3 border">Invoice</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($repairs->sortByDesc('created_at') as $repair)
                    <tr class="text-center hover:bg-gray-50 transition border-b">
                        <td class="border px-3 py-2 font-medium text-blue-700">{{ $repair->tracking_id ?? 'N/A' }}</td>
                        <td class="border px-3 py-2">{{ $repair->user->name ?? $repair->customer_name ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $repair->phone ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $repair->phone_brand }}</td>
                        <td class="border px-3 py-2">{{ $repair->phone_model }}</td>
                        <td class="border px-3 py-2 text-left">{{ $repair->complaint }}</td>
                        <td class="border px-3 py-2">{{ $repair->technician ?? '-' }}</td>
                        <td class="border px-3 py-2">
                            <span class="px-2 py-1 rounded text-sm font-semibold
                                {{ $repair->status == 'finished' ? 'bg-green-100 text-green-700' : 
                                   ($repair->status == 'in_progress' ? 'bg-yellow-100 text-yellow-700' : 
                                   'bg-gray-100 text-gray-700') }}">
                                {{ ucfirst($repair->status) }}
                            </span>
                        </td>
                        <td class="border px-3 py-2">
                            @if ($repair->status == 'unfinished')
                                <span class="text-gray-500 italic">Unfinished</span>
                            @elseif ($repair->status == 'finished' && (!$repair->payment || $repair->payment->status == 'unpaid'))
                                <button onclick="openPaymentModal({{ $repair->id }})"
                                    class="bg-emerald-600 text-white px-3 py-1 rounded hover:bg-emerald-700 transition">
                                    Process Payment
                                </button>
                            @elseif ($repair->payment && $repair->payment->status == 'paid')
                                <span class="text-green-600 font-semibold">Paid</span>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="border px-3 py-2">
                            <a href="{{ route('invoice.show', $repair->id) }}" 
                               class="bg-gray-700 text-white px-3 py-1 rounded hover:bg-gray-800 transition">
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

<!-- Redeem Loyalty Code Modal -->
<dialog id="redeemModal" class="modal">
    <form method="POST" action="{{ route('loyalty.redeem') }}" class="p-6 bg-white rounded shadow-md w-[400px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Redeem Loyalty Code</h3>

        @if(session('success'))
            <div class="mb-3 p-2 bg-green-100 text-green-700 rounded text-sm">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="mb-3 p-2 bg-red-100 text-red-700 rounded text-sm">{{ session('error') }}</div>
        @endif

        <input type="text" name="redeem_code" placeholder="Enter loyalty code" required 
               class="w-full border p-2 mb-3 rounded">

        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('redeemModal').close()" 
                    class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button type="submit" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Redeem</button>
        </div>
    </form>
</dialog>

<script>
function openPaymentModal(id) {
    const tr = document.querySelector(`tr[data-repair-id="${id}"]`);
    const amount = tr ? tr.dataset.cost : 0;
    document.getElementById('repair_id').value = id;
    document.getElementById('payment_amount').value = amount;
    document.getElementById('addPaymentModal').showModal();
}

// auto open modal jika ada flash message
@if(session('success') || session('error'))
    document.getElementById('redeemModal').showModal();
@endif
</script>
@endsection
