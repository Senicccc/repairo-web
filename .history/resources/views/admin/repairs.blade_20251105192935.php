<section id="repairs" class="tab-section">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Repairs Management</h2>
        <button id="addRepairBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
            <span class="mr-2">+</span> Add Repair
        </button>
    </div>
    
    <div class="mb-4">
        <input type="text" placeholder="Search repairs..." 
               class="border border-gray-300 px-4 py-2 rounded-lg w-1/2 search" data-table="repairs">
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
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
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($repairs as $repair)
                    <tr class="hover:bg-gray-50">
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

<script>
document.addEventListener('DOMContentLoaded', () => {
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
                alert('Failed to delete repair.');
            }
        });
    });
});
</script>
