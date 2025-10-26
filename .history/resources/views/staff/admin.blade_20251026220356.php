@extends('layouts.app')

@section('content')
<div class="flex">
    <!-- Sidebar -->
    <aside class="w-1/5 bg-gray-900 text-white min-h-screen p-5">
        <h2 class="text-2xl font-bold mb-8 text-center">Admin Panel</h2>
        <ul class="space-y-3">
            <li><a href="{{ route('admin.users') }}" class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->is('admin/users') ? 'bg-gray-700' : '' }}">Users</a></li>
            <li><a href="{{ route('admin.repairs') }}" class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->is('admin/repairs') ? 'bg-gray-700' : '' }}">Repairs</a></li>
            <li><a href="{{ route('admin.payments') }}" class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->is('admin/payments') ? 'bg-gray-700' : '' }}">Payments</a></li>
            <li><a href="{{ route('admin.loyalty') }}" class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->is('admin/loyalty') ? 'bg-gray-700' : '' }}">Loyalty Rewards</a></li>
        </ul>
    </aside>

    <!-- Konten -->
    <main class="w-4/5 bg-gray-50 min-h-screen p-6">
        <h1 class="text-3xl font-bold mb-6">Selamat Datang, Admin</h1>
        <p class="text-gray-600 mb-4">Gunakan menu di samping untuk mengelola data pengguna, servis, pembayaran, dan reward loyalitas.</p>
    </main>
</div>
@endsection
