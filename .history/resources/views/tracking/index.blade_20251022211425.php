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
            <table class="w-full text-left text-sm border-collapse border border-gray-200">
                <tr>
                    <th class="py-1 px-2 border">Tracking ID</th>
                    <td class="py-1 px-2 border">{{ $repair->tracking_id }}</td>
                </tr>
                <tr>
                    <th class="py-1 px-2 border">Customer</th>
                    <td class="py-1 px-2 border">{{ $repair->user->name ?? $repair->customer_name }}</td>
                </tr>
                <tr>
                    <th class="py-1 px-2 border">Phone</th>
                    <td class="py-1 px-2 border">{{ $repair->phone }}</td>
                </tr>
                <tr>
                    <th class="py-1 px-2 border">Brand</th>
                    <td class="py-1 px-2 border">{{ $repair->phone_brand }}</td>
                </tr>
                <tr>
                    <th class="py-1 px-2 border">Model</th>
                    <td class="py-1 px-2 border">{{ $repair->phone_model }}</td>
                </tr>
                <tr>
                    <th class="py-1 px-2 border">IMEI</th>
                    <td class="py-1 px-2 border">{{ $repair->imei ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="py-1 px-2 border">Complaint</th>
                    <td class="py-1 px-2 border">{{ $repair->complaint }}</td>
                </tr>
                <tr>
                    <th class="py-1 px-2 border">Technician</th>
                    <td class="py-1 px-2 border">{{ $repair->technician ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="py-1 px-2 border">Estimated Cost</th>
                    <td class="py-1 px-2 border">{{ $repair->cost ? 'Rp '.number_format($repair->cost,0,',','.') : '-' }}</td>
                </tr>
                <tr>
                    <th class="py-1 px-2 border">Status</th>
                    <td class="py-1 px-2 border">{{ ucfirst($repair->status) }}</td>
                </tr>
                <tr>
                    <th class="py-1 px-2 border">Payment Status</th>
                    <td class="py-1 px-2 border">{{ $repair->payment->status ?? 'Unpaid' }}</td>
                </tr>
                <tr>
                    <th class="py-1 px-2 border">Payment Method</th>
                    <td>{{ $repair->payment->payment_method ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="py-1 px-2 border">Created At</th>
                    <td class="py-1 px-2 border">{{ $repair->created_at->format('d M Y H:i') }}</td>
                </tr>
                <tr>
                    <th class="py-1 px-2 border">Last Updated</th>
                    <td class="py-1 px-2 border">{{ $repair->updated_at->format('d M Y H:i') }}</td>
                </tr>
            </table>
        </div>
        @else
        <p class="text-red-500">Tracking ID not found.</p>
        @endif
    @endisset
</div>
@endsection
