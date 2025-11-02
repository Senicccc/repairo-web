@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Redeem Loyalty Code</h2>
        <a href="{{ route('cashier.dashboard') }}" 
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
            ← Back to Dashboard
        </a>
    </div>

    <div class="bg-white shadow-lg rounded-lg p-6">
        <form id="redeemForm">
            @csrf
            <div class="text-center mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Enter Loyalty Code</label>
                <input type="text" id="redeem_code" placeholder="Enter loyalty code" 
                       class="w-full border border-gray-300 rounded-lg p-4 text-center font-mono text-xl tracking-wider uppercase"
                       onkeyup="this.value = this.value.toUpperCase()">
            </div>

            <div id="redeemResult" class="mb-4 min-h-20"></div>

            <div class="flex justify-center gap-3" id="redeemButtons">
                <button type="button" onclick="checkRedeemCode()" 
                        class="bg-yellow-500 text-white px-6 py-3 rounded-lg hover:bg-yellow-600 transition font-medium">
                    Check Code
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let currentRewardId = null;

function checkRedeemCode() {
    const code = document.getElementById('redeem_code').value.trim();
    if (!code) {
        document.getElementById('redeemResult').innerHTML = `
            <div class='p-4 bg-red-100 text-red-700 rounded-lg text-center'>
                Please enter a loyalty code
            </div>
        `;
        return;
    }

    document.getElementById('redeemButtons').innerHTML = `
        <button type="button" disabled class="bg-yellow-400 text-white px-6 py-3 rounded-lg font-medium">
            Checking...
        </button>
    `;

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
            resultDiv.innerHTML = `
                <div class='p-4 bg-green-100 text-green-700 rounded-lg text-center'>
                    <div class="text-lg font-semibold">🎉 Valid Code!</div>
                    <div class="text-sm mt-1">Reward: ${data.claim}</div>
                </div>
            `;
            buttonsDiv.innerHTML = `
                <a href="{{ route('cashier.dashboard') }}" 
                   class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">
                    Cancel
                </a>
                <button type="button" onclick="claimReward()" 
                        class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition font-medium">
                    Claim Reward
                </button>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class='p-4 bg-red-100 text-red-700 rounded-lg text-center'>
                    ❌ Invalid code or already claimed
                </div>
            `;
            buttonsDiv.innerHTML = `
                <a href="{{ route('cashier.dashboard') }}" 
                   class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">
                    Back
                </a>
                <button type="button" onclick="checkRedeemCode()" 
                        class="bg-yellow-500 text-white px-6 py-3 rounded-lg hover:bg-yellow-600 transition font-medium">
                    Try Again
                </button>
            `;
        }
    })
    .catch(error => {
        document.getElementById('redeemResult').innerHTML = `
            <div class='p-4 bg-red-100 text-red-700 rounded-lg text-center'>
                Network error, please try again
            </div>
        `;
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
            resultDiv.innerHTML = `
                <div class='p-4 bg-green-200 text-green-900 rounded-lg text-center'>
                    <div class="text-lg font-semibold">✅ Reward claimed successfully!</div>
                </div>
            `;
            buttonsDiv.innerHTML = `
                <a href="{{ route('cashier.dashboard') }}" 
                   class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-medium">
                    Back to Dashboard
                </a>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class='p-4 bg-red-200 text-red-900 rounded-lg text-center'>
                    ❌ Failed to claim reward
                </div>
            `;
        }
    });
}
</script>
@endsection