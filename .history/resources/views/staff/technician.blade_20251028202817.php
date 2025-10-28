@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Technician Dashboard</h2>

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
    <form method="POST" id="jobForm" class="p-6 bg-white rounded shadow-md w-[720px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Update Job / Add Spareparts</h3>
        <input type="hidden" name="repair_id" id="modal_repair_id">

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Diagnosis</label>
                <textarea name="diagnosis" placeholder="Final Diagnosis" class="w-full border p-2 mb-3 rounded h-28"></textarea>

                <label class="block text-sm font-medium mb-1">Cost (final)</label>
                <input type="number" step="0.01" name="cost" placeholder="Cost" class="w-full border p-2 mb-3 rounded">

                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" class="w-full border p-2 mb-3 rounded">
                    <option value="in_progress">In Progress</option>
                    <option value="finished">Finished</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Add Sparepart</label>

                <div class="mb-2">
                    <input id="spSearch" type="text" placeholder="Search sparepart by name, brand or model..." class="w-full border p-2 rounded">
                </div>

                <div id="spResults" class="max-h-40 overflow-auto border rounded mb-2 p-2 hidden"></div>

                <div class="mb-2">
                    <label class="text-xs">Chosen sparepart</label>
                    <div id="chosenSp" class="p-2 border rounded mb-2 text-sm text-gray-700">None</div>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <input id="spQuantity" type="number" min="1" value="1" class="border p-2 rounded" placeholder="Qty">
                    <input id="spPrice" type="number" step="0.01" class="border p-2 rounded" placeholder="Price">
                    <select id="spSource" class="border p-2 rounded">
                        <option value="in_store">Internal (in_store)</option>
                        <option value="customer_owned">Customer-owned</option>
                        <option value="external_purchase">External purchase</option>
                    </select>
                </div>

                <div class="mt-3 flex gap-2">
                    <button type="button" id="addSpBtn" class="px-3 py-1 bg-indigo-600 text-white rounded">Add to list</button>
                    <button type="button" id="clearChosen" class="px-3 py-1 bg-gray-300 rounded">Clear</button>
                </div>

                <hr class="my-3">

                <label class="block text-sm font-medium mb-1">Spareparts to save</label>
                <div id="spListArea" class="max-h-36 overflow-auto border rounded p-2"></div>
            </div>
        </div>

        <div class="mt-4 flex justify-end gap-2">
            <button type="button" onclick="document.getElementById('jobModal').close()" class="px-3 py-1 bg-gray-300 rounded">Cancel</button>
            <button type="button" id="saveAllBtn" class="px-3 py-1 bg-green-600 text-white rounded">Save (diagnosis + spareparts)</button>
        </div>
    </form>
</dialog>

<script>
/* Globals used inside modal */
let selectedSparepart = null; // object {id,brand,model,name,category,price,stock}
let pendingSpareparts = [];   // array of items to create [{sparepart_id,name,quantity,price,source}]

const spSearch = document.getElementById('spSearch');
const spResults = document.getElementById('spResults');
const chosenSp = document.getElementById('chosenSp');
const spQuantity = document.getElementById('spQuantity');
const spPrice = document.getElementById('spPrice');
const spSource = document.getElementById('spSource');
const addSpBtn = document.getElementById('addSpBtn');
const spListArea = document.getElementById('spListArea');
const saveAllBtn = document.getElementById('saveAllBtn');
const clearChosen = document.getElementById('clearChosen');

let searchTimeout = null;
spSearch.addEventListener('input', function() {
    const q = this.value.trim();
    if (searchTimeout) clearTimeout(searchTimeout);
    if (!q) { spResults.classList.add('hidden'); spResults.innerHTML = ''; return; }
    searchTimeout = setTimeout(() => {
        fetch(`{{ route('spareparts.search') }}?q=` + encodeURIComponent(q), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(list => {
            spResults.innerHTML = '';
            if (!list.length) {
                spResults.innerHTML = `<div class="text-sm text-gray-500">No results</div>`;
            } else {
                list.forEach(sp => {
                    const div = document.createElement('div');
                    div.className = 'p-2 cursor-pointer hover:bg-gray-100 text-sm';
                    div.textContent = `${sp.brand} ${sp.model} — ${sp.name} (${sp.category}) — Rp ${Number(sp.price).toLocaleString() } — stock:${sp.stock}`;
                    div.dataset.sp = JSON.stringify(sp);
                    div.addEventListener('click', () => {
                        selectedSparepart = sp;
                        chosenSp.innerHTML = `<strong>${sp.brand} ${sp.model} - ${sp.name}</strong><div class="text-xs text-gray-600">${sp.category} — Rp ${Number(sp.price).toLocaleString()}</div>`;
                        spPrice.value = sp.price;
                        spQuantity.value = 1;
                        spResults.classList.add('hidden');
                    });
                    spResults.appendChild(div);
                });
            }
            spResults.classList.remove('hidden');
        });
    }, 250);
});

addSpBtn.addEventListener('click', () => {
    const name = selectedSparepart ? `${selectedSparepart.brand} ${selectedSparepart.model} - ${selectedSparepart.name}` : null;
    const sparepart_id = selectedSparepart ? selectedSparepart.id : null;
    const quantity = parseInt(spQuantity.value) || 1;
    const price = parseFloat(spPrice.value) || 0;
    const source = spSource.value;

    if (!name) {
        alert('Choose a sparepart from search or manually type a name into the "Chosen sparepart" area');
        return;
    }

    pendingSpareparts.push({sparepart_id, name, quantity, price, source});
    renderPendingList();

    // clear selection
    selectedSparepart = null;
    chosenSp.innerHTML = 'None';
    spSearch.value = '';
    spPrice.value = '';
});

clearChosen.addEventListener('click', () => {
    selectedSparepart = null;
    chosenSp.innerHTML = 'None';
    spSearch.value = '';
    spResults.classList.add('hidden');
});

function renderPendingList() {
    spListArea.innerHTML = '';
    pendingSpareparts.forEach((it, idx) => {
        const el = document.createElement('div');
        el.className = 'flex justify-between items-center gap-2 p-2 border-b text-sm';
        el.innerHTML = `<div>
            <div class="font-medium">${it.name}</div>
            <div class="text-xs text-gray-600">Qty: ${it.quantity} · Rp ${Number(it.price).toLocaleString()} · ${it.source}</div>
        </div>
        <div class="flex gap-2">
            <button class="px-2 py-1 bg-red-500 text-white rounded" onclick="removePending(${idx})">Remove</button>
        </div>`;
        spListArea.appendChild(el);
    });
}

function removePending(i) {
    pendingSpareparts.splice(i,1);
    renderPendingList();
}

saveAllBtn.addEventListener('click', async () => {
    // submit diagnosis/cost/status via jobForm (traditional submit) THEN post spareparts individually
    const form = document.getElementById('jobForm');
    const repairId = document.getElementById('modal_repair_id').value;
    // first submit diagnosis/cost/status using fetch to repair update route
    const formData = new FormData(form);
    // the form currently points to /technician/repairs/{id}/update when opened
    const url = form.action;
    try {
        await fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        });
    } catch (err) {
        console.error(err);
    }

    // then add each sparepart to repair via POST to repairs.addSparepart
    for (const it of pendingSpareparts) {
        const payload = new URLSearchParams();
        payload.append('sparepart_id', it.sparepart_id ?? '');
        payload.append('name', it.name);
        payload.append('quantity', it.quantity);
        payload.append('price', it.price);
        payload.append('source', it.source);

        await fetch(`/repairs/${repairId}/spareparts`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: payload.toString()
        });
    }

    // done -> reload to reflect updates
    location.reload();
});

/* Open modal helper already exists in your other script; ensure when opening, jobForm.action = /technician/repairs/{id}/update and modal_repair_id set */
</script>


<script>
function openJobModal(id) {
    const card = document.querySelector(`[data-active-id='${id}']`)
    const form = document.getElementById('jobForm')
    form.action = `/technician/repairs/${id}/update`
    document.getElementById('modal_repair_id').value = id
    form.querySelector("textarea[name='sparepart']").value = card?.dataset.sparepart || ''
    form.querySelector("textarea[name='diagnosis']").value = card?.dataset.diagnosis || ''
    form.querySelector("select[name='status']").value = card?.dataset.status || 'in_progress'
    form.querySelector("input[name='cost']").value = card?.dataset.cost || ''
    document.getElementById('jobModal').showModal()
}

document.getElementById('sparepartList').addEventListener('change', e => {
    document.querySelector("textarea[name='sparepart']").value = e.target.value
})

document.getElementById('sparepartSearch').addEventListener('input', e => {
    const filter = e.target.value.toLowerCase()
    const options = document.querySelectorAll('#sparepartList option')
    options.forEach(opt => {
        opt.style.display = opt.value.toLowerCase().includes(filter) ? '' : 'none'
    })
})
</script>
@endsection
