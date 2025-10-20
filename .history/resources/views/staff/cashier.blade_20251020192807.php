@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Cashier Dashboard</h2>

    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Repair List</h3>
        <button onclick="document.getElementById('addRepairModal').showModal()" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + Add New Repair
        </button>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">Tracking ID</th>
                    <th class="px-4 py-2 border">Customer</th>
                    <th class="px-4 py-2 border">Device Type</th>
                    <th class="px-4 py-2 border">Brand / Model</th>
                    <th class="px-4 py-2 border">Complaint</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($repairs as $repair)
                <tr class="text-center">
                    <td class="px-4 py-2 border">{{ $repair->tracking_id }}</td>
                    <td class="px-4 py-2 border">{{ $repair->user ? $repair->user->name : 'Guest' }}</td>
                    <td class="px-4 py-2 border">{{ $repair->phone_brand }}</td>
                    <td class="px-4 py-2 border">{{ $repair->phone_model }}</td>
                    <td class="px-4 py-2 border">{{ $repair->complaint }}</td>
                    <td class="px-4 py-2 border">
                        <span class="px-2 py-1 rounded text-sm 
                            {{ $repair->status == 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($repair->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2 border space-x-2">
                        @if ($repair->status != 'finished' && !$repair->payment)
                            <button onclick="document.getElementById('addPaymentModal').showModal()" 
                                    class="bg-emerald-600 text-white px-3 py-1 rounded hover:bg-emerald-700">
                                Pay
                            </button>
                        @endif
                        <a href="{{ route('invoice.show', $repair->id) }}" 
                           class="bg-gray-700 text-white px-3 py-1 rounded hover:bg-gray-800">
                            Print Invoice
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Repair Modal -->
<dialog id="addRepairModal" class="modal">
    <form method="POST" action="{{ route('repairs.store') }}" class="p-6 bg-white rounded shadow-md w-[500px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Add New Repair</h3>
        <input type="text" name="name" placeholder="Customer Name (optional)" class="w-full border p-2 mb-3 rounded">
        <input type="text" name="phone" placeholder="Customer Phone Number" required class="w-full border p-2 mb-3 rounded">
        <input type="text" name="device_type" placeholder="Device Type" required class="w-full border p-2 mb-3 rounded">
        <input type="text" name="brand" placeholder="Brand" required class="w-full border p-2 mb-3 rounded">
        <input type="text" name="model" placeholder="Model (optional)" class="w-full border p-2 mb-3 rounded">
        <textarea name="damage_description" placeholder="Damage Description" required class="w-full border p-2 mb-3 rounded"></textarea>
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('addRepairModal').close()" class="px-3 py-1 bg-gray-300 rounded">Cancel</button>
            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded">Save</button>
        </div>
    </form>
</dialog>

<!-- Payment Modal -->
<dialog id="addPaymentModal" class="modal">
    <form method="POST" action="{{ route('payments.store') }}" class="p-6 bg-white rounded shadow-md w-[400px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Service Payment</h3>
        <input type="hidden" name="repair_id" id="repair_id">
        <input type="number" name="amount" placeholder="Total Amount" class="w-full border p-2 mb-3 rounded">
        <select name="method" class="w-full border p-2 mb-3 rounded">
            <option value="cash">Cash</option>
            <option value="transfer">Bank Transfer</option>
            <option value="ewallet">E-Wallet</option>
        </select>
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('addPaymentModal').close()" class="px-3 py-1 bg-gray-300 rounded">Cancel</button>
            <button type="submit" class="px-3 py-1 bg-emerald-600 text-white rounded">Pay</button>
        </div>
    </form>
</dialog>
<script>
    // when opening payment modal, set repair id from clicked row
    document.querySelectorAll('button[onclick*="addPaymentModal"]').forEach(function(btn, idx){
        btn.addEventListener('click', function(e){
            // find the closest tr and extract first cell as id
            var tr = e.target.closest('tr');
            if(!tr) return;
            var id = tr.querySelector('td')?.innerText || tr.dataset.id;
            document.getElementById('repair_id').value = id;
            document.getElementById('addPaymentModal').showModal();
        });
    });
</script>
@endsection
