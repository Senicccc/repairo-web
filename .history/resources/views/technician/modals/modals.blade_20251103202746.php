<!-- Job Update Modal -->
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

// Initialize event listeners
function initializeEventListeners() {
    // Toggle between in-store and external sparepart sections
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

    // Search for spareparts
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

    // Add in-store sparepart to list - FIXED URL
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

            const it = {
                sparepart_id: selectedSparepart.id,
                name: `${selectedSparepart.brand} ${selectedSparepart.model} - ${selectedSparepart.name}`,
                category: selectedSparepart.category,
                quantity: quantity,
                price: price,
                source: 'in_store'
            };

            try {
                console.log('Sending sparepart data:', it);
                
                const sparepartData = new URLSearchParams();
                sparepartData.append('sparepart_id', it.sparepart_id || '');
                sparepartData.append('name', it.name);
                sparepartData.append('quantity', it.quantity.toString());
                sparepartData.append('price', it.price.toString());
                sparepartData.append('source', it.source);
                sparepartData.append('category', it.category);

                // 🔥 FIXED: Gunakan URL yang benar dengan prefix technician
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

                console.log('Response status:', sparepartResponse.status);

                if (!sparepartResponse.ok) {
                    let errorMsg = 'Unknown error';
                    try {
                        const errorResult = await sparepartResponse.json();
                        errorMsg = errorResult.message || errorMsg;
                    } catch (e) {
                        const text = await sparepartResponse.text();
                        errorMsg = `HTTP ${sparepartResponse.status}: ${text}`;
                    }
                    alert('Failed to save sparepart: ' + errorMsg);
                } else {
                    const result = await sparepartResponse.json();
                    console.log('Success response:', result);
                    await loadSparepartsForRepair(repairId);
                    updateFinalCost();
                    alert('Sparepart added successfully!');
                }
            } catch (err) {
                console.error('Network error:', err);
                alert('Network error saving sparepart: ' + err.message);
            }

            // Reset selection
            selectedSparepart = null;
            chosenSp.innerHTML = 'None';
            spSearch.value = '';
            spQuantity.value = 1;
            spPrice.value = '';
            spResults.classList.add('hidden');
        });
    }

    // Add external sparepart to list - FIXED URL
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

            const it = {
                sparepart_id: null,
                name: `${extBrand.value} ${extModel.value} - ${extName.value}`.trim(),
                category: extCategory.value,
                quantity: quantity,
                price: price,
                source: spSource.value
            };

            try {
                console.log('Sending external sparepart data:', it);
                
                const sparepartData = new URLSearchParams();
                sparepartData.append('sparepart_id', '');
                sparepartData.append('name', it.name);
                sparepartData.append('quantity', it.quantity.toString());
                sparepartData.append('price', it.price.toString());
                sparepartData.append('source', it.source);
                sparepartData.append('category', it.category);

                // 🔥 FIXED: Gunakan URL yang benar dengan prefix technician
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

                console.log('Response status:', sparepartResponse.status);

                if (!sparepartResponse.ok) {
                    const errorResult = await sparepartResponse.json();
                    alert('Failed to save sparepart: ' + (errorResult.message || 'Unknown error'));
                } else {
                    const result = await sparepartResponse.json();
                    console.log('Success response:', result);
                    await loadSparepartsForRepair(repairId);
                    updateFinalCost();
                    alert('Sparepart added successfully!');
                }
            } catch (err) {
                console.error('Network error:', err);
                alert('Network error saving sparepart: ' + err.message);
            }

            // Reset form
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

    // Form submission handler
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

    // If sparepart exists in database (has ID), remove from database
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

            const result = await response.json();

            if (response.ok && result.success) {
                console.log('Sparepart removed from database');
            } else {
                console.error('Failed to remove from database:', result.message);
                alert('Warning: Sparepart removed from list but may still exist in database');
            }
        } catch (error) {
            console.error('Error removing from database:', error);
            alert('Warning: Sparepart removed from list but may still exist in database');
        }
    }

    // Remove from JavaScript list
    pendingSpareparts.splice(index, 1);
    renderPendingList();
    updateFinalCost();
}

async function loadSparepartsForRepair(repairId) {
    try {
        const response = await fetch(`/repairs/${repairId}/spareparts`, {
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
    
    // Populate form fields from card data
    form.querySelector("textarea[name='diagnosis']").value = card?.dataset.diagnosis || '';
    form.querySelector("select[name='status']").value = card?.dataset.status || 'in_progress';
    form.querySelector("input[name='cost']").value = card?.dataset.cost || '';
    
    // Reset pendingSpareparts first
    pendingSpareparts = [];
    
    try {
        // Load existing spareparts from database
        await loadSparepartsForRepair(id);
    } catch (error) {
        console.error('Error loading existing spareparts:', error);
    }
    
    // Render list and update cost
    renderPendingList();
    updateFinalCost();
    
    // Reset UI elements
    selectedSparepart = null;
    chosenSp.innerHTML = 'None';
    spSearch.value = '';
    spQuantity.value = 1;
    spPrice.value = '';
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
    
    // Show modal
    document.getElementById('jobModal').showModal();
}

// Form submission handler
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
    
    // Disable submit button to prevent double submission
    submitButton.disabled = true;
    submitButton.textContent = 'Saving...';

    try {
        // Update repair data
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

        // SUCCESS
        document.getElementById('jobModal').close();
        
        // Show success message
        setTimeout(() => {
            alert('Job updated successfully!');
            location.reload();
        }, 500);
        
    } catch(err) {
        console.error('Operation failed:', err);
        alert('Error updating job: ' + err.message);
        
        // Re-enable submit button
        submitButton.disabled = false;
        submitButton.textContent = 'Save All';
    }
}

// Initialize everything when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
});

// Close modal when clicking outside
const jobModal = document.getElementById('jobModal');
if (jobModal) {
    jobModal.addEventListener('click', function(event) {
        if (event.target === this) {
            this.close();
        }
    });
}
</script>