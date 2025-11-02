@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Cashier Dashboard</h2>
        <div class="flex gap-2">
            <a href="{{ route('cashier.repairs.index') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                🔧 All Repairs
            </a>
            <a href="{{ route('cashier.repairs.create') }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                + New Repair
            </a>
        </div>
    </div>

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">📋</div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Repairs</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $repairs->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">⏳</div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $repairs->where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">✅</div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Finished</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $repairs->where('status', 'finished')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">💰</div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Paid</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $repairs->where('status', 'paid')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- RECENT REPAIRS TABLE -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
        <div class="p-4 border-b bg-gray-50">
            <h3 class="text-lg font-semibold">Recent Repairs</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-gray-800">
                <thead class="bg-gray-100 text-gray-700">
                    <tr class="text-center uppercase text-xs font-semibold">
                        <th class="px-4 py-3 border">Tracking ID</th>
                        <th class="px-4 py-3 border">Customer</th>
                        <th class="px-4 py-3 border">Device</th>
                        <th class="px-4 py-3 border">Status</th>
                        <th class="px-4 py-3 border">Cost</th>
                        <th class="px-4 py-3 border">Date</th>
                        <th class="px-4 py-3 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($repairs->take(10) as $repair)
                    <tr class="text-center hover:bg-gray-50 transition border-b">
                        <td class="border px-3 py-2 font-medium text-blue-700">{{ $repair->tracking_id }}</td>
                        <td class="border px-3 py-2">{{ $repair->user->name ?? $repair->customer_name }}</td>
                        <td class="border px-3 py-2">{{ $repair->phone_brand }} {{ $repair->phone_model }}</td>
                        <td class="border px-3 py-2">
                            <span class="{{ \App\Models\Repair::getStatusColor($repair->status) }} px-2 py-1 rounded text-xs font-medium">
                                {{ \App\Models\Repair::getStatuses()[$repair->status] ?? ucfirst($repair->status) }}
                            </span>
                        </td>
                        <td class="border px-3 py-2 font-semibold">
                            {{ $repair->cost ? 'Rp ' . number_format($repair->cost, 0, ',', '.') : '-' }}
                        </td>
                        <td class="border px-3 py-2 text-xs">{{ $repair->created_at->format('M d, Y') }}</td>
                        <td class="border px-3 py-2">
                            <div class="flex justify-center gap-1">
                                <a href="{{ route('cashier.repairs.show', $repair->id) }}" 
                                   class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition">
                                    View
                                </a>
                                @if($repair->status === 'finished' && (!$repair->payment || $repair->payment->status !== 'paid'))
                                <a href="{{ route('cashier.payments.create', $repair->id) }}" 
                                   class="bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600 transition">
                                    Payment
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t bg-gray-50">
            <a href="{{ route('cashier.repairs.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                View all repairs →
            </a>
        </div>
    </div>
</div>
@endsection