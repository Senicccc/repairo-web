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
