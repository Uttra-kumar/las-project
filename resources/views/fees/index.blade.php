@extends('layouts.app')

@section('title', 'Fees Type Management')

@section('content')
<style>
    .fees-container {
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
        border-color: #10b981;
        background: white;
    }

    .search-wrapper button {
        background: #10b981;
        border: none;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.8rem;
    }

    .btn-add {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
        box-shadow: 0 4px 12px rgba(16,185,129,0.4);
    }

    /* Session Info Bar */
    .session-info {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: 10px 15px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .session-info .current-session {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .session-info i {
        color: #10b981;
    }

    .session-selector {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .session-selector select {
        padding: 6px 12px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        font-size: 0.8rem;
    }

    .btn-copy {
        background: #f59e0b;
        border: none;
        color: white;
        padding: 6px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-copy:hover {
        background: #d97706;
    }

    /* Heading Section */
    .heading-section {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 12px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .heading-section h2 {
        font-size: 1.1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
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
        padding: 10px 12px;
        text-align: left;
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

    .feetype-id-badge {
        display: inline-block;
        padding: 3px 8px;
        background: #d1fae5;
        color: #059669;
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

    .btn-view, .btn-edit, .btn-delete {
        padding: 4px 8px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        transition: all 0.2s;
        font-weight: 700;
    }

    .btn-view {
        background: #e0e7ff;
        color: #4338ca;
    }

    .btn-edit {
        background: #dbeafe;
        color: #2563eb;
    }

    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Modal Styles */
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
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        font-size: 1rem;
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.3rem;
        cursor: pointer;
    }

    .modal-body {
        padding: 16px;
    }

    .form-group {
        margin-bottom: 12px;
    }

    .form-group label {
        display: block;
        margin-bottom: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .form-group input, .form-group select {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.8rem;
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
        font-size: 0.75rem;
    }

    .btn-submit {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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

    @media (max-width: 768px) {
        .top-bar {
            flex-direction: column;
        }
        .search-wrapper {
            max-width: 100%;
        }
        .session-info {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<div class="fees-container">
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="search-wrapper">
            <input type="text" id="searchInput" placeholder="Search by fee type name or ID..." autocomplete="off">
            <button onclick="searchFees()"><i class="fas fa-search"></i> </button>
            <button onclick="window.location.href='{{ route('fees.index') }}'"><i class="fas fa-redo"></i> </button>
        </div>
        <button class="btn-add" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Add Fee Type
        </button>
    </div>

    <!-- Session Info & Copy Section -->
    <div class="session-info">
        <div class="current-session">
            <i class="fas fa-calendar-alt"></i>
            <span>Current Session: <strong>{{ $currentSession->session_name ?? 'No Active Session' }}</strong></span>
        </div>
        <div class="session-selector">
            <select id="previousSessionSelect">
                <option value="">Select Previous Session</option>
                @foreach($allSessions as $session)
                    @if($currentSession && $session->session_id != $currentSession->session_id)
                    <option value="{{ $session->session_id }}">{{ $session->session_name }}</option>
                    @endif
                @endforeach
            </select>
            <button class="btn-copy" onclick="loadPreviousSessionData()">
                <i class="fas fa-eye"></i> View Data
            </button>
            <button class="btn-copy" onclick="copyFromPrevious()" style="background: #10b981;">
                <i class="fas fa-forward"></i> Copy to Current
            </button>
        </div>
    </div>

    <!-- Heading Section -->
    <div class="heading-section">
        <h2>
            <i class="fas fa-tags"></i> Fee Type Management
        </h2>
    </div>

    <!-- Table -->
    <div id="feesTableWrapper">
        @include('fees.fees-table', ['feesTypes' => $feesTypes])
    </div>
</div>

<!-- Previous Session Data Modal -->
<div class="modal" id="prevSessionModal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3><i class="fas fa-history"></i> Previous Session Fee Types</h3>
            <button class="modal-close" onclick="closePrevModal()">&times;</button>
        </div>
        <div class="modal-body" id="prevSessionData">
            <div style="text-align: center; padding: 20px;">Select a session to view data</div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closePrevModal()">Close</button>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal" id="feesModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add Fee Type</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="feesForm">
            <div class="modal-body">
                <input type="hidden" id="feeTypeId" name="fee_type_id">
                <div class="form-group">
                    <label>Fee Type Name *</label>
                    <input type="text" id="name" name="name" required placeholder="e.g., Tuition Fee">
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
                <button type="submit" class="btn-submit">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentViewData = [];

    function openAddModal() {
        document.getElementById('modalTitle').innerText = 'Add Fee Type';
        document.getElementById('feesForm').reset();
        document.getElementById('feeTypeId').value = '';
        document.getElementById('statusGroup').style.display = 'none';
        document.getElementById('feesModal').classList.add('show');
    }

    function openEditModal(id) {
        document.getElementById('modalTitle').innerText = 'Edit Fee Type';
        document.getElementById('statusGroup').style.display = 'block';
        
        fetch(`/admin/fees-types/edit/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('feeTypeId').value = data.id;
                document.getElementById('name').value = data.name;
                document.getElementById('status').value = data.status;
                document.getElementById('feesModal').classList.add('show');
            });
    }

    function viewFeeType(id) {
        fetch(`/admin/fees-types/view/${id}`)
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    title: 'Fee Type Details',
                    html: `
                        <div style="text-align: left;">
                            <p><strong>Fee Type ID:</strong> ${data.fee_type_id}</p>
                            <p><strong>Name:</strong> ${data.name}</p>
                            <p><strong>Session:</strong> ${data.session_id}</p>
                            <p><strong>Status:</strong> ${data.status}</p>
                            <p><strong>Created:</strong> ${new Date(data.created_at).toLocaleString()}</p>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonColor: '#10b981'
                });
            });
    }

    function closeModal() {
        document.getElementById('feesModal').classList.remove('show');
        document.getElementById('feesForm').reset();
    }

    function closePrevModal() {
        document.getElementById('prevSessionModal').classList.remove('show');
    }

    document.getElementById('feesForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const feeTypeId = document.getElementById('feeTypeId').value;
        const url = feeTypeId ? `/admin/fees-types/update/${feeTypeId}` : '/admin/fees-types/store';
        const method = feeTypeId ? 'PUT' : 'POST';
        
        const formData = {
            name: document.getElementById('name').value,
            _token: '{{ csrf_token() }}'
        };
        
        if (feeTypeId) {
            formData.status = document.getElementById('status').value;
        }
        
        Swal.fire({
            title: 'Saving...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
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
        });
    });

    function deleteFeeType(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#10b981',
            confirmButtonText: 'Yes, delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/fees-types/delete/${id}`, {
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

    function searchFees() {
        const search = document.getElementById('searchInput').value;
        fetch(`/admin/fees-types/search?search=${search}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('feesTableWrapper').innerHTML = html;
            });
    }

    // Load previous session data for preview
    function loadPreviousSessionData() {
        const sessionId = document.getElementById('previousSessionSelect').value;
        if (!sessionId) {
            Swal.fire('Error!', 'Please select a session first!', 'error');
            return;
        }
        
        Swal.fire({
            title: 'Loading...',
            text: 'Fetching data from previous session',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        fetch(`/admin/fees-types/by-session?session_id=${sessionId}`)
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success && data.data.length > 0) {
                    currentViewData = data.data;
                    let html = `
                        <table style="width:100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                                    <th style="padding: 8px; text-align: left;">ID</th>
                                    <th style="padding: 8px; text-align: left;">Fee Type Name</th>
                                    <th style="padding: 8px; text-align: left;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    data.data.forEach(item => {
                        html += `
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 6px 8px;"><span class="feetype-id-badge">${item.fee_type_id}</span></td>
                                <td style="padding: 6px 8px;">${item.name}</td>
                                <td style="padding: 6px 8px;"><span class="status-badge status-${item.status}">${item.status}</span></td>
                            </tr>
                        `;
                    });
                    html += `</tbody></table>`;
                    document.getElementById('prevSessionData').innerHTML = html;
                } else {
                    document.getElementById('prevSessionData').innerHTML = '<div style="text-align: center; padding: 20px;">No fee types found in selected session</div>';
                }
                document.getElementById('prevSessionModal').classList.add('show');
            });
    }

    // Copy from previous session to current
    function copyFromPrevious() {
        const sessionId = document.getElementById('previousSessionSelect').value;
        if (!sessionId) {
            Swal.fire('Error!', 'Please select a session first!', 'error');
            return;
        }
        
        Swal.fire({
            title: 'Copying Data...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        fetch('/admin/fees-types/copy-from-previous', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ previous_session_id: sessionId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Copied Successfully!',
                    html: `${data.message}<br><br>Copied: ${data.copied}<br>Skipped: ${data.skipped}`,
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Error!', text: data.error });
            }
        });
    }

    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') searchFees();
    });
</script>
@endsection