{{-- CSRF Meta --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<section id="repairs" class="tab-section p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Repairs Management</h2>
    </div>
    
    {{-- Search --}}
    <div class="mb-4">
        <input type="text" id="repairSearch" placeholder="Search repairs..." 
               class="border border-gray-300 px-4 py-2 rounded-lg w-1/2">
    </div>
    
    {{-- Table wrapper --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200" id="repairsTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tracking ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Technician</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="repairsTableBody">
                @foreach ($repairs as $repair)
                    <tr data-id="{{ $repair->id }}" class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $repair->tracking_id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $repair->user->name ?? ($repair->customer_name ?? 'Unregistered User') }}<br>
                            <small class="text-gray-500">{{ $repair->phone ?? '-' }}</small>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $repair->phone_brand ?? '-' }} {{ $repair->phone_model ?? '' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full status-badge
                                {{ $repair->status === 'finished' ? 'bg-green-100 text-green-800' : 
                                   ($repair->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                   ($repair->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($repair->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                                {{ ucfirst(str_replace('_', ' ', $repair->status ?? 'unknown')) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 technician-cell">{{ $repair->technician ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 cost-cell">
                            {{ $repair->cost ? 'Rp ' . number_format($repair->cost, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium flex gap-3">
                            <button class="text-blue-600 hover:text-blue-900 edit-repair" data-id="{{ $repair->id }}">Edit</button>
                            <button class="text-red-600 hover:text-red-900 delete-repair" data-id="{{ $repair->id }}">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4 text-sm text-gray-600">Total: {{ $repairs->count() }} repairs</div>
</section>

{{-- Edit Modal --}}
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
document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('editModal');
    const editForm = document.getElementById('editRepairForm');
    const closeModalBtn = document.getElementById('closeModal');
    const cancelEditBtn = document.getElementById('cancelEdit');
    const repairsTableBody = document.getElementById('repairsTableBody');
    const repairSearch = document.getElementById('repairSearch');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let currentEditRow = null;

    
    // Debug logging
    console.log('Repairs management script loaded');
    console.log('CSRF Token:', csrfToken);

    // Search functionality
    repairSearch.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        const rows = repairsTableBody.querySelectorAll('tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });

    // Event delegation for Edit buttons
    repairsTableBody.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-repair');
        const deleteBtn = e.target.closest('.delete-repair');

        if (editBtn) {
            e.preventDefault();
            const repairId = editBtn.getAttribute('data-id');
            console.log('Edit button clicked for repair ID:', repairId);
            openEditModal(repairId, editBtn.closest('tr'));
        }

        if (deleteBtn) {
            e.preventDefault();
            const repairId = deleteBtn.getAttribute('data-id');
            console.log('Delete button clicked for repair ID:', repairId);
            deleteRepair(repairId, deleteBtn.closest('tr'));
        }
    });

    // Open edit modal
    function openEditModal(repairId, row) {
        console.log('Opening edit modal for repair:', repairId);
        
        currentEditRow = row;
        
        // Get current values from the row
        const statusBadge = row.querySelector('.status-badge');
        const technicianCell = row.querySelector('.technician-cell');
        const costCell = row.querySelector('.cost-cell');
        
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
        editModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Delete repair function
    async function deleteRepair(repairId, row) {
        console.log('Attempting to delete repair:', repairId);
        
        if (!confirm('Are you sure you want to delete this repair? This action cannot be undone.')) {
            return;
        }

        try {
            const response = await fetch(`/admin/repairs/${repairId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                console.log('Repair deleted successfully');
                // Remove row from table
                row.remove();
                
                // Update total count
                updateRepairCount();
                
                alert('Repair deleted successfully!');
            } else {
                throw new Error(data.message || 'Failed to delete repair');
            }
        } catch (error) {
            console.error('Error deleting repair:', error);
            alert('Error: ' + error.message);
        }
    }

    // Update repair count
    function updateRepairCount() {
        const rows = repairsTableBody.querySelectorAll('tr:not([style*="display: none"])');
        const countElement = document.querySelector('.text-sm.text-gray-600');
        if (countElement) {
            countElement.textContent = `Total: ${rows.length} repairs`;
        }
    }

    // Close modal handlers
    function closeModal() {
        editModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        currentEditRow = null;
    }

    closeModalBtn.addEventListener('click', closeModal);
    cancelEditBtn.addEventListener('click', closeModal);

    // Click outside modal to close
    editModal.addEventListener('click', function(e) {
        if (e.target === editModal) {
            closeModal();
        }
    });

    // Escape key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !editModal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Form submission
    editForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const repairId = document.getElementById('editRepairId').value;
        const formData = new FormData(editForm);
        
        console.log('Submitting form for repair:', repairId);
        console.log('Form data:', Object.fromEntries(formData));

        try {
            const response = await fetch(`/admin/repairs/${repairId}`, {
                method: 'POST', // Laravel will use _method=PUT
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                console.log('Repair updated successfully:', data);
                
                // Update the row with new data
                if (currentEditRow) {
                    const statusBadge = currentEditRow.querySelector('.status-badge');
                    const technicianCell = currentEditRow.querySelector('.technician-cell');
                    const costCell = currentEditRow.querySelector('.cost-cell');
                    
                    // Update status
                    const newStatus = document.getElementById('editStatus').value;
                    const statusText = newStatus.replace('_', ' ');
                    statusBadge.textContent = statusText.charAt(0).toUpperCase() + statusText.slice(1);
                    
                    // Update status badge classes
                    statusBadge.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full status-badge ' +
                        (newStatus === 'finished' ? 'bg-green-100 text-green-800' : 
                         newStatus === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                         newStatus === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                         newStatus === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800');
                    
                    // Update technician
                    const newTechnician = document.getElementById('editTechnician').value;
                    technicianCell.textContent = newTechnician || '-';
                    
                    // Update cost
                    const newCost = document.getElementById('editCost').value;
                    costCell.textContent = newCost ? 'Rp ' + parseInt(newCost).toLocaleString('id-ID') : '-';
                }
                
                closeModal();
                alert('Repair updated successfully!');
                
            } else {
                throw new Error(data.message || 'Failed to update repair');
            }
        } catch (error) {
            console.error('Error updating repair:', error);
            alert('Error: ' + error.message);
        }
    });
});
</script>