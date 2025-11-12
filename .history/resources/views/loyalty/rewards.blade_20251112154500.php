@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">

    {{-- HEADER --}}
    <div class="text-center mb-8">
        <h2 class="text-4xl font-extrabold text-[#1800ad] tracking-tight">Loyalty Program</h2>
        <p class="text-gray-500 mt-2">Earn points, claim exclusive rewards, and enjoy more benefits.</p>
    </div>

    {{-- SUCCESS / ERROR ALERTS --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-lg mb-4 shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg mb-4 shadow-sm">
            {{ session('error') }}
        </div>
    @endif
    @if(session('redeem_code'))
        <div class="bg-yellow-50 border border-yellow-200 p-5 rounded-lg shadow-sm mb-8">
            <h3 class="font-semibold text-lg text-gray-800 mb-2">Your Redeem Code</h3>
            <p class="text-2xl font-mono font-bold text-[#1800ad]" id="redeem-code">{{ session('redeem_code') }}</p>
            <p class="text-sm text-gray-600 mt-1">Give this code to the cashier to redeem your reward.</p>
            <button id="copyBtn" class="mt-3 px-4 py-2 bg-[#1800ad] text-white font-medium rounded-lg hover:bg-[#0e0080] transition">Copy Code</button>
        </div>
    @endif

    {{-- CURRENT POINTS CARD --}}
    <div class="bg-white rounded-2xl shadow-md p-6 text-center border border-gray-100 mb-10">
        <p class="text-sm font-medium text-gray-500">Your Current Points</p>
        <p class="text-5xl font-extrabold mt-2 text-[#1800ad]">{{ number_format(auth()->user()->loyalty_points ?? 0) }}</p>
        <p class="text-sm text-gray-500 mt-1">Available for rewards</p>
        <a href="{{ route('users.dashboard') }}" class="inline-block mt-4 text-sm font-medium text-[#1800ad] hover:underline">
            ← Back to Dashboard
        </a>
    </div>

    {{-- REWARDS SECTION --}}
    <div class="mb-12">
        <h3 class="text-2xl font-bold mb-6 text-[#1800ad]">Available Rewards</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($rewardOptions as $r)
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-lg transition p-5 flex flex-col justify-between">
                <div class="space-y-2">
                    <p><span class="font-semibold text-gray-600">Points Required:</span> {{ $r['points'] }}</p>
                    <p><span class="font-semibold text-gray-600">Discount:</span> Rp{{ number_format($r['discount'], 0, ',', '.') }}</p>
                    <p><span class="font-semibold text-gray-600">Gift 1:</span> {{ $r['gift1'] }}</p>
                    <p><span class="font-semibold text-gray-600">Gift 2:</span> {{ $r['gift2'] }}</p>
                </div>

                <button class="mt-5 bg-[#1800ad] hover:bg-[#0e0080] text-white py-2 rounded-lg font-semibold transition open-modal disabled:opacity-40 disabled:cursor-not-allowed"
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
    </div>

    {{-- CLAIMED REWARDS SECTION --}}
    <div class="mb-16">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-2xl font-bold text-[#1800ad]">Your Claimed Rewards (Last 10)</h3>
        </div>

        <div class="bg-blue-50 border border-blue-100 p-4 rounded-lg mb-4">
            <p class="text-sm text-blue-800">
                <strong>Note:</strong> “Claimed” means you just claimed it and haven’t used it yet. “Used” means it has been redeemed with the cashier.
            </p>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-700 border-b">
                    <tr>
                        <th class="p-3 text-left">Code</th>
                        <th class="p-3 text-left">Reward</th>
                        <th class="p-3 text-left">Points</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Claimed At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentRewards as $r)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-3 font-mono">{{ $r->redeem_code ?? '-' }}</td>
                        <td class="p-3">
                            @if($r->reward_type === 'discount')
                                Discount Rp{{ number_format((int)$r->reward_value, 0, ',', '.') }}
                            @else
                                {{ $r->reward_value }}
                            @endif
                        </td>
                        <td class="p-3">{{ $r->points_used }}</td>
                        <td class="p-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($r->status === 'claimed') bg-yellow-100 text-yellow-800 
                                @elseif($r->status === 'used') bg-green-100 text-green-800 
                                @endif">
                                {{ ucfirst($r->status) }}
                            </span>
                        </td>
                        <td class="p-3 text-gray-600">{{ $r->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">No rewards claimed yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL --}}
<div id="claimModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-96 p-6 relative animate-fadeIn">
        <h3 class="text-xl font-semibold mb-4 text-[#1800ad]">Confirm Reward Claim</h3>
        <form id="modalForm" action="{{ route('loyalty.claim') }}" method="POST" class="space-y-4">
            @csrf
            <p>Points Required: <span id="modalPoints" class="font-bold text-[#1800ad]"></span></p>

            <div>
                <label class="font-semibold text-gray-700">Choose Reward:</label>
                <div class="mt-3 space-y-2">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="reward_type" value="discount" class="reward-option" checked>
                        <span>Discount Rp<span id="modalDiscount"></span></span>
                        <input type="hidden" name="discount" id="formDiscount">
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="reward_type" value="gift1" class="reward-option">
                        <span id="gift1Label"></span>
                        <input type="hidden" name="gift1" id="formGift1">
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="reward_type" value="gift2" class="reward-option">
                        <span id="gift2Label"></span>
                        <input type="hidden" name="gift2" id="formGift2">
                    </label>
                </div>
            </div>

            <input type="hidden" name="points" id="formPoints">

            <div class="flex justify-end space-x-2 mt-5">
                <button type="button" id="closeModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-gray-800 font-medium">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-[#1800ad] hover:bg-[#0e0080] text-white rounded-lg font-medium">Confirm</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(e){
        // Copy
        if(e.target && e.target.id === 'copyBtn'){
            const code = document.getElementById('redeem-code').innerText.trim();
            navigator.clipboard.writeText(code).then(() => alert('Code copied: ' + code));
        }

        // Open Modal
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

        // Close Modal
        if(e.target && e.target.id === 'closeModal'){
            document.getElementById('claimModal').classList.add('hidden');
            document.getElementById('claimModal').classList.remove('flex');
        }
    });

    document.getElementById('claimModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            this.classList.remove('flex');
        }
    });
});
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.animate-fadeIn { animation: fadeIn 0.2s ease-out; }
</style>
@endsection
