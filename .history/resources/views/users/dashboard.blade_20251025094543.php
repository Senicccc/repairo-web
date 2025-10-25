@extends('layouts.app')

@section('content')
<h1>User Dashboard</h1>
<p>List of your repair requests will appear here.</p>

<a href="{{ route('loyalty.rewards') }}" 
   class="inline-block mt-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
   Go to Loyalty Rewards
</a>
@endsection
