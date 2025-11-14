@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10">

    {{-- PROFILE CARD --}}
    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
        <h2 class="text-3xl font-bold text-center text-[#1800AD] mb-8 tracking-tight">
            User Profile Details
        </h2>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-center font-medium">
                {{ session('success') }}
            </div>
        @endif

        {{-- PROFILE TABLE --}}
        <div class="overflow-hidden rounded-xl border border-gray-200">
            <table class="w-full text-sm">
                @php
                    $rows = [
                        'ID' => $user->id,
                        'Name' => $user->name,
                        'Phone' => $user->phone,
                        'Email' => $user->email,
                        'Created At' => $user->created_at,
                    ];
                @endphp

                @foreach($rows as $label => $value)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="font-semibold p-4 w-1/3 text-gray-700 bg-gray-50">
                            {{ $label }}
                        </td>
                        <td class="p-4 text-gray-800">
                            {{ $value }}
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>

        {{-- EDIT PROFILE BUTTON --}}
        <div class="text-center mt-8">
            <a href="{{ route('profile.edit') }}"
                class="inline-flex items-center gap-2 bg-[#1800AD] hover:bg-[#13008a] text-white px-6 py-3 rounded-xl font-semibold shadow-lg transition transform hover:scale-[1.03]">
                Edit Profile
            </a>
        </div>
    </div>

    {{-- DIVIDER --}}
    <div class="my-10 border-t border-gray-200"></div>

    {{-- TOGGLE BUTTON --}}
    <div class="text-center">
        <button onclick="toggleRepairList()"
            class="inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 text-white px-6 py-3 rounded-xl font-medium shadow-md transition transform hover:scale-[1.03]">
            @if($user->role === 'technician')
                View Taken Jobs
            @else
                View Repair History
            @endif
        </button>
    </div>

    {{-- REPAIR LIST --}}
    <div id="repairList" class="hidden mt-8">

        @if($repairs->isEmpty())
            <p class="text-center text-gray-500 text-lg py-6">No repair records found.</p>

        @else
            <div class="overflow-hidden rounded-2xl shadow-lg border border-gray-200 mt-4">
                <table class="w-full text-sm">
                    <thead class="bg-[#1800AD] text-white">
                        <tr>
                            <th class="p-3">Tracking ID</th>
                            <th class="p-3">Brand</th>
                            <th class="p-3">Model</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Cost</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @foreach($repairs as $repair)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 font-medium text-gray-800">
                                    {{ $repair->tracking_id }}
                                </td>

                                <td class="p-4 text-gray-700">
                                    {{ $repair->phone_brand }}
                                </td>

                                <td class="p-4 text-gray-700">
                                    {{ $repair->phone_model }}
                                </td>

                                <td class="p-4">
                                    @php
                                        $color = [
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'processing' => 'bg-blue-100 text-blue-700',
                                            'completed' => 'bg-green-100 text-green-700',
                                            'cancelled' => 'bg-red-100 text-red-700',
                                        ][$repair->status] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                        {{ ucfirst($repair->status) }}
                                    </span>
                                </td>

                                <td class="p-4 text-gray-800 font-semibold">
                                    {{ $repair->cost ? 'Rp ' . number_format($repair->cost) : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        @endif

    </div>

</div>

<script>
function toggleRepairList() {
    const list = document.getElementById('repairList');
    list.classList.toggle('hidden');
}
</script>
@endsection
