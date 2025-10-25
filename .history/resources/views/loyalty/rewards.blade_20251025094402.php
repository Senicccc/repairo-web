@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">Loyalty Rewards</h2>
    <p class="mb-4">Your Points: {{ $points }}</p>

    @foreach($rewardOptions as $r)
        @if($points >= $r['points'])
        <div class="mb-3 p-3 border rounded flex justify-between items-center">
            <div>
                <strong>{{ $r['points'] }} Points</strong> → Discount ${{ $r['discount'] }} <br>
                Gifts: {{ $r['gift1'] }} / {{ $r['gift2'] }}
            </div>
            <form action="{{ route('loyalty.claim') }}" method="POST">
                @csrf
                <input type="hidden" name="points" value="{{ $r['points'] }}">
                <select name="reward_type" class="border rounded p-1">
                    <option value="discount">Discount ${{ $r['discount'] }}</option>
                    <option value="gift">{{ $r['gift1'] }}</option>
                    <option value="gift">{{ $r['gift2'] }}</option>
                </select>
                <input type="hidden" name="reward_value" value="">
                <button type="submit" class="ml-2 bg-blue-500 text-white px-3 py-1 rounded">Claim</button>
            </form>
        </div>
        @endif
    @endforeach
</div>

<script>
    document.querySelectorAll('form select').forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').querySelector('input[name="reward_value"]').value = this.value;
        });
        select.dispatchEvent(new Event('change'));
    });
</script>
@endsection
