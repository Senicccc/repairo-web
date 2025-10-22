@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Invoice - Tracking #{{ $repair->id }}</h2>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <h3 class="font-medium">Customer</h3>
            <p>{{ $repair->customer_name }}</p>
            <p>{{ $repair->phone }}</p>
        </div>
        <div>
            <h3 class="font-medium">Device</h3>
            <p>{{ $repair->phone_brand }} - {{ $repair->phone_model }}</p>
            <p>Complaint: {{ $repair->complaint }}</p>
        </div>
    </div>

    <div class="mb-4">
        <h3 class="font-medium">Charges</h3>
        <p>Repair cost: {{ $repair->cost ?? 'TBD' }}</p>
    </div>

    <div class="flex justify-end">
        <a href="javascript:window.print()" class="px-4 py-2 bg-gray-700 text-white rounded">Print</a>
    </div>
</div>
@endsection
