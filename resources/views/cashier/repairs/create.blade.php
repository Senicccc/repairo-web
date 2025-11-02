@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Create New Repair</h2>
        <a href="{{ route('cashier.repairs.index') }}" 
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
            ← Back to Repairs
        </a>
    </div>

    <div class="bg-white shadow-lg rounded-lg p-6">
        <form method="POST" action="{{ route('cashier.repairs.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Info -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Customer Information</h3>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name</label>
                    <input type="text" name="name" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                    <input type="text" name="phone" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Device Info -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Device Information</h3>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Brand *</label>
                    <input type="text" name="phone_brand" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Model *</label>
                    <input type="text" name="phone_model" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">IMEI (Optional)</label>
                    <input type="text" name="imei" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Cost (Optional)</label>
                    <input type="number" name="cost" step="0.01" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Complaint -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Damage Description *</label>
                    <textarea name="complaint" required rows="4" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Describe the problem..."></textarea>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t">
                <a href="{{ route('cashier.repairs.index') }}" 
                   class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-medium">
                    Create Repair
                </button>
            </div>
        </form>
    </div>
</div>
@endsection