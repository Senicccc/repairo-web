@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
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
        <tr class="border-b">
            <td class="font-semibold p-2">Updated At</td>
            <td class="p-2">{{ $user->updated_at }}</td>
        </tr>
    </table>

    <div class="mt-6 text-center">
        <a href="{{ route('profile.edit') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Edit Profile
        </a>
    </div>
</div>
@endsection
