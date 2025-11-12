@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

    {{-- Welcome Section --}}
    <div class="bg-white shadow-md rounded-2xl border border-gray-100 overflow-hidden mb-8">
        <div class="p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    Welcome back, <span class="text-[#1800ad]">{{ Auth::user()->name }}</span> 👋
                </h1>
                <p class="text-gray-500 mt-2 text-sm">Here’s your repair overview and loyalty progress.</p>
            </div>
            <div class="bg-gradient-to-br from-[#1800ad] to-indigo-700 text-white px-6 py-5 rounded-xl shadow-md text-center min-w-[240px]">
                <p class="text-sm font-medium opacity-80">Your Points Balance</p>
                <p class="text-4xl font-bold mt-1">{{ number_format($userPoints ?? auth()->user()->loyalty_points ?? 0) }} pts</p>
                <p class="text-xs opacity-75 mt-1">Available for rewards</p>
                <div class="mt-4">
                    <a href="{{ route('loyalty.rewards') }}" 
                       class="inline-block bg-white text-[#1800ad] text-sm font-semibold px-4 py-2 rounded-lg shadow-sm hover:bg-gray-100 transition">
                        Loyalty Center →
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Overview --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        @php
            $stats = [
                ['label' => 'Total Requests', 'value' => $totalRepairs, 'color' => 'bg-[#1800ad]', 'icon' => 'file-text'],
                ['label' => 'Completed', 'value' => $completedRepairs, 'color' => 'bg-green-500', 'icon' => 'check-circle'],
                ['label' => 'In Progress', 'value' => $inProgressRepairs, 'color' => 'bg-yellow-500', 'icon' => 'clock'],
                ['label' => 'Cancelled', 'value' => $cancelledRepairs, 'color' => 'bg-red-500', 'icon' => 'x-circle'],
            ];
        @endphp

        @foreach ($stats as $s)
        <div class="bg-white shadow-md rounded-2xl border border-gray-100 hover:shadow-lg transition">
            <div class="p-6 flex items-center">
                <div class="w-10 h-10 {{ $s['color'] }} rounded-full flex items-center justify-center">
                    @if($s['icon'] === 'file-text')
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.6a1 1 0 01.7.3l5.4 5.4a1 1 0 01.3.7V19a2 2 0 01-2 2z"/></svg>
                    @elseif($s['icon'] === 'check-circle')
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @elseif($s['icon'] === 'clock')
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @elseif($s['icon'] === 'x-circle')
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 14l2-2 2-2m-4 4l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @endif
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">{{ $s['label'] }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $s['value'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Recent Repairs --}}
    <div class="bg-white shadow-md rounded-2xl border border-gray-100 mb-8">
        <div class="px-8 py-5 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Recent Repair Requests</h2>
                <p class="text-sm text-gray-500">Latest device repair updates</p>
            </div>
            <a href="{{ route('repairs.create') }}" 
               class="bg-[#1800ad] text-white px-4 py-2 rounded-lg text-sm font-medium shadow hover:bg-indigo-900 transition">
                + New Request
            </a>
        </div>

        <div class="overflow-x-auto">
            @if($recentRepairs->count())
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-6 py-3 text-left">Tracking ID</th>
                            <th class="px-6 py-3 text-left">Device</th>
                            <th class="px-6 py-3 text-left">Complaint</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Cost</th>
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentRepairs as $repair)
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
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-semibold">{{ $repair->tracking_id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $repair->phone_brand }} {{ $repair->phone_model }}</div>
                                <div class="text-xs text-gray-500">IMEI: {{ $repair->imei ?? '—' }}</div>
                            </td>
                            <td class="px-6 py-4 max-w-xs truncate">{{ $repair->complaint }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $color }}">
                                    {{ ucfirst(str_replace('_', ' ', $repair->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $repair->cost ? 'Rp '.number_format($repair->cost, 0, ',', '.') : '—' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $repair->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('repairs.show', $repair->id) }}" class="text-[#1800ad] hover:underline font-medium">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-10 text-gray-500 text-sm">
                    No repair requests yet. Start by creating one!
                </div>
            @endif
        </div>

        @if($recentRepairs->count())
            <div class="px-8 py-4 border-t border-gray-100 text-right">
                <a href="{{ route('repairs.history') }}" class="text-sm text-[#1800ad] hover:underline font-semibold">
                    View all history →
                </a>
            </div>
        @endif
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white shadow-md rounded-2xl border border-gray-100">
        <div class="px-8 py-5 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-900">Quick Actions</h2>
        </div>
        <div class="p-6 grid md:grid-cols-3 gap-6">
            <a href="{{ route('repairs.create') }}" 
               class="group flex items-center p-5 border border-gray-200 rounded-xl hover:border-[#1800ad] hover:bg-blue-50 transition">
                <div class="w-12 h-12 bg-[#1800ad] rounded-full flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 6v12m6-6H6"/></svg>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold text-gray-900">New Repair Request</h3>
                    <p class="text-sm text-gray-500">Submit a device for service</p>
                </div>
            </a>

            <a href="{{ route('repairs.history') }}" 
               class="group flex items-center p-5 border border-gray-200 rounded-xl hover:border-green-500 hover:bg-green-50 transition">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2z"/></svg>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold text-gray-900">Repair History</h3>
                    <p class="text-sm text-gray-500">See all your past repairs</p>
                </div>
            </a>

            <a href="{{ route('profile.edit') }}" 
               class="group flex items-center p-5 border border-gray-200 rounded-xl hover:border-purple-500 hover:bg-purple-50 transition">
                <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold text-gray-900">Profile Settings</h3>
                    <p class="text-sm text-gray-500">Update your personal info</p>
                </div>
            </a>
        </div>
    </div>

</div>
@endsection
