@extends('layouts.app')

@section('title', 'Session Management')

@section('content')
<style>
    .session-container {
        animation: fadeIn 0.3s ease;
    }

    /* Top Bar */
    .top-bar {
        background: white;
        padding: 12px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .search-wrapper {
        display: flex;
        gap: 10px;
        align-items: center;
        flex: 1;
        max-width: 350px;
    }

    .search-wrapper input {
        flex: 1;
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.85rem;
        outline: none;
        background: #f8fafc;
    }

    .search-wrapper input:focus {
        border-color: #667eea;
        background: white;
    }

    .search-wrapper button {
        background: #667eea;
        border: none;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.8rem;
    }

    .btn-add {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 8px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102,126,234,0.4);
    }

    /* Table */
    .table-wrapper {
        background: white;
        border-radius: 10px;
        overflow-x: auto;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
    }

    .data-table th {
        background: #f8fafc;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        color: #1e293b;
        border-bottom: 1px solid #e2e8f0;
    }

    .data-table td {
        padding: 10px 15px;
        border-bottom: 1px solid #e2e8f0;
        color: #475569;
    }

    .data-table tr:hover {
        background: #f8fafc;
    }

    .session-id-badge {
        display: inline-block;
        padding: 4px 10px;
        background: #e0e7ff;
        color: #4338ca;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.7rem;
        font-family: monospace;
    }

    .status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
    }

    .status-active {
        background: #dcfce7;
        color: #16a34a;
    }

    .status-inactive {
        background: #fee2e2;
        color: #dc2626;
    }

    .action-btns {
        display: flex;
        gap: 8px;
    }

    .btn-edit, .btn-delete {
        padding: 5px 10px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
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
        max-width: 400px;
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
        font-size: 1rem;
        margin: 0;
        color: #1e293b;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.3rem;
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
        font-size: 0.8rem;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-cancel {
        background: #e2e8f0;
        color: #475569;
    }

    .pagination {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .top-bar {
            flex-direction: column;
            align-items: stretch;
        }
        .search-wrapper {
            max-width: 100%;
        }
        .data-table th, .data-table td {
            padding: 8px 10px;
        }
        .action-btns {
            flex-direction: row;
        }
    }
</style>

<div class="session-container">
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="search-wrapper">
            <input type="text" id="searchInput" placeholder="Search by session name or session ID..." autocomplete="off">
            <button onclick="searchSessions()">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
        <button class="btn-add" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Add New Session
        </button>
    </div>

    <!-- Table Wrapper - Only table updates -->
    <div id="sessionsTableWrapper">
        @include('control-panel.session-table', ['sessions' => $sessions])
    </div>
</div>

<!-- Modal -->
<div class="modal" id="sessionModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add New Session</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="sessionForm">
            <div class="modal-body">
                <input type="hidden" id="sessionId" name="session_id">
                <div class="form-group">
                    <label>Session Name *</label>
                    <input type="text" id="sessionName" name="session_name" required placeholder="e.g., 2024-2025, 2025-2026">
                </div>
                <div class="form-group" id="statusGroup" style="display:none;">
                    <label>Status</label>
                    <select id="status" name="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-submit">Save Session</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('modalTitle').innerText = 'Add New Session';
        document.getElementById('sessionForm').reset();
        document.getElementById('sessionId').value = '';
        document.getElementById('statusGroup').style.display = 'none';
        document.getElementById('sessionModal').classList.add('show');
    }

    function openEditModal(id) {
        document.getElementById('modalTitle').innerText = 'Edit Session';
        document.getElementById('statusGroup').style.display = 'block';
        
        fetch(`/admin/sessions/edit/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('sessionId').value = data.id;
                document.getElementById('sessionName').value = data.session_name;
                document.getElementById('status').value = data.status;
                document.getElementById('sessionModal').classList.add('show');
            });
    }

    function closeModal() {
        document.getElementById('sessionModal').classList.remove('show');
        document.getElementById('sessionForm').reset();
    }

    document.getElementById('sessionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const sessionId = document.getElementById('sessionId').value;
        const url = sessionId ? `/admin/sessions/update/${sessionId}` : '/admin/sessions/store';
        const method = sessionId ? 'PUT' : 'POST';
        
        const formData = {
            session_name: document.getElementById('sessionName').value,
            _token: '{{ csrf_token() }}'
        };
        
        if (sessionId) {
            formData.status = document.getElementById('status').value;
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
            }
        });
    });

    function deleteSession(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#667eea',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/sessions/delete/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', data.success, 'success');
                        setTimeout(() => location.reload(), 1500);
                    }
                });
            }
        });
    }

    function searchSessions() {
        const search = document.getElementById('searchInput').value;
        
        // AJAX request - only updates table, not full page
        fetch(`/admin/sessions/search?search=${encodeURIComponent(search)}`)
            .then(response => response.text())
            .then(html => {
                // Only update the table wrapper, not the whole page
                document.getElementById('sessionsTableWrapper').innerHTML = html;
            })
            .catch(error => {
                console.error('Search error:', error);
            });
    }

    // Debounced search (optional - remove if not needed)
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        clearTimeout(searchTimeout);
        if (e.key === 'Enter') {
            searchSessions();
        } else {
            searchTimeout = setTimeout(() => {
                searchSessions();
            }, 500);
        }
    });
</script>
@endsection