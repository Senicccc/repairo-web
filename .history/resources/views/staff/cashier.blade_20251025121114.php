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
                        <th class="px-4 py-3 border" data-field="cost">Cost</th>
                        <th class="px-4 py-3 border">Invoice</th>
                        <th class="px-4 py-3 border">Redeem</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($repairs->sortByDesc('created_at') as $repair)
                    <tr class="text-center hover:bg-gray-50 transition border-b" 
                        data-repair-id="{{ $repair->id }}" 
                        data-cost="{{ $repair->cost ?? 0 }}">
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
                        <td class="border px-3 py-2" data-field="cost">{{ $repair->cost ?? 0 }}</td>
                        <td class="border px-3 py-2">
                            <a href="{{ route('invoice.show', $repair->id) }}" 
                               class="bg-gray-700 text-white px-3 py-1 rounded hover:bg-gray-800 transition">
                                Invoice
                            </a>
                        </td>
                        <td class="border px-3 py-2"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Repair Modal -->
<dialog id="addRepairModal" class="modal">
    <form method="POST" action="{{ route('repairs.store') }}" class="p-6 bg-white rounded shadow-md w-[480px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Add New Repair</h3>
        <input type="text" name="name" placeholder="Customer Name (optional)" class="w-full border p-2 mb-3 rounded">
        <input type="text" name="phone" placeholder="Phone Number" required class="w-full border p-2 mb-3 rounded">
        <input type="text" name="phone_brand" placeholder="Phone Brand" required class="w-full border p-2 mb-3 rounded">
        <input type="text" name="phone_model" placeholder="Phone Model" required class="w-full border p-2 mb-3 rounded">
        <input type="text" name="imei" placeholder="IMEI (optional)" class="w-full border p-2 mb-3 rounded">
        <textarea name="complaint" placeholder="Damage Description" required class="w-full border p-2 mb-3 rounded"></textarea>
        <input type="number" step="0.01" name="cost" placeholder="Estimated Cost (optional)" class="w-full border p-2 mb-3 rounded">
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('addRepairModal').close()" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
        </div>
    </form>
</dialog>

<!-- Create User Modal -->
<dialog id="createUserModal" class="modal">
    <form method="POST" action="{{ route('users.store') }}" class="p-6 bg-white rounded shadow-md w-[420px] mx-auto">
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
            <button type="button" onclick="document.getElementById('createUserModal').close()" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">Create</button>
        </div>
    </form>
</dialog>

<!-- Add Payment Modal -->
<dialog id="addPaymentModal" class="modal">
    <form method="POST" action="{{ route('payments.store') }}" class="p-6 bg-white rounded shadow-md w-[400px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Repair Payment</h3>
        <input type="hidden" name="repair_id" id="repair_id">
        <input type="number" name="amount" placeholder="Amount Paid" required class="w-full border p-2 mb-3 rounded">
        <select name="status" required class="w-full border p-2 mb-3 rounded">
            <option value="paid">Paid</option>
            <option value="unpaid">Unpaid</option>
        </select>
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('addPaymentModal').close()" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button type="submit" class="px-3 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700">Submit</button>
        </div>
    </form>
</dialog>

<!-- Redeem Loyalty Modal -->
<dialog id="redeemModal" class="modal">
    <div class="p-6 bg-white rounded shadow-md w-[400px] mx-auto">
        <h3 class="text-lg font-semibold mb-4">Redeem Loyalty Code</h3>
        <input type="text" id="redeem_code_modal" placeholder="Enter loyalty code" class="w-full border p-2 mb-3 rounded">
        <div id="redeemResult" class="mb-3 text-sm"></div>
        <div class="flex justify-end gap-2">
            <button onclick="document.getElementById('redeemModal').close()" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button onclick="checkRedeemCode()" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Check</button>
            <button id="confirmClaimBtn" onclick="confirmClaim()" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 hidden">Confirm Claim</button>
        </div>
    </div>
</dialog>

<script>
function openPaymentModal(repairId) {
    document.getElementById('repair_id').value = repairId;
    document.getElementById('addPaymentModal').showModal();
}

function filterTable() {
    let filter = document.getElementById('searchBar').value.toLowerCase();
    document.querySelectorAll('#repairsTable tbody tr').forEach(row => {
        row.style.display = [...row.children].some(td => td.innerText.toLowerCase().includes(filter)) ? '' : 'none';
    });
}

// Highlight selected row
document.querySelectorAll('#repairsTable tbody tr').forEach(row => {
    row.addEventListener('click', () => {
        document.querySelectorAll('#repairsTable tbody tr').forEach(r => r.classList.remove('selected'));
        row.classList.add('selected');
    });
});

// Redeem logic
let currentRewardId = null;
let currentRewardType = null;
let currentRewardValue = null;

function openRedeemModal() {
    document.getElementById('redeem_code_modal').value = '';
    document.getElementById('redeemResult').innerHTML = '';
    document.getElementById('confirmClaimBtn').classList.add('hidden');
    currentRewardId = null;
    currentRewardType = null;
    currentRewardValue = null;
    document.getElementById('redeemModal').showModal();
}

function checkRedeemCode() {
    const code = document.getElementById('redeem_code_modal').value;
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
        if(data.valid) {
            resultDiv.innerHTML = `<div class="p-2 bg-green-100 text-green-700 rounded">Claim: ${data.claim}</div>`;
            currentRewardId = data.reward_id;

            if(data.claim.startsWith('Discount')) {
                currentRewardType = 'discount';
                currentRewardValue = parseInt(data.claim.replace(/\D/g,''));
            } else {
                currentRewardType = 'gift';
                currentRewardValue = data.claim;
            }

            document.getElementById('confirmClaimBtn').classList.remove('hidden');
        } else {
            resultDiv.innerHTML = `<div class="p-2 bg-red-100 text-red-700 rounded">Code is not valid</div>`;
            document.getElementById('confirmClaimBtn').classList.add('hidden');
        }
    });
}

function confirmClaim() {
    if(!currentRewardId) return;

    const repairRow = document.querySelector('tr.selected');
    if(!repairRow) {
        alert('Please select a repair transaction first!');
        return;
    }
    const repairId = repairRow.dataset.repairId;

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
        if(data.success) {
            if(currentRewardType === 'discount') {
                const costCell = repairRow.querySelector('td[data-field="cost"]');
                if(costCell) {
                    let currentCost = parseInt(repairRow.dataset.cost);
                    let newCost = Math.max(0, currentCost - currentRewardValue);
                    repairRow.dataset.cost = newCost;
                    costCell.innerText = newCost.toLocaleString('id-ID');
                }
            }
            resultDiv.innerHTML = `<div class="p-2 bg-green-200 text-green-900 rounded">${data.message}</div>`;
            document.getElementById('confirmClaimBtn').classList.add('hidden');
        } else {
            resultDiv.innerHTML = `<div class="p-2 bg-red-200 text-red-900 rounded">${data.message}</div>`;
        }
    });
}
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto bg-white rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Cashier Panel</h2>

    <button onclick="document.getElementById('redeemModal').showModal()" 
            class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
        Redeem Loyalty Code
    </button>
</div>

<!-- Modal Redeem Loyalty -->
<dialog id="redeemModal" class="modal">
    <div class="p-6 bg-white rounded shadow-md w-[400px] mx-auto">
        <h3 class="text-lg font-semibold mb-4">Redeem Loyalty Code</h3>
        <input type="text" id="redeem_code_modal" placeholder="Enter loyalty code" class="w-full border p-2 mb-3 rounded uppercase">
        <div id="redeemResult" class="mb-3 text-sm"></div>
        <div class="flex justify-end gap-2">
            <button type="button" onclick="document.getElementById('redeemModal').close()" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button type="button" onclick="checkRedeemCode()" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Check</button>
            <button type="button" id="confirmClaimBtn" onclick="confirmClaim()" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 hidden">Confirm Claim</button>
        </div>
    </div>
</dialog>

<script>
function checkRedeemCode() {
    const code = document.getElementById('redeem_code_modal').value.trim();
    const result = document.getElementById('redeemResult');
    const confirmBtn = document.getElementById('confirmClaimBtn');

    if (!code) {
        result.innerHTML = '<span class="text-red-600">Please enter a code.</span>';
        return;
    }

    fetch("{{ route('loyalty.check') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ redeem_code: code })
    })
    .then(res => res.json())
    .then(data => {
        if (data.valid) {
            result.innerHTML = `<span class="text-green-600">Valid code! Reward: ${data.claim}</span>`;
            confirmBtn.dataset.rewardId = data.reward_id;
            confirmBtn.classList.remove('hidden');
        } else {
            result.innerHTML = '<span class="text-red-600">Code is not valid.</span>';
            confirmBtn.classList.add('hidden');
        }
    });
}

function confirmClaim() {
    const rewardId = document.getElementById('confirmClaimBtn').dataset.rewardId;
    const result = document.getElementById('redeemResult');

    fetch("{{ route('loyalty.confirmClaim') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ reward_id: rewardId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            result.innerHTML = '<span class="text-green-600">Reward redeemed successfully!</span>';
            document.getElementById('confirmClaimBtn').classList.add('hidden');
        } else {
            result.innerHTML = '<span class="text-red-600">Failed to redeem reward.</span>';
        }
    });
}
</script>
@endsection
