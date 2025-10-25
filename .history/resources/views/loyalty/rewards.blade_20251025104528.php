@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">

    <h2 class="text-2xl font-bold mb-4">Loyalty Program</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('redeem_code'))
        <div class="mb-4 p-4 border rounded bg-yellow-50">
            <h3 class="font-semibold">Your Redeem Code</h3>
            <p class="text-lg font-mono my-2">
                <strong id="redeem-code">{{ session('redeem_code') }}</strong>
            </p>
            <p class="text-sm text-gray-600">Give this code to the cashier to redeem your reward.</p>
            <button id="copyBtn" class="mt-2 px-3 py-1 bg-blue-500 text-white rounded">Copy Code</button>
        </div>
    @endif

    <div class="mb-6">
        <p class="font-semibold">Your Current Points: <span class="text-blue-600">{{ auth()->user()->loyalty_points }}</span></p>
    </div>

    <h3 class="text-xl font-semibold mb-2">Available Rewards</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($rewardOptions as $r)
            <div class="p-3 border rounded shadow-sm">
                <p><strong>Points Required:</strong> {{ $r['points'] }}</p>
                <p><strong>Discount:</strong> Rp{{ number_format((int)$r['discount']) }}</p>
                <p><strong>Gift 1:</strong> {{ $r['gift1'] }}</p>
                <p><strong>Gift 2:</strong> {{ $r['gift2'] }}</p>
            </div>
        @endforeach
    </div>

    <h3 class="text-xl font-semibold mt-8 mb-2">Your Claimed Rewards (Last 10)</h3>
    <table class="w-full mt-2 border border-gray-300 rounded">
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
        @foreach(Auth::user()->loyaltyRewards()->latest()->take(10)->get() as $r)
            <tr class="text-center">
                <td class="p-2 font-mono border">{{ $r->redeem_code ?? '-' }}</td>
                <td class="p-2 border">
                    @if($r->reward_type === 'discount')
                        Discount Rp{{ number_format((int)$r->reward_value) }}
                    @else
                        {{ $r->reward_value }}
                    @endif
                </td>
                <td class="p-2 border">{{ $r->points_used }}</td>
                <td class="p-2 border">{{ ucfirst($r->status) }}</td>
                <td class="p-2 border">{{ $r->created_at->format('d M Y H:i') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script>
document.addEventListener('click', function(e){
    if(e.target && e.target.id === 'copyBtn'){
        const code = document.getElementById('redeem-code').innerText.trim();
        navigator.clipboard.writeText(code).then(() => {
            alert('Code copied to clipboard: ' + code);
        });
    }
});
</script>
@endsection
