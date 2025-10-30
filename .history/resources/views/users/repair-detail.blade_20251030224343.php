@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900">Repair Details</h1>
                <p class="text-gray-600">Tracking ID: {{ $repair->tracking_id }}</p>
            </div>
            
            <div class="p-6">
                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Device Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Brand & Model</dt>
                                <dd class="text-sm text-gray-900">{{ $repair->phone_brand }} {{ $repair->phone_model }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">IMEI</dt>
                                <dd class="text-sm text-gray-900">{{ $repair->imei ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Complaint</dt>
                                <dd class="text-sm text-gray-900">{{ $repair->complaint }}</dd>
                            </div>
                        </dl>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Repair Status</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="text-sm">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'in_progress' => 'bg-blue-100 text-blue-800',
                                            'diagnosed' => 'bg-indigo-100 text-indigo-800',
                                            'waiting_parts' => 'bg-orange-100 text-orange-800',
                                            'finished' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800'
                                        ];
                                        $color = $statusColors[$repair->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                        {{ ucfirst(str_replace('_', ' ', $repair->status)) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Technician</dt>
                                <dd class="text-sm text-gray-900">{{ $repair->technician ?? 'Not assigned' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Diagnosis</dt>
                                <dd class="text-sm text-gray-900">{{ $repair->diagnosis ?? 'Not yet diagnosed' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estimated Cost</dt>
                                <dd class="text-sm text-gray-900">
                                    @if($repair->cost)
                                        Rp {{ number_format($repair->cost) }}
                                    @else
                                        <span class="text-gray-400">Not estimated yet</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Spareparts Used -->
                @if($repair->repairSpareparts && $repair->repairSpareparts->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Spareparts Used</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sparepart</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($repair->repairSpareparts as $sparepart)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $sparepart->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $sparepart->quantity }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">Rp {{ number_format($sparepart->price) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ ucfirst($sparepart->source) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Payment Info -->
                @if($repair->payment)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Status</dt>
                            <dd class="text-sm text-gray-900">{{ ucfirst($repair->payment->status) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                            <dd class="text-sm text-gray-900">{{ ucfirst($repair->payment->payment_method) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Amount Paid</dt>
                            <dd class="text-sm text-gray-900">Rp {{ number_format($repair->payment->amount) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Date</dt>
                            <dd class="text-sm text-gray-900">{{ $repair->payment->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
                @endif

                <!-- Timeline -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Repair Timeline</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Repair Request Submitted</p>
                                <p class="text-sm text-gray-500">{{ $repair->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($repair->updated_at->gt($repair->created_at))
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                <p class="text-sm text-gray-500">{{ $repair->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <a href="{{ route('users.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                ← Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection