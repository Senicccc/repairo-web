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
                     data-id="{{ $cj->id }}"
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
                    <p><strong>Status:</strong> {{ ucfirst($cj->status) }}</p>
                    <div class="mt-3">
                        <button onclick="openJobModal({{ $cj->id }})" class="bg-green-600 text-white px-3 py-1 rounded">Update & Finish</button>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 p-4 rounded">No active jobs.</div>
        @endif
    </div>
</div>

<dialog id="jobModal" class="modal">
    <form method="POST" id="jobForm" class="p-6 bg-white rounded shadow-md w-[720px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Update Job / Spareparts</h3>
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
                <input id="spSearch" type="text" placeholder="Search sparepart..." class="w-full border p-2 rounded mb-2">
                <div id="spResults" class="max-h-40 overflow-auto border rounded mb-2 p-2 hidden"></div>

                <label class="text-xs">Chosen sparepart</label>
                <div id="chosenSp" class="p-2 border rounded mb-2 text-sm text-gray-700">None</div>

                <div class="grid grid-cols-3 gap-2 mb-2">
                    <input id="spQuantity" type="number" min="1" value="1" class="border p-2 rounded" placeholder="Qty">
                    <input id="spPrice" type="number" step="0.01" class="border p-2 rounded" placeholder="Price" readonly>
                    <select id="spSource" class="border p-2 rounded">
                        <option value="in_store">Internal (in_store)</option>
                        <option value="customer_owned">Customer-owned</option>
                        <option value="external_purchase">External purchase</option>
                    </select>
                </div>

                <div class="flex gap-2 mb-2">
                    <button type="button" id="addSpBtn" class="px-3 py-1 bg-indigo-600 text-white rounded">Add</button>
                    <button type="button" id="clearChosen" class="px-3 py-1 bg-gray-300 rounded">Clear</button>
                </div>

                <label class="block text-sm font-medium mb-1">Spareparts to save</label>
                <div id="spListArea" class="max-h-36 overflow-auto border rounded p-2"></div>
            </div>
        </div>

        <div class="mt-4 flex justify-end gap-2">
            <button type="button" onclick="document.getElementById('jobModal').close()" class="px-3 py-1 bg-gray-300 rounded">Cancel</button>
            <button type="button" id="saveAllBtn" class="px-3 py-1 bg-green-600 text-white rounded">Save All</button>
        </div>
    </form>
</dialog>

<script>
let selectedSp = null;
let spList = [];

const spSearch = document.getElementById('spSearch');
const spResults = document.getElementById('spResults');
const chosenSp = document.getElementById('chosenSp');
const spQuantity = document.getElementById('spQuantity');
const spPrice = document.getElementById('spPrice');
const spSource = document.getElementById('spSource');
const spListArea = document.getElementById('spListArea');

spSearch.addEventListener('input', () => {
    const q = spSearch.value.trim();
    if (!q) { spResults.classList.add('hidden'); return; }
    fetch(`{{ route('spareparts.search') }}?q=` + encodeURIComponent(q))
        .then(r => r.json())
        .then(list => {
            spResults.innerHTML = '';
            if(!list.length){ spResults.innerHTML='<div class="text-sm text-gray-500">No results</div>'; }
            else {
                list.forEach(sp => {
                    const div = document.createElement('div');
                    div.className='p-2 cursor-pointer hover:bg-gray-100 text-sm';
                    div.textContent=`${sp.brand} ${sp.model} - ${sp.name} (${sp.category}) Rp ${Number(sp.price).toLocaleString()}`;
                    div.onclick = () => {
                        selectedSp=sp;
                        chosenSp.innerHTML=`<strong>${sp.brand} ${sp.model} - ${sp.name}</strong><div class="text-xs text-gray-600">Rp ${Number(sp.price).toLocaleString()}</div>`;
                        spQuantity.value=1;
                        spSource.value='in_store';
                        spPrice.value=sp.price;
                        spResults.classList.add('hidden');
                    };
                    spResults.appendChild(div);
                });
            }
            spResults.classList.remove('hidden');
        });
});

spSource.addEventListener('change', () => {
    if(selectedSp){
        if(spSource.value==='in_store') spPrice.value=selectedSp.price;
        else if(spSource.value==='external_purchase') spPrice.readOnly=false;
        else spPrice.value=0;
    }
});

document.getElementById('addSpBtn').addEventListener('click', () => {
    if(!selectedSp){ alert('Choose sparepart'); return; }
    spList.push({
        id:selectedSp.id,
        name:`${selectedSp.brand} ${selectedSp.model} - ${selectedSp.name}`,
        qty: parseInt(spQuantity.value)||1,
        price: parseFloat(spPrice.value)||0,
        source: spSource.value
    });
    renderSpList();
    selectedSp=null;
    chosenSp.innerHTML='None';
    spSearch.value='';
    spPrice.value='';
    spPrice.readOnly=true;
});

function renderSpList(){
    spListArea.innerHTML='';
    spList.forEach((sp,i)=>{
        const div=document.createElement('div');
        div.className='flex justify-between items-center gap-2 p-2 border-b text-sm';
        div.innerHTML=`<div>${sp.name}<div class="text-xs text-gray-600">Qty:${sp.qty} Rp:${sp.price.toLocaleString()} [${sp.source}]</div></div>
        <button class="px-2 py-1 bg-red-500 text-white rounded" onclick="removeSp(${i})">Remove</button>`;
        spListArea.appendChild(div);
    });
}

function removeSp(i){ spList.splice(i,1); renderSpList(); }

function openJobModal(id){
    const card=document.querySelector(`[data-id='${id}']`);
    const form=document.getElementById('jobForm');
    document.getElementById('modal_repair_id').value=id;
    form.querySelector("textarea[name='diagnosis']").value=card.dataset.diagnosis||'';
    form.querySelector("input[name='cost']").value=card.dataset.cost||'';
    form.querySelector("select[name='status']").value=card.dataset.status||'in_progress';
    spList=[]; renderSpList();
    document.getElementById('jobModal').showModal();
}

document.getElementById('saveAllBtn').addEventListener('click', async ()=>{
    const form=document.getElementById('jobForm');
    const repairId=document.getElementById('modal_repair_id').value;
    const formData=new FormData(form);
    await fetch(`/repairs/${repairId}`,{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},body:formData});
    for(const sp of spList){
        const payload=new URLSearchParams();
        payload.append('sparepart_id',sp.id);
        payload.append('name',sp.name);
        payload.append('quantity',sp.qty);
        payload.append('price',sp.price);
        payload.append('source',sp.source);
        await fetch(`/repairs/${repairId}/spareparts`,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:payload.toString()});
    }
    location.reload();
});
</script>
@endsection
