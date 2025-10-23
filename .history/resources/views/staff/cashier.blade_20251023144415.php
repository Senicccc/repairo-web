@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Cashier Dashboard</h2>
        <button onclick="document.getElementById('addRepairModal').showModal()" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            + Add New Repair
        </button>
    </div>

    <div class="mb-4">
        <input type="text" id="searchBar" placeholder="Search anything..." 
               class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring focus:ring-blue-200 focus:outline-none"
               onkeyup="filterTable()">
    </div>

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
                        <th class="px-4 py-3 border">Invoice</th>
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
                            <span class="px-2 py-1 rounded text-sm font-semibold
                                {{ $repair->status == 'finished' ? 'bg-green-100 text-green-700' : 
                                   ($repair->status == 'in_progress' ? 'bg-yellow-100 text-yellow-700' : 
                                   'bg-gray-100 text-gray-700') }}">
                                {{ ucfirst($repair->status) }}
                            </span>
                        </td>
                        <!-- Payment Column -->
                        <!-- Payment Column -->
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

                        <!-- Invoice Column -->
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

<!-- Add Repair Modal -->
<dialog id="addRepairModal" class="modal">
    <form method="POST" action="{{ route('repairs.store') }}" 
          class="p-6 bg-white rounded shadow-md w-[480px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Add New Repair</h3>

        <div class="flex justify-between items-center mb-3">
            <h4 class="text-sm font-medium">Customer Info</h4>
            <button type="button" onclick="document.getElementById('createUserModal').showModal()" 
                    class="text-blue-600 text-sm hover:underline">
                + Create Account
            </button>
        </div>

        <input type="text" name="name" placeholder="Customer Name (optional)" class="w-full border p-2 mb-3 rounded">
        <input type="text" name="phone" placeholder="Phone Number" required class="w-full border p-2 mb-3 rounded">
        <input type="text" name="phone_brand" placeholder="Phone Brand" required class="w-full border p-2 mb-3 rounded">
        <input type="text" name="phone_model" placeholder="Phone Model" required class="w-full border p-2 mb-3 rounded">
        <input type="text" name="imei" placeholder="IMEI (optional)" class="w-full border p-2 mb-3 rounded">
        <textarea name="complaint" placeholder="Damage Description" required class="w-full border p-2 mb-3 rounded"></textarea>
        <input type="number" step="0.01" name="cost" placeholder="Estimated Cost (optional)" class="w-full border p-2 mb-3 rounded">

        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('addRepairModal').close()" 
                    class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
        </div>
    </form>
</dialog>

<!-- Create Account Modal -->
<dialog id="createUserModal" class="modal">
    <form method="POST" action="{{ route('users.store') }}" 
          class="p-6 bg-white rounded shadow-md w-[420px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Create Customer Account</h3>

        <input type="text" name="name" placeholder="Full Name" required class="w-full border p-2 mb-3 rounded">
        <input type="text" name="phone" placeholder="Phone Number" required class="w-full border p-2 mb-3 rounded">
        <input type="email" name="email" placeholder="Email" required class="w-full border p-2 mb-3 rounded">
        <input type="password" name="password" placeholder="Password" required class="w-full border p-2 mb-3 rounded">

        <select name="role" required class="w-full border p-2 mb-4 rounded">
            <option value="customer">Customer</option>
        </select>

        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('createUserModal').close()" 
                    class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">Create</button>
        </div>
    </form>
</dialog>

<!-- Payment Modal -->
<dialog id="addPaymentModal" class="modal">
    <form method="POST" action="{{ route('payments.store') }}" 
          class="p-6 bg-white rounded shadow-md w-[400px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Repair Payment</h3>

        <input type="hidden" name="repair_id" id="repair_id">
        <input type="number" name="amount" id="payment_amount" placeholder="Total Amount" required class="w-full border p-2 mb-3 rounded">

        <select name="method" required class="w-full border p-2 mb-3 rounded">
            <option value="cash">Cash</option>
            <option value="transfer">Bank Transfer</option>
            <option value="ewallet">E-Wallet</option>
        </select>

        <select name="status" required class="w-full border p-2 mb-3 rounded">
            <option value="paid">Paid</option>
            <option value="unpaid">Unpaid</option>
        </select>

        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('addPaymentModal').close()" 
                    class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button type="submit" class="px-3 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700">Confirm</button>
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
