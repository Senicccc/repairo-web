@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold mb-6 text-gray-900 text-center">📦 Track Your Repair</h2>

    {{-- Search Form --}}
    <form method="POST" action="{{ route('tracking.search') }}" class="flex flex-col sm:flex-row items-center gap-3 mb-8">
        @csrf
        <input type="text" name="tracking_id" placeholder="Enter Tracking ID (e.g. SRV20241215-0001)" 
               class="w-full sm:flex-1 border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
               required value="{{ request('tracking_id') }}">
        <button type="submit"
                class="w-full sm:w-auto bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 shadow-sm transition">
            Track
        </button>
    </form>

    {{-- When repair found --}}
    @isset($repair)
        @if($repair)
        <div class="bg-white shadow-md rounded-2xl overflow-hidden border border-gray-100">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-700 to-blue-500 text-white p-5">
                <h3 class="text-2xl font-bold">Repair Tracking Details</h3>
                <p class="text-blue-100 mt-1">Tracking ID: {{ $repair->tracking_id }}</p>
            </div>

            <div class="p-6 space-y-6">

                {{-- Status Progress Bar --}}
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">Repair Progress</h4>
                    <div class="flex justify-between text-xs sm:text-sm font-medium mb-2 text-gray-600">
                        <span class="{{ $repair->status == 'pending' ? 'text-blue-600 font-semibold' : '' }}">Pending</span>
                        <span class="{{ $repair->status == 'in_progress' ? 'text-blue-600 font-semibold' : '' }}">In Progress</span>
                        <span class="{{ $repair->status == 'diagnosed' ? 'text-blue-600 font-semibold' : '' }}">Diagnosed</span>
                        <span class="{{ $repair->status == 'waiting_parts' ? 'text-blue-600 font-semibold' : '' }}">Waiting Parts</span>
                        <span class="{{ $repair->status == 'finished' ? 'text-blue-600 font-semibold' : '' }}">Finished</span>
                    </div>
                    <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden">
                        <div class="bg-blue-600 h-2 transition-all duration-500"
                             style="width:
                                {{ $repair->status == 'pending' ? '20%' :
                                   ($repair->status == 'in_progress' ? '40%' :
                                   ($repair->status == 'diagnosed' ? '60%' :
                                   ($repair->status == 'waiting_parts' ? '80%' : '100%'))) }}">
                        </div>
                    </div>
                </div>

                {{-- Current Status --}}
                <div class="text-center">
                    <span class="{{ \App\Models\Repair::getStatusColor($repair->status) }} px-5 py-2 rounded-full text-base font-semibold shadow-sm">
                        {{ \App\Models\Repair::getStatuses()[$repair->status] ?? ucfirst($repair->status) }}
                    </span>
                </div>

                {{-- Details Grid --}}
                <div class="grid md:grid-cols-2 gap-6">
                    {{-- Customer & Device Info --}}
                    <div class="space-y-5">
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <h4 class="font-semibold text-gray-800 mb-3 border-b pb-2">Customer Information</h4>
                            <dl class="text-sm space-y-1">
                                <div class="flex justify-between"><dt>Name:</dt><dd class="font-medium">{{ $repair->user->name ?? $repair->customer_name ?? '-' }}</dd></div>
                                <div class="flex justify-between"><dt>Phone:</dt><dd class="font-medium">{{ $repair->phone ?? '-' }}</dd></div>
                                <div class="flex justify-between"><dt>IMEI:</dt><dd class="font-medium">{{ $repair->imei ?? '-' }}</dd></div>
                            </dl>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <h4 class="font-semibold text-gray-800 mb-3 border-b pb-2">Device Information</h4>
                            <dl class="text-sm space-y-1">
                                <div class="flex justify-between"><dt>Brand:</dt><dd class="font-medium">{{ $repair->phone_brand }}</dd></div>
                                <div class="flex justify-between"><dt>Model:</dt><dd class="font-medium">{{ $repair->phone_model }}</dd></div>
                                <div class="flex justify-between"><dt>Complaint:</dt><dd class="font-medium text-right">{{ $repair->complaint }}</dd></div>
                            </dl>
                        </div>
                    </div>

                    {{-- Repair & Payment Info --}}
                    <div class="space-y-5">
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <h4 class="font-semibold text-gray-800 mb-3 border-b pb-2">Repair Details</h4>
                            <dl class="text-sm space-y-1">
                                <div class="flex justify-between"><dt>Technician:</dt><dd class="font-medium">{{ $repair->technician ?? 'Not assigned' }}</dd></div>
                                <div class="flex justify-between"><dt>Diagnosis:</dt><dd class="font-medium text-right">{{ $repair->diagnosis ?? 'Not yet diagnosed' }}</dd></div>
                            </dl>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <h4 class="font-semibold text-gray-800 mb-3 border-b pb-2">Payment Information</h4>
                            <dl class="text-sm space-y-1">
                                <div class="flex justify-between"><dt>Estimated Cost:</dt>
                                    <dd class="font-medium text-green-600">{{ $repair->cost ? 'Rp '.number_format($repair->cost,0,',','.') : 'Not estimated yet' }}</dd>
                                </div>
                                <div class="flex justify-between"><dt>Payment Status:</dt>
                                    <dd class="font-medium {{ $repair->payment && $repair->payment->status == 'paid' ? 'text-green-600' : 'text-orange-600' }}">
                                        {{ $repair->payment->status ?? 'Unpaid' }}
                                    </dd>
                                </div>
                                <div class="flex justify-between"><dt>Payment Method:</dt><dd class="font-medium">{{ $repair->payment->payment_method ?? '-' }}</dd></div>
                            </dl>
                        </div>
                    </div>
                </div>

                {{-- Spareparts Section --}}
                @if($repair->repairSpareparts && $repair->repairSpareparts->count() > 0)
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <h4 class="font-semibold text-gray-800 mb-3 border-b pb-2">Spareparts Used</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left border">Name</th>
                                    <th class="px-4 py-2 text-center border">Qty</th>
                                    <th class="px-4 py-2 text-right border">Price</th>
                                    <th class="px-4 py-2 text-left border">Source</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($repair->repairSpareparts as $spare)
                                    @php $subtotal = $spare->price * $spare->quantity; $total += $subtotal; @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 border">{{ $spare->name }}</td>
                                        <td class="px-4 py-2 text-center border">{{ $spare->quantity }}</td>
                                        <td class="px-4 py-2 text-right border">Rp{{ number_format($spare->price,0,',','.') }}</td>
                                        <td class="px-4 py-2 border">
                                            @if($spare->source === 'in_store')
                                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Internal</span>
                                            @elseif($spare->source === 'customer_owned')
                                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Customer</span>
                                            @else
                                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">External</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="bg-gray-100 font-semibold">
                                    <td colspan="2" class="px-4 py-2 text-right border">Total:</td>
                                    <td class="px-4 py-2 text-right border">Rp{{ number_format($total,0,',','.') }}</td>
                                    <td class="border"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                {{-- Notes --}}
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <h4 class="font-semibold text-blue-800 mb-2">Important Notes</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Keep your Tracking ID safe for future reference.</li>
                        <li>• You’ll be notified when your device is ready for pickup.</li>
                        <li>• Payment is required upon collection.</li>
                        <li>• For inquiries, contact support with your Tracking ID.</li>
                    </ul>
                </div>
            </div>
        </div>
        @else
        {{-- Not Found --}}
        <div class="bg-red-50 border border-red-200 rounded-xl p-8 text-center">
            <div class="text-red-600 text-4xl mb-2">❌</div>
            <h3 class="text-xl font-semibold text-red-800 mb-1">Tracking ID Not Found</h3>
            <p class="text-red-600">The ID "{{ request('tracking_id') }}" was not found in our system.</p>
            <p class="text-sm text-red-500 mt-2">Please check the ID and try again.</p>
        </div>
        @endif
    @endisset

    {{-- Default Empty State --}}
    @if(!isset($repair) && !request()->has('tracking_id'))
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-8 text-center">
        <div class="text-blue-600 text-4xl mb-3">🔍</div>
        <h3 class="text-xl font-semibold text-blue-800 mb-2">Track Your Repair Status</h3>
        <p class="text-blue-600">Enter your Tracking ID above to check your repair progress.</p>
        <p class="text-blue-500 text-sm mt-2">You can find it on your repair receipt.</p>
    </div>
    @endif
</div>

@endsection
