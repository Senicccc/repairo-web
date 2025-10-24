@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4 text-center">Profil Saya</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-2">
        <p><strong>Nama:</strong> {{ $user->name }}</p>
        <p><strong>No. Telepon:</strong> {{ $user->phone }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Role:</strong> {{ $user->role }}</p>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('profile.edit') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Edit Profil
        </a>
    </div>
</div>
@endsection
