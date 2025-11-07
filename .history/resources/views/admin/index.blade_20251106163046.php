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

<!-- Include All Modals -->
@include('admin.modals.edit-repair')
@include('admin.modals.edit-payment') 
@include('admin.modals.edit-loyalty')
@include('admin.modals.edit-sparepart')
@include('admin.modals.create-sparepart')

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
            
            // Initialize search functionality for the loaded section
            initializeSectionSearch(section);
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

// Initialize search functionality for sections
function initializeSectionSearch(section) {
    const searchInput = document.getElementById(`${section}Search`);
    const tableBody = document.getElementById(`${section}TableBody`);
    
    if (searchInput && tableBody) {
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            const rows = tableBody.querySelectorAll('tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
            
            // Update count
            updateSectionCount(section);
        });
        console.log(`🔍 ${section} search initialized`);
    }
}

function updateSectionCount(section) {
    const tableBody = document.getElementById(`${section}TableBody`);
    if (!tableBody) return;
    
    const visibleRows = tableBody.querySelectorAll('tr:not([style*="display: none"])');
    const countElement = document.querySelector(`#${section} .text-sm.text-gray-600`);
    if (countElement) {
        const label = section === 'spareparts' ? 'items' : section;
        countElement.textContent = `Total: ${visibleRows.length} ${label}`;
    }
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
            updateSectionCount('repairs');
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
            updateSectionCount('payments');
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
            updateSectionCount('loyalty');
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

// Open Edit Modal untuk Sparepart
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
    const currentCategory = categoryCell.textContent.trim() || 'Original';
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
            updateSectionCount('spareparts');
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