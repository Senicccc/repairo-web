@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6 space-y-6">

    <h2 class="text-3xl font-bold mb-4 text-center">Loyalty Program</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-3 rounded shadow">
            {{ session('error') }}
        </div>
    @endif

    @if(session('redeem_code'))
        <div class="bg-yellow-50 border p-4 rounded shadow space-y-2">
            <h3 class="font-semibold text-lg">Your Redeem Code</h3>
            <p class="text-xl font-mono font-bold" id="redeem-code">{{ session('redeem_code') }}</p>
            <p class="text-sm text-gray-600">Give this code to the cashier to redeem your reward.</p>
            <button id="copyBtn" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Copy Code</button>
        </div>
    @endif

    <div class="bg-white p-6 rounded shadow text-center">
        <p class="text-sm font-medium text-gray-500">Current Points</p>
        <p class="text-4xl font-bold mt-2 text-blue-600">{{ number_format(auth()->user()->loyalty_points ?? 0) }} pts</p>
        <p class="text-sm text-gray-500 mt-1">Available for rewards</p>
        <div class="mt-3">
            <a href="{{ route('users.dashboard') }}" class="inline-block text-sm text-gray-700 underline">← Back to dashboard</a>
        </div>
    </div>

    <h3 class="text-2xl font-semibold mt-6 mb-4">Available Rewards</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($rewardOptions as $r)
        <div class="border rounded shadow hover:shadow-lg p-4 flex flex-col justify-between">
            <div>
                <p><strong>Points Required:</strong> {{ $r['points'] }}</p>
                <p><strong>Discount:</strong> Rp{{ number_format($r['discount'], 0, ',', '.') }}</p>
                <p><strong>Gift 1:</strong> {{ $r['gift1'] }}</p>
                <p><strong>Gift 2:</strong> {{ $r['gift2'] }}</p>
            </div>
            <button class="mt-4 bg-green-500 hover:bg-green-600 text-white py-2 rounded font-semibold open-modal w-full sm:inline-block @if(auth()->user()->loyalty_points < $r['points']) opacity-50 cursor-not-allowed @endif"
                    data-points="{{ $r['points'] }}"
                    data-discount="{{ $r['discount'] }}"
                    data-gift1="{{ $r['gift1'] }}"
                    data-gift2="{{ $r['gift2'] }}"
                    @if(auth()->user()->loyalty_points < $r['points']) disabled @endif>
                @if(auth()->user()->loyalty_points < $r['points'])
                    Not Enough Points
                @else
                    Claim Reward
                @endif
            </button>
        </div>
        @endforeach
    </div>

    <h3 class="text-2xl font-semibold mt-8 mb-4">Your Claimed Rewards (Last 10)</h3>
    <div class="bg-blue-50 p-4 rounded mb-4">
        <p class="text-sm text-blue-800">
            <strong>Note:</strong> If the status is "claimed" that means you just claimed it and have not used it with the cashier. 
            If it's "used" then you have redeemed it with the cashier.
        </p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 rounded">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Code</th>
                    <th class="p-2 border">Reward</th>
                    <th class="p-2 border">Points</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Claimed At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentRewards as $r)
                <tr class="text-center">
                    <td class="p-2 font-mono border">{{ $r->redeem_code ?? '-' }}</td>
                    <td class="p-2 border">
                        @if($r->reward_type === 'discount')
                            Discount Rp{{ number_format((int)$r->reward_value, 0, ',', '.') }}
                        @else
                            {{ $r->reward_value }}
                        @endif
                    </td>
                    <td class="p-2 border">{{ $r->points_used }}</td>
                    <td class="p-2 border">
                        <span class="px-2 py-1 rounded text-xs 
                            @if($r->status === 'claimed') bg-yellow-100 text-yellow-800 
                            @elseif($r->status === 'used') bg-green-100 text-green-800 
                            @endif">
                            {{ ucfirst($r->status) }}
                        </span>
                    </td>
                    <td class="p-2 border">{{ $r->created_at->format('d M Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">No rewards claimed yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="claimModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded shadow-lg w-96 p-6 relative">
        <h3 class="text-xl font-semibold mb-4">Confirm Reward Claim</h3>
        <form id="modalForm" action="{{ route('loyalty.claim') }}" method="POST" class="space-y-4">
            @csrf
            <p>Points Required: <span id="modalPoints" class="font-bold"></span></p>

            <div>
                <label class="font-semibold">Choose Reward:</label>
                <div class="mt-2 space-y-2">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="reward_type" value="discount" class="reward-option" checked>
                        <span>Discount Rp<span id="modalDiscount"></span></span>
                        <input type="hidden" name="discount" id="formDiscount">
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="reward_type" value="gift1" class="reward-option">
                        <span id="gift1Label"></span>
                        <input type="hidden" name="gift1" id="formGift1">
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="reward_type" value="gift2" class="reward-option">
                        <span id="gift2Label"></span>
                        <input type="hidden" name="gift2" id="formGift2">
                    </label>
                </div>
            </div>

            <input type="hidden" name="points" id="formPoints">

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" id="closeModal" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-3 py-1 bg-green-500 rounded text-white hover:bg-green-600">Confirm</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy code functionality
    document.addEventListener('click', function(e){
        if(e.target && e.target.id === 'copyBtn'){
            const code = document.getElementById('redeem-code').innerText.trim();
            navigator.clipboard.writeText(code).then(() => {
                alert('Code copied: ' + code);
            });
        }

        // Open modal
        if(e.target && e.target.classList.contains('open-modal')){
            const btn = e.target;

            document.getElementById('modalPoints').innerText = btn.dataset.points;
            document.getElementById('modalDiscount').innerText = parseInt(btn.dataset.discount).toLocaleString('id-ID');
            document.getElementById('gift1Label').innerText = btn.dataset.gift1;
            document.getElementById('gift2Label').innerText = btn.dataset.gift2;

            document.getElementById('formPoints').value = btn.dataset.points;
            document.getElementById('formDiscount').value = btn.dataset.discount;
            document.getElementById('formGift1').value = btn.dataset.gift1;
            document.getElementById('formGift2').value = btn.dataset.gift2;

            document.getElementById('claimModal').classList.remove('hidden');
            document.getElementById('claimModal').classList.add('flex');
        }

        // Close modal
        if(e.target && e.target.id === 'closeModal'){
            document.getElementById('claimModal').classList.add('hidden');
            document.getElementById('claimModal').classList.remove('flex');
        }
    });

    // Close modal when clicking outside
    document.getElementById('claimModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            this.classList.remove('flex');
        }
    });
});
</script>
@endsection