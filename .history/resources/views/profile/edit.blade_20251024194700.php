@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4 text-center">Edit Profile</h2>

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf

        <div class="mb-3">
            <label class="block mb-1 font-semibold">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-3">
            <label class="block mb-1 font-semibold">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-3">
            <label class="block mb-1 font-semibold">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded p-2">
        </div>

 
        <div class="mb-3">
            <label class="block mb-1 font-semibold">Created At</label>
            <input type="text" name="created_at" value="{{ old('created_at', $user->created_at) }}" class="w-full border rounded p-2" readonly>
        </div>

        <div class="mb-3">
            <label class="block mb-1 font-semibold">Updated At</label>
            <input type="text" name="updated_at" value="{{ old('updated_at', $user->updated_at) }}" class="w-full border rounded p-2" readonly>
        </div>

        <div class="mb-3">
            <label class="block mb-1 font-semibold">Role</label>
            <input type="text" name="role" value="{{ old('role', $user->role) }}" class="w-full border rounded p-2">
        </div>

        <div class="flex justify-between mt-6">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Save Changes
            </button>
            <a href="{{ route('profile.show') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
