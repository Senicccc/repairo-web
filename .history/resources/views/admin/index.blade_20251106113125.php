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

<script>
// =============================================
// GLOBAL ADMIN FUNCTIONS - WORK FOR ALL SECTIONS
// =============================================

console.log('🔄 ADMIN DASHBOARD SCRIPT LOADED');

// Tab Navigation
function showSection(section, btn) {
    console.log('Loading section:', section);
    
    fetch(`/admin/section/${section}`)
        .then(response => response.text())
        .then(html => {
            const content = document.getElementById('content-area');
            content.classList.add('opacity-0', 'translate-y-1');
            setTimeout(() => {
                content.innerHTML = html;
                content.classList.remove('opacity-0', 'translate-y-1');
                content.classList.add('opacity-100', 'translate-y-0');
                
                // Initialize search berdasarkan section
                if (section === 'repairs') {
                    initializeRepairsSearch();
                } else if (section === 'payments') {
                    initializePaymentsSearch();
                }
            }, 150);
            updateActiveTab(btn);
        })
        .catch(error => console.error('Error loading section:', error));
}

function updateActiveTab(activeBtn) {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
        btn.classList.add('text-gray-700');
    });
    activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
    activeBtn.classList.remove('text-gray-700');
}

// =============================================
// GLOBAL REPAIRS FUNCTIONALITY
// =============================================

// Event Delegation untuk SEMUA repair buttons
document.addEventListener('click', function(e) {
    // Edit Repair Button
    if (e.target.classList.contains('edit-repair')) {
        e.preventDefault();
        const repairId = e.target.getAttribute('data-id');
        console.log('✏️ Edit repair clicked:', repairId);
        openEditModal(repairId, e.target.closest('tr'));
    }
    
    // Delete Repair Button
    if (e.target.classList.contains('delete-repair')) {
        e.preventDefault();
        const repairId = e.target.getAttribute('data-id');
        console.log('🗑️ Delete repair clicked:', repairId);
        deleteRepair(repairId, e.target.closest('tr'));
    }
});

// Search functionality untuk repairs
function initializeRepairsSearch() {
    const repairSearch = document.getElementById('repairSearch');
    const repairsTableBody = document.getElementById('repairsTableBody');
    
    if (repairSearch && repairsTableBody) {
        repairSearch.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            const rows = repairsTableBody.querySelectorAll('tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
            
            // Update count
            updateRepairCount();
        });
        console.log('🔍 Repairs search initialized');
    }
}

// Open Edit Modal
function openEditModal(repairId, row) {
    if (!row) {
        console.error('Row not found');
        return;
    }
    
    const statusBadge = row.querySelector('.status-badge');
    const technicianCell = row.querySelector('.technician-cell');
    const costCell = row.querySelector('.cost-cell');
    
    if (!statusBadge || !technicianCell || !costCell) {
        console.error('Required cells not found');
        return;
    }
    
    // Get current values
    const currentStatus = statusBadge.textContent.trim().toLowerCase().replace(/\s+/g, '_');
    const currentTechnician = technicianCell.textContent.trim() === '-' ? '' : technicianCell.textContent.trim();
    const currentCost = costCell.textContent.trim().replace(/[^0-9]/g, '') || '';
    
    console.log('Current values:', { currentStatus, currentTechnician, currentCost });
    
    // Populate form
    document.getElementById('editRepairId').value = repairId;
    document.getElementById('editStatus').value = currentStatus;
    document.getElementById('editTechnician').value = currentTechnician;
    document.getElementById('editCost').value = currentCost;
    
    // Show modal
    document.getElementById('editModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Delete Repair
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
            }
        });

        if (response.ok) {
            console.log('Repair deleted successfully');
            row.remove();
            updateRepairCount();
            alert('✅ Repair deleted successfully!');
        } else {
            const data = await response.json();
            throw new Error(data.message || 'Failed to delete repair');
        }
    } catch (error) {
        console.error('Error deleting repair:', error);
        alert('❌ Error: ' + error.message);
    }
}

// Update repair count
function updateRepairCount() {
    const repairsTableBody = document.getElementById('repairsTableBody');
    if (!repairsTableBody) return;
    
    const visibleRows = repairsTableBody.querySelectorAll('tr:not([style*="display: none"])');
    const countElement = document.querySelector('#repairs .text-sm.text-gray-600');
    if (countElement) {
        countElement.textContent = `Total: ${visibleRows.length} repairs`;
    }
}

// Repair Modal functionality
document.getElementById('closeModal')?.addEventListener('click', closeModal);
document.getElementById('cancelEdit')?.addEventListener('click', closeModal);

document.addEventListener('click', function(e) {
    if (e.target.id === 'editModal') {
        closeModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('editModal') && !document.getElementById('editModal').classList.contains('hidden')) {
        closeModal();
    }
});

function closeModal() {
    const modal = document.getElementById('editModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Repair Form submission - PERBAIKAN: ALERT SAJA
document.getElementById('editRepairForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const repairId = document.getElementById('editRepairId').value;
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.textContent;
    
    const formData = new FormData(this);
    
    // Show loading state
    submitBtn.textContent = 'Saving...';
    submitBtn.disabled = true;
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch(`/admin/repairs/${repairId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok) {
            console.log('Repair updated successfully:', data);
            
            // ALERT SUCCESS DAN TUTUP MODAL
            alert('✅ Repair updated successfully!');
            closeModal();
            
        } else {
            throw new Error(data.message || 'Failed to update repair');
        }
    } catch (error) {
        console.error('Error updating repair:', error);
        alert('❌ Error: ' + error.message);
    } finally {
        // Restore button state
        submitBtn.textContent = originalBtnText;
        submitBtn.disabled = false;
    }
});

// =============================================
// GLOBAL PAYMENTS FUNCTIONALITY
// =============================================

// Event Delegation untuk payment buttons
document.addEventListener('click', function(e) {
    // Edit Payment Button
    if (e.target.classList.contains('edit-payment')) {
        e.preventDefault();
        const paymentId = e.target.getAttribute('data-id');
        console.log('✏️ Edit payment clicked:', paymentId);
        openPaymentEditModal(paymentId, e.target.closest('tr'));
    }
    
    // Delete Payment Button
    if (e.target.classList.contains('delete-payment')) {
        e.preventDefault();
        const paymentId = e.target.getAttribute('data-id');
        console.log('🗑️ Delete payment clicked:', paymentId);
        deletePayment(paymentId, e.target.closest('tr'));
    }
});

// Search functionality untuk payments
function initializePaymentsSearch() {
    const paymentSearch = document.getElementById('paymentSearch');
    const paymentsTableBody = document.getElementById('paymentsTableBody');
    
    if (paymentSearch && paymentsTableBody) {
        paymentSearch.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            const rows = paymentsTableBody.querySelectorAll('tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
            
            // Update count
            updatePaymentCount();
        });
        console.log('🔍 Payments search initialized');
    }
}

// Open Payment Edit Modal
function openPaymentEditModal(paymentId, row) {
    if (!row) {
        console.error('Row not found');
        return;
    }
    
    const statusBadge = row.querySelector('.status-badge');
    const amountCell = row.querySelector('.amount-cell');
    
    if (!statusBadge || !amountCell) {
        console.error('Required cells not found');
        return;
    }
    
    // Get current values
    const currentStatus = statusBadge.textContent.trim().toLowerCase();
    const currentAmount = amountCell.textContent.trim().replace(/[^0-9]/g, '') || '';
    
    // Get payment method from row (column index 3)
    const methodCell = row.querySelector('td:nth-child(4)');
    const currentMethod = methodCell ? methodCell.textContent.trim().toLowerCase().replace(/\s+/g, '_') : 'cash';
    
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

// Delete Payment
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
            }
        });

        if (response.ok) {
            console.log('Payment deleted successfully');
            row.remove();
            updatePaymentCount();
            alert('✅ Payment deleted successfully!');
        } else {
            const data = await response.json();
            throw new Error(data.message || 'Failed to delete payment');
        }
    } catch (error) {
        console.error('Error deleting payment:', error);
        alert('❌ Error: ' + error.message);
    }
}

// Update payment count
function updatePaymentCount() {
    const paymentsTableBody = document.getElementById('paymentsTableBody');
    if (!paymentsTableBody) return;
    
    const visibleRows = paymentsTableBody.querySelectorAll('tr:not([style*="display: none"])');
    const countElement = document.querySelector('#payments .text-sm.text-gray-600');
    if (countElement) {
        countElement.textContent = `Total: ${visibleRows.length} payments`;
    }
}

// Payment Modal functionality
document.getElementById('closePaymentModal')?.addEventListener('click', closePaymentModal);
document.getElementById('cancelPaymentEdit')?.addEventListener('click', closePaymentModal);

document.addEventListener('click', function(e) {
    if (e.target.id === 'editPaymentModal') {
        closePaymentModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('editPaymentModal') && !document.getElementById('editPaymentModal').classList.contains('hidden')) {
        closePaymentModal();
    }
});

function closePaymentModal() {
    const modal = document.getElementById('editPaymentModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Payment Form submission - PERBAIKAN: ALERT SAJA
document.getElementById('editPaymentForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const paymentId = document.getElementById('editPaymentId').value;
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.textContent;
    
    // Gunakan JSON untuk payment
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

        if (response.ok) {
            console.log('Payment updated successfully:', data);
            
            alert('✅ Payment updated successfully!');
            closePaymentModal();
            
        } else {
            throw new Error(data.message || 'Failed to update payment');
        }
    } catch (error) {
        console.error('Error updating payment:', error);
        alert('❌ Error: ' + error.message);
    } finally {
        // Restore button state
        submitBtn.textContent = originalBtnText;
        submitBtn.disabled = false;
    }
});

// Initialize default tab
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Admin dashboard initialized');
    const firstBtn = document.querySelector('.tab-btn');
    if (firstBtn) {
        firstBtn.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
    }
});

// =============================================
// GLOBAL LOYALTY FUNCTIONALITY
// =============================================

// Event Delegation untuk loyalty buttons
document.addEventListener('click', function(e) {
    // Edit Loyalty Button
    if (e.target.classList.contains('edit-loyalty')) {
        e.preventDefault();
        const loyaltyId = e.target.getAttribute('data-id');
        console.log('✏️ Edit loyalty clicked:', loyaltyId);
        openLoyaltyEditModal(loyaltyId, e.target.closest('tr'));
    }
    
    // Delete Loyalty Button
    if (e.target.classList.contains('delete-loyalty')) {
        e.preventDefault();
        const loyaltyId = e.target.getAttribute('data-id');
        console.log('🗑️ Delete loyalty clicked:', loyaltyId);
        deleteLoyalty(loyaltyId, e.target.closest('tr'));
    }
});

// Search functionality untuk loyalty
function initializeLoyaltySearch() {
    const loyaltySearch = document.getElementById('loyaltySearch');
    const loyaltyTableBody = document.getElementById('loyaltyTableBody');
    
    if (loyaltySearch && loyaltyTableBody) {
        loyaltySearch.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            const rows = loyaltyTableBody.querySelectorAll('tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
            
            // Update count
            updateLoyaltyCount();
        });
        console.log('🔍 Loyalty search initialized');
    }
}

// Open Loyalty Edit Modal
function openLoyaltyEditModal(loyaltyId, row) {
    if (!row) {
        console.error('Row not found');
        return;
    }
    
    const statusBadge = row.querySelector('.status-badge');
    const rewardValueCell = row.querySelector('.reward-value-cell');
    const pointsCell = row.querySelector('.points-cell');
    
    if (!statusBadge || !rewardValueCell || !pointsCell) {
        console.error('Required cells not found');
        return;
    }
    
    // Get current values
    const currentStatus = statusBadge.textContent.trim().toLowerCase();
    const currentPoints = pointsCell.textContent.trim().replace(/[^0-9]/g, '') || '';
    
    // Get reward type from row (column index 2)
    const typeCell = row.querySelector('td:nth-child(3)');
    const currentType = typeCell ? typeCell.textContent.trim().toLowerCase() : 'discount';
    
    // Get reward value
    let currentValue = rewardValueCell.textContent.trim();
    
    console.log('Current loyalty values:', { currentStatus, currentType, currentPoints, currentValue });
    
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

// Delete Loyalty
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
            }
        });

        if (response.ok) {
            console.log('Loyalty reward deleted successfully');
            row.remove();
            updateLoyaltyCount();
            alert('✅ Loyalty reward deleted successfully!');
        } else {
            const data = await response.json();
            throw new Error(data.message || 'Failed to delete loyalty reward');
        }
    } catch (error) {
        console.error('Error deleting loyalty reward:', error);
        alert('❌ Error: ' + error.message);
    }
}

// Update loyalty count
function updateLoyaltyCount() {
    const loyaltyTableBody = document.getElementById('loyaltyTableBody');
    if (!loyaltyTableBody) return;
    
    const visibleRows = loyaltyTableBody.querySelectorAll('tr:not([style*="display: none"])');
    const countElement = document.querySelector('#loyalty .text-sm.text-gray-600');
    if (countElement) {
        countElement.textContent = `Total: ${visibleRows.length} rewards`;
    }
}

// Loyalty Modal functionality
document.getElementById('closeLoyaltyModal')?.addEventListener('click', closeLoyaltyModal);
document.getElementById('cancelLoyaltyEdit')?.addEventListener('click', closeLoyaltyModal);

document.addEventListener('click', function(e) {
    if (e.target.id === 'editLoyaltyModal') {
        closeLoyaltyModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('editLoyaltyModal') && !document.getElementById('editLoyaltyModal').classList.contains('hidden')) {
        closeLoyaltyModal();
    }
});

function closeLoyaltyModal() {
    const modal = document.getElementById('editLoyaltyModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Loyalty Form submission
document.getElementById('editLoyaltyForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const loyaltyId = document.getElementById('editLoyaltyId').value;
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.textContent;
    
    // Gunakan JSON untuk loyalty
    const formData = {
        status: document.getElementById('editLoyaltyStatus').value,
        reward_type: document.getElementById('editLoyaltyType').value,
        points_used: document.getElementById('editLoyaltyPoints').value,
        reward_value: document.getElementById('editLoyaltyValue').value,
        _method: 'PUT'
    };
    
    console.log('Submitting loyalty data:', formData);
    
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

        if (response.ok) {
            console.log('Loyalty reward updated successfully:', data);
            
            // ALERT SUCCESS DAN TUTUP MODAL
            alert('✅ Loyalty reward updated successfully!');
            closeLoyaltyModal();
            
        } else {
            throw new Error(data.message || 'Failed to update loyalty reward');
        }
    } catch (error) {
        console.error('Error updating loyalty reward:', error);
        alert('❌ Error: ' + error.message);
    } finally {
        // Restore button state
        submitBtn.textContent = originalBtnText;
        submitBtn.disabled = false;
    }
});
</script>
@endsection