@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Process Payment</h2>
        <a href="{{ route('cashier.dashboard') }}" 
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
            ← Back to Dashboard
        </a>
    </div>

    <!-- Repair Info -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <h3 class="text-lg font-semibold mb-2">Repair Information</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-medium">Tracking ID:</span>
                <span class="font-mono text-blue-600">{{ $repair->tracking_id }}</span>
            </div>
            <div>
                <span class="font-medium">Customer:</span>
                <span>{{ $repair->user->name ?? $repair->customer_name }}</span>
            </div>
            <div>
                <span class="font-medium">Device:</span>
                <span>{{ $repair->phone_brand }} {{ $repair->phone_model }}</span>
            </div>
            <div>
                <span class="font-medium">Repair Cost:</span>
                <span class="font-semibold text-green-600">Rp {{ number_format($repair->cost, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Payment Form -->
    <div class="bg-white shadow-lg rounded-lg p-6">
        <form method="POST" action="{{ route('cashier.payments.store') }}">
            @csrf
            
            <input type="hidden" name="repair_id" value="{{ $repair->id }}">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount *</label>
                    <input type="number" name="amount" value="{{ $repair->cost }}" required 
                           class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 font-semibold">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                    <select name="payment_method" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="cash">Cash</option>
                        <option value="transfer">Bank Transfer</option>
                        <option value="ewallet">E-Wallet</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="paid">Paid</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8 pt-6 border-t">
                <a href="{{ route('cashier.dashboard') }}" 
                   class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition font-medium">
                    Process Payment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection