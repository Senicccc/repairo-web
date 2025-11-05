@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 p-6 shadow-sm">
        <h2 class="text-2xl font-semibold text-blue-600 mb-8 text-center">Admin Panel</h2>
        <nav class="space-y-2">
            <button onclick="showSection('users')" class="tab-btn w-full text-left py-2 px-3 rounded-lg font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">Users</button>
            <button onclick="showSection('repairs')" class="tab-btn w-full text-left py-2 px-3 rounded-lg font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">Repairs</button>
            <button onclick="showSection('payments')" class="tab-btn w-full text-left py-2 px-3 rounded-lg font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">Payments</button>
            <button onclick="showSection('loyalty')" class="tab-btn w-full text-left py-2 px-3 rounded-lg font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">Loyalty</button>
            <button onclick="showSection('spareparts')" class="tab-btn w-full text-left py-2 px-3 rounded-lg font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">Spareparts</button>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
            <a href="{{ route('home') }}" class="text-sm font-medium text-blue-600 hover:underline">Go to Website</a>
        </div>

        <div id="content-area" class="bg-white rounded-lg shadow p-6">
            @include('admin.sections.users')
        </div>
    </main>
</div>

<script>
function showSection(section) {
    fetch(`/admin/section/${section}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('content-area').innerHTML = html;
            updateActiveTab(section);
        })
        .catch(error => console.error('Error:', error));
}

function updateActiveTab(section) {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-blue-50', 'text-blue-600', 'font-semibold');
    });
    event.target.classList.add('bg-blue-50', 'text-blue-600', 'font-semibold');
}

// Init default active
document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.tab-btn').classList.add('bg-blue-50', 'text-blue-600', 'font-semibold');
});
</script>
@endsection
