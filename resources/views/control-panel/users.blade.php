@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<style>
    .users-container {
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
    }

    /* Search and Add Section */
    .search-add-section {
        background: white;
        padding: 7px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }

    .search-add-wrapper {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-box-wrapper {
        flex: 1;
        max-width: 300px;
    }

    .search-input-group {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .search-input-group input {
        flex: 1;
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.85rem;
        outline: none;
        background: #f8fafc;
    }

    .search-input-group input:focus {
        border-color: #667eea;
        background: white;
    }

    .search-btn {
        background: #667eea;
        border: none;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.8rem;
        transition: all 0.2s;
    }
   .redo-btn {
        background: lightcoral;
        border: none;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.8rem;
        transition: all 0.2s;
    }
    .search-btn:hover {
        background: #5a67d8;
    }

    .btn-add-user {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 8px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-add-user:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102,126,234,0.4);
    }

    /* Table Wrapper */
    .users-table-wrapper {
        background: white;
        border-radius: 10px;
        overflow-x: auto;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
    }

    .users-table th {
        background: #f8fafc;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        color: #1e293b;
        border-bottom: 1px solid #e2e8f0;
    }

    .users-table td {
        padding: 5px 15px;
        border-bottom: 1px solid #e2e8f0;
        color: #475569;
    }

    .users-table tr:hover {
        background: #f8fafc;
    }

    /* Role Badges */
    .role-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
    }

    .role-admin { background: #fee2e2; color: #dc2626; }
    .role-teacher { background: #dbeafe; color: #2563eb; }
    .role-accountant { background: #dcfce7; color: #16a34a; }
   

    /* Status */
    .status-active {
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #22c55e;
        border-radius: 50%;
        margin-right: 6px;
    }

    .status-inactive {
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #ef4444;
        border-radius: 50%;
        margin-right: 6px;
    }

    /* Action Buttons */
    .action-btns {
        display: flex;
        gap: 8px;
    }

    .btn-edit, .btn-delete {
        padding: 5px 10px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.75rem;
        transition: all 0.2s;
    }

    .btn-edit {
        background: #dbeafe;
        color: #2563eb;
    }

    .btn-edit:hover {
        background: #bfdbfe;
    }

    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-delete:hover {
        background: #fecaca;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .modal.show {
        display: flex;
    }

    .modal-content {
        background: white;
        width: 90%;
        max-width: 450px;
        border-radius: 12px;
        animation: slideIn 0.2s ease;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .modal-header {
        padding: 15px 20px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        font-size: 1.1rem;
        margin: 0;
        color: #1e293b;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #94a3b8;
    }

    .modal-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-size: 0.8rem;
        font-weight: 500;
        color: #1e293b;
    }

    .form-group input, .form-group select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.85rem;
        outline: none;
    }

    .form-group input:focus, .form-group select:focus {
        border-color: #667eea;
    }

    .modal-footer {
        padding: 15px 20px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-submit, .btn-cancel {
        padding: 8px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.85rem;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-cancel {
        background: #e2e8f0;
        color: #475569;
    }

    /* Pagination */
    .pagination {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }

    .pagination nav {
        display: inline-block;
    }

    .pagination .relative {
        color: #667eea;
    }

    @media (max-width: 768px) {
        .search-add-wrapper {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-box-wrapper {
            max-width: 100%;
        }
        
        .users-table th, .users-table td {
            padding: 8px 10px;
        }
        
        .action-btns {
            flex-direction: column;
        }
        
        .btn-add-user {
            justify-content: center;
        }
    }
</style>

<div class="users-container">
    <!-- Search and Add Button Section (No Heading) -->
    <div class="search-add-section">
        <div class="search-add-wrapper">
            <div class="search-box-wrapper">
                <div class="search-input-group">
                    <input type="text" id="searchInput" placeholder="Search by name, email, mobile..." autocomplete="off">
                    <button class="search-btn" onclick="searchUsers()">
                        <i class="fas fa-search"></i>
                    </button>
                    <button class="redo-btn" onclick="window.location.href='{{ route('users.index') }}'">
                        <i class="fas fa-redo"></i>
                    </button>
                </div>
            </div>
            <button class="btn-add-user" onclick="openAddModal()">
                <i class="fas fa-plus"></i> Add New User
            </button>
        </div>
    </div>

    <!-- Users Table -->
    <div class="users-table-wrapper" id="usersTableWrapper">
        @include('control-panel.users-table', ['users' => $users])
    </div>
</div>

<!-- Modal -->
<div class="modal" id="userModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add New User</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="userForm">
            <div class="modal-body">
                <input type="hidden" id="userId" name="user_id">
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" id="name" name="name" required placeholder="Enter full name">
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" id="email" name="email" required placeholder="Enter email address">
                </div>
                <div class="form-group">
                    <label>Mobile *</label>
                    <input type="text" id="mobile" name="mobile" required placeholder="Enter mobile number">
                </div>
                <div class="form-group">
                    <label>Role *</label>
                    <select id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="teacher">Teacher</option>
                        <option value="accountant">Accountant</option>
                    </select>
                </div>
                <div class="form-group" id="passwordGroup">
                    <label>Password *</label>
                    <input type="password" id="password" name="password" placeholder="Enter password (min 6 chars)">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-submit">Save User</button>
            </div>
        </form>
    </div>
</div>

<script>
    let isEditMode = false;

    function openAddModal() {
        isEditMode = false;
        document.getElementById('modalTitle').innerText = 'Add New User';
        document.getElementById('userForm').reset();
        document.getElementById('userId').value = '';
        document.getElementById('passwordGroup').style.display = 'block';
        document.getElementById('userModal').classList.add('show');
    }

    function openEditModal(id) {
        isEditMode = true;
        document.getElementById('modalTitle').innerText = 'Edit User';
        document.getElementById('passwordGroup').style.display = 'none';
        
        fetch(`/users/edit/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('userId').value = data.id;
                document.getElementById('name').value = data.name;
                document.getElementById('email').value = data.email;
                document.getElementById('mobile').value = data.mobile;
                document.getElementById('role').value = data.role;
                document.getElementById('userModal').classList.add('show');
            })
            .catch(error => {
                Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to load user data' });
            });
    }

    function closeModal() {
        document.getElementById('userModal').classList.remove('show');
        document.getElementById('userForm').reset();
    }

    document.getElementById('userForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const userId = document.getElementById('userId').value;
        const url = userId ? `/users/update/${userId}` : '/users/store';
        const method = userId ? 'PUT' : 'POST';
        
        const formData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            mobile: document.getElementById('mobile').value,
            role: document.getElementById('role').value,
            _token: '{{ csrf_token() }}'
        };
        
        if (!userId) {
            const password = document.getElementById('password').value;
            if (!password) {
                Swal.fire({ icon: 'error', title: 'Error!', text: 'Password is required!' });
                return;
            }
            formData.password = password;
        }
        
        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: 'Success!', text: data.success, timer: 1500, showConfirmButton: false });
                closeModal();
                setTimeout(() => location.reload(), 1500);
            } else if (data.errors) {
                let errorMsg = '';
                for (let key in data.errors) {
                    errorMsg += data.errors[key][0] + '\n';
                }
                Swal.fire({ icon: 'error', title: 'Error!', text: errorMsg });
            } else if (data.error) {
                Swal.fire({ icon: 'error', title: 'Error!', text: data.error });
            }
        })
        .catch(error => {
            Swal.fire({ icon: 'error', title: 'Error!', text: 'Something went wrong!' });
        });
    });

    function deleteUser(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#667eea',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/users/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', data.success, 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else if (data.error) {
                        Swal.fire('Error!', data.error, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error!', 'Failed to delete user!', 'error');
                });
            }
        });
    }

    function searchUsers() {
        const search = document.getElementById('searchInput').value;
        fetch(`/users/search?search=${encodeURIComponent(search)}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('usersTableWrapper').innerHTML = html;
            })
            .catch(error => {
                console.error('Search failed:', error);
            });
    }

    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            searchUsers();
        }
    });
</script>
@endsection