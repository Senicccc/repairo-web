@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="bg-white shadow-md rounded-2xl border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-100">
                <h1 class="text-2xl font-bold text-gray-900">Repair History</h1>
                <p class="text-gray-500 text-sm mt-1">All your repair requests</p>
            </div>
            
            <div class="overflow-x-auto">
                @if($repairs->count())
                    <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
                        <thead class="bg-gray-50 text-xs text-gray-600 uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-3 text-left">Tracking ID</th>
                                <th class="px-6 py-3 text-left">Device</th>
                                <th class="px-6 py-3 text-left">Complaint</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-left">Cost</th>
                                <th class="px-6 py-3 text-left">Date</th>
                                <th class="px-6 py-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($repairs as $repair)
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
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-semibold">{{ $repair->tracking_id }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $repair->phone_brand }} {{ $repair->phone_model }}</div>
                                        <div class="text-xs text-gray-500">{{ $repair->imei ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4">{{ Str::limit($repair->complaint, 50) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                            {{ ucfirst(str_replace('_', ' ', $repair->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($repair->cost)
                                            Rp {{ number_format($repair->cost) }}
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">{{ $repair->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('repairs.show', $repair->id) }}" class="text-[#1800ad] hover:underline font-semibold">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $repairs->links() }}
                    </div>
                @else
                    <div class="text-center py-12 text-gray-500 text-sm">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.6a1 1 0 01.7.3l5.4 5.4a1 1 0 01.3.7V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="font-medium text-gray-700">No repair history yet</p>
                        <p class="text-xs text-gray-400 mt-1">You haven’t submitted any repair requests.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
