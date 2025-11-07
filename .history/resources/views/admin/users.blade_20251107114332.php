<section id="users" class="tab-section">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Users Management</h2>
        <button onclick="openUserModal()" 
            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
            <span class="mr-2">+</span> Add User
        </button>
    </div>

    <div class="mb-4">
        <input type="text" id="userSearch" placeholder="Search users by name, email, or role..." 
            class="border border-gray-300 px-4 py-2 rounded-lg w-1/2">
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="usersTableBody">
                @foreach ($users as $user)
                <tr id="user-row-{{ $user->id }}" class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $user->id }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $user->phone ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 
                               ($user->role === 'technician' ? 'bg-blue-100 text-blue-800' : 
                               ($user->role === 'cashier' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <button onclick="editUser({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                        <button onclick="deleteUser({{ $user->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-sm text-gray-600">
        Total: {{ $users->count() }} users
    </div>

    <div class="mt-4 flex items-center justify-between">
        <div class="text-sm text-gray-600">
            Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ number_format($users->total()) }} users
        </div>
        <div class="flex space-x-2">
            @if ($users->onFirstPage())
                <span class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-400 bg-gray-100 cursor-not-allowed">
                    Previous
                </span>
            @else
                <button onclick="loadPage('users', '{{ $users->previousPageUrl() }}')" class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-200">
                    Previous
                </button>
            @endif

            @if ($users->hasMorePages())
                <button onclick="loadPage('users', '{{ $users->nextPageUrl() }}')" class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-200">
                    Next
                </button>
            @else
                <span class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-400 bg-gray-100 cursor-not-allowed">
                    Next
                </span>
            @endif
        </div>
    </div>
</section>

<!-- Modal -->
<div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
        <h3 id="modalTitle" class="text-xl font-semibold mb-4">Add User</h3>

        <form id="userForm">
            @csrf
            <input type="hidden" id="user_id">

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Full Name</label>
                <input type="text" id="user_name" class="w-full border border-gray-300 rounded-lg p-2.5" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" id="user_email" class="w-full border border-gray-300 rounded-lg p-2.5" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Phone</label>
                <input type="text" id="user_phone" class="w-full border border-gray-300 rounded-lg p-2.5" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Role</label>
                <select id="user_role" class="w-full border border-gray-300 rounded-lg p-2.5" required>
                    <option value="user">User</option>
                    <option value="cashier">Cashier</option>
                    <option value="technician">Technician</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" id="user_password" class="w-full border border-gray-300 rounded-lg p-2.5" placeholder="Leave blank to keep current (on edit)">
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeUserModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Cancel</button>
                <button id="saveUserBtn" type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>



<script>
// Modal Functions
function openUserModal() {
    document.getElementById('modalTitle').innerText = 'Add User';
    document.getElementById('userForm').reset();
    document.getElementById('user_id').value = '';
    document.getElementById('userModal').classList.replace('hidden', 'flex');
    document.getElementById('user_password').required = true;
}

function closeUserModal() {
    document.getElementById('userModal').classList.replace('flex', 'hidden');
}

// Edit User
async function editUser(id) {
    try {
        const response = await fetch(`/admin/users/${id}`);
        if (!response.ok) throw new Error('Failed to fetch user data');
        
        const user = await response.json();
        
        document.getElementById('modalTitle').innerText = 'Edit User';
        document.getElementById('user_id').value = user.id;
        document.getElementById('user_name').value = user.name;
        document.getElementById('user_email').value = user.email;
        document.getElementById('user_phone').value = user.phone || '';
        document.getElementById('user_role').value = user.role;
        document.getElementById('user_password').required = false;
        document.getElementById('user_password').value = '';
        
        document.getElementById('userModal').classList.replace('hidden', 'flex');
    } catch (error) {
        console.error('Error fetching user:', error);
        showAlert('Error loading user data', 'error');
    }
}

// Form Submission
document.getElementById('userForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const submitBtn = document.getElementById('saveUserBtn');
    const originalText = submitBtn.innerText;
    submitBtn.disabled = true;
    submitBtn.innerText = 'Saving...';
    
    try {
        const id = document.getElementById('user_id').value;
        const url = id ? `/admin/users/${id}` : '/admin/users';
        const method = id ? 'PUT' : 'POST';

        const formData = {
            name: document.getElementById('user_name').value,
            email: document.getElementById('user_email').value,
            phone: document.getElementById('user_phone').value,
            role: document.getElementById('user_role').value,
            password: document.getElementById('user_password').value
        };

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();
        
        if (data.success || data.message) {
            showAlert(data.message || 'User saved successfully!', 'success');
            closeUserModal();
            
            // Refresh users section
            setTimeout(() => {
                showSection('users', document.querySelector('[onclick*="users"]'));
            }, 500);
        } else {
            throw new Error(data.message || 'Failed to save user');
        }
        
    } catch (error) {
        console.error('Error saving user:', error);
        showAlert(error.message || 'Failed to save user', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerText = originalText;
    }
});

// Delete User
async function deleteUser(id) {
    if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        return;
    }

    try {
        const response = await fetch(`/admin/users/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        
        if (data.message) {
            document.getElementById(`user-row-${id}`)?.remove();
            showAlert('User deleted successfully!', 'success');
            updateUserCount();
        } else {
            throw new Error(data.message || 'Failed to delete user');
        }
        
    } catch (error) {
        console.error('Error deleting user:', error);
        showAlert('Failed to delete user', 'error');
    }
}

// Update user count after deletion
function updateUserCount() {
    const tableBody = document.getElementById('usersTableBody');
    const countElement = document.querySelector('#users .text-sm.text-gray-600');
    if (tableBody && countElement) {
        const visibleRows = tableBody.querySelectorAll('tr:not([style*="display: none"])');
        countElement.textContent = `Total: ${visibleRows.length} users`;
    }
}

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('userSearch');
    if (searchInput) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.trim();
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Reset to original view if search is empty
            if (query === '') {
                showSection('users', document.querySelector('[onclick*="users"]'));
                return;
            }
            
            // Show loading state in table
            const tableBody = document.getElementById('usersTableBody');
            if (tableBody) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-600">
                            <div class="flex justify-center items-center">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mr-2"></div>
                                Searching...
                            </div>
                        </td>
                    </tr>
                `;
            }
            
            // Debounce search - wait 500ms after typing stops
            searchTimeout = setTimeout(() => {
                fetch(`/admin/search/users?q=${encodeURIComponent(query)}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Search failed');
                        return response.text();
                    })
                    .then(html => {
                        const contentArea = document.getElementById('content-area');
                        contentArea.innerHTML = html;
                        console.log('✅ Search completed');
                    })
                    .catch(error => {
                        console.error('Error searching users:', error);
                        showAlert('Error searching users: ' + error.message, 'error');
                        // Restore section on error
                        showSection('users', document.querySelector('[onclick*="users"]'));
                    });
            }, 500);
        });
    }
});
</script>
