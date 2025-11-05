<section id="repairs" class="tab-section">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Repairs Management</h2>
    </div>
    
    <div class="mb-4">
        <input type="text" placeholder="Search repairs..." 
               class="border border-gray-300 px-4 py-2 rounded-lg w-1/2 search" data-table="repairs">
    </div>
    
    {{-- Wrapper biar tabel bisa digeser kiri-kanan --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
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
            <tbody class="bg-white divide-y divide-gray-200" id="repairs-table">
                @foreach ($repairs as $repair)
                    <tr class="hover:bg-gray-50" data-id="{{ $repair->id }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                            {{ $repair->tracking_id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $repair->user->name ?? 'Unregistered User' }}<br>
                            <small class="text-gray-500">{{ $repair->phone ?? '-' }}</small>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $repair->phone_brand ?? '-' }} {{ $repair->phone_model ?? '' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $repair->status === 'finished' ? 'bg-green-100 text-green-800' : 
                                   ($repair->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                   ($repair->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($repair->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                                {{ ucfirst(str_replace('_', ' ', $repair->status ?? 'unknown')) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $repair->technician ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $repair->cost ? 'Rp ' . number_format($repair->cost, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-3">
                            <button class="text-blue-600 hover:text-blue-900 edit-repair" data-id="{{ $repair->id }}">Edit</button>
                            <button class="text-red-600 hover:text-red-900 delete-repair" data-id="{{ $repair->id }}">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4 text-sm text-gray-600">
        Total: {{ $repairs->count() }} repairs
    </div>
</section>

{{-- Edit Modal --}}
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white rounded-lg w-1/3 p-6 relative">
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

    // Open Edit Modal
    document.querySelectorAll('.edit-repair').forEach(button => {
        button.addEventListener('click', () => {
            const row = button.closest('tr');
            const id = row.dataset.id;

            document.getElementById('editRepairId').value = id;
            document.getElementById('editStatus').value = row.querySelector('td:nth-child(4) span').textContent.trim().toLowerCase().replace(' ', '_');
            document.getElementById('editTechnician').value = row.querySelector('td:nth-child(5)').textContent.trim() === '-' ? '' : row.querySelector('td:nth-child(5)').textContent.trim();
            const costText = row.querySelector('td:nth-child(6)').textContent.trim().replace(/[^0-9]/g,'');
            document.getElementById('editCost').value = costText ? parseInt(costText) : '';

            editModal.classList.remove('hidden');
        });
    });

    closeModalBtn.addEventListener('click', () => editModal.classList.add('hidden'));

    // Submit Edit via AJAX
    editForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const id = document.getElementById('editRepairId').value;
        const formData = new FormData(editForm);

        const res = await fetch(`/admin/repairs/${id}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json',
            },
            body: formData
        });

        if (res.ok) {
            location.reload();
        } else {
            const data = await res.json();
            alert('Failed to update repair: ' + (data.message || 'Unknown error'));
        }
    });

    // Delete Repair
    document.querySelectorAll('.delete-repair').forEach(button => {
        button.addEventListener('click', async () => {
            if (!confirm('Are you sure you want to delete this repair?')) return;

            const id = button.dataset.id;
            const res = await fetch(`/admin/repairs/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            if (res.ok) {
                location.reload();
            } else {
                const data = await res.json();
                alert('Failed to delete repair: ' + (data.message || 'Unknown error'));
            }
        });
    });
});
</script>
