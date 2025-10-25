@extends('layouts.app')

@section('content')
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

<!-- existing reward list here -->
@foreach($rewardOptions as $r)
   <!-- ... existing markup ... -->
@endforeach

@foreach(auth()->user()->loyaltyRewards as $reward)
    <p>{{ $reward->reward_type }} - {{ $reward->reward_value }} - {{ $reward->status }}</p>
@endforeach


<h3 class="mt-6">Your Claimed Rewards</h3>
<table class="w-full mt-2">
    <thead><tr><th>Code</th><th>Reward</th><th>Points</th><th>Status</th><th>Claimed At</th></tr></thead>
    <tbody>
    @foreach(Auth::user()->loyaltyRewards()->latest()->take(10)->get() as $r)
        <tr>
            <td class="font-mono">{{ $r->redeem_code ?? '-' }}</td>
            <td>{{ $r->reward_type === 'discount' ? 'Discount Rp'.number_format($r->reward_value) : $r->reward_value }}</td>
            <td>{{ $r->points_used }}</td>
            <td>{{ ucfirst($r->status) }}</td>
            <td>{{ $r->created_at->format('d M Y H:i') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

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
