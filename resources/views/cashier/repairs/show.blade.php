@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Repair Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('cashier.repairs.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                ← Back to Repairs
            </a>
            <a href="{{ route('invoice.show', $repair->id) }}" 
               class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                🧾 Generate Invoice
            </a>
        </div>
    </div>

    <!-- Repair Info -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Customer Info -->
            <div>
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Customer Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="font-medium">Name:</span>
                        <span>{{ $repair->user->name ?? $repair->customer_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Phone:</span>
                        <span>{{ $repair->phone }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Tracking ID:</span>
                        <span class="font-mono text-blue-600">{{ $repair->tracking_id }}</span>
                    </div>
                </div>
            </div>

            <!-- Device Info -->
            <div>
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Device Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="font-medium">Brand/Model:</span>
                        <span>{{ $repair->phone_brand }} {{ $repair->phone_model }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">IMEI:</span>
                        <span>{{ $repair->imei ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Status:</span>
                        <span class="{{ \App\Models\Repair::getStatusColor($repair->status) }} px-2 py-1 rounded text-xs font-medium">
                            {{ \App\Models\Repair::getStatuses()[$repair->status] ?? ucfirst($repair->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Complaint -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">Complaint Description</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-700">{{ $repair->complaint }}</p>
            </div>
        </div>

        <!-- Diagnosis & Cost -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <h3 class="text-lg font-semibold mb-2">Technician Diagnosis</h3>
                <div class="bg-gray-50 p-4 rounded-lg min-h-20">
                    <p class="text-gray-700">{{ $repair->diagnosis ?? 'No diagnosis provided yet.' }}</p>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-2">Cost Information</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">
                        {{ $repair->cost ? 'Rp ' . number_format($repair->cost, 0, ',', '.') : 'Not set' }}
                    </div>
                    @if($repair->technician)
                    <p class="text-sm text-gray-600 mt-1">Technician: {{ $repair->technician }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Payment Information</h3>
        @if($repair->payment)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="text-sm text-gray-600">Status</div>
                <div class="text-lg font-bold text-green-600 capitalize">{{ $repair->payment->status }}</div>
            </div>
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="text-sm text-gray-600">Method</div>
                <div class="text-lg font-bold text-blue-600 capitalize">{{ $repair->payment->payment_method }}</div>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <div class="text-sm text-gray-600">Amount</div>
                <div class="text-lg font-bold text-purple-600">Rp {{ number_format($repair->payment->amount, 0, ',', '.') }}</div>
            </div>
        </div>
        @else
        <div class="text-center p-6 bg-yellow-50 rounded-lg">
            <p class="text-yellow-700">No payment recorded yet.</p>
            @if($repair->status === 'finished')
            <a href="{{ route('cashier.payments.create', $repair->id) }}" 
               class="mt-3 inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                Process Payment
            </a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection