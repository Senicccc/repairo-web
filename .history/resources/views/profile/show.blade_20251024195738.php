@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4 text-center">User Profile Details</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full text-sm border mb-6">
        <tr class="border-b"><td class="font-semibold p-2 w-1/3">ID</td><td class="p-2">{{ $user->id }}</td></tr>
        <tr class="border-b"><td class="font-semibold p-2">Name</td><td class="p-2">{{ $user->name }}</td></tr>
        <tr class="border-b"><td class="font-semibold p-2">Phone</td><td class="p-2">{{ $user->phone }}</td></tr>
        <tr class="border-b"><td class="font-semibold p-2">Email</td><td class="p-2">{{ $user->email }}</td></tr>
        <tr class="border-b"><td class="font-semibold p-2">Role</td><td class="p-2 capitalize">{{ $user->role }}</td></tr>
        <tr class="border-b"><td class="font-semibold p-2">Created At</td><td class="p-2">{{ $user->created_at }}</td></tr>
    </table>

    <div class="text-center mb-4">
        <a href="{{ route('profile.edit') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Edit Profile</a>
    </div>

    <hr class="my-4">

    <div class="text-center mb-4">
        @if($user->role === 'technician')
            <button onclick="toggleRepairList()" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded">View Taken Jobs</button>
        @else
            <button onclick="toggleRepairList()" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded">View Repair History</button>
        @endif
    </div>

    <div id="repairList" class="hidden">
        @if($repairs->isEmpty())
            <p class="text-center text-gray-600">No repair records found.</p>
        @else
            <table class="w-full text-sm border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-2">Tracking ID</th>
                        <th class="border p-2">Phone Brand</th>
                        <th class="border p-2">Model</th>
                        <th class="border p-2">Status</th>
                        <th class="border p-2">Cost</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($repairs as $repair)
                        <tr>
                            <td class="border p-2">{{ $repair->tracking_id }}</td>
                            <td class="border p-2">{{ $repair->phone_brand }}</td>
                            <td class="border p-2">{{ $repair->phone_model }}</td>
                            <td class="border p-2 capitalize">{{ $repair->status }}</td>
                            <td class="border p-2">{{ $repair->cost ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
