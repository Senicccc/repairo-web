@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Technician Dashboard</h2>

    {{-- All Repairs --}}
    <div class="mb-6">
        <h3 class="text-xl font-semibold mb-2">All Repairs</h3>
        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-3 py-2 border">ID</th>
                        <th class="px-3 py-2 border">Tracking ID</th>
                        <th class="px-3 py-2 border">Customer Name</th>
                        <th class="px-3 py-2 border">Phone</th>
                        <th class="px-3 py-2 border">Brand</th>
                        <th class="px-3 py-2 border">Model</th>
                        <th class="px-3 py-2 border">IMEI</th>
                        <th class="px-3 py-2 border">Complaint</th>
                        <th class="px-3 py-2 border">Technician</th>
                        <th class="px-3 py-2 border">Sparepart</th>
                        <th class="px-3 py-2 border">Diagnosis</th>
                        <th class="px-3 py-2 border">Cost</th>
                        <th class="px-3 py-2 border">Status</th>
                        <th class="px-3 py-2 border">Created At</th>
                        <th class="px-3 py-2 border">Updated At</th>
                        <th class="px-3 py-2 border">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($repairs as $r)
                    <tr class="text-center" data-repair-id="{{ $r->id }}" 
                        data-sparepart="{{ e($r->sparepart) }}" data-diagnosis="{{ e($r->diagnosis) }}" data-status="{{ $r->status }}" data-cost="{{ $r->cost }}">
                        <td class="border px-3 py-2">{{ $r->id }}</td>
                        <td class="border px-3 py-2">{{ $r->tracking_id }}</td>
                        <td class="border px-3 py-2">{{ $r->customer_name ?? ($r->user->name ?? '-') }}</td>
                        <td class="border px-3 py-2">{{ $r->phone ?? ($r->user->phone ?? '-') }}</td>
                        <td class="border px-3 py-2">{{ $r->phone_brand }}</td>
                        <td class="border px-3 py-2">{{ $r->phone_model }}</td>
                        <td class="border px-3 py-2">{{ $r->imei ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $r->complaint }}</td>
                        <td class="border px-3 py-2">{{ $r->technician ?? ($r->technicianUser->name ?? '-') }}</td>
                        <td class="border px-3 py-2">{{ $r->sparepart ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $r->diagnosis ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $r->cost ?? 0 }}</td>
                        <td class="border px-3 py-2">{{ ucfirst($r->status) }}</td>
                        <td class="border px-3 py-2">{{ $r->created_at?->format('Y-m-d H:i') }}</td>
                        <td class="border px-3 py-2">{{ $r->updated_at?->format('Y-m-d H:i') }}</td>
                        <td class="border px-3 py-2">
                            <button onclick="openJobModal({{ $r->id }})" class="bg-green-600 text-white px-3 py-1 rounded">Update</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="16" class="p-4 text-center">No repairs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<dialog id="jobModal" class="modal">
    <form method="POST" id="jobForm" class="p-6 bg-white rounded shadow-md w-[480px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Update Job</h3>
        <input type="hidden" name="repair_id" id="modal_repair_id">
        <textarea name="sparepart" placeholder="Sparepart used" class="w-full border p-2 mb-3 rounded"></textarea>
        <textarea name="diagnosis" placeholder="Final Diagnosis" class="w-full border p-2 mb-3 rounded"></textarea>
        <input type="number" step="0.01" name="cost" placeholder="Cost" class="w-full border p-2 mb-3 rounded">
        <select name="status" class="w-full border p-2 mb-3 rounded">
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="finished">Finished</option>
            <option value="cancelled">Cancelled</option>
        </select>
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('jobModal').close()" 
                    class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">Save</button>
        </div>
    </form>
</dialog>

<script>
    function openJobModal(id) {
        const activeRow = document.querySelector(`[data-repair-id='${id}']`);
        const form = document.getElementById('jobForm');
        form.action = `/technician/repairs/${id}/update`;
        document.getElementById('modal_repair_id').value = id;

        form.querySelector("textarea[name='sparepart']").value = activeRow?.getAttribute('data-sparepart') || '';
        form.querySelector("textarea[name='diagnosis']").value = activeRow?.getAttribute('data-diagnosis') || '';
        form.querySelector("select[name='status']").value = activeRow?.getAttribute('data-status') || 'pending';
        form.querySelector("input[name='cost']").value = activeRow?.getAttribute('data-cost') || '';
        
        document.getElementById('jobModal').showModal();
    }
</script>
@endsection
