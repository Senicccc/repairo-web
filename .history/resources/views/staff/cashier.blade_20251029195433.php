@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Cashier Dashboard</h2>
        <div class="flex gap-2">
            <button onclick="openRedeemModal()"
                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition">
                Redeem
            </button>
            <button onclick="document.getElementById('createUserModal').showModal()" 
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                + Create Account
            </button>
            <button onclick="document.getElementById('addRepairModal').showModal()" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                + Add New Repair
            </button>
        </div>
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
                        
                        {{-- Updated Status Column --}}
                        <td class="border px-3 py-2">
                            <span class="{{ \App\Models\Repair::getStatusColor($repair->status) }} px-2 py-1 rounded text-xs font-medium">
                                {{ \App\Models\Repair::getStatuses()[$repair->status] ?? ucfirst($repair->status) }}
                            </span>
                        </td>
                        
                        {{-- Updated Payment Column --}}
                        <td class="border px-3 py-2">
                            @if ($repair->status === 'finished' && (!$repair->payment || $repair->payment->status == 'unpaid'))
                                <button onclick="openPaymentModal({{ $repair->id }})"
                                    class="bg-emerald-600 text-white px-3 py-1 rounded hover:bg-emerald-700 transition text-sm">
                                    Process Payment
                                </button>
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
                               class="bg-gray-700 text-white px-3 py-1 rounded hover:bg-gray-800 transition text-sm">
                                Invoice
                            </a>
                        </td>
                        <td class="border px-3 py-2">
                            @if($repair->status === 'finished' && $repair->cost && $repair->cost > 0)
                                <button onclick="openRedeemForRepair({{ $repair->id }}, {{ $repair->cost }})"
                                    class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600 transition">
                                    Redeem
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ✅ Add New Repair Modal -->
<dialog id="addRepairModal" class="modal">
    <form method="POST" action="{{ route('repairs.store') }}" 
          class="p-6 bg-white rounded shadow-md w-[480px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Add New Repair</h3>

        <h4 class="text-sm font-medium mb-3">Customer Info</h4>

        <input type="text" name="name" placeholder="Customer Name (optional)" class="w-full border p-2 mb-3 rounded">
        <input type="text" name="phone" placeholder="Phone Number" required class="w-full border p-2 mb-3 rounded">
        <input type="text" name="phone_brand" placeholder="Phone Brand" required class="w-full border p-2 mb-3 rounded">
        <input type="text" name="phone_model" placeholder="Phone Model" required class="w-full border p-2 mb-3 rounded">
        <input type="text" name="imei" placeholder="IMEI (optional)" class="w-full border p-2 mb-3 rounded">
        <textarea name="complaint" placeholder="Damage Description" required class="w-full border p-2 mb-3 rounded h-20"></textarea>
        <input type="number" step="0.01" name="cost" placeholder="Estimated Cost (optional)" class="w-full border p-2 mb-3 rounded">

        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('addRepairModal').close()" 
                    class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
        </div>
    </form>
</dialog>

<!-- ✅ Create Account Modal -->
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

<!-- ✅ Payment Modal -->
<dialog id="addPaymentModal" class="modal">
    <form method="POST" action="{{ route('payments.store') }}" 
          class="p-6 bg-white rounded shadow-md w-[400px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Repair Payment</h3>

        <input type="hidden" name="repair_id" id="repair_id">
        
        <div class="mb-3">
            <label class="block text-sm font-medium mb-1">Repair Cost</label>
            <input type="number" name="amount" id="payment_amount" placeholder="Total Amount" required 
                   class="w-full border p-2 rounded bg-gray-50 font-semibold">
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium mb-1">Payment Method</label>
            <select name="method" required class="w-full border p-2 rounded">
                <option value="cash">Cash</option>
                <option value="transfer">Bank Transfer</option>
                <option value="ewallet">E-Wallet</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Payment Status</label>
            <select name="status" required class="w-full border p-2 rounded">
                <option value="paid">Paid</option>
                <option value="unpaid">Unpaid</option>
            </select>
        </div>

        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('addPaymentModal').close()" 
                    class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button type="submit" class="px-3 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700">Confirm Payment</button>
        </div>
    </form>
</dialog>

<!-- ✅ Redeem Loyalty Modal -->
<dialog id="redeemModal" class="modal">
    <div class="p-6 bg-white rounded shadow-md w-[400px] mx-auto text-center">
        <h3 class="text-lg font-semibold mb-4">Redeem Loyalty Code</h3>
        <input type="text" id="redeem_code_modal" placeholder="Enter loyalty code" 
               class="w-full border p-2 mb-3 rounded uppercase text-center font-mono text-lg tracking-wider"
               onkeyup="this.value = this.value.toUpperCase()">
        <div id="redeemResult" class="mb-3 text-sm min-h-6"></div>
        <div class="flex justify-center gap-2" id="redeemButtons">
            <button type="button" onclick="closeRedeemModal()" 
                    class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button type="button" onclick="checkRedeemCode()" id="checkBtn" 
                    class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Check Code</button>
        </div>
    </div>
</dialog>

<!-- ✅ Redeem for Specific Repair Modal -->
<dialog id="redeemRepairModal" class="modal">
    <div class="p-6 bg-white rounded shadow-md w-[450px] mx-auto text-center">
        <h3 class="text-lg font-semibold mb-2">Redeem Points for Repair</h3>
        <p class="text-sm text-gray-600 mb-4">Tracking ID: <span id="redeem_tracking_id" class="font-semibold"></span></p>
        
        <div class="bg-gray-50 p-3 rounded mb-4">
            <p class="text-sm">Repair Cost: <span id="redeem_repair_cost" class="font-semibold text-lg"></span></p>
            <p class="text-xs text-gray-500">Available points will be converted to discount</p>
        </div>
        
        <input type="hidden" id="redeem_repair_id">
        <input type="text" id="redeem_repair_code" placeholder="Enter loyalty code" 
               class="w-full border p-2 mb-3 rounded uppercase text-center font-mono text-lg tracking-wider"
               onkeyup="this.value = this.value.toUpperCase()">
        
        <div id="redeemRepairResult" class="mb-3 text-sm min-h-6"></div>
        
        <div class="flex justify-center gap-2" id="redeemRepairButtons">
            <button type="button" onclick="closeRedeemRepairModal()" 
                    class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button type="button" onclick="checkRedeemForRepair()" 
                    class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Check Code</button>
        </div>
    </div>
</dialog>

<script>
let currentRewardId = null;
let currentRepairId = null;

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

function openRedeemModal() {
    document.getElementById('redeem_code_modal').value = '';
    document.getElementById('redeemResult').innerHTML = '';
    document.getElementById('redeemButtons').innerHTML = `
        <button type="button" onclick="closeRedeemModal()" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
        <button type="button" onclick="checkRedeemCode()" id="checkBtn" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Check Code</button>
    `;
    document.getElementById('redeemModal').showModal();
}

function openRedeemForRepair(repairId, cost) {
    const tr = document.querySelector(`tr[data-repair-id="${repairId}"]`);
    const trackingId = tr ? tr.querySelector('td:first-child').textContent : '';
    
    currentRepairId = repairId;
    document.getElementById('redeem_repair_id').value = repairId;
    document.getElementById('redeem_tracking_id').textContent = trackingId;
    document.getElementById('redeem_repair_cost').textContent = 'Rp ' + cost.toLocaleString();
    document.getElementById('redeem_repair_code').value = '';
    document.getElementById('redeemRepairResult').innerHTML = '';
    
    document.getElementById('redeemRepairButtons').innerHTML = `
        <button type="button" onclick="closeRedeemRepairModal()" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
        <button type="button" onclick="checkRedeemForRepair()" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Check Code</button>
    `;
    
    document.getElementById('redeemRepairModal').showModal();
}

function closeRedeemModal() {
    document.getElementById('redeemModal').close();
    currentRewardId = null;
}

function closeRedeemRepairModal() {
    document.getElementById('redeemRepairModal').close();
    currentRewardId = null;
    currentRepairId = null;
}

function checkRedeemCode() {
    const code = document.getElementById('redeem_code_modal').value.trim();
    if (!code) {
        document.getElementById('redeemResult').innerHTML = `<div class='p-2 bg-red-100 text-red-700 rounded'>Please enter a code</div>`;
        return;
    }

    document.getElementById('checkBtn').disabled = true;
    document.getElementById('checkBtn').textContent = 'Checking...';

    fetch("{{ route('loyalty.check') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ redeem_code: code })
    })
    .then(res => res.json())
    .then(data => {
        const resultDiv = document.getElementById('redeemResult');
        const buttonsDiv = document.getElementById('redeemButtons');

        if (data.valid) {
            currentRewardId = data.reward_id;
            resultDiv.innerHTML = `<div class='p-2 bg-green-100 text-green-700 rounded font-semibold'>🎉 Valid Code! Reward: ${data.claim}</div>`;
            buttonsDiv.innerHTML = `
                <button type="button" onclick="closeRedeemModal()" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="button" onclick="claimReward()" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">Claim Reward</button>
            `;
        } else {
            resultDiv.innerHTML = `<div class='p-2 bg-red-100 text-red-700 rounded'>❌ Invalid code or already claimed</div>`;
            buttonsDiv.innerHTML = `
                <button type="button" onclick="closeRedeemModal()" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="button" onclick="checkRedeemCode()" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Try Again</button>
            `;
        }
    })
    .catch(error => {
        document.getElementById('redeemResult').innerHTML = `<div class='p-2 bg-red-100 text-red-700 rounded'>Network error, please try again</div>`;
    })
    .finally(() => {
        document.getElementById('checkBtn').disabled = false;
        document.getElementById('checkBtn').textContent = 'Check Code';
    });
}

function checkRedeemForRepair() {
    const code = document.getElementById('redeem_repair_code').value.trim();
    if (!code) {
        document.getElementById('redeemRepairResult').innerHTML = `<div class='p-2 bg-red-100 text-red-700 rounded'>Please enter a code</div>`;
        return;
    }

    fetch("{{ route('loyalty.check') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ 
            redeem_code: code,
            repair_id: currentRepairId 
        })
    })
    .then(res => res.json())
    .then(data => {
        const resultDiv = document.getElementById('redeemRepairResult');
        const buttonsDiv = document.getElementById('redeemRepairButtons');

        if (data.valid) {
            currentRewardId = data.reward_id;
            resultDiv.innerHTML = `
                <div class='p-2 bg-green-100 text-green-700 rounded font-semibold'>
                    🎉 Valid Code!<br>
                    <span class="text-sm">Reward: ${data.claim}</span>
                    ${data.discount ? `<br><span class="text-sm">Discount: Rp ${data.discount.toLocaleString()}</span>` : ''}
                </div>
            `;
            buttonsDiv.innerHTML = `
                <button type="button" onclick="closeRedeemRepairModal()" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="button" onclick="claimRewardForRepair()" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">Apply Discount</button>
            `;
        } else {
            resultDiv.innerHTML = `<div class='p-2 bg-red-100 text-red-700 rounded'>❌ Invalid code or already claimed</div>`;
        }
    });
}

function claimReward() {
    if (!currentRewardId) return;

    fetch("{{ route('loyalty.confirmClaim') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ reward_id: currentRewardId })
    })
    .then(res => res.json())
    .then(data => {
        const resultDiv = document.getElementById('redeemResult');
        const buttonsDiv = document.getElementById('redeemButtons');

        if (data.success) {
            resultDiv.innerHTML = `<div class='p-2 bg-green-200 text-green-900 rounded font-semibold'>✅ Reward claimed successfully!</div>`;
            buttonsDiv.innerHTML = `
                <button type="button" onclick="closeRedeemModal()" class="px-4 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Done</button>
            `;
        } else {
            resultDiv.innerHTML = `<div class='p-2 bg-red-200 text-red-900 rounded font-semibold'>❌ Failed to claim reward</div>`;
        }
    });
}

function claimRewardForRepair() {
    if (!currentRewardId || !currentRepairId) return;

    fetch("{{ route('loyalty.confirmClaim') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ 
            reward_id: currentRewardId,
            repair_id: currentRepairId
        })
    })
    .then(res => res.json())
    .then(data => {
        const resultDiv = document.getElementById('redeemRepairResult');
        const buttonsDiv = document.getElementById('redeemRepairButtons');

        if (data.success) {
            resultDiv.innerHTML = `
                <div class='p-2 bg-green-200 text-green-900 rounded font-semibold'>
                    ✅ Discount applied successfully!<br>
                    <span class="text-sm">New amount: Rp ${data.new_amount?.toLocaleString() || '0'}</span>
                </div>
            `;
            buttonsDiv.innerHTML = `
                <button type="button" onclick="location.reload()" class="px-4 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Refresh Page</button>
            `;
        } else {
            resultDiv.innerHTML = `<div class='p-2 bg-red-200 text-red-900 rounded font-semibold'>❌ Failed to apply discount</div>`;
        }
    });
}

// Close modals when clicking outside
document.getElementById('addRepairModal').addEventListener('click', function(event) {
    if (event.target === this) this.close();
});
document.getElementById('createUserModal').addEventListener('click', function(event) {
    if (event.target === this) this.close();
});
document.getElementById('addPaymentModal').addEventListener('click', function(event) {
    if (event.target === this) this.close();
});
document.getElementById('redeemModal').addEventListener('click', function(event) {
    if (event.target === this) this.close();
});
document.getElementById('redeemRepairModal').addEventListener('click', function(event) {
    if (event.target === this) this.close();
});
</script>
@endsection