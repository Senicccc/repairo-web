@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50">
    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-lg">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold text-gray-800">Technician Panel</h2>
            <p class="text-sm text-gray-600 mt-1">{{ Auth::user()->name }}</p>
        </div>

        @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

        
        <nav class="mt-6">
            <a href="{{ route('technician.dashboard', ['section' => 'active']) }}" 
               class="sidebar-nav flex items-center px-6 py-3 text-gray-700 {{ $activeSection == 'active' ? 'bg-blue-50 border-r-4 border-blue-500' : 'hover:bg-gray-50' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Active Jobs
                <span class="ml-auto bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                    {{ $currentJobs->count() }}
                </span>
            </a>
            
            <a href="{{ route('technician.dashboard', ['section' => 'available']) }}" 
               class="sidebar-nav flex items-center px-6 py-3 text-gray-600 {{ $activeSection == 'available' ? 'bg-blue-50 border-r-4 border-blue-500' : 'hover:bg-gray-50' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Available Jobs
                <span class="ml-auto bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                    {{ $availableJobs->count() }}
                </span>
            </a>
            
            <a href="{{ route('technician.dashboard', ['section' => 'finished']) }}" 
               class="sidebar-nav flex items-center px-6 py-3 text-gray-600 {{ $activeSection == 'finished' ? 'bg-blue-50 border-r-4 border-blue-500' : 'hover:bg-gray-50' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Finished Jobs
                <span class="ml-auto bg-gray-500 text-white text-xs px-2 py-1 rounded-full">
                    {{ $otherJobs->count() }}
                </span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto">
        <div class="p-6">
            @if($activeSection == 'active')
                @include('technician.active-jobs')
            @elseif($activeSection == 'available')
                @include('technician.available-jobs')
            @elseif($activeSection == 'finished')
                @include('technician.finished-jobs')
            @endif
        </div>
    </div>
</div>

@include('technician.modals.modals')

<script>
let selectedSparepart = null;
let pendingSpareparts = [];

function openJobModal(id) {
    const card = document.querySelector(`[data-active-id='${id}']`);
    const form = document.getElementById('jobForm');
    form.action = `{{ url('technician/jobs') }}/${id}/update`; 
    document.getElementById('modal_repair_id').value = id;
    
    // Populate form fields from card data
    form.querySelector("textarea[name='diagnosis']").value = card?.dataset.diagnosis || '';
    form.querySelector("select[name='status']").value = card?.dataset.status || 'in_progress';
    form.querySelector("input[name='cost']").value = card?.dataset.cost || '';

    // Reset pendingSpareparts first
    pendingSpareparts = [];
    
    // Load existing spareparts
    fetch(`/technician/repairs/${id}/spareparts`) 
        .then(response => response.json())
        .then(existingSpareparts => {
            existingSpareparts.forEach(sp => {
                pendingSpareparts.push({
                    id: sp.id,
                    sparepart_id: sp.sparepart_id,
                    name: sp.name,
                    category: sp.category,
                    quantity: sp.quantity,
                    price: parseFloat(sp.price),
                    source: sp.source
                });
            });
            renderPendingList();
            updateFinalCost();
        })
        .catch(error => {
            console.error('Error loading spareparts:', error);
        });
    
    // Reset UI
    selectedSparepart = null;
    document.getElementById('chosenSp').innerHTML = 'None';
    document.getElementById('spSearch').value = '';
    document.getElementById('spQuantity').value = 1;
    document.getElementById('spPrice').value = '';
    
    // Show modal
    document.getElementById('jobModal').showModal();
}

//  RENDER SPAREPART LIST
function renderPendingList() {
    const spListArea = document.getElementById('spListArea');
    if (!spListArea) return;
    
    spListArea.innerHTML = '';
    
    if (pendingSpareparts.length === 0) {
        spListArea.innerHTML = '<div class="text-gray-500 text-center py-4">No spareparts added yet</div>';
        return;
    }
    
    pendingSpareparts.forEach((it, idx) => {
        const el = document.createElement('div');
        el.className = 'flex justify-between items-center gap-2 p-3 border-b text-sm bg-gray-50 rounded mb-2';
        el.innerHTML = `
            <div class="flex-1">
                <div class="font-medium text-gray-800">${it.name}</div>
                <div class="text-xs text-gray-600 mt-1">
                    Qty: ${it.quantity} | Price: Rp ${Number(it.price).toLocaleString()} | Total: Rp ${Number(it.price * it.quantity).toLocaleString()}
                </div>
            </div>
            <button type="button" class="px-3 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600" onclick="removePending(${idx})">
                Remove
            </button>
        `;
        spListArea.appendChild(el);
    });
}

// 💰 UPDATE FINAL COST
function updateFinalCost() {
    const finalCost = document.getElementById('finalCost');
    if (!finalCost) return;
    
    const total = pendingSpareparts.reduce((sum, it) => sum + (it.price * it.quantity), 0);
    finalCost.value = total.toFixed(2);
}

async function removePending(index) {
    if (!confirm('Remove this sparepart?')) return;
    
    const sparepart = pendingSpareparts[index];
    const repairId = document.getElementById('modal_repair_id')?.value;

    if (sparepart.id) {
        try {
            await fetch(`/technician/repairs/${repairId}/spareparts/${sparepart.id}`, { // ✅ FIXED route
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });
        } catch (error) {
            console.error('Error removing sparepart:', error);
        }
    }

    pendingSpareparts.splice(index, 1);
    renderPendingList();
    updateFinalCost();
}

document.addEventListener('DOMContentLoaded', function() {
    const spSearch = document.getElementById('spSearch');
    if (spSearch) {
        let searchTimeout = null;
        spSearch.addEventListener('input', function() {
            const q = this.value.trim();
            if (searchTimeout) clearTimeout(searchTimeout);
            if (!q) return;
            
            searchTimeout = setTimeout(() => {
                fetch(`/technician/spareparts/search?q=` + encodeURIComponent(q)) // ✅ FIXED route
                    .then(response => response.json())
                    .then(list => {
                        const spResults = document.getElementById('spResults');
                        spResults.innerHTML = '';
                        
                        list.forEach(sp => {
                            const div = document.createElement('div');
                            div.className = 'p-2 cursor-pointer hover:bg-gray-100 text-sm border-b';
                            div.textContent = `${sp.brand} ${sp.model} - ${sp.name} (Rp ${Number(sp.price).toLocaleString()})`;
                            div.addEventListener('click', () => {
                                selectedSparepart = sp;
                                document.getElementById('chosenSp').innerHTML = 
                                    `<strong>${sp.brand} ${sp.model} - ${sp.name}</strong>`;
                                document.getElementById('spPrice').value = sp.price;
                                document.getElementById('spQuantity').value = 1;
                                spResults.classList.add('hidden');
                            });
                            spResults.appendChild(div);
                        });
                        spResults.classList.remove('hidden');
                    });
            }, 300);
        });
    }

    const addSpBtn = document.getElementById('addSpBtn');
    if (addSpBtn) {
        addSpBtn.addEventListener('click', () => {
            if (!selectedSparepart) { 
                alert('Please select a sparepart first'); 
                return; 
            }
            
            const quantity = parseInt(document.getElementById('spQuantity').value) || 1;
            const price = parseFloat(document.getElementById('spPrice').value) || selectedSparepart.price;
            
            const it = { 
                sparepart_id: selectedSparepart.id,
                name: `${selectedSparepart.brand} ${selectedSparepart.model} - ${selectedSparepart.name}`,
                category: selectedSparepart.category,
                quantity: quantity,
                price: price,
                source: 'in_store'
            };
            
            pendingSpareparts.push(it);
            renderPendingList();
            updateFinalCost();
            
            // Reset
            selectedSparepart = null;
            document.getElementById('chosenSp').innerHTML = 'None';
            document.getElementById('spSearch').value = '';
        });
    }
});
</script>

@endsection