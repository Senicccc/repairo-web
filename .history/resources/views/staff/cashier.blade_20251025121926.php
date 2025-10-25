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

<!-- Redeem Loyalty Modal -->
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
    const code = document.getElementById('redeem_code_modal').value.trim();
    if(!code) return;
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
            resultDiv.innerHTML = `<div class='p-2 bg-green-100 text-green-700 rounded'>Claim: ${data.claim}</div>`;
            currentRewardId = data.reward_id;
            currentRewardType = data.claim.startsWith('Discount') ? 'discount' : 'gift';
            currentRewardValue = data.claim;
            document.getElementById('confirmClaimBtn').classList.remove('hidden');
        } else {
            resultDiv.innerHTML = `<div class='p-2 bg-red-100 text-red-700 rounded'>Code is not valid</div>`;
            document.getElementById('confirmClaimBtn').classList.add('hidden');
        }
    });
}

function confirmClaim() {
    if(!currentRewardId) return;
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
            resultDiv.innerHTML = `<div class='p-2 bg-green-200 text-green-900 rounded'>Reward redeemed successfully!</div>`;
            document.getElementById('confirmClaimBtn').classList.add('hidden');
        } else {
            resultDiv.innerHTML = `<div class='p-2 bg-red-200 text-red-900 rounded'>Failed to redeem reward.</div>`;
        }
    });
}
</script>
@endsection
