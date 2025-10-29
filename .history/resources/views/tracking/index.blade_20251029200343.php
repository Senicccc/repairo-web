@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Track Your Repair</h2>

    <form method="POST" action="{{ route('tracking.search') }}" class="mb-6">
        @csrf
        <div class="flex space-x-2">
            <input type="text" name="tracking_id" placeholder="Enter Tracking ID (e.g., SRV20241215-0001)" 
                   class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                   required value="{{ request('tracking_id') }}">
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                Track
            </button>
        </div>
    </form>

    @isset($repair)
        @if($repair)
        <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
            {{-- Header --}}
            <div class="bg-blue-600 text-white p-4">
                <h3 class="text-xl font-bold">Repair Tracking Details</h3>
                <p class="text-blue-100">Tracking ID: {{ $repair->tracking_id }}</p>
            </div>

            <div class="p-6">
                {{-- Status Timeline --}}
                <div class="mb-6">
                    <h4 class="text-lg font-semibold mb-4 text-gray-800">Repair Status</h4>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium {{ $repair->status == 'pending' ? 'text-blue-600' : 'text-gray-500' }}">Pending</span>
                        <span class="text-sm font-medium {{ $repair->status == 'in_progress' ? 'text-blue-600' : 'text-gray-500' }}">In Progress</span>
                        <span class="text-sm font-medium {{ $repair->status == 'diagnosed' ? 'text-blue-600' : 'text-gray-500' }}">Diagnosed</span>
                        <span class="text-sm font-medium {{ $repair->status == 'waiting_parts' ? 'text-blue-600' : 'text-gray-500' }}">Waiting Parts</span>
                        <span class="text-sm font-medium {{ $repair->status == 'finished' ? 'text-blue-600' : 'text-gray-500' }}">Finished</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-500
                            {{ $repair->status == 'pending' ? 'w-1/5' : 
                               ($repair->status == 'in_progress' ? 'w-2/5' : 
                               ($repair->status == 'diagnosed' ? 'w-3/5' : 
                               ($repair->status == 'waiting_parts' ? 'w-4/5' : 'w-full'))) }}">
                        </div>
                    </div>
                </div>

                {{-- Current Status Badge --}}
                <div class="mb-6 text-center">
                    <span class="{{ \App\Models\Repair::getStatusColor($repair->status) }} px-4 py-2 rounded-full text-lg font-semibold">
                        Current Status: {{ \App\Models\Repair::getStatuses()[$repair->status] ?? ucfirst($repair->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Customer & Device Info --}}
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-800 mb-3 border-b pb-2">Customer Information</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Name:</span>
                                    <span class="font-medium">{{ $repair->user->name ?? $repair->customer_name ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Phone:</span>
                                    <span class="font-medium">{{ $repair->phone ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">IMEI:</span>
                                    <span class="font-medium">{{ $repair->imei ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-800 mb-3 border-b pb-2">Device Information</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Brand:</span>
                                    <span class="font-medium">{{ $repair->phone_brand }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Model:</span>
                                    <span class="font-medium">{{ $repair->phone_model }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Complaint:</span>
                                    <span class="font-medium text-right">{{ $repair->complaint }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Repair Details --}}
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-800 mb-3 border-b pb-2">Repair Details</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Technician:</span>
                                    <span class="font-medium">{{ $repair->technician ?? 'Not assigned' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Diagnosis:</span>
                                    <span class="font-medium text-right">{{ $repair->diagnosis ?? 'Not yet diagnosed' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Spareparts Used:</span>
                                    <span class="font-medium text-right">{{ $repair->sparepart ?? 'None' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-800 mb-3 border-b pb-2">Payment Information</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Estimated Cost:</span>
                                    <span class="font-medium text-green-600">
                                        {{ $repair->cost ? 'Rp '.number_format($repair->cost, 0, ',', '.') : 'Not estimated yet' }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Payment Status:</span>
                                    <span class="font-medium {{ $repair->payment && $repair->payment->status == 'paid' ? 'text-green-600' : 'text-orange-600' }}">
                                        {{ $repair->payment->status ?? 'Unpaid' }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Payment Method:</span>
                                    <span class="font-medium">{{ $repair->payment->payment_method ?? '-' }}</span>
                                </div>
                                @if($repair->payment && $repair->payment->payment_date)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Payment Date:</span>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($repair->payment->payment_date)->format('d M Y H:i') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Spareparts Details --}}
                @if($repair->repairSpareparts && $repair->repairSpareparts->count() > 0)
                <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-800 mb-3 border-b pb-2">Spareparts Used</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-3 py-2 border text-left">Sparepart Name</th>
                                    <th class="px-3 py-2 border text-center">Quantity</th>
                                    <th class="px-3 py-2 border text-right">Price</th>
                                    <th class="px-3 py-2 border text-left">Source</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalSparepartCost = 0;
                                @endphp
                                @foreach($repair->repairSpareparts as $sparepart)
                                    @php
                                        $subtotal = $sparepart->price * $sparepart->quantity;
                                        $totalSparepartCost += $subtotal;
                                    @endphp
                                    <tr>
                                        <td class="px-3 py-2 border">{{ $sparepart->name }}</td>
                                        <td class="px-3 py-2 border text-center">{{ $sparepart->quantity }}</td>
                                        <td class="px-3 py-2 border text-right">Rp{{ number_format($sparepart->price, 0, ',', '.') }}</td>
                                        <td class="px-3 py-2 border">
                                            @if($sparepart->source === 'in_store')
                                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Internal</span>
                                            @elseif($sparepart->source === 'customer_owned')
                                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Customer Provided</span>
                                            @else
                                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">External Purchase</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="bg-gray-100 font-semibold">
                                    <td colspan="2" class="px-3 py-2 border text-right">Total Spareparts:</td>
                                    <td class="px-3 py-2 border text-right">Rp{{ number_format($totalSparepartCost, 0, ',', '.') }}</td>
                                    <td class="px-3 py-2 border"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                {{-- Timeline --}}
                <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-800 mb-3 border-b pb-2">Repair Timeline</h4>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                            <div class="flex-1">
                                <p class="font-medium">Repair Created</p>
                                <p class="text-sm text-gray-600">{{ $repair->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @if($repair->diagnosis)
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-green-600 rounded-full mt-2"></div>
                            <div class="flex-1">
                                <p class="font-medium">Device Diagnosed</p>
                                <p class="text-sm text-gray-600">Diagnosis: {{ $repair->diagnosis }}</p>
                            </div>
                        </div>
                        @endif
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-purple-600 rounded-full mt-2"></div>
                            <div class="flex-1">
                                <p class="font-medium">Last Updated</p>
                                <p class="text-sm text-gray-600">{{ $repair->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-800 mb-2">Important Notes</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Please keep your Tracking ID safe for future reference</li>
                        <li>• You will be notified when your device is ready for pickup</li>
                        <li>• Payment is required upon device collection</li>
                        <li>• For inquiries, contact us with your Tracking ID</li>
                    </ul>
                </div>

            </div>
        </div>
        @else
        <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
            <div class="text-red-600 text-4xl mb-3">❌</div>
            <h3 class="text-lg font-semibold text-red-800 mb-2">Tracking ID Not Found</h3>
            <p class="text-red-600">The Tracking ID "{{ request('tracking_id') }}" was not found in our system.</p>
            <p class="text-red-500 text-sm mt-2">Please check the ID and try again, or contact our support.</p>
        </div>
        @endif
    @endisset

    {{-- Show message when no search performed --}}
    @if(!isset($repair) && !request()->has('tracking_id'))
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
        <div class="text-blue-600 text-4xl mb-3">🔍</div>
        <h3 class="text-lg font-semibold text-blue-800 mb-2">Track Your Repair Status</h3>
        <p class="text-blue-600">Enter your Tracking ID above to check the current status of your device repair.</p>
        <p class="text-blue-500 text-sm mt-2">Your Tracking ID can be found on your repair receipt or invoice.</p>
    </div>
    @endif
</div>

<style>
    .progress-bar {
        transition: width 0.5s ease-in-out;
    }
</style>

<script>
    // Animate progress bar
    document.addEventListener('DOMContentLoaded', function() {
        const progressBar = document.querySelector('.bg-blue-600.h-2');
        if (progressBar) {
            // Force reflow to trigger animation
            void progressBar.offsetWidth;
        }
    });
</script>
@endsection