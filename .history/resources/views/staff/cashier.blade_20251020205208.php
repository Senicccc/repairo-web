@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Cashier Dashboard</h2>

    <!-- Header Section -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Repair Service List</h3>
        <button onclick="document.getElementById('addRepairModal').showModal()" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            + Add New Repair
        </button>
    </div>

    <!-- Repair Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full border border-gray-200 text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-3 py-2 border">Tracking ID</th>
                    <th class="px-3 py-2 border">Customer</th>
                    <th class="px-3 py-2 border">Phone</th>
                    <th class="px-3 py-2 border">Brand</th>
                    <th class="px-3 py-2 border">Model</th>
                    <th class="px-3 py-2 border">Complaint</th>
                    <th class="px-3 py-2 border">Technician</th>
                    <th class="px-3 py-2 border">Status</th>
                    <th class="px-3 py-2 border">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($repairs as $repair)
                <tr class="text-center" data-repair-id="{{ $repair->id }}" data-cost="{{ $repair->cost ?? 0 }}">
                    <td class="border px-3 py-2">{{ $repair->tracking_id ?? 'N/A' }}</td>
                    <td class="border px-3 py-2">{{ $repair->user->name ?? '-' }}</td>
                    <td class="border px-3 py-2">{{ $repair->phone ?? '-' }}</td>
                    <td class="border px-3 py-2">{{ $repair->phone_brand }}</td>
                    <td class="border px-3 py-2">{{ $repair->phone_model }}</td>
                    <td class="border px-3 py-2">{{ $repair->complaint }}</td>
                    <td class="border px-3 py-2">{{ $repair->technician ?? '-' }}</td>
                    <td class="border px-3 py-2">
                        <span class="px-2 py-1 rounded text-sm
                            {{ $repair->status == 'finished' ? 'bg-green-100 text-green-700' : 
                               ($repair->status == 'in_progress' ? 'bg-yellow-100 text-yellow-700' : 
                               'bg-gray-100 text-gray-700') }}">
                            {{ ucfirst($repair->status) }}
                        </span>
                    </td>
                    <td class="border px-3 py-2 space-x-2">
                        @if ($repair->status == 'finished' && !$repair->payment)
                            <button onclick="openPaymentModal({{ $repair->id }})"
                                class="bg-emerald-600 text-white px-3 py-1 rounded hover:bg-emerald-700">
                                Pay
                            </button>
                        @endif
                        @if ($repair->payment)
                            <a href="{{ route('invoice.show', $repair->id) }}" 
                               class="bg-gray-700 text-white px-3 py-1 rounded hover:bg-gray-800">
                                Invoice
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- ===================== Add Repair Modal ===================== -->
<dialog id="addRepairModal" class="modal">
    <form method="POST" action="{{ route('repairs.store') }}" class="p-6 bg-white rounded shadow-md w-[480px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Add New Repair</h3>

        <!-- Customer Info -->
        <div class="flex justify-between items-center mb-3">
            <h4 class="text-sm font-medium">Customer Info</h4>
            <button type="button" onclick="document.getElementById('createUserModal').showModal()" 
                    class="text-blue-600 text-sm hover:underline">
                + Create Account
            </button>
        </div>

        <input type="text" name="name" placeholder="Customer Name (optional)" class="w-full border p-2 mb-3 rounded">
        <input type="text" name="phone" placeholder="Phone Number" required class="w-full border p-2 mb-3 rounded">

        <!-- Device Details -->
        <input type="text" name="phone_brand" placeholder="Phone Brand" required class="w-full border p-2 mb-3 rounded">
        <input type="text" name="phone_model" placeholder="Phone Model" required class="w-full border p-2 mb-3 rounded">
        <input type="text" name="imei" placeholder="IMEI (optional)" class="w-full border p-2 mb-3 rounded">
        <textarea name="complaint" placeholder="Damage Description" required class="w-full border p-2 mb-3 rounded"></textarea>

        <!-- Estimated Cost -->
        <input type="number" step="0.01" name="cost" placeholder="Estimated Cost (optional)" class="w-full border p-2 mb-3 rounded">

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('addRepairModal').close()" 
                    class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
        </div>
    </form>
</dialog>

<!-- ===================== Create Account Modal ===================== -->
<dialog id="createUserModal" class="modal">
    <form method="POST" action="{{ route('users.store') }}" class="p-6 bg-white rounded shadow-md w-[420px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Create Customer Account</h3>

        <input type="text" name="name" placeholder="Full Name" required class="w-full border p-2 mb-3 rounded">
        <input type="text" name="phone" placeholder="Phone Number" required class="w-full border p-2 mb-3 rounded">
        <input type="email" name="email" placeholder="Email" class="w-full border p-2 mb-3 rounded">
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

<!-- ===================== Payment Modal ===================== -->
<dialog id="addPaymentModal" class="modal">
    <form method="POST" action="{{ route('payments.store') }}" class="p-6 bg-white rounded shadow-md w-[400px] mx-auto">
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
            <option value="pending">Pending</option>
        </select>

        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('addPaymentModal').close()" 
                    class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button type="submit" class="px-3 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700">Confirm</button>
        </div>
    </form>
</dialog>

<!-- ===================== JavaScript Section ===================== -->
<script>
    function openPaymentModal(id) {
        const tr = document.querySelector(`tr[data-repair-id="${id}"]`);
        const amount = tr ? tr.dataset.cost : 0;
        document.getElementById('repair_id').value = id;
        document.getElementById('payment_amount').value = amount;
        document.getElementById('addPaymentModal').showModal();
    }
</script>
@endsection
