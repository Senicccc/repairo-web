<!-- Job Update Modal -->
<dialog id="jobModal" class="modal">
    <form method="POST" id="jobForm" class="p-6 bg-white rounded shadow-md w-[720px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Update Job / Add Spareparts</h3>
        <input type="hidden" name="repair_id" id="modal_repair_id">

        <div class="space-y-4">
            <div class="border-b pb-4">
                <h4 class="font-medium mb-2 text-blue-600">Step 1: Diagnosis</h4>
                <label class="block text-sm font-medium mb-1">Final Diagnosis</label>
                <textarea name="diagnosis" placeholder="Enter final diagnosis here..." class="w-full border p-2 rounded h-28"></textarea>
            </div>

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
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="diagnosed">Diagnosed</option>
                            <option value="waiting_parts">Waiting Parts</option>
                            <option value="finished">Finished</option>
                            <option value="cancelled">Cancelled</option>
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

function initializeEventListeners() {
    if (spSource) {
        spSource.addEventListener('change', function() {
            if (this.value === 'in_store') {
                inStoreSection.classList.remove('hidden');
                externalSection.classList.add('hidden');
            } else {
                inStoreSection.classList.add('hidden');
                externalSection.classList.remove('hidden');
            }
        });
    }

    let searchTimeout = null;
    if (spSearch) {
        spSearch.addEventListener('input', function() {
            const q = this.value.trim();
            if (searchTimeout) clearTimeout(searchTimeout);
            if (!q) { 
                spResults.classList.add('hidden'); 
                spResults.innerHTML = ''; 
                return; 
            }
            
            searchTimeout = setTimeout(() => {
                fetch(`{{ route('spareparts.search') }}?q=` + encodeURIComponent(q), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(list => {
                    spResults.innerHTML = '';
                    if (!list || !list.length) {
                        spResults.innerHTML = `<div class="text-sm text-gray-500">No results found</div>`;
                    } else {
                        list.forEach(sp => {
                            const div = document.createElement('div');
                            div.className = 'p-2 cursor-pointer hover:bg-gray-100 text-sm border-b';
                            div.textContent = `${sp.brand} ${sp.model} — ${sp.name} (${sp.category}) — Rp ${Number(sp.price).toLocaleString()} — stock: ${sp.stock}`;
                            div.dataset.sp = JSON.stringify(sp);
                            div.addEventListener('click', () => {
                                selectedSparepart = sp;
                                chosenSp.innerHTML = `<strong>${sp.brand} ${sp.model} - ${sp.name}</strong><div class="text-xs text-gray-600">${sp.category} — Rp ${Number(sp.price).toLocaleString()} — Stock: ${sp.stock}</div>`;
                                spPrice.value = sp.price;
                                spQuantity.value = 1;
                                spResults.classList.add('hidden');
                            });
                            spResults.appendChild(div);
                        });
                    }
                    spResults.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error searching spareparts:', error);
                    spResults.innerHTML = `<div class="text-sm text-red-500">Error searching spareparts</div>`;
                    spResults.classList.remove('hidden');
                });
            }, 300);
        });
    }

    if (addSpBtn) {
        addSpBtn.addEventListener('click', async () => {
            if (!selectedSparepart) {
                alert('Please choose a sparepart first');
                return;
            }
            const quantity = parseInt(spQuantity.value) || 1;
            const price = parseFloat(spPrice.value) || selectedSparepart.price;
            if (quantity < 1) {
                alert('Quantity must be at least 1');
                return;
            }
            if (price < 0) {
                alert('Price cannot be negative');
                return;
            }
            const repairId = document.getElementById('modal_repair_id').value;
            if (!repairId) {
                alert('Repair ID not found');
                return;
            }

            const newSparepart = {
                id: null,
                sparepart_id: selectedSparepart.id,
                name: `${selectedSparepart.brand} ${selectedSparepart.model} - ${selectedSparepart.name}`,
                category: selectedSparepart.category,
                quantity: quantity,
                price: price,
                source: 'in_store'
            };

            try {
                const sparepartData = new URLSearchParams();
                sparepartData.append('sparepart_id', newSparepart.sparepart_id || '');
                sparepartData.append('name', newSparepart.name);
                sparepartData.append('quantity', newSparepart.quantity.toString());
                sparepartData.append('price', newSparepart.price.toString());
                sparepartData.append('source', newSparepart.source);
                sparepartData.append('category', newSparepart.category);

                const sparepartResponse = await fetch(`/technician/repairs/${repairId}/spareparts`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: sparepartData.toString()
                });

                if (!sparepartResponse.ok) {
                    let errorMsg = 'Unknown error';
                    try {
                        const errorResult = await sparepartResponse.json();
                        errorMsg = errorResult.message || errorMsg;
                    } catch (e) {
                        const text = await sparepartResponse.text();
                        errorMsg = `HTTP ${sparepartResponse.status}: ${text}`;
                    }
                    throw new Error(errorMsg);
                }

                const result = await sparepartResponse.json();
                
                if (result.success) {
                    newSparepart.id = result.sparepart_id;
                    pendingSpareparts.push(newSparepart);
                    renderPendingList();
                    updateFinalCost();
                    alert('Sparepart added successfully!');
                } else {
                    throw new Error(result.message || 'Failed to add sparepart');
                }

            } catch (err) {
                console.error('Error:', err);
                alert('Error saving sparepart: ' + err.message);
            }

            selectedSparepart = null;
            chosenSp.innerHTML = 'None';
            spSearch.value = '';
            spQuantity.value = 1;
            spPrice.value = '';
            spResults.classList.add('hidden');
        });
    }

    if (addExtSpBtn) {
        addExtSpBtn.addEventListener('click', async () => {
            if (!extName.value.trim()) {
                alert('Please enter sparepart name');
                return;
            }
            const quantity = parseInt(extQuantity.value) || 1;
            const price = parseFloat(extPrice.value) || 0;
            if (quantity < 1) {
                alert('Quantity must be at least 1');
                return;
            }
            if (price < 0) {
                alert('Price cannot be negative');
                return;
            }
            const repairId = document.getElementById('modal_repair_id').value;
            if (!repairId) {
                alert('Repair ID not found');
                return;
            }

            const newSparepart = {
                id: null,
                sparepart_id: null,
                name: `${extBrand.value} ${extModel.value} - ${extName.value}`.trim(),
                category: extCategory.value,
                quantity: quantity,
                price: price,
                source: spSource.value
            };

            try {
                const sparepartData = new URLSearchParams();
                sparepartData.append('sparepart_id', '');
                sparepartData.append('name', newSparepart.name);
                sparepartData.append('quantity', newSparepart.quantity.toString());
                sparepartData.append('price', newSparepart.price.toString());
                sparepartData.append('source', newSparepart.source);
                sparepartData.append('category', newSparepart.category);

                const sparepartResponse = await fetch(`/technician/repairs/${repairId}/spareparts`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: sparepartData.toString()
                });

                if (!sparepartResponse.ok) {
                    const errorResult = await sparepartResponse.json();
                    throw new Error(errorResult.message || 'Unknown error');
                }

                const result = await sparepartResponse.json();
                
                if (result.success) {
                    newSparepart.id = result.sparepart_id;
                    pendingSpareparts.push(newSparepart);
                    renderPendingList();
                    updateFinalCost();
                    alert('Sparepart added successfully!');
                } else {
                    throw new Error(result.message || 'Failed to add sparepart');
                }

            } catch (err) {
                console.error('Error:', err);
                alert('Error saving sparepart: ' + err.message);
            }

            extBrand.value = '';
            extModel.value = '';
            extName.value = '';
            extQuantity.value = 1;
            extPrice.value = '';
        });
    }

    if (clearChosen) {
        clearChosen.addEventListener('click', () => {
            selectedSparepart = null;
            chosenSp.innerHTML = 'None';
            spSearch.value = '';
            spQuantity.value = 1;
            spPrice.value = '';
            spResults.classList.add('hidden');
        });
    }

    const jobForm = document.getElementById('jobForm');
    if (jobForm) {
        jobForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleFormSubmission();
        });
    }
}

function renderPendingList() {
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
                    Category: ${it.category} | Qty: ${it.quantity} | 
                    Price: Rp ${Number(it.price).toLocaleString()} | 
                    Total: Rp ${Number(it.price * it.quantity).toLocaleString()} | 
                    Source: ${it.source}
                </div>
            </div>
            <div class="flex gap-2">
                <button type="button" class="px-3 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600" onclick="removePending(${idx})">
                    Remove
                </button>
            </div>
        `;
        spListArea.appendChild(el);
    });
}

function updateFinalCost() {
    if (!finalCost) return;
    
    const total = pendingSpareparts.reduce((sum, it) => sum + (it.price * it.quantity), 0);
    finalCost.value = total.toFixed(2);
}

async function removePending(index) {
    if (!confirm('Are you sure you want to remove this sparepart?')) {
        return;
    }
    
    const sparepart = pendingSpareparts[index];
    const repairId = document.getElementById('modal_repair_id')?.value;

    if (!repairId) {
        console.error('Repair ID not found');
        return;
    }

    if (sparepart.id) {
        try {
            const response = await fetch(`/repairs/${repairId}/spareparts/${sparepart.id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    console.log('Sparepart removed from database');
                } else {
                    console.error('Failed to remove from database:', result.message);
                    alert('Warning: Sparepart removed from list but may still exist in database');
                }
            } else {
                console.error('HTTP error removing sparepart:', response.status);
                alert('Warning: Sparepart removed from list but may still exist in database');
            }
        } catch (error) {
            console.error('Error removing from database:', error);
            alert('Warning: Sparepart removed from list but may still exist in database');
        }
    }

    pendingSpareparts.splice(index, 1);
    renderPendingList();
    updateFinalCost();
}

async function loadSparepartsForRepair(repairId) {
    try {
        const response = await fetch(`/technician/repairs/${repairId}/spareparts`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const existingSpareparts = await response.json();
            pendingSpareparts = [];
            
            if (Array.isArray(existingSpareparts)) {
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
            }
            
            renderPendingList();
            updateFinalCost();
        } else {
            console.error('Failed to load spareparts:', response.status);
        }
    } catch (error) {
        console.error('Error loading spareparts:', error);
    }
}

async function openJobModal(id) {
    const card = document.querySelector(`[data-active-id='${id}']`);
    const form = document.getElementById('jobForm');
    if (!form) {
        console.error('Job form not found');
        return;
    }
    form.action = `/technician/jobs/${id}/update`;
    document.getElementById('modal_repair_id').value = id;
    
    form.querySelector("textarea[name='diagnosis']").value = card?.dataset.diagnosis || '';
    form.querySelector("select[name='status']").value = card?.dataset.status || 'in_progress';
    form.querySelector("input[name='cost']").value = card?.dataset.cost || '';
    
    pendingSpareparts = [];
    await loadSparepartsForRepair(id);
    
    selectedSparepart = null;
    chosenSp.innerHTML = 'None';
    spSearch.value = '';
    spQuantity.value = 1;
    spPrice.value = '';
    spResults.classList.add('hidden');
    
    extBrand.value = '';
    extModel.value = '';
    extName.value = '';
    extQuantity.value = 1;
    extPrice.value = '';
    
    spSource.value = 'in_store';
    inStoreSection.classList.remove('hidden');
    externalSection.classList.add('hidden');
    
    document.getElementById('jobModal').showModal();
}

async function handleFormSubmission() {
    const form = document.getElementById('jobForm');
    if (!form) {
        console.error('Job form not found');
        return;
    }
    
    const repairId = document.getElementById('modal_repair_id')?.value;
    const submitButton = form.querySelector('button[type="submit"]');
    
    if (!repairId) {
        alert('Error: Repair ID not found');
        return;
    }
    
    submitButton.disabled = true;
    submitButton.textContent = 'Saving...';

    try {
        const formData = new FormData(form);
        
        const repairResponse = await fetch(`/technician/jobs/${repairId}/update`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        if (!repairResponse.ok) {
            throw new Error('Failed to update repair data');
        }

        try {
            await fetch(`/technician/repairs/${repairId}/update-sparepart-field`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
        } catch (e) {
            console.warn('Failed to update sparepart field:', e);
        }

        document.getElementById('jobModal').close();
        
        setTimeout(() => {
            alert('Job updated successfully!');
            location.reload();
        }, 500);
        
    } catch(err) {
        console.error('Operation failed:', err);
        alert('Error updating job: ' + err.message);
        
        submitButton.disabled = false;
        submitButton.textContent = 'Save All';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
});

const jobModal = document.getElementById('jobModal');
if (jobModal) {
    jobModal.addEventListener('click', function(event) {
        if (event.target === this) {
            this.close();
        }
    });
}
</script>