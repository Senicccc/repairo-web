@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Repair Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('cashier.repairs.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                ← Back to Repairs
            </a>
            <a href="{{ route('invoice.show', $repair->id) }}" 
               class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                🧾 Generate Invoice
            </a>
        </div>
    </div>

    <!-- Repair Info Card -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Customer Info -->
            <div>
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Customer Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="font-medium">Name:</span>
                        <span>{{ $repair->user->name ?? $repair->customer_name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Phone:</span>
                        <span>{{ $repair->phone ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Tracking ID:</span>
                        <span class="font-mono text-blue-600">{{ $repair->tracking_id }}</span>
                    </div>
                </div>
            </div>

            <!-- Device Info -->
            <div>
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Device Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="font-medium">Brand/Model:</span>
                        <span>{{ $repair->phone_brand }} {{ $repair->phone_model }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">IMEI:</span>
                        <span>{{ $repair->imei ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Status:</span>
                        <span class="{{ \App\Models\Repair::getStatusColor($repair->status) }} px-2 py-1 rounded text-xs font-medium">
                            {{ \App\Models\Repair::getStatuses()[$repair->status] ?? ucfirst($repair->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Complaint -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">Complaint Description</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-700">{{ $repair->complaint }}</p>
            </div>
        </div>

        <!-- Diagnosis & Cost -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <h3 class="text-lg font-semibold mb-2">Technician Diagnosis</h3>
                <div class="bg-gray-50 p-4 rounded-lg min-h-20">
                    <p class="text-gray-700">{{ $repair->diagnosis ?? 'No diagnosis provided yet.' }}</p>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-2">Cost Information</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">
                        {{ $repair->cost ? 'Rp ' . number_format($repair->cost, 0, ',', '.') : 'Not set' }}
                    </div>
                    @if($repair->technician)
                    <p class="text-sm text-gray-600 mt-1">Technician: {{ $repair->technician }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Spareparts Used -->
    @if($repair->repairSpareparts->count() > 0)
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Spareparts Used</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Sparepart</th>
                        <th class="px-4 py-2 text-left">Category</th>
                        <th class="px-4 py-2 text-right">Quantity</th>
                        <th class="px-4 py-2 text-right">Price</th>
                        <th class="px-4 py-2 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($repair->repairSpareparts as $sparepart)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $sparepart->name }}</td>
                        <td class="px-4 py-2 capitalize">{{ $sparepart->category ?? '-' }}</td>
                        <td class="px-4 py-2 text-right">{{ $sparepart->quantity }}</td>
                        <td class="px-4 py-2 text-right">Rp {{ number_format($sparepart->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right font-medium">
                            Rp {{ number_format($sparepart->quantity * $sparepart->price, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Payment Status -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Payment Information</h3>
        @if($repair->payment)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="text-sm text-gray-600">Status</div>
                <div class="text-lg font-bold text-green-600 capitalize">{{ $repair->payment->status }}</div>
            </div>
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="text-sm text-gray-600">Method</div>
                <div class="text-lg font-bold text-blue-600 capitalize">{{ $repair->payment->payment_method }}</div>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <div class="text-sm text-gray-600">Amount</div>
                <div class="text-lg font-bold text-purple-600">Rp {{ number_format($repair->payment->amount, 0, ',', '.') }}</div>
            </div>
        </div>
        @else
        <div class="text-center p-6 bg-yellow-50 rounded-lg">
            <p class="text-yellow-700">No payment recorded yet.</p>
            @if($repair->status === 'finished')
            <button onclick="openPaymentModal({{ $repair->id }}, {{ $repair->cost ?? 0 }})"
                class="mt-3 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                Process Payment
            </button>
            @endif
        </div>
        @endif
    </div>
</div>

<!-- Payment Modal -->
<dialog id="paymentModal" class="modal">
    <form method="POST" action="{{ route('payments.store') }}" class="p-6 bg-white rounded shadow-md w-96 mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Process Payment</h3>

        <input type="hidden" name="repair_id" id="payment_repair_id">
        
        <div class="mb-3">
            <label class="block text-sm font-medium mb-1">Amount</label>
            <input type="number" name="amount" id="payment_amount" required 
                   class="w-full border p-2 rounded bg-gray-50 font-semibold">
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium mb-1">Payment Method</label>
            <select name="payment_method" required class="w-full border p-2 rounded">
                <option value="cash">Cash</option>
                <option value="transfer">Bank Transfer</option>
                <option value="ewallet">E-Wallet</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Status</label>
            <select name="status" required class="w-full border p-2 rounded">
                <option value="paid">Paid</option>
                <option value="unpaid">Unpaid</option>
            </select>
        </div>

        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('paymentModal').close()" 
                    class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">Process</button>
        </div>
    </form>
</dialog>

<script>
function openPaymentModal(repairId, amount) {
    document.getElementById('payment_repair_id').value = repairId;
    document.getElementById('payment_amount').value = amount;
    document.getElementById('paymentModal').showModal();
}

// Close modal when clicking outside
document.getElementById('paymentModal').addEventListener('click', function(event) {
    if (event.target === this) this.close();
});
</script>
@endsection