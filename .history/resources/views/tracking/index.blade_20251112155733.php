@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6 sm:p-8">
    <h2 class="text-3xl font-extrabold mb-6 text-gray-900 text-center tracking-tight">🔧 Track Your Repair</h2>

    <form method="POST" action="{{ route('tracking.search') }}" class="mb-8">
        @csrf
        <div class="flex flex-col sm:flex-row sm:space-x-3 space-y-3 sm:space-y-0">
            <input type="text" name="tracking_id" placeholder="Enter Tracking ID (e.g., SRV20241215-0001)" 
                   class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none text-gray-800 shadow-sm placeholder-gray-400"
                   required value="{{ request('tracking_id') }}">
            <button type="submit" 
                    class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 active:scale-[0.98] transition font-semibold shadow-sm">
                Track
            </button>
        </div>
    </form>

    @isset($repair)
        @if($repair)
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-200 transition-all">
            
            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-5">
                <h3 class="text-2xl font-bold tracking-tight">Repair Tracking Details</h3>
                <p class="text-blue-100 mt-1 text-sm">Tracking ID: {{ $repair->tracking_id }}</p>
            </div>

            <div class="p-6 sm:p-8">
                {{-- Status Timeline --}}
                <div class="mb-8">
                    <h4 class="text-lg font-semibold mb-4 text-gray-800">Repair Status</h4>
                    <div class="flex items-center justify-between text-xs sm:text-sm font-medium mb-2 text-gray-500">
                        <span class="{{ $repair->status == 'pending' ? 'text-blue-600' : '' }}">Pending</span>
                        <span class="{{ $repair->status == 'in_progress' ? 'text-blue-600' : '' }}">In Progress</span>
                        <span class="{{ $repair->status == 'diagnosed' ? 'text-blue-600' : '' }}">Diagnosed</span>
                        <span class="{{ $repair->status == 'waiting_parts' ? 'text-blue-600' : '' }}">Waiting Parts</span>
                        <span class="{{ $repair->status == 'finished' ? 'text-blue-600' : '' }}">Finished</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                        <div class="bg-blue-600 h-2 rounded-full progress-bar
                            {{ $repair->status == 'pending' ? 'w-1/5' : 
                               ($repair->status == 'in_progress' ? 'w-2/5' : 
                               ($repair->status == 'diagnosed' ? 'w-3/5' : 
                               ($repair->status == 'waiting_parts' ? 'w-4/5' : 'w-full'))) }}">
                        </div>
                    </div>
                </div>

                {{-- Current Status Badge --}}
                <div class="mb-8 text-center">
                    <span class="{{ \App\Models\Repair::getStatusColor($repair->status) }} 
                                 px-6 py-2.5 rounded-full text-lg font-semibold shadow-sm">
                        Current Status: {{ \App\Models\Repair::getStatuses()[$repair->status] ?? ucfirst($repair->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Customer & Device Info --}}
                    <div class="space-y-5">
                        <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 shadow-sm">
                            <h4 class="font-semibold text-gray-900 mb-3 border-b pb-2">Customer Information</h4>
                            <dl class="text-sm space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Name:</dt>
                                    <dd class="font-medium">{{ $repair->user->name ?? $repair->customer_name ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Phone:</dt>
                                    <dd class="font-medium">{{ $repair->phone ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">IMEI:</dt>
                                    <dd class="font-medium">{{ $repair->imei ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 shadow-sm">
                            <h4 class="font-semibold text-gray-900 mb-3 border-b pb-2">Device Information</h4>
                            <dl class="text-sm space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Brand:</dt>
                                    <dd class="font-medium">{{ $repair->phone_brand }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Model:</dt>
                                    <dd class="font-medium">{{ $repair->phone_model }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Complaint:</dt>
                                    <dd class="font-medium text-right">{{ $repair->complaint }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    {{-- Repair Details --}}
                    <div class="space-y-5">
                        <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 shadow-sm">
                            <h4 class="font-semibold text-gray-900 mb-3 border-b pb-2">Repair Details</h4>
                            <dl class="text-sm space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Technician:</dt>
                                    <dd class="font-medium">{{ $repair->technician ?? 'Not assigned' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Diagnosis:</dt>
                                    <dd class="font-medium text-right">{{ $repair->diagnosis ?? 'Not yet diagnosed' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Spareparts Used:</dt>
                                    <dd class="font-medium text-right">{{ $repair->sparepart ?? 'None' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 shadow-sm">
                            <h4 class="font-semibold text-gray-900 mb-3 border-b pb-2">Payment Information</h4>
                            <dl class="text-sm space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Estimated Cost:</dt>
                                    <dd class="font-medium text-green-600">
                                        {{ $repair->cost ? 'Rp '.number_format($repair->cost, 0, ',', '.') : 'Not estimated yet' }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Payment Status:</dt>
                                    <dd class="font-medium {{ $repair->payment && $repair->payment->status == 'paid' ? 'text-green-600' : 'text-orange-600' }}">
                                        {{ $repair->payment->status ?? 'Unpaid' }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Payment Method:</dt>
                                    <dd class="font-medium">{{ $repair->payment->payment_method ?? '-' }}</dd>
                                </div>
                                @if($repair->payment && $repair->payment->payment_date)
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Payment Date:</dt>
                                    <dd class="font-medium">{{ \Carbon\Carbon::parse($repair->payment->payment_date)->format('d M Y H:i') }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

                {{-- Spareparts Table --}}
                @if($repair->repairSpareparts && $repair->repairSpareparts->count() > 0)
                <div class="mt-8 bg-gray-50 p-5 rounded-xl border border-gray-200 shadow-sm">
                    <h4 class="font-semibold text-gray-900 mb-3 border-b pb-2">Spareparts Used</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                            <thead class="bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="px-3 py-2 border text-left">Sparepart Name</th>
                                    <th class="px-3 py-2 border text-center">Qty</th>
                                    <th class="px-3 py-2 border text-right">Price</th>
                                    <th class="px-3 py-2 border text-left">Source</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalSparepartCost = 0; @endphp
                                @foreach($repair->repairSpareparts as $sparepart)
                                    @php
                                        $subtotal = $sparepart->price * $sparepart->quantity;
                                        $totalSparepartCost += $subtotal;
                                    @endphp
                                    <tr class="hover:bg-gray-100 transition">
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
                                    <td colspan="2" class="px-3 py-2 border text-right">Total:</td>
                                    <td class="px-3 py-2 border text-right">Rp{{ number_format($totalSparepartCost, 0, ',', '.') }}</td>
                                    <td class="px-3 py-2 border"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                {{-- Timeline --}}
                <div class="mt-8 bg-gray-50 p-5 rounded-xl border border-gray-200 shadow-sm">
                    <h4 class="font-semibold text-gray-900 mb-3 border-b pb-2">Repair Timeline</h4>
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
                <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-5 shadow-sm">
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
        <div class="bg-red-50 border border-red-200 rounded-xl p-6 text-center shadow-sm">
            <div class="text-red-600 text-5xl mb-3">❌</div>
            <h3 class="text-lg font-semibold text-red-800 mb-2">Tracking ID Not Found</h3>
            <p class="text-red-600">The Tracking ID "{{ request('tracking_id') }}" was not found in our system.</p>
            <p class="text-red-500 text-sm mt-2">Please check the ID and try again, or contact our support.</p>
        </div>
        @endif
    @endisset

    @if(!isset($repair) && !request()->has('tracking_id'))
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 text-center shadow-sm">
        <div class="text-blue-600 text-5xl mb-3">🔍</div>
        <h3 class="text-xl font-semibold text-blue-800 mb-2">Track Your Repair Status</h3>
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
document.addEventListener('DOMContentLoaded', () => {
    const progressBar = document.querySelector('.bg-blue-600.h-2');
    if (progressBar) void progressBar.offsetWidth;
});
</script>
@endsection
