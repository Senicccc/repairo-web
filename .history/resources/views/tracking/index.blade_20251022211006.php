@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Track Your Repair</h2>

    <form method="POST" action="{{ route('tracking.search') }}" class="mb-6">
        @csrf
        <div class="flex space-x-2">
            <input type="text" name="tracking_id" placeholder="Enter Tracking ID" 
                   class="w-full border p-2 rounded" required>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Search
            </button>
        </div>
    </form>

    @isset($repair)
        @if($repair)
        <div class="bg-white shadow-md rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">Repair Details</h3>
            <table class="w-full text-left text-sm">
                <tr>
                    <th class="py-1">Tracking ID:</th>
                    <td>{{ $repair->tracking_id }}</td>
                </tr>
                <tr>
                    <th class="py-1">Customer:</th>
                    <td>{{ $repair->user->name ?? $repair->customer_name }}</td>
                </tr>
                <tr>
                    <th class="py-1">Phone:</th>
                    <td>{{ $repair->phone }}</td>
                </tr>
                <tr>
                    <th class="py-1">Brand:</th>
                    <td>{{ $repair->phone_brand }}</td>
                </tr>
                <tr>
                    <th class="py-1">Model:</th>
                    <td>{{ $repair->phone_model }}</td>
                </tr>
                <tr>
                    <th class="py-1">Complaint:</th>
                    <td>{{ $repair->complaint }}</td>
                </tr>
                <tr>
                    <th class="py-1">Technician:</th>
                    <td>{{ $repair->technician ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="py-1">Status:</th>
                    <td>{{ ucfirst($repair->status) }}</td>
                </tr>
                <tr>
                    <th class="py-1">Payment:</th>
                    <td>{{ $repair->payment->status ?? 'Unpaid' }}</td>
                </tr>
            </table>
        </div>
        @else
        <p class="text-red-500">Tracking ID not found.</p>
        @endif
    @endisset
</div>
@endsection
