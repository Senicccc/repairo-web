@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Technician Dashboard</h2>

    {{-- Current Jobs --}}
    <div class="mb-6">
        <h3 class="text-xl font-semibold mb-2">Your Active Jobs <small class="text-sm text-gray-500">({{ $currentJobs->count() }} / 6)</small></h3>
        @if ($currentJobs->count() > 0)
            <div class="space-y-4">
                @foreach($currentJobs as $cj)
                <div class="bg-white p-4 rounded shadow" data-active-id="{{ $cj->id }}">
                    <p><strong>ID:</strong> {{ $cj->id }}</p>
                    <p><strong>Tracking:</strong> {{ $cj->tracking_id }}</p>
                    <p><strong>Customer:</strong> {{ $cj->customer_name ?? optional($cj->user)->name ?? '-' }}</p>
                    <p><strong>Brand:</strong> {{ $cj->phone_brand }}</p>
                    <p><strong>Model:</strong> {{ $cj->phone_model }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($cj->status) }}</p>
                    <button onclick="openJobModal({{ $cj->id }})" class="bg-green-600 text-white px-3 py-1 rounded mt-2">Update & Finish</button>
                </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 p-4 rounded">You have no active jobs. Pick one from available jobs.</div>
        @endif
    </div>

    {{-- Available Jobs --}}
    <div class="mb-6">
        <h3 class="text-xl font-semibold mb-2">Available Jobs</h3>
        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-3 py-2 border">ID</th>
                        <th class="px-3 py-2 border">Tracking</th>
                        <th class="px-3 py-2 border">Customer</th>
                        <th class="px-3 py-2 border">Brand</th>
                        <th class="px-3 py-2 border">Model</th>
                        <th class="px-3 py-2 border">Complaint</th>
                        <th class="px-3 py-2 border">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($availableJobs as $r)
                    <tr class="text-center">
                        <td class="border px-3 py-2">{{ $r->id }}</td>
                        <td class="border px-3 py-2">{{ $r->tracking_id }}</td>
                        <td class="border px-3 py-2">{{ $r->customer_name ?? optional($r->user)->name ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $r->phone_brand }}</td>
                        <td class="border px-3 py-2">{{ $r->phone_model }}</td>
                        <td class="border px-3 py-2">{{ $r->complaint }}</td>
                        <td class="border px-3 py-2">
                            <form method="POST" action="{{ route('technician.claim', $r->id) }}">
                                @csrf
                                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Take Job</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4">No available jobs</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Update --}}
<dialog id="jobModal" class="modal">
    <form method="POST" id="jobForm" class="p-6 bg-white rounded shadow-md w-[480px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Update Job</h3>
        <input type="hidden" name="repair_id" id="modal_repair_id">

        <div id="sparepartList">
            <div class="sparepart-item mb-3 border p-2 rounded">
                <input type="text" placeholder="Search Sparepart..." class="searchSparepart w-full border p-2 rounded mb-2">
                <select name="spareparts[0][sparepart_id]" class="w-full border p-2 rounded sparepartSelect"></select>
                <input type="number" name="spareparts[0][quantity]" placeholder="Qty" class="w-full border p-2 rounded mt-1" value="1">
                <select name="spareparts[0][source]" class="w-full border p-2 rounded mt-1">
                    <option value="in_store">In Store</option>
                    <option value="customer_owned">Customer Owned</option>
                    <option value="external_purchase">External Purchase</option>
                </select>
                <input type="number" step="0.01" name="spareparts[0][price]" placeholder="Price" class="w-full border p-2 rounded mt-1">
            </div>
        </div>
        <button type="button" id="addSparepart" class="bg-blue-500 text-white px-3 py-1 rounded mb-3">+ Add Sparepart</button>

        <textarea name="diagnosis" placeholder="Final Diagnosis" class="w-full border p-2 mb-3 rounded"></textarea>
        <select name="status" class="w-full border p-2 mb-3 rounded">
            <option value="in_progress">In Progress</option>
            <option value="finished">Finished</option>
        </select>
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('jobModal').close()" class="px-3 py-1 bg-gray-300 rounded">Cancel</button>
            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded">Save</button>
        </div>
    </form>
</dialog>

<script>
let sparepartIndex = 1;
document.getElementById('addSparepart').addEventListener('click', function() {
    const div = document.createElement('div');
    div.classList.add('sparepart-item', 'mb-3', 'border', 'p-2', 'rounded');
    div.innerHTML = `
        <input type="text" placeholder="Search Sparepart..." class="searchSparepart w-full border p-2 rounded mb-2">
        <select name="spareparts[${sparepartIndex}][sparepart_id]" class="w-full border p-2 rounded sparepartSelect"></select>
        <input type="number" name="spareparts[${sparepartIndex}][quantity]" placeholder="Qty" class="w-full border p-2 rounded mt-1" value="1">
        <select name="spareparts[${sparepartIndex}][source]" class="w-full border p-2 rounded mt-1">
            <option value="in_store">In Store</option>
            <option value="customer_owned">Customer Owned</option>
            <option value="external_purchase">External Purchase</option>
        </select>
        <input type="number" step="0.01" name="spareparts[${sparepartIndex}][price]" placeholder="Price" class="w-full border p-2 rounded mt-1">
    `;
    document.getElementById('sparepartList').appendChild(div);
    sparepartIndex++;
    attachSearch(div.querySelector('.searchSparepart'), div.querySelector('.sparepartSelect'));
});

function openJobModal(id) {
    const form = document.getElementById('jobForm');
    form.action = `/technician/repairs/${id}/update`;
    document.getElementById('modal_repair_id').value = id;
    document.getElementById('jobModal').showModal();
}

function attachSearch(input, select) {
    input.addEventListener('input', async function() {
        const q = input.value.trim();
        if (q.length < 2) return;
        const res = await fetch(`/spareparts/search?q=${q}`);
        const data = await res.json();
        select.innerHTML = data.map(s => `<option value="${s.id}">${s.name} (${s.category}) - Rp${s.price}</option>`).join('');
    });
}

document.querySelectorAll('.searchSparepart').forEach((input, i) => {
    const select = document.querySelectorAll('.sparepartSelect')[i];
    attachSearch(input, select);
});
</script>
@endsection
