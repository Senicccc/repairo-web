@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4 text-center">User Profile Details</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full text-sm border">
        <tr class="border-b">
            <td class="font-semibold p-2 w-1/3">ID</td>
            <td class="p-2">{{ $user->id }}</td>
        </tr>
        <tr class="border-b">
            <td class="font-semibold p-2">Name</td>
            <td class="p-2">{{ $user->name }}</td>
        </tr>
        <tr class="border-b">
            <td class="font-semibold p-2">Phone</td>
            <td class="p-2">{{ $user->phone }}</td>
        </tr>
        <tr class="border-b">
            <td class="font-semibold p-2">Email</td>
            <td class="p-2">{{ $user->email }}</td>
        </tr>
        <tr class="border-b">
            <td class="font-semibold p-2">Created At</td>
            <td class="p-2">{{ $user->created_at }}</td>
        </tr>
    </table>

    <div class="mt-6 flex justify-center gap-3">
        <a href="{{ route('profile.edit') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Edit Profile
        </a>

        <button id="toggleRepairs" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
            View Service History
        </button>
    </div>

    <div id="repairsSection" class="mt-6 hidden">
        <h3 class="text-lg font-semibold mb-2 text-center">Repair History</h3>

        @if($repairs->isEmpty())
            <p class="text-center text-gray-500">No repair records found.</p>
        @else
            <table class="w-full text-sm border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">Tracking ID</th>
                        <th class="p-2 border">Phone Brand</th>
                        <th class="p-2 border">Model</th>
                        <th class="p-2 border">Complaint</th>
                        <th class="p-2 border">Status</th>
                        <th class="p-2 border">Technician</th>
                        <th class="p-2 border">Cost</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($repairs as $repair)
                        <tr>
                            <td class="p-2 border text-center">{{ $repair->tracking_id ?? '-' }}</td>
                            <td class="p-2 border text-center">{{ $repair->phone_brand }}</td>
                            <td class="p-2 border text-center">{{ $repair->phone_model }}</td>
                            <td class="p-2 border text-center">{{ Str::limit($repair->complaint, 30) }}</td>
                            <td class="p-2 border text-center">{{ ucfirst($repair->status) }}</td>
                            <td class="p-2 border text-center">{{ $repair->technician ?? '-' }}</td>
                            <td class="p-2 border text-center">Rp{{ number_format($repair->cost, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<script>
    document.getElementById('toggleRepairs').addEventListener('click', function() {
        const section = document.getElementById('repairsSection');
        section.classList.toggle('hidden');
        this.textContent = section.classList.contains('hidden') ? 'View Service History' : 'Hide Service History';
    });
</script>
@endsection
