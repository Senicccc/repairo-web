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
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-2xl">🎁</span>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Enter Loyalty Code</h3>
            <p class="text-sm text-gray-600 mt-1">Redeem points for rewards</p>
        </div>

        <form id="redeemForm">
            @csrf
            <div class="mb-6">
                <input type="text" id="redeem_code" placeholder="Enter loyalty code" 
                       class="w-full border border-gray-300 rounded-lg p-4 text-center font-mono text-xl tracking-wider uppercase focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                       onkeyup="this.value = this.value.toUpperCase()">
            </div>

            <div id="redeemResult" class="mb-6 min-h-20"></div>

            <div class="flex justify-center gap-3" id="redeemButtons">
                <button type="button" onclick="checkRedeemCode()" 
                        class="bg-yellow-500 text-white px-8 py-3 rounded-lg hover:bg-yellow-600 transition font-medium">
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
        showResult('error', 'Please enter a loyalty code');
        return;
    }

    setButtonState('checking');

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
        if (data.valid) {
            currentRewardId = data.reward_id;
            showResult('success', `🎉 Valid Code!<br><strong>Reward:</strong> ${data.claim}`);
            setClaimButtons();
        } else {
            showResult('error', '❌ Invalid code or already claimed');
            setRetryButtons();
        }
    })
    .catch(error => {
        showResult('error', 'Network error, please try again');
        setRetryButtons();
    });
}

function claimReward() {
    if (!currentRewardId) return;

    setButtonState('claiming');

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
        if (data.success) {
            showResult('success_claimed', '✅ Reward claimed successfully!');
            setSuccessButton();
        } else {
            showResult('error', '❌ Failed to claim reward');
            setRetryButtons();
        }
    });
}

function showResult(type, message) {
    const resultDiv = document.getElementById('redeemResult');
    const colors = {
        'error': 'bg-red-100 text-red-700 border-red-200',
        'success': 'bg-green-100 text-green-700 border-green-200',
        'success_claimed': 'bg-green-200 text-green-900 border-green-300'
    };
    
    resultDiv.innerHTML = `
        <div class="p-4 rounded-lg border text-center ${colors[type]}">
            ${message}
        </div>
    `;
}

function setButtonState(state) {
    const buttonsDiv = document.getElementById('redeemButtons');
    const states = {
        'checking': `
            <button type="button" disabled class="bg-yellow-400 text-white px-8 py-3 rounded-lg font-medium">
                Checking...
            </button>
        `,
        'claiming': `
            <button type="button" disabled class="bg-green-400 text-white px-8 py-3 rounded-lg font-medium">
                Claiming...
            </button>
        `
    };
    
    if (states[state]) {
        buttonsDiv.innerHTML = states[state];
    }
}

function setClaimButtons() {
    const buttonsDiv = document.getElementById('redeemButtons');
    buttonsDiv.innerHTML = `
        <a href="{{ route('cashier.dashboard') }}" 
           class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">
            Cancel
        </a>
        <button type="button" onclick="claimReward()" 
                class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition font-medium">
            Claim Reward
        </button>
    `;
}

function setRetryButtons() {
    const buttonsDiv = document.getElementById('redeemButtons');
    buttonsDiv.innerHTML = `
        <a href="{{ route('cashier.dashboard') }}" 
           class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">
            Back
        </a>
        <button type="button" onclick="checkRedeemCode()" 
                class="bg-yellow-500 text-white px-8 py-3 rounded-lg hover:bg-yellow-600 transition font-medium">
            Try Again
        </button>
    `;
}

function setSuccessButton() {
    const buttonsDiv = document.getElementById('redeemButtons');
    buttonsDiv.innerHTML = `
        <a href="{{ route('cashier.dashboard') }}" 
           class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-medium">
            Back to Dashboard
        </a>
    `;
}
</script>
@endsection