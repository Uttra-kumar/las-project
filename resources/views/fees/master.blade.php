@extends('layouts.app')

@section('title', 'Fees Master')

@section('content')
<style>
    .fees-master-container {
        animation: fadeIn 0.3s ease;
    }

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

    .filter-wrapper {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .filter-wrapper select, .filter-wrapper input {
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
    }

    .search-wrapper {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .search-wrapper input {
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
        width: 200px;
    }

    .search-wrapper button, .btn-add {
        background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
        border: none;
        color: white;
        padding: 6px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Session Info Bar */
    .session-info {
        background: #fef3c7;
        border: 1px solid #fde68a;
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

    .session-selector {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .session-selector select {
        padding: 6px 12px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        font-size: 0.75rem;
    }

    .btn-copy {
        background: #f59e0b;
        border: none;
        color: white;
        padding: 6px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-copy-green {
        background: #10b981;
    }

    /* Heading Section */
    .heading-section {
        background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
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
        border: 1px solid #e2e8f0;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.75rem;
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
        background: #fef3c7;
    }

    .badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 5px;
        font-size: 0.65rem;
        font-weight: 600;
    }

    .master-id-badge {
        background: #fef3c7;
        color: #d97706;
    }

    .class-badge {
        background: #e0e7ff;
        color: #4338ca;
    }

    .feetype-badge {
        background: #d1fae5;
        color: #059669;
    }

    .amount-badge {
        background: #fae8ff;
        color: #9333ea;
    }

    .status-badge {
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
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
        gap: 6px;
    }

    .btn-view, .btn-edit, .btn-delete {
        padding: 4px 8px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.65rem;
        font-weight: 600;
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
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        font-size: 0.95rem;
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.2rem;
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
        font-size: 0.7rem;
        font-weight: 600;
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
        background: linear-gradient(135deg, #f59e0b, #ea580c);
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
        .filter-wrapper {
            flex-direction: column;
            width: 100%;
        }
        .search-wrapper {
            width: 100%;
        }
        .search-wrapper input {
            flex: 1;
        }
        .session-info {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<div class="fees-master-container">
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="filter-wrapper">
            
            <div class="search-wrapper">
                <input type="text" id="searchInput" placeholder="Search..." autocomplete="off">
                <button onclick="searchFeesMaster()"><i class="fas fa-search"></i> Search</button>
                <button onclick="window.location.href='{{ route('fees.master') }}'"><i class="fas fa-redo"></i> </button>
            </div>
        </div>
        <button class="btn-add" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Add Fee Structure
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
            <button class="btn-copy btn-copy-green" onclick="copyFromPrevious()">
                <i class="fas fa-forward"></i> Copy to Current
            </button>
        </div>
    </div>

    <!-- Heading -->
    <div class="heading-section">
        <h2>
            <i class="fas fa-layer-group"></i> Fees Master
        </h2>
    </div>

    <!-- Table -->
    <div id="feesMasterTableWrapper">
        @include('fees.master-table', ['feesMasters' => $feesMasters])
    </div>
</div>

<!-- Previous Session Data Modal -->
<div class="modal" id="prevSessionModal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header">
            <h3><i class="fas fa-history"></i> Previous Session Fee Structures</h3>
            <button class="modal-close" onclick="closePrevModal()">&times;</button>
        </div>
        <div class="modal-body" id="prevSessionData" style="max-height: 400px; overflow-y: auto;">
            <div style="text-align: center; padding: 20px;">Select a session to view data</div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closePrevModal()">Close</button>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal" id="feesMasterModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add Fee Structure</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="feesMasterForm">
            <div class="modal-body">
                <input type="hidden" id="feeMasterId">
                <div class="form-group">
                    <label>Class *</label>
                    <select id="class_id" name="class_id" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Fee Type *</label>
                    <select id="fees_type_id" name="fees_type_id" required>
                        <option value="">Select Fee Type</option>
                        @foreach($feesTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Amount (₹) *</label>
                    <input type="number" id="amount" name="amount" step="0.01" min="0" placeholder="Enter amount" required>
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
        document.getElementById('modalTitle').innerText = 'Add Fee Structure';
        document.getElementById('feesMasterForm').reset();
        document.getElementById('feeMasterId').value = '';
        document.getElementById('statusGroup').style.display = 'none';
        document.getElementById('feesMasterModal').classList.add('show');
    }

    function openEditModal(id) {
        document.getElementById('modalTitle').innerText = 'Edit Fee Structure';
        document.getElementById('statusGroup').style.display = 'block';
        
        fetch(`/admin/fees-master/edit/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('feeMasterId').value = data.id;
                document.getElementById('class_id').value = data.class_id;
                document.getElementById('fees_type_id').value = data.fees_type_id;
                document.getElementById('amount').value = data.amount;
                document.getElementById('status').value = data.status;
                document.getElementById('feesMasterModal').classList.add('show');
            });
    }

    function viewFeesMaster(id) {
        fetch(`/admin/fees-master/view/${id}`)
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    title: 'Fee Structure Details',
                    html: `
                        <div style="text-align: left;">
                            <p><strong>Master ID:</strong> ${data.master_id}</p>
                            <p><strong>Class:</strong> ${data.class ? data.class.class_name : 'N/A'}</p>
                            <p><strong>Fee Type:</strong> ${data.fees_type ? data.fees_type.name : 'N/A'}</p>
                            <p><strong>Amount:</strong> ₹ ${parseFloat(data.amount).toLocaleString('en-IN')}</p>
                            <p><strong>Session:</strong> ${data.session_id}</p>
                            <p><strong>Status:</strong> ${data.status}</p>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonColor: '#f59e0b'
                });
            });
    }

    function closeModal() {
        document.getElementById('feesMasterModal').classList.remove('show');
        document.getElementById('feesMasterForm').reset();
    }

    function closePrevModal() {
        document.getElementById('prevSessionModal').classList.remove('show');
    }

    document.getElementById('feesMasterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = document.getElementById('feeMasterId').value;
        const url = id ? `/admin/fees-master/update/${id}` : '/admin/fees-master/store';
        const method = id ? 'PUT' : 'POST';
        
        const formData = {
            class_id: document.getElementById('class_id').value,
            fees_type_id: document.getElementById('fees_type_id').value,
            amount: document.getElementById('amount').value,
            _token: '{{ csrf_token() }}'
        };
        
        if (id) {
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

    function deleteFeesMaster(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#f59e0b',
            confirmButtonText: 'Yes, delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/fees-master/delete/${id}`, {
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

    function searchFeesMaster() {
        const classId = document.getElementById('filterClass').value;
        const feeTypeId = document.getElementById('filterFeeType').value;
        const search = document.getElementById('searchInput').value;
        let url = `/admin/fees-master/search?search=${search}`;
        if (classId) url += `&class_id=${classId}`;
        if (feeTypeId) url += `&fees_type_id=${feeTypeId}`;
        
        fetch(url)
            .then(response => response.text())
            .then(html => {
                document.getElementById('feesMasterTableWrapper').innerHTML = html;
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
        
        fetch(`/admin/fees-master/by-session?session_id=${sessionId}`)
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success && data.data.length > 0) {
                    currentViewData = data.data;
                    let html = `
                        <table style="width:100%; border-collapse: collapse; font-size: 0.7rem;">
                            <thead>
                                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                                    <th style="padding: 8px; text-align: left;">Master ID</th>
                                    <th style="padding: 8px; text-align: left;">Class</th>
                                    <th style="padding: 8px; text-align: left;">Fee Type</th>
                                    <th style="padding: 8px; text-align: left;">Amount</th>
                                    <th style="padding: 8px; text-align: left;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    data.data.forEach(item => {
                        html += `
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 6px 8px;"><span class="badge master-id-badge">${item.master_id}</span></td>
                                <td style="padding: 6px 8px;"><span class="badge class-badge">${item.class ? item.class.class_name : 'N/A'}</span></td>
                                <td style="padding: 6px 8px;"><span class="badge feetype-badge">${item.fees_type ? item.fees_type.name : 'N/A'}</span></td>
                                <td style="padding: 6px 8px;"><span class="badge amount-badge">₹ ${item.amount}</span></td>
                                <td style="padding: 6px 8px;"><span class="status-badge status-${item.status}">${item.status}</span></td>
                            </tr>
                        `;
                    });
                    html += `</tbody>atable>`;
                    document.getElementById('prevSessionData').innerHTML = html;
                } else {
                    document.getElementById('prevSessionData').innerHTML = '<div style="text-align: center; padding: 20px;">No fee structures found in selected session</div>';
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
        
        fetch('/admin/fees-master/copy-from-previous', {
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
                    confirmButtonColor: '#f59e0b'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Error!', text: data.error });
            }
        });
    }

    // Filters
    document.getElementById('filterClass').addEventListener('change', () => searchFeesMaster());
    document.getElementById('filterFeeType').addEventListener('change', () => searchFeesMaster());
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') searchFeesMaster();
    });
</script>
@endsection