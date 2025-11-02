@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6">Redeem Loyalty Reward</h2>
    
    <div class="bg-white p-6 rounded shadow">
        <form id="redeemForm" class="space-y-4">
            @csrf
            <div>
                <label for="redeem_code" class="block text-sm font-medium text-gray-700">Redeem Code</label>
                <input type="text" id="redeem_code" name="redeem_code" 
                       class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Enter redeem code" required>
            </div>
            
            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                Check Code
            </button>
        </form>
        
        <div id="result" class="mt-4 hidden"></div>
    </div>
</div>

<script>
document.getElementById('redeemForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const resultDiv = document.getElementById('result');
    
    fetch('{{ route("cashier.loyalty.check") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.valid) {
            resultDiv.innerHTML = `
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <p><strong>Valid Code!</strong></p>
                    <p>Reward: ${data.claim}</p>
                    <button id="confirmRedeem" data-reward-id="${data.reward_id}" 
                            class="mt-2 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        Confirm Redemption
                    </button>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    Invalid or already used redeem code.
                </div>
            `;
        }
        resultDiv.classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error:', error);
        resultDiv.innerHTML = `
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                An error occurred. Please try again.
            </div>
        `;
        resultDiv.classList.remove('hidden');
    });
});

document.addEventListener('click', function(e) {
    if (e.target && e.target.id === 'confirmRedeem') {
        const rewardId = e.target.getAttribute('data-reward-id');
        
        fetch('{{ route("cashier.loyalty.confirm") }}', {
            method: 'POST',
            body: JSON.stringify({ reward_id: rewardId }),
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            const resultDiv = document.getElementById('result');
            if (data.success) {
                resultDiv.innerHTML = `
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        Reward successfully redeemed and marked as used!
                    </div>
                `;
            } else {
                resultDiv.innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        Failed to redeem reward. Please try again.
                    </div>
                `;
            }
        });
    }
});
</script>
@endsection