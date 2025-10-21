@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Technician Dashboard</h2>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full border border-gray-200 text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-3 py-2 border">Tracking ID</th>
                    <th class="px-3 py-2 border">Customer</th>
                    <th class="px-3 py-2 border">Phone</th>
                    <th class="px-3 py-2 border">Brand</th>
                    <th class="px-3 py-2 border">Model</th>
                    <th class="px-3 py-2 border">Complaint</th>
                    <th class="px-3 py-2 border">Technician</th>
                    <th class="px-3 py-2 border">Status</th>
                    <th class="px-3 py-2 border">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($repairs as $repair)
                <tr class="text-center">
                    <td class="border px-3 py-2">{{ $repair->tracking_id }}</td>
                    <td class="border px-3 py-2">{{ $repair->user->name ?? '-' }}</td>
                    <td class="border px-3 py-2">{{ $repair->phone }}</td>
                    <td class="border px-3 py-2">{{ $repair->phone_brand }}</td>
                    <td class="border px-3 py-2">{{ $repair->phone_model }}</td>
                    <td class="border px-3 py-2">{{ $repair->complaint }}</td>
                    <td class="border px-3 py-2">{{ $repair->technician ?? '-' }}</td>
                    <td class="border px-3 py-2">{{ ucfirst($repair->status) }}</td>
                    <td class="border px-3 py-2">
                        @if (!$repair->technician)
                        <form method="POST" action="{{ url('technician.claim'.$repair->id) }}">
                            @csrf
                            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Take Job</button>
                        </form>
                        @else
                        <button onclick="openJobModal({{ $repair->id }})" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Update</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<dialog id="jobModal" class="modal">
    <form method="POST" id="jobForm" class="p-6 bg-white rounded shadow-md w-[480px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Update Job</h3>
        <input type="hidden" name="repair_id" id="modal_repair_id">
        <textarea name="sparepart" placeholder="Sparepart used" class="w-full border p-2 mb-3 rounded"></textarea>
        <textarea name="diagnosis" placeholder="Final Diagnosis" class="w-full border p-2 mb-3 rounded"></textarea>
        <select name="status" class="w-full border p-2 mb-3 rounded">
            <option value="in_progress">In Progress</option>
            <option value="finished">Finished</option>
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
        const tr = document.querySelector(`tr[data-repair-id='${id}']`);
        const form = document.getElementById('jobForm');
        form.action = `/technician/update/${id}`;
        document.getElementById('modal_repair_id').value = id;
        document.getElementById('jobModal').showModal();
    }
</script>
@endsection
