@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-50 text-gray-800 font-inter">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 shadow-sm flex flex-col">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-blue-600 text-center tracking-tight">Repairo System Admin Panel</h2>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            @php
                $menuItems = [
                    ['id' => 'users', 'label' => 'Users'],
                    ['id' => 'repairs', 'label' => 'Repairs'],
                    ['id' => 'payments', 'label' => 'Payments'],
                    ['id' => 'loyalty', 'label' => 'Loyalty'],
                    ['id' => 'spareparts', 'label' => 'Spare Parts'],
                ];
            @endphp

            @foreach ($menuItems as $item)
                <button 
                    onclick="showSection('{{ $item['id'] }}', this)" 
                    class="tab-btn w-full text-left py-2.5 px-4 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-all duration-200 font-medium"
                >
                    {{ $item['label'] }}
                </button>
            @endforeach
        </nav>

        <div class="p-4 border-t border-gray-200 text-sm text-center text-gray-500">
            © {{ date('Y') }} Repairo Gadget Repair
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10 overflow-y-auto">
        <header class="flex justify-between items-center mb-8 border-b pb-4">
            <h1 class="text-3xl font-semibold text-gray-900 tracking-tight">Admin Dashboard</h1>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600">Welcome, <strong>Admin</strong></span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Logout</button>
                </form>
            </div>
        </header>

        <!-- Dynamic Content -->
        <div id="content-area" class="bg-white rounded-xl shadow-md p-6 transition-all duration-300">
            @include('admin.users')
        </div>
    </main>
</div>

{{-- GLOBAL EDIT MODAL FOR REPAIRS --}}
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-md p-6 relative">
        <button type="button" id="closeModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <h3 class="text-lg font-semibold mb-4">Edit Repair</h3>
        <form id="editRepairForm">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" id="editRepairId" name="repair_id">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="editStatus" name="status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="finished">Finished</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Technician</label>
                <input type="text" id="editTechnician" name="technician" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cost</label>
                <input type="number" id="editCost" name="cost" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter cost in Rupiah">
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" id="cancelEdit" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">Save Changes</button>
            </div>
        </form>
    </div>
</div>

{{-- GLOBAL EDIT MODAL FOR PAYMENTS --}}
<div id="editPaymentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-md p-6 relative">
        <button type="button" id="closePaymentModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <h3 class="text-lg font-semibold mb-4">Edit Payment</h3>
        <form id="editPaymentForm">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" id="editPaymentId" name="payment_id">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="editPaymentStatus" name="status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="unpaid">Unpaid</option>
                    <option value="paid">Paid</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                <select id="editPaymentMethod" name="payment_method" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer</option>
                    <option value="ewallet">E-Wallet</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                <input type="number" id="editPaymentAmount" name="amount" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter amount in Rupiah">
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" id="cancelPaymentEdit" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">Save Changes</button>
            </div>
        </form>
    </div>
</div>

{{-- GLOBAL EDIT MODAL FOR LOYALTY --}}
<div id="editLoyaltyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-md p-6 relative">
        <button type="button" id="closeLoyaltyModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <h3 class="text-lg font-semibold mb-4">Edit Loyalty Reward</h3>
        <form id="editLoyaltyForm">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" id="editLoyaltyId" name="loyalty_id">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="editLoyaltyStatus" name="status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="claimed">Claimed</option>
                    <option value="used">Used</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Reward Type</label>
                <select id="editLoyaltyType" name="reward_type" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="discount">Discount</option>
                    <option value="gift">Gift</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Points Used</label>
                <input type="number" id="editLoyaltyPoints" name="points_used" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter points used">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Reward Value</label>
                <input type="text" id="editLoyaltyValue" name="reward_value" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter reward value">
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" id="cancelLoyaltyEdit" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">Save Changes</button>
            </div>
        </form>
    </div>
</div>

{{-- GLOBAL EDIT MODAL FOR SPAREPARTS --}}
<div id="editSparepartModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-md p-6 relative">
        <button type="button" id="closeSparepartModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <h3 class="text-lg font-semibold mb-4">Edit Sparepart</h3>
        <form id="editSparepartForm">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" id="editSparepartId" name="sparepart_id">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" id="editSparepartName" name="name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter sparepart name" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                <input type="text" id="editSparepartBrand" name="brand" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter brand" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select id="editSparepartCategory" name="category" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="Original">Original</option>
                    <option value="OEM">OEM</option>
                    <option value="Aftermarket">Aftermarket</option>
                    <option value="Replica">Replica</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                <input type="number" id="editSparepartPrice" name="price" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter price" required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
                <input type="number" id="editSparepartStock" name="stock" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter stock quantity" required>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" id="cancelSparepartEdit" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">Save Changes</button>
            </div>
        </form>
    </div>
</div>

{{-- CREATE SPAREPART MODAL --}}
<div id="createSparepartModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-md p-6 relative">
        <button type="button" id="closeCreateSparepartModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <h3 class="text-lg font-semibold mb-4">Add New Sparepart</h3>
        <form id="createSparepartForm">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" id="createSparepartName" name="name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter sparepart name" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                <input type="text" id="createSparepartBrand" name="brand" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter brand" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select id="createSparepartCategory" name="category" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Select Category</option>
                    <option value="Original">Original</option>
                    <option value="OEM">OEM</option>
                    <option value="Aftermarket">Aftermarket</option>
                    <option value="Replica">Replica</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Compatible Models</label>
                <input type="text" id="createSparepartModels" name="compatible_models" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="iPhone 12, Samsung S21, etc (comma separated)">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                <input type="number" id="createSparepartPrice" name="price" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter price" required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
                <input type="number" id="createSparepartStock" name="stock" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter stock quantity" required>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" id="cancelCreateSparepart" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-200">Add Sparepart</button>
            </div>
        </form>
    </div>
</div>

{{-- CREATE SPAREPART MODAL --}}
<div id="createSparepartModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-md p-6 relative">
        <button type="button" id="closeCreateSparepartModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <h3 class="text-lg font-semibold mb-4">Add New Sparepart</h3>
        <form id="createSparepartForm">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" id="createSparepartName" name="name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter sparepart name" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                <input type="text" id="createSparepartBrand" name="brand" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter brand" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select id="createSparepartCategory" name="category" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Select Category</option>
                    <option value="screen">Screen</option>
                    <option value="battery">Battery</option>
                    <option value="camera">Camera</option>
                    <option value="charging_port">Charging Port</option>
                    <option value="housing">Housing</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Compatible Models</label>
                <input type="text" id="createSparepartModels" name="compatible_models" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="iPhone 12, Samsung S21, etc (comma separated)">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                <input type="number" id="createSparepartPrice" name="price" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter price" required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
                <input type="number" id="createSparepartStock" name="stock" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter stock quantity" required>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" id="cancelCreateSparepart" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-200">Add Sparepart</button>
            </div>
        </form>
    </div>
</div>

<script>
// =============================================
// GLOBAL ADMIN FUNCTIONS
// =============================================

console.log('🔄 ADMIN DASHBOARD SCRIPT LOADED');

// Tab Navigation
function showSection(section, btn) {
    console.log('Loading section:', section);
    
    fetch(`/admin/section/${section}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            const content = document.getElementById('content-area');
            content.innerHTML = html;
            updateActiveTab(btn);
        })
        .catch(error => {
            console.error('Error loading section:', error);
            showAlert('Error loading section: ' + error.message, 'error');
        });
}

function updateActiveTab(activeBtn) {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
        btn.classList.add('text-gray-600', 'hover:bg-blue-50', 'hover:text-blue-600');
    });
    
    activeBtn.classList.remove('text-gray-600', 'hover:bg-blue-50', 'hover:text-blue-600');
    activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
}

// =============================================
// EVENT DELEGATION UNTUK SEMUA BUTTONS
// =============================================

document.addEventListener('click', function(e) {
    // Repair Buttons
    if (e.target.classList.contains('edit-repair')) {
        e.preventDefault();
        const repairId = e.target.getAttribute('data-id');
        console.log('✏️ Edit repair clicked:', repairId);
        openEditModal(repairId, e.target.closest('tr'));
    }
    
    if (e.target.classList.contains('delete-repair')) {
        e.preventDefault();
        const repairId = e.target.getAttribute('data-id');
        console.log('🗑️ Delete repair clicked:', repairId);
        deleteRepair(repairId, e.target.closest('tr'));
    }
    
    // Payment Buttons
    if (e.target.classList.contains('edit-payment')) {
        e.preventDefault();
        const paymentId = e.target.getAttribute('data-id');
        console.log('✏️ Edit payment clicked:', paymentId);
        openPaymentEditModal(paymentId, e.target.closest('tr'));
    }
    
    if (e.target.classList.contains('delete-payment')) {
        e.preventDefault();
        const paymentId = e.target.getAttribute('data-id');
        console.log('🗑️ Delete payment clicked:', paymentId);
        deletePayment(paymentId, e.target.closest('tr'));
    }
    
    // Loyalty Buttons
    if (e.target.classList.contains('edit-loyalty')) {
        e.preventDefault();
        const loyaltyId = e.target.getAttribute('data-id');
        console.log('✏️ Edit loyalty clicked:', loyaltyId);
        openLoyaltyEditModal(loyaltyId, e.target.closest('tr'));
    }
    
    if (e.target.classList.contains('delete-loyalty')) {
        e.preventDefault();
        const loyaltyId = e.target.getAttribute('data-id');
        console.log('🗑️ Delete loyalty clicked:', loyaltyId);
        deleteLoyalty(loyaltyId, e.target.closest('tr'));
    }
    
    // Spareparts Buttons
    if (e.target.classList.contains('edit-sparepart')) {
        e.preventDefault();
        const sparepartId = e.target.getAttribute('data-id');
        console.log('✏️ Edit sparepart clicked:', sparepartId);
        openSparepartEditModal(sparepartId, e.target.closest('tr'));
    }
    
    if (e.target.classList.contains('delete-sparepart')) {
        e.preventDefault();
        const sparepartId = e.target.getAttribute('data-id');
        console.log('🗑️ Delete sparepart clicked:', sparepartId);
        deleteSparepart(sparepartId, e.target.closest('tr'));
    }
});

// =============================================
// REPAIR FUNCTIONS
// =============================================

function openEditModal(repairId, row) {
    if (!row) {
        console.error('Row not found');
        return;
    }
    
    const cells = row.querySelectorAll('td');
    if (cells.length < 6) {
        console.error('Not enough cells in row');
        return;
    }
    
    // Get current values - adjust indexes based on your table structure
    const statusCell = cells[4]; // Status column
    const technicianCell = cells[5]; // Technician column  
    const costCell = cells[6]; // Cost column
    
    const currentStatus = statusCell.querySelector('.status-badge')?.textContent.trim().toLowerCase().replace(/\s+/g, '_') || 'pending';
    const currentTechnician = technicianCell.textContent.trim() === '-' ? '' : technicianCell.textContent.trim();
    const currentCost = costCell.textContent.trim().replace(/[^0-9]/g, '') || '';
    
    console.log('Current repair values:', { currentStatus, currentTechnician, currentCost });
    
    // Populate form
    document.getElementById('editRepairId').value = repairId;
    document.getElementById('editStatus').value = currentStatus;
    document.getElementById('editTechnician').value = currentTechnician;
    document.getElementById('editCost').value = currentCost;
    
    // Show modal
    document.getElementById('editModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

async function deleteRepair(repairId, row) {
    if (!confirm('Are you sure you want to delete this repair? This action cannot be undone.')) {
        return;
    }

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch(`/admin/repairs/${repairId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            console.log('Repair deleted successfully');
            row.remove();
            updateRepairCount();
            showAlert('✅ Repair deleted successfully!', 'success');
        } else {
            throw new Error(data.message || 'Failed to delete repair');
        }
    } catch (error) {
        console.error('Error deleting repair:', error);
        showAlert('❌ Error: ' + error.message, 'error');
    }
}

// =============================================
// PAYMENT FUNCTIONS
// =============================================

function openPaymentEditModal(paymentId, row) {
    if (!row) {
        console.error('Row not found');
        return;
    }
    
    const cells = row.querySelectorAll('td');
    if (cells.length < 5) {
        console.error('Not enough cells in row');
        return;
    }
    
    // Get current values - adjust indexes based on your table structure
    const statusCell = cells[3]; // Status column
    const methodCell = cells[4]; // Method column  
    const amountCell = cells[5]; // Amount column
    
    const currentStatus = statusCell.querySelector('.status-badge')?.textContent.trim().toLowerCase() || 'unpaid';
    const currentMethod = methodCell.textContent.trim().toLowerCase().replace(/\s+/g, '_') || 'cash';
    const currentAmount = amountCell.textContent.trim().replace(/[^0-9]/g, '') || '';
    
    console.log('Current payment values:', { currentStatus, currentMethod, currentAmount });
    
    // Populate form
    document.getElementById('editPaymentId').value = paymentId;
    document.getElementById('editPaymentStatus').value = currentStatus;
    document.getElementById('editPaymentMethod').value = currentMethod;
    document.getElementById('editPaymentAmount').value = currentAmount;
    
    // Show modal
    document.getElementById('editPaymentModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

async function deletePayment(paymentId, row) {
    if (!confirm('Are you sure you want to delete this payment? This action cannot be undone.')) {
        return;
    }

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch(`/admin/payments/${paymentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            console.log('Payment deleted successfully');
            row.remove();
            updatePaymentCount();
            showAlert('✅ Payment deleted successfully!', 'success');
        } else {
            throw new Error(data.message || 'Failed to delete payment');
        }
    } catch (error) {
        console.error('Error deleting payment:', error);
        showAlert('❌ Error: ' + error.message, 'error');
    }
}

// =============================================
// LOYALTY FUNCTIONS
// =============================================

function openLoyaltyEditModal(loyaltyId, row) {
    if (!row) {
        console.error('Row not found');
        return;
    }
    
    const cells = row.querySelectorAll('td');
    if (cells.length < 8) {
        console.error('Not enough cells in row');
        return;
    }
    
    // Get current values - adjust indexes based on your table structure
    // [ID, User, Reward Type, Reward Value, Points Used, Code, Status, Actions]
    const statusCell = cells[6]; // Status column
    const typeCell = cells[2];   // Reward Type column
    const pointsCell = cells[4]; // Points Used column
    const valueCell = cells[3];  // Reward Value column
    
    // Get values from cells
    const currentStatus = statusCell.querySelector('.status-badge')?.textContent.trim().toLowerCase() || 'claimed';
    const currentType = typeCell.textContent.trim().toLowerCase() || 'discount';
    const currentPoints = pointsCell.textContent.trim().replace(/[^0-9]/g, '') || '';
    
    // Get reward value - handle currency format for discount
    let currentValue = valueCell.textContent.trim();
    if (currentType === 'discount' && currentValue.includes('Rp')) {
        // Extract numeric value from currency format "Rp 50.000"
        currentValue = currentValue.replace('Rp', '').replace(/\./g, '').trim();
    }
    
    console.log('Current loyalty values:', { 
        currentStatus, 
        currentType, 
        currentPoints, 
        currentValue 
    });
    
    // Populate form
    document.getElementById('editLoyaltyId').value = loyaltyId;
    document.getElementById('editLoyaltyStatus').value = currentStatus;
    document.getElementById('editLoyaltyType').value = currentType;
    document.getElementById('editLoyaltyPoints').value = currentPoints;
    document.getElementById('editLoyaltyValue').value = currentValue;
    
    // Show modal
    document.getElementById('editLoyaltyModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

async function deleteLoyalty(loyaltyId, row) {
    if (!confirm('Are you sure you want to delete this loyalty reward? This action cannot be undone.')) {
        return;
    }

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch(`/admin/loyalty/${loyaltyId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            console.log('Loyalty reward deleted successfully');
            row.remove();
            updateLoyaltyCount();
            showAlert('✅ Loyalty reward deleted successfully!', 'success');
        } else {
            throw new Error(data.message || 'Failed to delete loyalty reward');
        }
    } catch (error) {
        console.error('Error deleting loyalty reward:', error);
        showAlert('❌ Error: ' + error.message, 'error');
    }
}

// =============================================
// SPAREPARTS FUNCTIONS
// =============================================

// Open Edit Modal untuk Sparepart - PERBAIKAN
function openSparepartEditModal(sparepartId, row) {
    if (!row) {
        console.error('Row not found');
        return;
    }
    
    const cells = row.querySelectorAll('td');
    if (cells.length < 8) {
        console.error('Not enough cells in row');
        return;
    }
    
    // Get current values
    const nameCell = cells[1];
    const categoryCell = cells[2];
    const modelsCell = cells[3];
    const stockCell = cells[4];
    const priceCell = cells[5];
    
    const currentName = nameCell.querySelector('.text-sm.font-medium')?.textContent.trim() || '';
    const currentBrand = nameCell.querySelector('.text-sm.text-gray-500')?.textContent.trim() || '';
    const currentCategory = categoryCell.textContent.trim() || 'Original'; // TIDAK PERLU lowercase/replace
    const currentModels = modelsCell.textContent.trim() === '-' ? '' : modelsCell.textContent.trim();
    const currentStock = stockCell.textContent.trim().replace(/[^0-9]/g, '') || '0';
    const currentPrice = priceCell.textContent.trim().replace(/[^0-9]/g, '') || '0';
    
    console.log('Current sparepart values:', { 
        currentName, currentBrand, currentCategory, currentModels, currentStock, currentPrice 
    });
    
    // Populate form
    document.getElementById('editSparepartId').value = sparepartId;
    document.getElementById('editSparepartName').value = currentName;
    document.getElementById('editSparepartBrand').value = currentBrand;
    document.getElementById('editSparepartCategory').value = currentCategory;
    document.getElementById('editSparepartModels').value = currentModels;
    document.getElementById('editSparepartStock').value = currentStock;
    document.getElementById('editSparepartPrice').value = currentPrice;
    
    // Show modal
    document.getElementById('editSparepartModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

async function deleteSparepart(sparepartId, row) {
    if (!confirm('Are you sure you want to delete this sparepart? This action cannot be undone.')) {
        return;
    }

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch(`/admin/spareparts/${sparepartId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            console.log('Sparepart deleted successfully');
            row.remove();
            updateSparepartCount();
            showAlert('✅ Sparepart deleted successfully!', 'success');
        } else {
            throw new Error(data.message || 'Failed to delete sparepart');
        }
    } catch (error) {
        console.error('Error deleting sparepart:', error);
        showAlert('❌ Error: ' + error.message, 'error');
    }
}

// Open Create Modal
function openCreateSparepartModal() {
    // Reset form
    document.getElementById('createSparepartForm').reset();
    document.getElementById('createSparepartModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// =============================================
// FORM SUBMISSION HANDLERS
// =============================================

// Repair Form Submission
document.getElementById('editRepairForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const repairId = document.getElementById('editRepairId').value;
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.textContent;
    
    const formData = {
        status: document.getElementById('editStatus').value,
        technician: document.getElementById('editTechnician').value,
        cost: document.getElementById('editCost').value,
        _method: 'PUT'
    };
    
    console.log('Submitting repair data:', formData);
    
    // Show loading state
    submitBtn.textContent = 'Saving...';
    submitBtn.disabled = true;
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch(`/admin/repairs/${repairId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            console.log('Repair updated successfully:', data);
            showAlert('✅ Repair updated successfully!', 'success');
            closeModal();
            
            // Refresh the repairs section after a delay
            setTimeout(() => {
                const repairTab = document.querySelector('[onclick*="repairs"]');
                if (repairTab) {
                    repairTab.click();
                }
            }, 1000);
            
        } else {
            throw new Error(data.message || 'Failed to update repair');
        }
    } catch (error) {
        console.error('Error updating repair:', error);
        showAlert('❌ Error: ' + error.message, 'error');
    } finally {
        // Restore button state
        submitBtn.textContent = originalBtnText;
        submitBtn.disabled = false;
    }
});

// Payment Form Submission
document.getElementById('editPaymentForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const paymentId = document.getElementById('editPaymentId').value;
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.textContent;
    
    const formData = {
        status: document.getElementById('editPaymentStatus').value,
        payment_method: document.getElementById('editPaymentMethod').value,
        amount: document.getElementById('editPaymentAmount').value,
        _method: 'PUT'
    };
    
    console.log('Submitting payment data:', formData);
    
    // Show loading state
    submitBtn.textContent = 'Saving...';
    submitBtn.disabled = true;
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch(`/admin/payments/${paymentId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            console.log('Payment updated successfully:', data);
            showAlert('✅ Payment updated successfully!', 'success');
            closePaymentModal();
            
            // Refresh the payments section after a delay
            setTimeout(() => {
                const paymentTab = document.querySelector('[onclick*="payments"]');
                if (paymentTab) {
                    paymentTab.click();
                }
            }, 1000);
            
        } else {
            throw new Error(data.message || 'Failed to update payment');
        }
    } catch (error) {
        console.error('Error updating payment:', error);
        showAlert('❌ Error: ' + error.message, 'error');
    } finally {
        // Restore button state
        submitBtn.textContent = originalBtnText;
        submitBtn.disabled = false;
    }
});

// Loyalty Form Submission
document.getElementById('editLoyaltyForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const loyaltyId = document.getElementById('editLoyaltyId').value;
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.textContent;
    
    const formData = {
        status: document.getElementById('editLoyaltyStatus').value,
        reward_type: document.getElementById('editLoyaltyType').value,
        points_used: parseInt(document.getElementById('editLoyaltyPoints').value),
        reward_value: document.getElementById('editLoyaltyValue').value,
        _method: 'PUT'
    };
    
    console.log('Submitting loyalty data:', formData);
    
    // Validation
    if (!formData.points_used || formData.points_used < 0) {
        showAlert('❌ Please enter a valid points value', 'error');
        return;
    }
    
    if (!formData.reward_value) {
        showAlert('❌ Please enter a reward value', 'error');
        return;
    }
    
    // Show loading state
    submitBtn.textContent = 'Saving...';
    submitBtn.disabled = true;
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch(`/admin/loyalty/${loyaltyId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            console.log('Loyalty reward updated successfully:', data);
            showAlert('✅ Loyalty reward updated successfully!', 'success');
            closeLoyaltyModal();
            
            // Refresh the loyalty section after a delay
            setTimeout(() => {
                const loyaltyTab = document.querySelector('[onclick*="loyalty"]');
                if (loyaltyTab) {
                    loyaltyTab.click();
                }
            }, 1000);
            
        } else {
            throw new Error(data.message || 'Failed to update loyalty reward');
        }
    } catch (error) {
        console.error('Error updating loyalty reward:', error);
        showAlert('❌ Error: ' + error.message, 'error');
    } finally {
        // Restore button state
        submitBtn.textContent = originalBtnText;
        submitBtn.disabled = false;
    }
});

// Sparepart Edit Form Submission
document.getElementById('editSparepartForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const sparepartId = document.getElementById('editSparepartId').value;
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.textContent;
    
    const formData = {
        name: document.getElementById('editSparepartName').value,
        brand: document.getElementById('editSparepartBrand').value,
        category: document.getElementById('editSparepartCategory').value,
        compatible_models: document.getElementById('editSparepartModels').value,
        price: document.getElementById('editSparepartPrice').value,
        stock: document.getElementById('editSparepartStock').value,
        _method: 'PUT'
    };
    
    console.log('Submitting sparepart data:', formData);
    
    // Show loading state
    submitBtn.textContent = 'Saving...';
    submitBtn.disabled = true;
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch(`/admin/spareparts/${sparepartId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            console.log('Sparepart updated successfully:', data);
            showAlert('✅ Sparepart updated successfully!', 'success');
            closeSparepartModal();
            
            // Refresh the spareparts section
            setTimeout(() => {
                const sparepartTab = document.querySelector('[onclick*="spareparts"]');
                if (sparepartTab) {
                    sparepartTab.click();
                }
            }, 1000);
            
        } else {
            throw new Error(data.message || 'Failed to update sparepart');
        }
    } catch (error) {
        console.error('Error updating sparepart:', error);
        showAlert('❌ Error: ' + error.message, 'error');
    } finally {
        submitBtn.textContent = originalBtnText;
        submitBtn.disabled = false;
    }
});

// Sparepart Create Form Submission
document.getElementById('createSparepartForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.textContent;
    
    const formData = {
        name: document.getElementById('createSparepartName').value,
        brand: document.getElementById('createSparepartBrand').value,
        category: document.getElementById('createSparepartCategory').value,
        compatible_models: document.getElementById('createSparepartModels').value,
        price: document.getElementById('createSparepartPrice').value,
        stock: document.getElementById('createSparepartStock').value,
    };
    
    console.log('Creating sparepart:', formData);
    
    // Show loading state
    submitBtn.textContent = 'Creating...';
    submitBtn.disabled = true;
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch(`/admin/spareparts`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            console.log('Sparepart created successfully:', data);
            showAlert('✅ Sparepart created successfully!', 'success');
            closeCreateSparepartModal();
            
            // Refresh the spareparts section
            setTimeout(() => {
                const sparepartTab = document.querySelector('[onclick*="spareparts"]');
                if (sparepartTab) {
                    sparepartTab.click();
                }
            }, 1000);
            
        } else {
            throw new Error(data.message || 'Failed to create sparepart');
        }
    } catch (error) {
        console.error('Error creating sparepart:', error);
        showAlert('❌ Error: ' + error.message, 'error');
    } finally {
        submitBtn.textContent = originalBtnText;
        submitBtn.disabled = false;
    }
});

// =============================================
// MODAL CONTROLS
// =============================================

// Repair Modal Controls
function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.getElementById('closeModal')?.addEventListener('click', closeModal);
document.getElementById('cancelEdit')?.addEventListener('click', closeModal);

// Payment Modal Controls
function closePaymentModal() {
    document.getElementById('editPaymentModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.getElementById('closePaymentModal')?.addEventListener('click', closePaymentModal);
document.getElementById('cancelPaymentEdit')?.addEventListener('click', closePaymentModal);

// Loyalty Modal Controls
function closeLoyaltyModal() {
    document.getElementById('editLoyaltyModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.getElementById('closeLoyaltyModal')?.addEventListener('click', closeLoyaltyModal);
document.getElementById('cancelLoyaltyEdit')?.addEventListener('click', closeLoyaltyModal);

// Sparepart Modal Controls
function closeSparepartModal() {
    document.getElementById('editSparepartModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function closeCreateSparepartModal() {
    document.getElementById('createSparepartModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.getElementById('closeSparepartModal')?.addEventListener('click', closeSparepartModal);
document.getElementById('cancelSparepartEdit')?.addEventListener('click', closeSparepartModal);
document.getElementById('closeCreateSparepartModal')?.addEventListener('click', closeCreateSparepartModal);
document.getElementById('cancelCreateSparepart')?.addEventListener('click', closeCreateSparepartModal);

// Close modals when clicking on background
document.addEventListener('click', function(e) {
    if (e.target.id === 'editModal') closeModal();
    if (e.target.id === 'editPaymentModal') closePaymentModal();
    if (e.target.id === 'editLoyaltyModal') closeLoyaltyModal();
    if (e.target.id === 'editSparepartModal') closeSparepartModal();
    if (e.target.id === 'createSparepartModal') closeCreateSparepartModal();
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
        closePaymentModal();
        closeLoyaltyModal();
        closeSparepartModal();
        closeCreateSparepartModal();
    }
});

// =============================================
// HELPER FUNCTIONS
// =============================================

function showAlert(message, type = 'info') {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.custom-alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create alert element
    const alert = document.createElement('div');
    alert.className = `custom-alert fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white font-medium ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    alert.textContent = message;
    
    document.body.appendChild(alert);
    
    // Remove alert after 3 seconds
    setTimeout(() => {
        alert.remove();
    }, 3000);
}

function updateRepairCount() {
    const repairsTableBody = document.getElementById('repairsTableBody');
    if (!repairsTableBody) return;
    
    const visibleRows = repairsTableBody.querySelectorAll('tr:not([style*="display: none"])');
    const countElement = document.querySelector('#repairs .text-sm.text-gray-600');
    if (countElement) {
        countElement.textContent = `Total: ${visibleRows.length} repairs`;
    }
}

function updatePaymentCount() {
    const paymentsTableBody = document.getElementById('paymentsTableBody');
    if (!paymentsTableBody) return;
    
    const visibleRows = paymentsTableBody.querySelectorAll('tr:not([style*="display: none"])');
    const countElement = document.querySelector('#payments .text-sm.text-gray-600');
    if (countElement) {
        countElement.textContent = `Total: ${visibleRows.length} payments`;
    }
}

function updateLoyaltyCount() {
    const loyaltyTableBody = document.getElementById('loyaltyTableBody');
    if (!loyaltyTableBody) return;
    
    const visibleRows = loyaltyTableBody.querySelectorAll('tr:not([style*="display: none"])');
    const countElement = document.querySelector('#loyalty .text-sm.text-gray-600');
    if (countElement) {
        countElement.textContent = `Total: ${visibleRows.length} rewards`;
    }
}

function updateSparepartCount() {
    const sparepartsTableBody = document.getElementById('sparepartsTableBody');
    if (!sparepartsTableBody) return;
    
    const visibleRows = sparepartsTableBody.querySelectorAll('tr:not([style*="display: none"])');
    const countElement = document.querySelector('#spareparts .text-sm.text-gray-600');
    if (countElement) {
        countElement.textContent = `Total: ${visibleRows.length} items`;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Admin dashboard initialized');
    
    // Set first tab as active
    const firstBtn = document.querySelector('.tab-btn');
    if (firstBtn) {
        firstBtn.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
    }
});
</script>
@endsection