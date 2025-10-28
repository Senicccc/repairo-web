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
        @method('PUT')
        <input type="hidden" name="repair_id" id="modal_repair_id">
        <input type="hidden" name="spareparts_data" id="sparepartsData">

        <h3 class="text-lg font-semibold mb-4">Update Job / Add Spareparts</h3>

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
const sparepartsData = document.getElementById('sparepartsData');

// Toggle sections
spSource.addEventListener('change', function() {
    if(this.value==='in_store') {
        inStoreSection.classList.remove('hidden');
        externalSection.classList.add('hidden');
    } else {
        inStoreSection.classList.add('hidden');
        externalSection.classList.remove('hidden');
    }
});

// Search spareparts
let searchTimeout=null;
spSearch.addEventListener('input', function(){
    const q = this.value.trim();
    if(searchTimeout) clearTimeout(searchTimeout);
    if(!q) { spResults.classList.add('hidden'); spResults.innerHTML=''; return; }
    searchTimeout=setTimeout(()=> {
        fetch(`{{ route('spareparts.search') }}?q=`+encodeURIComponent(q),{headers:{'X-Requested-With':'XMLHttpRequest'}})
        .then(r=>r.json())
        .then(data=>{
            spResults.innerHTML='';
            if(data.length>0){
                data.forEach(sp=>{
                    const d = document.createElement('div');
                    d.className='p-1 cursor-pointer hover:bg-gray-100 text-sm';
                    d.textContent=`${sp.name} (${sp.brand} ${sp.model})`;
                    d.onclick=()=>{ selectedSparepart=sp; chosenSp.textContent=d.textContent; spPrice.value=sp.price || 0; spResults.classList.add('hidden'); };
                    spResults.appendChild(d);
                });
                spResults.classList.remove('hidden');
            } else { spResults.innerHTML='<div class="text-xs text-gray-400">No result</div>'; spResults.classList.remove('hidden'); }
        });
    },300);
});

// Add in-store sparepart
addSpBtn.addEventListener('click', ()=>{
    if(!selectedSparepart){ alert('Pick sparepart first'); return; }
    pendingSpareparts.push({ 
        sparepart_id: selectedSparepart.id,
        name:selectedSparepart.name,
        quantity: Number(spQuantity.value),
        price: Number(spPrice.value),
        source:'in_store'
    });
    renderPendingList();
    chosenSp.textContent='None'; selectedSparepart=null; spSearch.value=''; spPrice.value=''; spQuantity.value=1;
});

// Add external sparepart
addExtSpBtn.addEventListener('click', ()=>{
    if(!extName.value) { alert('Name required'); return; }
    pendingSpareparts.push({
        name: extName.value,
        brand: extBrand.value,
        model: extModel.value,
        category: extCategory.value,
        quantity: Number(extQuantity.value),
        price: Number(extPrice.value),
        source:'external_purchase'
    });
    renderPendingList();
    extName.value=''; extBrand.value=''; extModel.value=''; extPrice.value=''; extQuantity.value=1;
});

// Render pending spareparts
function renderPendingList(){
    spListArea.innerHTML='';
    pendingSpareparts.forEach((it,idx)=>{
        const el=document.createElement('div');
        el.className='flex justify-between items-center gap-2 p-2 border-b text-sm';
        el.innerHTML=`<div>
            <div class="font-medium">${it.name}</div>
            <div class="text-xs text-gray-600">Qty: ${it.quantity} · Rp ${Number(it.price).toLocaleString()} · ${it.source}</div>
        </div>
        <div class="flex gap-2">
            <button type="button" class="px-2 py-1 bg-red-500 text-white rounded" onclick="removePending(${idx})">Remove</button>
        </div>`;
        spListArea.appendChild(el);
    });
    updateFinalCost();
    sparepartsData.value=JSON.stringify(pendingSpareparts);
}

// Remove sparepart
function removePending(idx){ pendingSpareparts.splice(idx,1); renderPendingList(); }

// Update final cost
function updateFinalCost(){
    const sum = pendingSpareparts.reduce((acc,it)=>acc + (Number(it.price)||0)* (Number(it.quantity)||0),0);
    finalCost.value=sum;
}

// Open modal
function openJobModal(id){
    document.getElementById('modal_repair_id').value=id;
    pendingSpareparts=[];
    renderPendingList();
    document.getElementById('jobModal').showModal();
}
</script>
@endsection
