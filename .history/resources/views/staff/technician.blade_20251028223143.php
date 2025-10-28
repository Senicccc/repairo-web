@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Technician Dashboard</h2>

    {{-- Active Jobs --}}
    <div class="mb-6">
        <h3 class="text-xl font-semibold mb-2">Your Active Jobs <small class="text-sm text-gray-500">({{ $currentJobs->count() }} / 6)</small></h3>
        @if ($currentJobs->count() > 0)
            <div class="space-y-4">
                @foreach($currentJobs as $cj)
                <div class="bg-white p-4 rounded shadow"
                     data-active-id="{{ $cj->id }}"
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

{{-- Modal --}}
<dialog id="jobModal" class="modal">
    <form method="POST" id="jobForm" class="p-6 bg-white rounded shadow-md w-[720px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Update Job / Add Spareparts</h3>
        <input type="hidden" name="repair_id" id="modal_repair_id">

        <div class="space-y-4">
            {{-- Step 1: Diagnosis --}}
            <div class="border-b pb-4">
                <h4 class="font-medium mb-2 text-blue-600">Step 1: Diagnosis</h4>
                <label class="block text-sm font-medium mb-1">Final Diagnosis</label>
                <textarea name="diagnosis" placeholder="Enter final diagnosis here..." class="w-full border p-2 rounded h-28"></textarea>
            </div>

            {{-- Step 2: Spareparts --}}
            <div class="border-b pb-4">
                <h4 class="font-medium mb-2 text-blue-600">Step 2: Spareparts</h4>
                
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Sparepart Source</label>
                    <select id="spSource" class="w-full border p-2 rounded">
                        <option value="in_store">Internal (in_store)</option>
                        <option value="customer_owned">Customer-owned</option>
                        <option value="external_purchase">External purchase</option>
                    </select>
                </div>

                {{-- Search for in_store spareparts --}}
                <div id="inStoreSection" class="mb-3">
                    <label class="block text-sm font-medium mb-1">Search Sparepart</label>
                    <input id="spSearch" type="text" placeholder="Search sparepart by name, brand or model..." class="w-full border p-2 rounded mb-2">
                    <div id="spResults" class="max-h-40 overflow-auto border rounded mb-2 p-2 hidden"></div>

                    <label class="text-xs">Chosen sparepart</label>
                    <div id="chosenSp" class="p-2 border rounded mb-2 text-sm text-gray-700">None</div>

                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <div>
                            <label class="text-xs">Quantity</label>
                            <input id="spQuantity" type="number" min="1" value="1" class="w-full border p-2 rounded">
                        </div>
                        <div>
                            <label class="text-xs">Price</label>
                            <input id="spPrice" type="number" step="0.01" class="w-full border p-2 rounded" placeholder="Price">
                        </div>
                    </div>

                    <div class="flex gap-2 mb-2">
                        <button type="button" id="addSpBtn" class="px-3 py-1 bg-indigo-600 text-white rounded">Add to list</button>
                        <button type="button" id="clearChosen" class="px-3 py-1 bg-gray-300 rounded">Clear</button>
                    </div>
                </div>

                {{-- Manual input for external spareparts --}}
                <div id="externalSection" class="mb-3 hidden">
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <div>
                            <label class="text-xs">Brand</label>
                            <input id="extBrand" type="text" class="w-full border p-2 rounded" placeholder="Brand">
                        </div>
                        <div>
                            <label class="text-xs">Model</label>
                            <input id="extModel" type="text" class="w-full border p-2 rounded" placeholder="Model">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <div>
                            <label class="text-xs">Name</label>
                            <input id="extName" type="text" class="w-full border p-2 rounded" placeholder="Sparepart Name">
                        </div>
                        <div>
                            <label class="text-xs">Category</label>
                            <select id="extCategory" class="w-full border p-2 rounded">
                                <option value="Original">Original</option>
                                <option value="OEM">OEM</option>
                                <option value="Aftermarket">Aftermarket</option>
                                <option value="Replica">Replica</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <div>
                            <label class="text-xs">Quantity</label>
                            <input id="extQuantity" type="number" min="1" value="1" class="w-full border p-2 rounded">
                        </div>
                        <div>
                            <label class="text-xs">Price</label>
                            <input id="extPrice" type="number" step="0.01" class="w-full border p-2 rounded" placeholder="Price">
                        </div>
                    </div>
                    <button type="button" id="addExtSpBtn" class="px-3 py-1 bg-indigo-600 text-white rounded">Add to list</button>
                </div>

                <label class="block text-sm font-medium mb-1">Spareparts to save</label>
                <div id="spListArea" class="max-h-36 overflow-auto border rounded p-2"></div>
            </div>

            {{-- Step 3: Final Cost & Status --}}
            <div>
                <h4 class="font-medium mb-2 text-blue-600">Step 3: Final Cost & Status</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Total Cost</label>
                        <input type="number" step="0.01" name="cost" id="finalCost" readonly class="w-full border p-2 mb-3 rounded bg-gray-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select name="status" class="w-full border p-2 mb-3 rounded">
                            <option value="in_progress">In Progress</option>
                            <option value="finished">Finished</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 flex justify-end gap-2">
            <button type="button" onclick="document.getElementById('jobModal').close()" class="px-3 py-1 bg-gray-300 rounded">Cancel</button>
            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded">Save All</button>
        </div>
    </form>
</dialog>

<script>
let selectedSparepart = null;
let pendingSpareparts = [];

const spSource = document.getElementById('spSource');
const inStoreSection = document.getElementById('inStoreSection');
const externalSection = document.getElementById('externalSection');
const spSearch = document.getElementById('spSearch');
const spResults = document.getElementById('spResults');
const chosenSp = document.getElementById('chosenSp');
const spQuantity = document.getElementById('spQuantity');
const spPrice = document.getElementById('spPrice');
const addSpBtn = document.getElementById('addSpBtn');
const clearChosen = document.getElementById('clearChosen');
const extBrand = document.getElementById('extBrand');
const extModel = document.getElementById('extModel');
const extName = document.getElementById('extName');
const extCategory = document.getElementById('extCategory');
const extQuantity = document.getElementById('extQuantity');
const extPrice = document.getElementById('extPrice');
const addExtSpBtn = document.getElementById('addExtSpBtn');
const spListArea = document.getElementById('spListArea');
const finalCost = document.getElementById('finalCost');

// Toggle between in-store and external sparepart sections
spSource.addEventListener('change', function() {
    if (this.value === 'in_store') {
        inStoreSection.classList.remove('hidden');
        externalSection.classList.add('hidden');
    } else {
        inStoreSection.classList.add('hidden');
        externalSection.classList.remove('hidden');
    }
});

// Search for spareparts
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
                    div.textContent = `${sp.brand} ${sp.model} — ${sp.name} (${sp.category}) — Rp ${Number(sp.price).toLocaleString()} — stock:${sp.stock}`;
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

// Add in-store sparepart to list
addSpBtn.addEventListener('click', () => {
    if (!selectedSparepart) { alert('Choose a sparepart'); return; }
    const it = { 
        sparepart_id: selectedSparepart.id,
        name: `${selectedSparepart.brand} ${selectedSparepart.model} - ${selectedSparepart.name}`,
        category: selectedSparepart.category,
        quantity: parseInt(spQuantity.value) || 1,
        price: parseFloat(spPrice.value) || 0,
        source: 'in_store'
    };
    pendingSpareparts.push(it);
    renderPendingList();
    updateFinalCost();
    selectedSparepart = null;
    chosenSp.innerHTML = 'None';
    spSearch.value = '';
    spPrice.value = '';
});

// Add external sparepart to list
addExtSpBtn.addEventListener('click', () => {
    if (!extName.value.trim()) { alert('Enter sparepart name'); return; }
    const it = { 
        sparepart_id: null,
        name: `${extBrand.value} ${extModel.value} - ${extName.value}`,
        category: extCategory.value,
        quantity: parseInt(extQuantity.value) || 1,
        price: parseFloat(extPrice.value) || 0,
        source: spSource.value
    };
    pendingSpareparts.push(it);
    renderPendingList();
    updateFinalCost();
    
    // Reset form
    extBrand.value = '';
    extModel.value = '';
    extName.value = '';
    extQuantity.value = 1;
    extPrice.value = '';
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
            <button type="button" class="px-2 py-1 bg-red-500 text-white rounded" onclick="removePending(${idx})">Remove</button>
        </div>`;
        spListArea.appendChild(el);
    });
    
    
    if (pendingSpareparts.length === 0) {
        spListArea.innerHTML = '<div class="text-gray-500 text-center py-4">No spareparts added yet</div>';
    }
}
function removePending(i) {
    pendingSpareparts.splice(i,1);
    renderPendingList();
    updateFinalCost();
}

function updateFinalCost() {
    const total = pendingSpareparts.reduce((sum, it) => sum + (it.price * it.quantity), 0);
    finalCost.value = total.toFixed(2);
    console.log('💰 Updated final cost:', total);
}

async function openJobModal(id) {
    const card = document.querySelector(`[data-active-id='${id}']`);
    const form = document.getElementById('jobForm');
    form.action = `/repairs/${id}`;
    document.getElementById('modal_repair_id').value = id;
    form.querySelector("textarea[name='diagnosis']").value = card?.dataset.diagnosis || '';
    form.querySelector("select[name='status']").value = card?.dataset.status || 'in_progress';
    form.querySelector("input[name='cost']").value = card?.dataset.cost || '';

    // Reset pendingSpareparts dulu
    pendingSpareparts = [];
    
    try {
        // Load existing spareparts dari database
        console.log('🔄 Loading existing spareparts for repair:', id);
        const response = await fetch(`/api/repairs/${id}/spareparts`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const existingSpareparts = await response.json();
            console.log('📦 Existing spareparts:', existingSpareparts);
            
            // Convert existing spareparts ke format yang sesuai
            existingSpareparts.forEach(sp => {
                pendingSpareparts.push({
                    sparepart_id: sp.sparepart_id,
                    name: sp.name,
                    category: sp.category,
                    quantity: sp.quantity,
                    price: parseFloat(sp.price),
                    source: sp.source
                });
            });
            
            console.log('✅ Loaded existing spareparts:', pendingSpareparts);
        }
    } catch (error) {
        console.error('❌ Error loading existing spareparts:', error);
    }
    
    // Render list dan update cost
    renderPendingList();
    updateFinalCost();
    
    // Reset UI elements
    chosenSp.innerHTML = 'None';
    spSearch.value = '';
    spResults.classList.add('hidden');
    
    // Reset external form
    extBrand.value = '';
    extModel.value = '';
    extName.value = '';
    extQuantity.value = 1;
    extPrice.value = '';
    
    // Reset to in_store by default
    spSource.value = 'in_store';
    inStoreSection.classList.remove('hidden');
    externalSection.classList.add('hidden');

    document.getElementById('jobModal').showModal();
}


async function removePending(index) {
    const sparepart = pendingSpareparts[index];
    const repairId = document.getElementById('modal_repair_id').value;
    
    console.log('🗑️ Removing sparepart:', sparepart);
    console.log('🔧 Repair ID:', repairId);

    // Jika sparepart sudah ada di database (punya ID), hapus dari database
    if (sparepart.id) {
        try {
            console.log('🗄️ Removing from database...');
            const response = await fetch(`/repairs/${repairId}/spareparts/${sparepart.id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();
            console.log('🗑️ Delete response:', result);

            if (response.ok && result.success) {
                console.log('✅ Sparepart removed from database');
            } else {
                console.error('❌ Failed to remove from database:', result.message);
                alert('Warning: Sparepart removed from list but may still exist in database');
            }
        } catch (error) {
            console.error('❌ Error removing from database:', error);
            alert('Warning: Sparepart removed from list but may still exist in database');
        }
    }

    // Hapus dari list JavaScript
    pendingSpareparts.splice(index, 1);
    renderPendingList();
    updateFinalCost();
    
    console.log('✅ Sparepart removed from list');
}

document.getElementById('jobForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const repairId = document.getElementById('modal_repair_id').value;

    console.log('🚀 STARTING FORM SUBMISSION');
    console.log('🔧 Repair ID:', repairId);
    console.log('📦 Pending Spareparts:', pendingSpareparts);

    // Update final cost before submission
    updateFinalCost();
    console.log('💰 Final Cost:', finalCost.value);

    try {
        // STEP 1: UPDATE REPAIR DATA
        console.log('🔄 STEP 1: Updating repair data...');
        const formData = new FormData(form);
        
        const repairResponse = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        });

        const repairResult = await repairResponse.json();
        console.log('✅ Repair Update Response:', repairResult);

        if (!repairResponse.ok) {
            throw new Error(repairResult.message || 'Failed to update repair');
        }

        // STEP 2: ADD SPAREPARTS IF ANY
        if (pendingSpareparts.length > 0) {
            console.log('🔧 STEP 2: Adding spareparts...');
            console.log(`📦 Total spareparts to add: ${pendingSpareparts.length}`);
            
            let successCount = 0;
            let errorMessages = [];

            for (let i = 0; i < pendingSpareparts.length; i++) {
                const it = pendingSpareparts[i];
                console.log(`🔄 Processing sparepart ${i + 1}/${pendingSpareparts.length}:`, it);
                
                try {
                    const sparepartData = new URLSearchParams();
                    sparepartData.append('name', it.name);
                    sparepartData.append('sparepart_id', it.sparepart_id || '');
                    sparepartData.append('quantity', it.quantity.toString());
                    sparepartData.append('price', it.price.toString());
                    sparepartData.append('source', it.source);
                    sparepartData.append('category', it.category || '');

                    console.log(`📤 Sending sparepart data:`, Object.fromEntries(sparepartData));

                    const sparepartResponse = await fetch(`/repairs/${repairId}/spareparts`, {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: sparepartData.toString()
                    });

                    const sparepartResult = await sparepartResponse.json();
                    console.log(`📥 Sparepart Response:`, sparepartResult);

                    if (sparepartResponse.ok && sparepartResult.success) {
                        successCount++;
                        console.log(`✅ Sparepart ${i + 1} added successfully!`);
                    } else {
                        const errorMsg = sparepartResult.message || 'Unknown error';
                        console.error(`❌ Sparepart ${i + 1} failed:`, errorMsg);
                        errorMessages.push(`Sparepart "${it.name}": ${errorMsg}`);
                    }

                } catch (sparepartErr) {
                    console.error(`❌ Network error for sparepart ${i + 1}:`, sparepartErr);
                    errorMessages.push(`Sparepart "${it.name}": Network error`);
                }
            }

            console.log(`📊 RESULTS: ${successCount}/${pendingSpareparts.length} spareparts added successfully`);
            
            if (errorMessages.length > 0) {
                console.warn('⚠️ Some spareparts failed:', errorMessages);
                // Show warning but continue
                alert(`Successfully added ${successCount} spareparts, but ${errorMessages.length} failed:\n\n${errorMessages.join('\n')}`);
            }
            
        } else {
            console.log('ℹ️ No spareparts to add');
        }

        // SUCCESS - RELOAD PAGE
        console.log('🎉 OPERATION COMPLETED SUCCESSFULLY!');
        document.getElementById('jobModal').close();
        
        // Tunggu sebentar sebelum reload untuk memastikan semua proses selesai
        setTimeout(() => {
            location.reload();
        }, 1000);
        
    } catch(err) {
        console.error('💥 OPERATION FAILED:', err);
        alert('Error updating job: ' + err.message);
    }

    async function openJobModal(id) {
    const card = document.querySelector(`[data-active-id='${id}']`);
    const form = document.getElementById('jobForm');
    form.action = `/repairs/${id}`;
    document.getElementById('modal_repair_id').value = id;
    form.querySelector("textarea[name='diagnosis']").value = card?.dataset.diagnosis || '';
    form.querySelector("select[name='status']").value = card?.dataset.status || 'in_progress';
    form.querySelector("input[name='cost']").value = card?.dataset.cost || '';

    // Reset pendingSpareparts dulu
    pendingSpareparts = [];
    
    try {
        // Load existing spareparts dari database
        console.log('🔄 Loading existing spareparts for repair:', id);
        const response = await fetch(`/api/repairs/${id}/spareparts`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const existingSpareparts = await response.json();
            console.log('📦 Existing spareparts:', existingSpareparts);
            
            // Convert existing spareparts ke format yang sesuai (INCLUDE ID)
            existingSpareparts.forEach(sp => {
                pendingSpareparts.push({
                    id: sp.id, // INI PENTING untuk delete
                    sparepart_id: sp.sparepart_id,
                    name: sp.name,
                    category: sp.category,
                    quantity: sp.quantity,
                    price: parseFloat(sp.price),
                    source: sp.source
                });
            });
            
            console.log('✅ Loaded existing spareparts:', pendingSpareparts);
        }
    } catch (error) {
        console.error('❌ Error loading existing spareparts:', error);
    }
    
    // Render list dan update cost
    renderPendingList();
    updateFinalCost();
    
    // Reset UI elements
    chosenSp.innerHTML = 'None';
    spSearch.value = '';
    spResults.classList.add('hidden');
    
    // Reset external form
    extBrand.value = '';
    extModel.value = '';
    extName.value = '';
    extQuantity.value = 1;
    extPrice.value = '';
    
    // Reset to in_store by default
    spSource.value = 'in_store';
    inStoreSection.classList.remove('hidden');
    externalSection.classList.add('hidden');

    document.getElementById('jobModal').showModal();
}
});
</script>
@endsection