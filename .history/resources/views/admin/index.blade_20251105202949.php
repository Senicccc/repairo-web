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
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Logout</button>
            </div>
        </header>

        <!-- Dynamic Content -->
        <div id="content-area" class="bg-white rounded-xl shadow-md p-6 transition-all duration-300">
            @include('admin.users')
        </div>
    </main>
</div>

{{-- GLOBAL EDIT MODAL --}}
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
                
                // Initialize repairs search jika section repairs
                if (section === 'repairs') {
                    initializeRepairsSearch();
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
            alert('Repair deleted successfully!');
        } else {
            const data = await response.json();
            throw new Error(data.message || 'Failed to delete repair');
        }
    } catch (error) {
        console.error('Error deleting repair:', error);
        alert('Error: ' + error.message);
    }
}

// Update repair count
function updateRepairCount() {
    const repairsTableBody = document.getElementById('repairsTableBody');
    if (!repairsTableBody) return;
    
    const visibleRows = repairsTableBody.querySelectorAll('tr:not([style*="display: none"])');
    const countElement = document.querySelector('.text-sm.text-gray-600');
    if (countElement) {
        countElement.textContent = `Total: ${visibleRows.length} repairs`;
    }
}

// Modal functionality
document.getElementById('closeModal')?.addEventListener('click', closeModal);
document.getElementById('cancelEdit')?.addEventListener('click', closeModal);

document.addEventListener('click', function(e) {
    if (e.target.id === 'editModal') {
        closeModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
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

// Form submission
document.getElementById('editRepairForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const repairId = document.getElementById('editRepairId').value;
    const formData = new FormData(this);
    
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

        if (response.ok) {
            console.log('Repair updated successfully');
            location.reload();
        } else {
            const data = await response.json();
            throw new Error(data.message || 'Failed to update repair');
        }
    } catch (error) {
        console.error('Error updating repair:', error);
        alert('Error: ' + error.message);
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
</script>
@endsection