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
// GLOBAL ADMIN FUNCTIONS - PERBAIKAN LENGKAP
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
                
                // Re-initialize event listeners setelah content berubah
                initializeEventListeners();
            }, 150);
            updateActiveTab(btn);
        })
        .catch(error => console.error('Error loading section:', error));
}

function updateActiveTab(activeBtn) {
    // Remove active class dari semua tab
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
        btn.classList.add('text-gray-600', 'hover:bg-blue-50', 'hover:text-blue-600');
    });
    
    // Add active class ke tab yang aktif
    activeBtn.classList.remove('text-gray-600', 'hover:bg-blue-50', 'hover:text-blue-600');
    activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
}

// =============================================
// EVENT LISTENERS GLOBAL - PERBAIKAN
// =============================================

function initializeEventListeners() {
    console.log('🔄 Initializing event listeners...');
    
    // Event delegation untuk SEMUA buttons
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
    });
}

// =============================================
// REPAIR FUNCTIONS 
// =============================================

// Open Edit Modal untuk Repair
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
    
    // Get current values - sesuaikan dengan index kolom Anda
    const statusCell = cells[4]; // Adjust index sesuai struktur tabel
    const technicianCell = cells[5]; // Adjust index
    const costCell = cells[6]; // Adjust index
    
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

// Delete Repair - PERBAIKAN: Gunakan method DELETE yang benar
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

// Update Repair - PERBAIKAN: Gunakan PUT method dengan JSON
async function updateRepair(formData) {
    const repairId = formData.get('repair_id');
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch(`/admin/repairs/${repairId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                status: formData.get('status'),
                technician: formData.get('technician'),
                cost: formData.get('cost')
            })
        });

        const data = await response.json();

        if (data.success) {
            console.log('Repair updated successfully:', data);
            showAlert('✅ Repair updated successfully!', 'success');
            closeModal();
            
            // Refresh section untuk update data terbaru
            const activeTab = document.querySelector('.tab-btn.bg-blue-600');
            if (activeTab) {
                showSection('repairs', activeTab);
            }
        } else {
            throw new Error(data.message || 'Failed to update repair');
        }
    } catch (error) {
        console.error('Error updating repair:', error);
        showAlert('❌ Error: ' + error.message, 'error');
    }
}

// =============================================
// PAYMENT FUNCTIONS - PERBAIKAN
// =============================================

// Open Payment Edit Modal
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
    
    // Get current values - sesuaikan index
    const statusCell = cells[3]; // Adjust index
    const methodCell = cells[4]; // Adjust index  
    const amountCell = cells[5]; // Adjust index
    
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

// Delete Payment - PERBAIKAN
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

// Update Payment - PERBAIKAN
async function updatePayment(formData) {
    const paymentId = formData.get('payment_id');
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch(`/admin/payments/${paymentId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                status: formData.get('status'),
                payment_method: formData.get('payment_method'),
                amount: formData.get('amount')
            })
        });

        const data = await response.json();

        if (data.success) {
            console.log('Payment updated successfully:', data);
            showAlert('✅ Payment updated successfully!', 'success');
            closePaymentModal();
            
            // Refresh section
            const activeTab = document.querySelector('.tab-btn.bg-blue-600');
            if (activeTab) {
                showSection('payments', activeTab);
            }
        } else {
            throw new Error(data.message || 'Failed to update payment');
        }
    } catch (error) {
        console.error('Error updating payment:', error);
        showAlert('❌ Error: ' + error.message, 'error');
    }
}

// =============================================
// LOYALTY REWARDS FUNCTIONALITY
// =============================================

// Initialize loyalty functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeLoyaltyEventListeners();
    initializeLoyaltySearch();
});

// Event listeners untuk loyalty
function initializeLoyaltyEventListeners() {
    console.log('🔍 Initializing loyalty event listeners...');
    
    // Event delegation untuk loyalty buttons
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

    // Loyalty Modal handlers
    document.getElementById('closeLoyaltyModal')?.addEventListener('click', closeLoyaltyModal);
    document.getElementById('cancelLoyaltyEdit')?.addEventListener('click', closeLoyaltyModal);
    
    // Loyalty Form submission
    document.getElementById('editLoyaltyForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        submitLoyaltyEdit();
    });
}

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

// Open Loyalty Edit Modal - PERBAIKAN: Ambil data dari row yang benar
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
    
    // Get current values - sesuaikan dengan struktur tabel
    // [ID, User, Reward Type, Reward Value, Points Used, Code, Status, Actions]
    const statusCell = cells[6]; // Status di kolom ke-7
    const typeCell = cells[2];   // Reward Type di kolom ke-3
    const pointsCell = cells[4]; // Points Used di kolom ke-5
    const valueCell = cells[3];  // Reward Value di kolom ke-4
    
    // Get values dari cells
    const currentStatus = statusCell.querySelector('.status-badge')?.textContent.trim().toLowerCase() || 'claimed';
    const currentType = typeCell.textContent.trim().toLowerCase() || 'discount';
    const currentPoints = pointsCell.textContent.trim().replace(/[^0-9]/g, '') || '';
    
    // Get reward value - handle format currency untuk discount
    let currentValue = valueCell.textContent.trim();
    if (currentType === 'discount' && currentValue.includes('Rp')) {
        // Extract numeric value dari format currency "Rp 50.000"
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

// Delete Loyalty Reward - PERBAIKAN: Method yang benar
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
            // Remove row dari table
            row.remove();
            // Update counter
            updateLoyaltyCount();
            // Show success message
            showAlert('✅ Loyalty reward deleted successfully!', 'success');
        } else {
            throw new Error(data.message || 'Failed to delete loyalty reward');
        }
    } catch (error) {
        console.error('Error deleting loyalty reward:', error);
        showAlert('❌ Error: ' + error.message, 'error');
    }
}

// Submit Loyalty Edit - PERBAIKAN: Gunakan PUT method
async function submitLoyaltyEdit() {
    const loyaltyId = document.getElementById('editLoyaltyId').value;
    const submitBtn = document.querySelector('#editLoyaltyForm button[type="submit"]');
    const originalBtnText = submitBtn.textContent;
    
    // Prepare data
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
            method: 'POST', // Laravel doesn't support PUT directly in forms
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
            
            // Refresh the loyalty section to show updated data
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
}

// Close Loyalty Modal
function closeLoyaltyModal() {
    const modal = document.getElementById('editLoyaltyModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
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

// Alert helper function
function showAlert(message, type = 'info') {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.custom-alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create alert element
    const alert = document.createElement('div');
    alert.className = `custom-alert fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white font-medium transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    alert.textContent = message;
    
    document.body.appendChild(alert);
    
    // Animate in
    setTimeout(() => {
        alert.style.transform = 'translateX(0)';
        alert.style.opacity = '1';
    }, 100);
    
    // Remove alert after 3 seconds
    setTimeout(() => {
        alert.style.transform = 'translateX(100%)';
        alert.style.opacity = '0';
        setTimeout(() => {
            alert.remove();
        }, 300);
    }, 3000);
}

// Close modal on background click
document.addEventListener('click', function(e) {
    if (e.target.id === 'editLoyaltyModal') {
        closeLoyaltyModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const loyaltyModal = document.getElementById('editLoyaltyModal');
        if (loyaltyModal && !loyaltyModal.classList.contains('hidden')) {
            closeLoyaltyModal();
        }
    }
});

// =============================================
// HELPER FUNCTIONS
// =============================================

function showAlert(message, type = 'info') {
    // Buat alert element
    const alert = document.createElement('div');
    alert.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white font-medium ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    alert.textContent = message;
    
    document.body.appendChild(alert);
    
    // Hapus alert setelah 3 detik
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

// Initialize saat page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Admin dashboard initialized');
    initializeEventListeners();
    
    // Set active tab pertama
    const firstBtn = document.querySelector('.tab-btn');
    if (firstBtn) {
        firstBtn.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
    }
});
</script>
@endsection