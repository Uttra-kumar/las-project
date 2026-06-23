@extends('layouts.app')

@section('title', 'Subject Management')

@section('content')
<style>
    .subjects-container {
        animation: fadeIn 0.3s ease;
    }

    /* Header */
    .subjects-header {
        background: linear-gradient(135deg, #1e3c72 0%, #0f2b4d 100%);
        color: white;
        padding: 10px 18px;
        border-radius: 10px;
        margin-bottom: 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .subjects-header h2 {
        font-size: 1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Top Bar */
    .top-bar {
        background: white;
        padding: 12px 15px;
        border-radius: 10px;
        margin-bottom: 18px;
        border: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .search-wrapper {
        display: flex;
        gap: 10px;
        align-items: center;
        flex: 1;
        max-width: 300px;
    }

    .search-wrapper input {
        flex: 1;
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
    }

    .search-wrapper button {
        background: #1e3c72;
        border: none;
        color: white;
        padding: 6px 14px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
    }

    .btn-add {
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        color: white;
        padding: 6px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Table */
    .table-wrapper {
        background: white;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.75rem;
        min-width: 500px;
    }

    .data-table th {
        text-align: left;
        padding: 10px 12px;
        background: #f8fafc;
        font-weight: 600;
        color: #1e293b;
        border-bottom: 1px solid #e2e8f0;
    }

    .data-table td {
        padding: 8px 12px;
        border-bottom: 1px solid #e2e8f0;
        color: #475569;
    }

    .data-table tr:hover {
        background: #f8fafc;
    }

    /* Badges */
    .subject-id-badge {
        background: #e0e7ff;
        color: #4338ca;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-family: monospace;
    }

    .status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
    }

    .status-active {
        background: #dcfce7;
        color: #16a34a;
    }

    .status-inactive {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Action Buttons */
    .action-btns {
        display: flex;
        gap: 8px;
    }

    .btn-edit, .btn-delete {
        padding: 4px 8px;
        border: none;
        border-radius: 5px;
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
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .modal.show { display: flex; }
    .modal-content {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 400px;
        padding: 0;
    }
    .modal-header {
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-header h3 { font-size: 0.9rem; margin: 0; }
    .modal-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
    }
    .modal-body { padding: 16px; }
    .form-group { margin-bottom: 12px; }
    .form-group label {
        display: block;
        font-size: 0.7rem;
        font-weight: 600;
        margin-bottom: 4px;
    }
    .form-group input, .form-group select {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
    }
    .modal-footer {
        padding: 12px 16px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }
    .btn-submit, .btn-cancel {
        padding: 6px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
    }
    .btn-submit {
        background: linear-gradient(135deg, #1e3c72, #2b4c7c);
        color: white;
    }
    .btn-cancel {
        background: #e2e8f0;
        color: #475569;
    }

    .pagination {
        margin-top: 15px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 640px) {
        .top-bar {
            flex-direction: column;
        }
        .search-wrapper {
            max-width: 100%;
        }
        .search-wrapper button {
            white-space: nowrap;
        }
    }
</style>

<div class="subjects-container">
    <div class="subjects-header">
        <h2><i class="fas fa-book"></i> Subject Management</h2>
    </div>

    <div class="top-bar">
        <div class="search-wrapper">
            <input type="text" id="searchInput" placeholder="Search by subject name or ID...">
            <button onclick="searchSubjects()"><i class="fas fa-search"></i> Search</button>
        </div>
        <button class="btn-add" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Add Subject
        </button>
    </div>

    <div id="subjectsTableWrapper">
        @include('academics.subjects-table', ['subjects' => $subjects])
    </div>
</div>

<!-- Modal -->
<div class="modal" id="subjectModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add Subject</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="subjectForm">
            <div class="modal-body">
                <input type="hidden" id="subjectId">
                <div class="form-group">
                    <label>Subject Name *</label>
                    <input type="text" id="subject_name" required placeholder="e.g., Mathematics, Science">
                </div>
                <div class="form-group" id="statusGroup" style="display:none;">
                    <label>Status</label>
                    <select id="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-submit">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('modalTitle').innerText = 'Add Subject';
        document.getElementById('subjectForm').reset();
        document.getElementById('subjectId').value = '';
        document.getElementById('statusGroup').style.display = 'none';
        document.getElementById('subjectModal').classList.add('show');
    }

    function openEditModal(id) {
        document.getElementById('modalTitle').innerText = 'Edit Subject';
        document.getElementById('statusGroup').style.display = 'block';
        
        fetch(`/admin/subjects/edit/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('subjectId').value = data.id;
                document.getElementById('subject_name').value = data.subject_name;
                document.getElementById('status').value = data.status;
                document.getElementById('subjectModal').classList.add('show');
            });
    }

    function closeModal() {
        document.getElementById('subjectModal').classList.remove('show');
        document.getElementById('subjectForm').reset();
    }

    document.getElementById('subjectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const subjectId = document.getElementById('subjectId').value;
        const url = subjectId ? `/admin/subjects/update/${subjectId}` : '/admin/subjects/store';
        
        const formData = {
            subject_name: document.getElementById('subject_name').value,
            _token: '{{ csrf_token() }}'
        };
        
        if (subjectId) {
            formData.status = document.getElementById('status').value;
        }
        
        Swal.fire({
            title: 'Saving...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        fetch(url, {
            method: subjectId ? 'PUT' : 'POST',
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

    function deleteSubject(id, name) {
        Swal.fire({
            title: 'Are you sure?',
            text: `Delete ${name}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#1e3c72',
            confirmButtonText: 'Yes, delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/subjects/delete/${id}`, {
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

    function searchSubjects() {
        const search = document.getElementById('searchInput').value;
        fetch(`/admin/subjects/search?search=${search}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('subjectsTableWrapper').innerHTML = html;
            });
    }

    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') searchSubjects();
    });
</script>
@endsection