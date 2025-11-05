<section id="users" class="tab-section">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Users Management</h2>
        <button onclick="openUserModal()" 
            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
            <span class="mr-2">+</span> Add User
        </button>
    </div>

    <div class="mb-4">
        <input type="text" placeholder="Search users..." 
            class="border border-gray-300 px-4 py-2 rounded-lg w-1/2 search" 
            data-table="users">
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
            <tbody class="bg-white divide-y divide-gray-200" id="users-table-body">
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
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
function openUserModal() {
    document.getElementById('modalTitle').innerText = 'Add User';
    document.getElementById('userForm').reset();
    document.getElementById('user_id').value = '';
    document.getElementById('userModal').classList.replace('hidden', 'flex');
}

function closeUserModal() {
    document.getElementById('userModal').classList.replace('flex', 'hidden');
}

function editUser(id) {
    fetch(`/admin/users/${id}`)
        .then(res => res.json())
        .then(user => {
            document.getElementById('modalTitle').innerText = 'Edit User';
            document.getElementById('user_id').value = user.id;
            document.getElementById('user_name').value = user.name;
            document.getElementById('user_email').value = user.email;
            document.getElementById('user_phone').value = user.phone;
            document.getElementById('user_role').value = user.role;
            document.getElementById('user_password').value = '';
            document.getElementById('userModal').classList.replace('hidden', 'flex');
        });
}

document.getElementById('userForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('user_id').value;
    const url = id ? `/admin/users/${id}` : '/admin/users';
    const method = id ? 'PUT' : 'POST';

    const payload = {
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
            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
        },
        body: JSON.stringify(payload)
    });

    const data = await response.json();
    alert(data.message || 'Success');
    closeUserModal();
    location.reload();
});

function deleteUser(id) {
    if (!confirm('Are you sure you want to delete this user?')) return;
    fetch(`/admin/users/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value }
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        document.getElementById(`user-row-${id}`).remove();
    })
    .catch(() => alert('Delete failed'));
}
</script>
