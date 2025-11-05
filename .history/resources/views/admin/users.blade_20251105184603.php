<section id="users" class="tab-section">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Users Management</h2>
        <button onclick="openUserModal()" 
            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center transition">
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

    <div class="mt-4 text-sm text-gray-600">
        Total: {{ $users->count() }} users
    </div>
</section>

<!-- Modal -->
<div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
        <h3 id="modalTitle" class="text-xl font-semibold mb-4">Add User</h3>

        <form id="userForm">
            @csrf
            <input type="hidden" name="id" id="user_id">

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Full Name</label>
                <input type="text" name="name" id="user_name" class="w-full border border-gray-300 rounded-lg p-2.5">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" id="user_email" class="w-full border border-gray-300 rounded-lg p-2.5">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Phone</label>
                <input type="text" name="phone" id="user_phone" class="w-full border border-gray-300 rounded-lg p-2.5">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Role</label>
                <select name="role" id="user_role" class="w-full border border-gray-300 rounded-lg p-2.5">
                    <option value="user">User</option>
                    <option value="customer">Customer</option>
                    <option value="cashier">Cashier</option>
                    <option value="technician">Technician</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" id="user_password" class="w-full border border-gray-300 rounded-lg p-2.5">
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
    document.getElementById('userModal').classList.remove('hidden');
    document.getElementById('userModal').classList.add('flex');
}

function closeUserModal() {
    document.getElementById('userModal').classList.remove('flex');
    document.getElementById('userModal').classList.add('hidden');
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
            document.getElementById('userModal').classList.remove('hidden');
            document.getElementById('userModal').classList.add('flex');
        });
}

document.getElementById('userForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    const id = formData.get('id');
    const url = id ? `/admin/users/${id}` : '/admin/users';
    const method = id ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: { 'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message || 'Success');
        location.reload();
    })
    .catch(() => alert('Something went wrong'));
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
