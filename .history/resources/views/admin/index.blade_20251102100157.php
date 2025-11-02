@extends('layouts.app')

@section('content')
<div class="flex">
    <!-- Sidebar -->
<aside class="w-1/5 bg-gray-900 text-white min-h-screen p-4">
    <h2 class="text-xl font-bold mb-6 text-center">Admin Panel</h2>
    <ul class="space-y-1">
        <li><button onclick="showSection('users')" class="tab-btn w-full text-left py-2 px-3 rounded hover:bg-gray-700">👤 Users</button></li>
        <li><button onclick="showSection('repairs')" class="tab-btn w-full text-left py-2 px-3 rounded hover:bg-gray-700">🔧 Repairs</button></li>
        <li><button onclick="showSection('payments')" class="tab-btn w-full text-left py-2 px-3 rounded hover:bg-gray-700">💳 Payments</button></li>
        <li><button onclick="showSection('loyalty')" class="tab-btn w-full text-left py-2 px-3 rounded hover:bg-gray-700">🎁 Loyalty</button></li>
        <li><button onclick="showSection('spareparts')" class="tab-btn w-full text-left py-2 px-3 rounded hover:bg-gray-700">🔩 Spareparts</button></li>
    </ul>
</aside>

    <!-- Main Content -->
    <main class="w-4/5 bg-white p-6 overflow-y-auto">
        <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
        
        <!-- Dynamic Content -->
        <div id="content-area">
            @include('admin.users')
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

function updateActiveTab(activeSection) {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-gray-700', 'text-white');
        btn.classList.add('text-gray-300');
    });
    event.target.classList.add('bg-gray-700', 'text-white');
    event.target.classList.remove('text-gray-300');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.tab-btn').classList.add('bg-gray-700', 'text-white');
});
</script>
@endsection