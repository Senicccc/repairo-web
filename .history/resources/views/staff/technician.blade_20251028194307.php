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
                <div class="bg-white p-4 rounded shadow" 
                     data-active-id="{{ $cj->id }}" 
                     data-sparepart="{{ $cj->sparepart ?? '' }}" 
                     data-diagnosis="{{ $cj->diagnosis ?? '' }}" 
                     data-status="{{ $cj->status ?? 'in_progress' }}" 
                     data-cost="{{ $cj->cost ?? '' }}">
                    <p><strong>ID:</strong> {{ $cj->id }}</p>
                    <p><strong>Tracking:</strong> {{ $cj->tracking_id }}</p>
                    <p><strong>Customer:</strong> {{ $cj->customer_name ?? optional($cj->user)->name ?? '-' }}</p>
                    <p><strong>Phone:</strong> {{ $cj->phone ?? optional($cj->user)->phone ?? '-' }}</p>
                    <p><strong>Brand:</strong> {{ $cj->phone_brand }}</p>
                    <p><strong>Model:</strong> {{ $cj->phone_model }}</p>
                    <p><strong>IMEI:</strong> {{ $cj->imei ?? '-' }}</p>
                    <p><strong>Complaint:</strong> {{ $cj->complaint }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($cj->status) }}</p>
                    <p><strong>Technician:</strong> {{ $cj->technician ?? optional($cj->technicianUser)->name ?? '-' }}</p>
                    <p><strong>Sparepart:</strong> {{ $cj->sparepart ?? '-' }}</p>
                    <p><strong>Diagnosis:</strong> {{ $cj->diagnosis ?? '-' }}</p>
                    <p><strong>Cost:</strong> {{ $cj->cost ?? '-' }}</p>
                    <p><strong>Created At:</strong> {{ $cj->created_at->format('Y-m-d H:i') }}</p>
                    <p><strong>Updated At:</strong> {{ $cj->updated_at->format('Y-m-d H:i') }}</p>
                    <div class="mt-3">
                        <button onclick="openJobModal({{ $cj->id }})" class="bg-green-600 text-white px-3 py-1 rounded">Update & Finish</button>
                    </div>
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
                        <th class="px-3 py-2 border">Tracking ID</th>
                        <th class="px-3 py-2 border">Customer</th>
                        <th class="px-3 py-2 border">Phone</th>
                        <th class="px-3 py-2 border">Brand</th>
                        <th class="px-3 py-2 border">Model</th>
                        <th class="px-3 py-2 border">IMEI</th>
                        <th class="px-3 py-2 border">Complaint</th>
                        <th class="px-3 py-2 border">Status</th>
                        <th class="px-3 py-2 border">Technician</th>
                        <th class="px-3 py-2 border">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($availableJobs as $repair)
                    <tr class="text-center" data-repair-id="{{ $repair->id }}">
                        <td class="border px-3 py-2">{{ $repair->id }}</td>
                        <td class="border px-3 py-2">{{ $repair->tracking_id }}</td>
                        <td class="border px-3 py-2">{{ $repair->customer_name ?? optional($repair->user)->name ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $repair->phone ?? optional($repair->user)->phone ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $repair->phone_brand }}</td>
                        <td class="border px-3 py-2">{{ $repair->phone_model }}</td>
                        <td class="border px-3 py-2">{{ $repair->imei ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $repair->complaint }}</td>
                        <td class="border px-3 py-2">{{ ucfirst($repair->status) }}</td>
                        <td class="border px-3 py-2">{{ $repair->technician ?? optional($repair->technicianUser)->name ?? '-' }}</td>
                        <td class="border px-3 py-2">
                            <form method="POST" action="{{ route('technician.claim', $repair->id) }}">
                                @csrf
                                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Take Job</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="p-4 text-center">No available jobs.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Finished / Other Jobs --}}
    <div>
        <h3 class="text-xl font-semibold mb-2">Finished / Your Jobs</h3>
        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-3 py-2 border">ID</th>
                        <th class="px-3 py-2 border">Tracking ID</th>
                        <th class="px-3 py-2 border">Customer</th>
                        <th class="px-3 py-2 border">Phone</th>
                        <th class="px-3 py-2 border">Brand</th>
                        <th class="px-3 py-2 border">Model</th>
                        <th class="px-3 py-2 border">IMEI</th>
                        <th class="px-3 py-2 border">Complaint</th>
                        <th class="px-3 py-2 border">Status</th>
                        <th class="px-3 py-2 border">Technician</th>
                        <th class="px-3 py-2 border">Sparepart</th>
                        <th class="px-3 py-2 border">Diagnosis</th>
                        <th class="px-3 py-2 border">Cost</th>
                        <th class="px-3 py-2 border">Created At</th>
                        <th class="px-3 py-2 border">Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($otherJobs as $r)
                    <tr class="text-center">
                        <td class="border px-3 py-2">{{ $r->id }}</td>
                        <td class="border px-3 py-2">{{ $r->tracking_id }}</td>
                        <td class="border px-3 py-2">{{ $r->customer_name ?? optional($r->user)->name ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $r->phone ?? optional($r->user)->phone ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $r->phone_brand }}</td>
                        <td class="border px-3 py-2">{{ $r->phone_model }}</td>
                        <td class="border px-3 py-2">{{ $r->imei ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $r->complaint }}</td>
                        <td class="border px-3 py-2">{{ ucfirst($r->status) }}</td>
                        <td class="border px-3 py-2">{{ $r->technician ?? optional($r->technicianUser)->name ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $r->sparepart ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $r->diagnosis ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $r->cost ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $r->created_at->format('Y-m-d H:i') }}</td>
                        <td class="border px-3 py-2">{{ $r->updated_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="15" class="p-4 text-center">No jobs yet.</td></tr>
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
        const activeCard = document.querySelector(`[data-active-id='${id}']`) || document.querySelector(`tr[data-repair-id='${id}']`);
        const form = document.getElementById('jobForm');
        form.action = `/technician/repairs/${id}/update`;
        document.getElementById('modal_repair_id').value = id;

        const spare = activeCard?.getAttribute('data-sparepart') || '';
        const diag = activeCard?.getAttribute('data-diagnosis') || '';
        const status = activeCard?.getAttribute('data-status') || 'in_progress';
        const cost = activeCard?.getAttribute('data-cost') || '';

        form.querySelector("textarea[name='sparepart']").value = spare;
        form.querySelector("textarea[name='diagnosis']").value = diag;
        form.querySelector("select[name='status']").value = status;
        const costField = form.querySelector("input[name='cost']");
        if (costField) costField.value = cost;

        document.getElementById('jobModal').showModal();
    }
</script>
@endsection
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
                <div class="bg-white p-4 rounded shadow" 
                     data-active-id="{{ $cj->id }}" 
                     data-sparepart="{{ $cj->sparepart ?? '' }}" 
                     data-diagnosis="{{ $cj->diagnosis ?? '' }}" 
                     data-status="{{ $cj->status ?? 'in_progress' }}" 
                     data-cost="{{ $cj->cost ?? '' }}">
                    <p><strong>ID:</strong> {{ $cj->id }}</p>
                    <p><strong>Tracking:</strong> {{ $cj->tracking_id }}</p>
                    <p><strong>Customer:</strong> {{ $cj->customer_name ?? optional($cj->user)->name ?? '-' }}</p>
                    <p><strong>Phone:</strong> {{ $cj->phone ?? optional($cj->user)->phone ?? '-' }}</p>
                    <p><strong>Brand:</strong> {{ $cj->phone_brand }}</p>
                    <p><strong>Model:</strong> {{ $cj->phone_model }}</p>
                    <p><strong>IMEI:</strong> {{ $cj->imei ?? '-' }}</p>
                    <p><strong>Complaint:</strong> {{ $cj->complaint }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($cj->status) }}</p>
                    <p><strong>Technician:</strong> {{ $cj->technician ?? optional($cj->technicianUser)->name ?? '-' }}</p>
                    <p><strong>Sparepart:</strong> {{ $cj->sparepart ?? '-' }}</p>
                    <p><strong>Diagnosis:</strong> {{ $cj->diagnosis ?? '-' }}</p>
                    <p><strong>Cost:</strong> {{ $cj->cost ?? '-' }}</p>
                    <div class="mt-3">
                        <button onclick="openJobModal({{ $cj->id }})" class="bg-green-600 text-white px-3 py-1 rounded">Update & Finish</button>
                    </div>
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
                        <th class="px-3 py-2 border">Tracking ID</th>
                        <th class="px-3 py-2 border">Customer</th>
                        <th class="px-3 py-2 border">Phone</th>
                        <th class="px-3 py-2 border">Brand</th>
                        <th class="px-3 py-2 border">Model</th>
                        <th class="px-3 py-2 border">Complaint</th>
                        <th class="px-3 py-2 border">Status</th>
                        <th class="px-3 py-2 border">Technician</th>
                        <th class="px-3 py-2 border">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($availableJobs as $repair)
                    <tr class="text-center" data-repair-id="{{ $repair->id }}">
                        <td class="border px-3 py-2">{{ $repair->id }}</td>
                        <td class="border px-3 py-2">{{ $repair->tracking_id }}</td>
                        <td class="border px-3 py-2">{{ $repair->customer_name ?? optional($repair->user)->name ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $repair->phone ?? optional($repair->user)->phone ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $repair->phone_brand }}</td>
                        <td class="border px-3 py-2">{{ $repair->phone_model }}</td>
                        <td class="border px-3 py-2">{{ $repair->complaint }}</td>
                        <td class="border px-3 py-2">{{ ucfirst($repair->status) }}</td>
                        <td class="border px-3 py-2">{{ $repair->technician ?? optional($repair->technicianUser)->name ?? '-' }}</td>
                        <td class="border px-3 py-2">
                            <form method="POST" action="{{ route('technician.claim', $repair->id) }}">
                                @csrf
                                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Take Job</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="p-4 text-center">No available jobs.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Finished Jobs --}}
    <div>
        <h3 class="text-xl font-semibold mb-2">Finished / Your Jobs</h3>
        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-3 py-2 border">ID</th>
                        <th class="px-3 py-2 border">Tracking ID</th>
                        <th class="px-3 py-2 border">Customer</th>
                        <th class="px-3 py-2 border">Phone</th>
                        <th class="px-3 py-2 border">Brand</th>
                        <th class="px-3 py-2 border">Model</th>
                        <th class="px-3 py-2 border">Complaint</th>
                        <th class="px-3 py-2 border">Status</th>
                        <th class="px-3 py-2 border">Technician</th>
                        <th class="px-3 py-2 border">Sparepart</th>
                        <th class="px-3 py-2 border">Diagnosis</th>
                        <th class="px-3 py-2 border">Cost</th>
                        <th class="px-3 py-2 border">Created At</th>
                        <th class="px-3 py-2 border">Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($otherJobs as $r)
                    <tr class="text-center">
                        <td class="border px-3 py-2">{{ $r->id }}</td>
                        <td class="border px-3 py-2">{{ $r->tracking_id }}</td>
                        <td class="border px-3 py-2">{{ $r->customer_name ?? optional($r->user)->name ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $r->phone ?? optional($r->user)->phone ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $r->phone_brand }}</td>
                        <td class="border px-3 py-2">{{ $r->phone_model }}</td>
                        <td class="border px-3 py-2">{{ $r->complaint }}</td>
                        <td class="border px-3 py-2">{{ ucfirst($r->status) }}</td>
                        <td class="border px-3 py-2">{{ $r->technician ?? optional($r->technicianUser)->name ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $r->sparepart ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $r->diagnosis ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $r->cost ?? '-' }}</td>
                        <td class="border px-3 py-2">{{ $r->created_at->format('Y-m-d H:i') }}</td>
                        <td class="border px-3 py-2">{{ $r->updated_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="15" class="p-4 text-center">No jobs yet.</td></tr>
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
            <option value="in_progress">In Progress</option>
            <option value="finished">Finished</option>
        </select>
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('jobModal').close()" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">Save</button>
        </div>
    </form>
</dialog>

<script>
function openJobModal(id) {
    const card = document.querySelector(`[data-active-id='${id}']`);
    const form = document.getElementById('jobForm');
    form.action = `/technician/repairs/${id}/update`;
    document.getElementById('modal_repair_id').value = id;
    form.querySelector("textarea[name='sparepart']").value = card?.dataset.sparepart || '';
    form.querySelector("textarea[name='diagnosis']").value = card?.dataset.diagnosis || '';
    form.querySelector("select[name='status']").value = card?.dataset.status || 'in_progress';
    form.querySelector("input[name='cost']").value = card?.dataset.cost || '';
    document.getElementById('jobModal').showModal();
}
</script>
@endsection
