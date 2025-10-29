@extends('layouts.app')

@section('content')
<div class="flex">
    <!-- Sidebar -->
    <aside class="w-1/5 bg-gray-900 text-white min-h-screen p-4">
        <h2 class="text-xl font-bold mb-6 text-center">Admin Panel</h2>
        <ul>
            <li>
                <a href="#dashboard" class="block py-2 px-3 hover:bg-gray-700 rounded mb-2 bg-gray-700">
                    📊 Dashboard
                </a>
            </li>
            <li>
                <a href="#users" class="block py-2 px-3 hover:bg-gray-700 rounded mb-2">
                    👥 Users Management
                </a>
            </li>
            <li>
                <a href="#repairs" class="block py-2 px-3 hover:bg-gray-700 rounded mb-2">
                    🔧 Repairs Management
                </a>
            </li>
            <li>
                <a href="#payments" class="block py-2 px-3 hover:bg-gray-700 rounded mb-2">
                    💰 Payments Management
                </a>
            </li>
            <li>
                <a href="#loyalty" class="block py-2 px-3 hover:bg-gray-700 rounded mb-2">
                    🎁 Loyalty Rewards
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="w-4/5 bg-gray-50 p-6 overflow-y-auto">
        <!-- Dashboard Section -->
        <section id="dashboard" class="section-content">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
                <div class="text-sm text-gray-600">
                    Last updated: {{ now()->format('d M Y H:i') }}
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <span class="text-blue-600 text-xl">👥</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-600">Total Users</h3>
                            <p class="text-2xl font-bold text-gray-800">{{ $users->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-full">
                            <span class="text-green-600 text-xl">🔧</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-600">Total Repairs</h3>
                            <p class="text-2xl font-bold text-gray-800">{{ $repairs->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-purple-500">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-3 rounded-full">
                            <span class="text-purple-600 text-xl">💰</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-600">Total Payments</h3>
                            <p class="text-2xl font-bold text-gray-800">{{ $payments->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <span class="text-yellow-600 text-xl">🎁</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-600">Total Rewards</h3>
                            <p class="text-2xl font-bold text-gray-800">{{ $rewards->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Quick Actions</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <button onclick="showSection('users')" class="bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                        👥 Manage Users
                    </button>
                    <button onclick="showSection('repairs')" class="bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 transition text-sm font-medium">
                        🔧 Manage Repairs
                    </button>
                    <button onclick="showSection('payments')" class="bg-purple-600 text-white py-3 px-4 rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                        💰 View Payments
                    </button>
                    <button onclick="showSection('loyalty')" class="bg-yellow-600 text-white py-3 px-4 rounded-lg hover:bg-yellow-700 transition text-sm font-medium">
                        🎁 Loyalty System
                    </button>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Recent Repairs</h3>
                    <div class="space-y-3">
                        @foreach($repairs->take(5) as $repair)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                            <div>
                                <p class="font-medium text-sm">{{ $repair->tracking_id ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-600">{{ $repair->phone_brand ?? '' }} {{ $repair->phone_model ?? '' }}</p>
                            </div>
                            <span class="{{ \App\Models\Repair::getStatusColor($repair->status ?? 'pending') }} px-2 py-1 rounded text-xs">
                                {{ \App\Models\Repair::getStatuses()[$repair->status ?? 'pending'] ?? ucfirst($repair->status ?? 'pending') }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Recent Payments</h3>
                    <div class="space-y-3">
                        @foreach($payments->take(5) as $payment)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                            <div>
                                <p class="font-medium text-sm">Rp {{ number_format($payment->amount ?? 0, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-600">{{ $payment->payment_method ?? 'N/A' }} • {{ $payment->repair->tracking_id ?? 'N/A' }}</p>
                            </div>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
                                {{ ucfirst($payment->status ?? 'unknown') }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- Users Management Section -->
        <section id="users" class="section-content hidden">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Users Management</h1>
                <button onclick="openAddUserModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    + Add User
                </button>
            </div>

            <div class="flex justify-between mb-4">
                <input type="text" id="usersSearch" placeholder="Search users..." class="border px-3 py-2 w-1/2 rounded">
                <select id="usersPerPage" class="border px-3 py-2 rounded">
                    <option value="10">10 / page</option>
                    <option value="25">25 / page</option>
                    <option value="50">50 / page</option>
                </select>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left">ID</th>
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Phone</th>
                            <th class="px-4 py-3 text-left">Role</th>
                            <th class="px-4 py-3 text-left">Created</th>
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $user->id }}</td>
                            <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3">{{ $user->phone ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs 
                                    {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : 
                                       ($user->role == 'technician' ? 'bg-blue-100 text-blue-800' : 
                                       ($user->role == 'cashier' ? 'bg-green-100 text-green-800' : 
                                       'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <button onclick="editUser({{ $user->id }})" class="text-blue-600 hover:text-blue-800 text-sm mr-2">Edit</button>
                                <button onclick="deleteUser({{ $user->id }})" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Repairs Management Section -->
        <section id="repairs" class="section-content hidden">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Repairs Management</h1>
                <button onclick="openAddRepairModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    + Add Repair
                </button>
            </div>

            <div class="flex justify-between mb-4">
                <input type="text" id="repairsSearch" placeholder="Search repairs..." class="border px-3 py-2 w-1/2 rounded">
                <select id="repairsPerPage" class="border px-3 py-2 rounded">
                    <option value="10">10 / page</option>
                    <option value="25">25 / page</option>
                    <option value="50">50 / page</option>
                </select>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left">Tracking ID</th>
                            <th class="px-4 py-3 text-left">Customer</th>
                            <th class="px-4 py-3 text-left">Device</th>
                            <th class="px-4 py-3 text-left">Technician</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Cost</th>
                            <th class="px-4 py-3 text-left">Created</th>
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($repairs as $repair)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-blue-700">{{ $repair->tracking_id ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $repair->user->name ?? $repair->customer_name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $repair->phone_brand ?? '' }} {{ $repair->phone_model ?? '' }}</td>
                            <td class="px-4 py-3">{{ $repair->technician ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="{{ \App\Models\Repair::getStatusColor($repair->status ?? 'pending') }} px-2 py-1 rounded text-xs">
                                    {{ \App\Models\Repair::getStatuses()[$repair->status ?? 'pending'] ?? ucfirst($repair->status ?? 'pending') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium">
                                {{ $repair->cost ? 'Rp '.number_format($repair->cost, 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $repair->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <button onclick="viewRepair({{ $repair->id }})" class="text-blue-600 hover:text-blue-800 text-sm mr-2">View</button>
                                <button onclick="editRepair({{ $repair->id }})" class="text-green-600 hover:text-green-800 text-sm mr-2">Edit</button>
                                <button onclick="deleteRepair({{ $repair->id }})" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Payments Management Section -->
        <section id="payments" class="section-content hidden">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Payments Management</h1>
            </div>

            <div class="flex justify-between mb-4">
                <input type="text" id="paymentsSearch" placeholder="Search payments..." class="border px-3 py-2 w-1/2 rounded">
                <select id="paymentsPerPage" class="border px-3 py-2 rounded">
                    <option value="10">10 / page</option>
                    <option value="25">25 / page</option>
                    <option value="50">50 / page</option>
                </select>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left">Invoice</th>
                            <th class="px-4 py-3 text-left">Customer</th>
                            <th class="px-4 py-3 text-left">Repair ID</th>
                            <th class="px-4 py-3 text-left">Method</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Amount</th>
                            <th class="px-4 py-3 text-left">Date</th>
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">{{ $payment->invoice_number ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $payment->repair->user->name ?? $payment->repair->customer_name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $payment->repair->tracking_id ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                                    {{ ucfirst($payment->payment_method ?? 'unknown') }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs 
                                    {{ ($payment->status ?? '') == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($payment->status ?? 'unknown') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium text-green-600">
                                Rp {{ number_format($payment->amount ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') : '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <button onclick="viewPayment({{ $payment->id }})" class="text-blue-600 hover:text-blue-800 text-sm mr-2">View</button>
                                <button onclick="editPayment({{ $payment->id }})" class="text-green-600 hover:text-green-800 text-sm">Edit</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Loyalty Rewards Section -->
        <section id="loyalty" class="section-content hidden">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Loyalty Rewards Management</h1>
                <button onclick="openAddRewardModal()" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition">
                    + Add Reward
                </button>
            </div>

            <div class="flex justify-between mb-4">
                <input type="text" id="rewardsSearch" placeholder="Search rewards..." class="border px-3 py-2 w-1/2 rounded">
                <select id="rewardsPerPage" class="border px-3 py-2 rounded">
                    <option value="10">10 / page</option>
                    <option value="25">25 / page</option>
                    <option value="50">50 / page</option>
                </select>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left">ID</th>
                            <th class="px-4 py-3 text-left">Reward Name</th>
                            <th class="px-4 py-3 text-left">Description</th>
                            <th class="px-4 py-3 text-left">Points Required</th>
                            <th class="px-4 py-3 text-left">Code</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rewards as $reward)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $reward->id }}</td>
                            <td class="px-4 py-3 font-medium">{{ $reward->reward_name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $reward->description ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $reward->points_required ?? '-' }}</td>
                            <td class="px-4 py-3 font-mono">{{ $reward->code ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs 
                                    {{ ($reward->is_active ?? false) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ($reward->is_active ?? false) ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <button onclick="editReward({{ $reward->id }})" class="text-blue-600 hover:text-blue-800 text-sm mr-2">Edit</button>
                                <button onclick="toggleReward({{ $reward->id }})" class="text-green-600 hover:text-green-800 text-sm mr-2">
                                    {{ ($reward->is_active ?? false) ? 'Deactivate' : 'Activate' }}
                                </button>
                                <button onclick="deleteReward({{ $reward->id }})" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<!-- Add User Modal -->
<dialog id="addUserModal" class="modal">
    <div class="p-6 bg-white rounded shadow-md w-[500px] mx-auto">
        <h3 class="text-lg font-semibold mb-4">Add New User</h3>
        <form id="addUserForm" method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input type="text" name="name" required class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" required class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Phone</label>
                    <input type="text" name="phone" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Role</label>
                    <select name="role" required class="w-full border p-2 rounded">
                        <option value="customer">Customer</option>
                        <option value="technician">Technician</option>
                        <option value="cashier">Cashier</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Password</label>
                    <input type="password" name="password" required class="w-full border p-2 rounded">
                </div>
            </div>
            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" onclick="document.getElementById('addUserModal').close()" 
                        class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Add User</button>
            </div>
        </form>
    </div>
</dialog>

<script>
// Section Management
function showSection(sectionName) {
    // Hide all sections
    document.querySelectorAll('.section-content').forEach(section => {
        section.classList.add('hidden');
    });
    
    // Show selected section
    document.getElementById(sectionName).classList.remove('hidden');
    
    // Update sidebar active state
    document.querySelectorAll('aside a').forEach(link => {
        link.classList.remove('bg-gray-700');
    });
    event.target.classList.add('bg-gray-700');
}

// Initialize with dashboard
document.addEventListener('DOMContentLoaded', function() {
    showSection('dashboard');
    
    // Add click events to sidebar links
    document.querySelectorAll('aside a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const sectionName = this.getAttribute('href').substring(1);
            showSection(sectionName);
        });
    });
});

// Modal Functions
function openAddUserModal() {
    document.getElementById('addUserModal').showModal();
}

function openAddRepairModal() {
    // Implementation for add repair modal
    alert('Add Repair functionality would go here');
}

function openAddRewardModal() {
    // Implementation for add reward modal
    alert('Add Reward functionality would go here');
}

// Search functionality for each section
document.getElementById('usersSearch')?.addEventListener('keyup', function() {
    filterTable('users', this.value.toLowerCase());
});

document.getElementById('repairsSearch')?.addEventListener('keyup', function() {
    filterTable('repairs', this.value.toLowerCase());
});

document.getElementById('paymentsSearch')?.addEventListener('keyup', function() {
    filterTable('payments', this.value.toLowerCase());
});

document.getElementById('rewardsSearch')?.addEventListener('keyup', function() {
    filterTable('loyalty', this.value.toLowerCase());
});

function filterTable(section, value) {
    const rows = document.querySelectorAll(`#${section} tbody tr`);
    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
    });
}

// Placeholder functions for actions
function editUser(id) {
    alert('Edit user ' + id);
}

function deleteUser(id) {
    if(confirm('Are you sure you want to delete this user?')) {
        alert('Delete user ' + id);
    }
}

function viewRepair(id) {
    alert('View repair ' + id);
}

function editRepair(id) {
    alert('Edit repair ' + id);
}

function deleteRepair(id) {
    if(confirm('Are you sure you want to delete this repair?')) {
        alert('Delete repair ' + id);
    }
}

function viewPayment(id) {
    alert('View payment ' + id);
}

function editPayment(id) {
    alert('Edit payment ' + id);
}

function editReward(id) {
    alert('Edit reward ' + id);
}

function toggleReward(id) {
    alert('Toggle reward ' + id);
}

function deleteReward(id) {
    if(confirm('Are you sure you want to delete this reward?')) {
        alert('Delete reward ' + id);
    }
}
</script>

<style>
.section-content {
    transition: all 0.3s ease;
}

.modal {
    border: none;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.modal::backdrop {
    background: rgba(0, 0, 0, 0.5);
}
</style>
@endsection