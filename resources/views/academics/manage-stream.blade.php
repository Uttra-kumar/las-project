@extends('layouts.app')

@section('title', 'Manage Streams')

@section('content')
<style>
    .stream-container {
        animation: fadeIn 0.3s ease;
    }

    .stream-header {
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

    .stream-header h2 {
        font-size: 1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

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

    .table-wrapper {
        background: white;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.7rem;
        min-width: 600px;
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

    .stream-id-badge {
        background: #e0e7ff;
        color: #4338ca;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.65rem;
        font-family: monospace;
    }

    .status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.6rem;
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

    .subject-tag {
        display: inline-block;
        background: #e0e7ff;
        color: #4338ca;
        padding: 2px 8px;
        border-radius: 15px;
        font-size: 0.6rem;
        margin: 2px;
    }

    .action-btns {
        display: flex;
        gap: 6px;
    }

    .btn-edit, .btn-delete {
        padding: 4px 8px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.65rem;
    }

    .btn-edit {
        background: #dbeafe;
        color: #2563eb;
    }

    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
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
        max-width: 550px;
        max-height: 80vh;
        overflow-y: auto;
        padding: 0;
    }
    .modal-header {
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8fafc;
        position: sticky;
        top: 0;
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
    .form-group select, .form-group input {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
    }
    .subjects-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
        max-height: 250px;
        overflow-y: auto;
        padding: 5px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fafcff;
    }
    .subject-check {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 5px 8px;
        cursor: pointer;
    }
    .subject-check:hover {
        background: #e2e8f0;
        border-radius: 5px;
    }
    .subject-check input {
        width: 14px;
        height: 14px;
        cursor: pointer;
    }
    .subject-check span {
        font-size: 0.7rem;
        cursor: pointer;
    }
    .modal-footer {
        padding: 12px 16px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        background: #f8fafc;
    }
    .btn-save, .btn-cancel {
        padding: 6px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .btn-save {
        background: linear-gradient(135deg, #10b981, #059669);
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
        .subjects-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="stream-container">
    <div class="stream-header">
        <h2><i class="fas fa-layer-group"></i> Manage Streams (11th & 12th)</h2>
    </div>

    <div class="top-bar">
        <div class="search-wrapper">
            <input type="text" id="searchInput" placeholder="Search by stream name or ID...">
            <button onclick="searchStreams()"><i class="fas fa-search"></i> Search</button>
        </div>
        <button class="btn-add" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Add Stream
        </button>
    </div>

    <div id="streamsTableWrapper">
        @include('academics.stream-table', ['streams' => $streams])
    </div>
</div>

<!-- Modal -->
<div class="modal" id="streamModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add Stream</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="streamForm">
            <div class="modal-body">
                <input type="hidden" id="streamId">
                <div class="form-group">
                    <label>Select Class <span class="req">*</span></label>
                    <select id="class_id" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Stream Name <span class="req">*</span></label>
                    <input type="text" id="stream_name" required placeholder="e.g., Science-Mathematics, Commerce, Arts">
                </div>
                <div class="form-group">
                    <label>Select Subjects <span class="req">*</span></label>
                    <div class="subjects-grid" id="subjectsGrid">
                        @foreach($subjects as $subject)
                        <label class="subject-check">
                            <input type="checkbox" value="{{ $subject->id }}" class="subject-checkbox">
                            <span>{{ $subject->subject_name }}</span>
                        </label>
                        @endforeach
                    </div>
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
                <button type="submit" class="btn-save">Save Stream</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('modalTitle').innerText = 'Add Stream';
        document.getElementById('streamForm').reset();
        document.getElementById('streamId').value = '';
        document.getElementById('statusGroup').style.display = 'none';
        
        // Uncheck all checkboxes
        document.querySelectorAll('.subject-checkbox').forEach(cb => {
            cb.checked = false;
        });
        
        document.getElementById('streamModal').classList.add('show');
    }

    function openEditModal(id) {
    document.getElementById('modalTitle').innerText = 'Edit Stream';
    document.getElementById('statusGroup').style.display = 'block';
    
    fetch(`/admin/streams/edit/${id}`)
        .then(response => response.json())
        .then(data => {
            // Set basic info
            document.getElementById('streamId').value = data.id;
            document.getElementById('class_id').value = data.class_id;
            document.getElementById('stream_name').value = data.stream_name;
            document.getElementById('status').value = data.status;
            
            // ✅ CRITICAL FIX: Get subjects and ensure it's array of numbers
            let selectedSubjects = data.subjects;
            
            // If it's a string, parse it
            if (typeof selectedSubjects === 'string') {
                selectedSubjects = JSON.parse(selectedSubjects);
            }
            
            // Convert all to numbers for proper comparison
            selectedSubjects = selectedSubjects.map(id => parseInt(id));
            
            // Debug
            console.log('Selected Subjects:', selectedSubjects);
            
            // Get all checkboxes
            let checkboxes = document.querySelectorAll('.subject-checkbox');
            
            // First uncheck all
            checkboxes.forEach(cb => {
                cb.checked = false;
            });
            
            // Then check matching ones
            checkboxes.forEach(cb => {
                let cbValue = parseInt(cb.value);
                if (selectedSubjects.includes(cbValue)) {
                    cb.checked = true;
                    console.log('Selected:', cbValue, cb.nextElementSibling.innerText);
                }
            });
            
            document.getElementById('streamModal').classList.add('show');
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error!', 'Failed to load stream data', 'error');
        });
}
    function closeModal() {
        document.getElementById('streamModal').classList.remove('show');
        document.getElementById('streamForm').reset();
    }

    document.getElementById('streamForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const streamId = document.getElementById('streamId').value;
        const url = streamId ? `/admin/streams/update/${streamId}` : '/admin/streams/store';
        
        // Get selected subjects
        let selectedSubjects = [];
        document.querySelectorAll('.subject-checkbox:checked').forEach(cb => {
            selectedSubjects.push(parseInt(cb.value));
        });
        
        if (selectedSubjects.length === 0) {
            Swal.fire('Error!', 'Please select at least one subject', 'error');
            return;
        }
        
        const formData = {
            class_id: document.getElementById('class_id').value,
            stream_name: document.getElementById('stream_name').value,
            subjects: selectedSubjects,
            _token: '{{ csrf_token() }}'
        };
        
        if (streamId) {
            formData.status = document.getElementById('status').value;
        }
        
        Swal.fire({
            title: 'Saving...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        fetch(url, {
            method: streamId ? 'PUT' : 'POST',
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
        })
        .catch(error => {
            Swal.fire({ icon: 'error', title: 'Error!', text: 'Something went wrong!' });
        });
    });

    function deleteStream(id, name) {
        Swal.fire({
            title: 'Are you sure?',
            text: `Delete "${name}" stream?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#1e3c72',
            confirmButtonText: 'Yes, delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/streams/delete/${id}`, {
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

    function searchStreams() {
        const search = document.getElementById('searchInput').value;
        fetch(`/admin/streams/search?search=${search}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('streamsTableWrapper').innerHTML = html;
            });
    }

    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') searchStreams();
    });
</script>
@endsection