@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">

    {{-- HEADER --}}
    <div class="text-center mb-10">
        <h2 class="text-4xl font-extrabold text-[#1800ad] tracking-tight flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[#1800ad]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3-1.343 3-3S13.657 2 12 2 9 3.343 9 5s1.343 3 3 3zM12 14v8m-6-6h12" />
            </svg>
            Loyalty Program
        </h2>
        <p class="text-gray-500 mt-2 text-lg">Earn points, claim exclusive rewards, and enjoy more benefits.</p>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-lg mb-4 shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg mb-4 shadow-sm">
            {{ session('error') }}
        </div>
    @endif
    @if(session('redeem_code'))
        <div class="bg-yellow-50 border border-yellow-200 p-5 rounded-lg shadow-sm mb-8">
            <h3 class="font-semibold text-lg text-gray-800 mb-2">Your Redeem Code</h3>
            <p class="text-2xl font-mono font-bold text-[#1800ad]" id="redeem-code">{{ session('redeem_code') }}</p>
            <p class="text-sm text-gray-600 mt-1">Give this code to the cashier to redeem your reward.</p>
            <button id="copyBtn" class="mt-3 px-4 py-2 bg-[#1800ad] text-white font-medium rounded-lg hover:bg-[#0e0080] transition">Copy Code</button>
        </div>
    @endif

    {{-- CURRENT POINTS --}}
    <div class="bg-white rounded-2xl shadow-md p-8 text-center border border-gray-100 mb-12 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-white via-white to-blue-50 opacity-80"></div>
        <div class="relative z-10">
            <div class="flex justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[#1800ad]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3a1 1 0 011 1v6a1 1 0 001 1h6a1 1 0 010 2h-6a1 1 0 00-1 1v6a1 1 0 01-2 0v-6a1 1 0 00-1-1H4a1 1 0 010-2h6a1 1 0 001-1V4a1 1 0 011-1z" />
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-500">Your Current Points</p>
            <p class="text-6xl font-extrabold mt-2 text-[#1800ad]">{{ number_format(auth()->user()->loyalty_points ?? 0) }}</p>
            <p class="text-sm text-gray-500 mt-1">Available for rewards</p>

            {{-- Progress Bar --}}
            @php
                $points = auth()->user()->loyalty_points ?? 0;
                $nextReward = collect($rewardOptions)->firstWhere('points', '>', $points);
                $progress = $nextReward ? min(100, ($points / $nextReward['points']) * 100) : 100;
            @endphp
            @if($nextReward)
                <div class="mt-6 text-sm text-gray-600">
                    <p>Next reward at <span class="font-semibold">{{ $nextReward['points'] }} pts</span></p>
                    <div class="w-full bg-gray-200 rounded-full h-3 mt-2 overflow-hidden">
                        <div class="bg-[#1800ad] h-3 rounded-full transition-all duration-500" style="width: {{ $progress }}%;"></div>
                    </div>
                    <p class="text-xs mt-1 text-gray-500">{{ round($progress, 1) }}% completed</p>
                </div>
            @endif

            <a href="{{ route('users.dashboard') }}" class="inline-block mt-5 text-sm font-medium text-[#1800ad] hover:underline">
                ← Back to Dashboard
            </a>
        </div>
    </div>

    {{-- AVAILABLE REWARDS --}}
    <div class="mb-14">
        <h3 class="text-2xl font-bold mb-6 text-[#1800ad] flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#1800ad]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3-1.343 3-3S13.657 2 12 2 9 3.343 9 5s1.343 3 3 3zM12 14v8m-6-6h12" />
            </svg>
            Available Rewards
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($rewardOptions as $r)
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-xl transition transform hover:-translate-y-1 p-6 flex flex-col justify-between">
                <div class="space-y-2">
                    <p><span class="font-semibold text-gray-600">Points Required:</span> {{ $r['points'] }}</p>
                    <p><span class="font-semibold text-gray-600">Discount:</span> Rp{{ number_format($r['discount'], 0, ',', '.') }}</p>
                    <p><span class="font-semibold text-gray-600">Gift 1:</span> {{ $r['gift1'] }}</p>
                    <p><span class="font-semibold text-gray-600">Gift 2:</span> {{ $r['gift2'] }}</p>
                </div>

                <button class="mt-5 bg-[#1800ad] hover:bg-[#0e0080] text-white py-2 rounded-lg font-semibold transition open-modal disabled:opacity-40 disabled:cursor-not-allowed"
                        data-points="{{ $r['points'] }}"
                        data-discount="{{ $r['discount'] }}"
                        data-gift1="{{ $r['gift1'] }}"
                        data-gift2="{{ $r['gift2'] }}"
                        @if(auth()->user()->loyalty_points < $r['points']) disabled @endif>
                    @if(auth()->user()->loyalty_points < $r['points'])
                        Not Enough Points
                    @else
                        Claim Reward
                    @endif
                </button>
            </div>
            @endforeach
        </div>
    </div>

    {{-- CLAIMED REWARDS --}}
    <div>
        <h3 class="text-2xl font-bold mb-4 text-[#1800ad] flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#1800ad]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Your Claimed Rewards (Last 10)
        </h3>

        <div class="bg-blue-50 border border-blue-100 p-4 rounded-lg mb-4">
            <p class="text-sm text-blue-800">
                <strong>Note:</strong> “Claimed” means you’ve just claimed it and haven’t used it yet. “Used” means it’s already redeemed.
            </p>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-700 border-b">
                    <tr>
                        <th class="p-3 text-left">Code</th>
                        <th class="p-3 text-left">Reward</th>
                        <th class="p-3 text-left">Points</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Claimed At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentRewards as $r)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-3 font-mono">{{ $r->redeem_code ?? '-' }}</td>
                        <td class="p-3">
                            @if($r->reward_type === 'discount')
                                Discount Rp{{ number_format((int)$r->reward_value, 0, ',', '.') }}
                            @else
                                {{ $r->reward_value }}
                            @endif
                        </td>
                        <td class="p-3">{{ $r->points_used }}</td>
                        <td class="p-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($r->status === 'claimed') bg-yellow-100 text-yellow-800 
                                @elseif($r->status === 'used') bg-green-100 text-green-800 
                                @endif">
                                {{ ucfirst($r->status) }}
                            </span>
                        </td>
                        <td class="p-3 text-gray-600">{{ $r->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">No rewards claimed yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL --}}
@include('loyalty.partials.modal')
@endsection
