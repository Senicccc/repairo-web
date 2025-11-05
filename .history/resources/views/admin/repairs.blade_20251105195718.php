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
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($repairs as $repair)
                    <tr data-id="{{ $repair->id }}" class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $repair->tracking_id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $repair->user->name ?? 'Unregistered User' }}<br>
                            <small class="text-gray-500">{{ $repair->phone ?? '-' }}</small>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $repair->phone_brand ?? '-' }} {{ $repair->phone_model ?? '' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $repair->status === 'finished' ? 'bg-green-100 text-green-800' : 
                                   ($repair->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                   ($repair->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($repair->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                                {{ ucfirst(str_replace('_', ' ', $repair->status ?? 'unknown')) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $repair->technician ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $repair->cost ? 'Rp ' . number_format($repair->cost, 0, ',', '.') : '-' }}</td>
                        <td class="px-6 py-4 text-sm font-medium flex gap-3">
                            <button class="text-blue-600 hover:text-blue-900 edit-repair">Edit</button>
                            <button class="text-red-600 hover:text-red-900 delete-repair">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4 text-sm text-gray-600">Total: {{ $repairs->count() }} repairs</div>
</section>

{{-- Edit Modal --}}
{{-- Edit Modal --}}
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg w-1/3 p-6 relative mx-auto">
        <h3 class="text-lg font-semibold mb-4">Edit Repair</h3>
        <form id="editRepairForm">
            @csrf
            @method('PUT')
            <input type="hidden" id="editRepairId" name="repair_id">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select id="editStatus" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="finished">Finished</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Technician</label>
                <input type="text" id="editTechnician" name="technician" class="mt-1 block w-full border-gray-300 rounded-md">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Cost</label>
                <input type="number" id="editCost" name="cost" class="mt-1 block w-full border-gray-300 rounded-md">
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" id="closeModal" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
            </div>
        </form>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const editModal = document.getElementById('editModal');
    const editForm = document.getElementById('editRepairForm');
    const closeModalBtn = document.getElementById('closeModal');
    const repairsTable = document.getElementById('repairsTable').querySelector('tbody');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // --- Search ---
    document.getElementById('repairSearch').addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        repairsTable.querySelectorAll('tr').forEach(row => {
            row.style.display = Array.from(row.cells).some(td => td.textContent.toLowerCase().includes(query))
                ? '' : 'none';
        });
    });

    // --- Event Delegation for Edit/Delete ---
    repairsTable.addEventListener('click', async (e) => {
        const row = e.target.closest('tr');
        if(!row) return;
        const id = row.dataset.id;

        // --- Edit ---
        if(e.target.classList.contains('edit-repair')){
            document.getElementById('editRepairId').value = id;
            document.getElementById('editStatus').value = row.querySelector('td:nth-child(4) span').textContent.trim().toLowerCase().replace(/\s+/g,'_');
            document.getElementById('editTechnician').value = row.querySelector('td:nth-child(5)').textContent.trim() === '-' ? '' : row.querySelector('td:nth-child(5)').textContent.trim();
            const costText = row.querySelector('td:nth-child(6)').textContent.trim().replace(/[^0-9]/g,'');
            document.getElementById('editCost').value = costText ? parseInt(costText) : '';
            editModal.classList.remove('hidden', 'invisible');
        }

        // --- Delete ---
        if(e.target.classList.contains('delete-repair')){
            if(!confirm('Are you sure you want to delete this repair?')) return;
            const res = await fetch(`/admin/repairs/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            });
            if(res.ok) location.reload();
            else {
                const data = await res.json();
                alert('Failed to delete repair: ' + (data.message || 'Unknown error'));
            }
        }
    });

    // --- Close Modal ---
    closeModalBtn.addEventListener('click', () => editModal.classList.add('hidden'));

    // --- Submit Edit ---
    editForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('editRepairId').value;
        const formData = new FormData(editForm);

        const res = await fetch(`/admin/repairs/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        });

        if(res.ok) location.reload();
        else {
            const data = await res.json();
            alert('Failed to update repair: ' + (data.message || 'Unknown error'));
        }
    });
});
</script>
