@extends('layouts.app')

@section('title', 'Fees Manager')

@section('content')
<style>
    .fees-manager-container {
        animation: fadeIn 0.3s ease;
    }

    .fees-header {
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

    .fees-header h2 {
        font-size: 1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .session-badge {
        background: rgba(255,255,255,0.2);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
    }

    .filter-section {
        background: white;
        border-radius: 10px;
        padding: 12px 15px;
        margin-bottom: 18px;
        border: 1px solid #e2e8f0;
    }

    .filter-row {
        display: flex;
        gap: 12px;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 180px;
    }

    .filter-group label {
        display: block;
        font-size: 0.65rem;
        font-weight: 600;
        color: #1e3c72;
        margin-bottom: 4px;
        text-transform: uppercase;
    }

    .filter-group select {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
    }

    .btn-filter {
        background: linear-gradient(135deg, #1e3c72, #2b4c7c);
        border: none;
        color: white;
        padding: 6px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.75rem;
    }

    /* Fees Cards */
    .fees-cards-section {
        background: white;
        border-radius: 10px;
        padding: 12px 15px;
        margin-bottom: 18px;
        border: 1px solid #e2e8f0;
    }

    .fees-cards-header {
        margin-bottom: 10px;
        padding-bottom: 6px;
        border-bottom: 1px solid #e2e8f0;
    }

    .fees-cards-header h3 {
        font-size: 0.8rem;
        font-weight: 600;
        color: #1e3c72;
        margin: 0;
    }

    .fees-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 10px;
    }

    .fees-card {
        background: #f8fafc;
        border-radius: 8px;
        padding: 10px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }

    .fees-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .fees-card.selected {
        border: 2px solid #10b981;
        background: #f0fdf4;
    }

    .fees-card-icon {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, #f59e0b, #ea580c);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.9rem;
    }

    .fees-card-info {
        flex: 1;
    }

    .fees-card-info h4 {
        font-size: 0.7rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 3px;
    }

    .fees-card-amount {
        font-size: 0.7rem;
        font-weight: 700;
        color: #f59e0b;
    }

    /* Already Assigned Section */
    .assigned-section {
        background: white;
        border-radius: 10px;
        padding: 12px 15px;
        margin-bottom: 18px;
        border: 1px solid #e2e8f0;
        display: none;
    }

    .assigned-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        padding-bottom: 6px;
        border-bottom: 1px solid #e2e8f0;
    }

    .assigned-header h3 {
        font-size: 0.8rem;
        margin: 0;
        color: #1e3c72;
    }

    .btn-remove-assigned {
        background: #ef4444;
        color: white;
        border: none;
        padding: 4px 12px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.65rem;
        display: none;
        font-weight: 600;
    }

    .assigned-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.7rem;
    }

    .assigned-table th {
        text-align: left;
        padding: 8px 10px;
        background: #f8fafc;
        font-weight: 600;
        border-bottom: 1px solid #e2e8f0;
    }

    .assigned-table td {
        padding: 6px 10px;
        border-bottom: 1px solid #e2e8f0;
    }

    .assigned-checkbox {
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .badge-assigned {
        background: #dcfce7;
        color: #15803d;
        padding: 2px 8px;
        border-radius: 15px;
        font-size: 0.6rem;
    }

    /* Student Table */
    .table-section {
        background: white;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .table-header {
        padding: 10px 15px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .selected-fees-info {
        background: #fef3c7;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 0.65rem;
        display: none;
        align-items: center;
        gap: 6px;
    }

    .assign-fees-btn {
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        color: white;
        padding: 5px 14px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .assign-fees-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .student-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.7rem;
    }

    .student-table th {
        text-align: left;
        padding: 8px 10px;
        background: #f8fafc;
        font-weight: 600;
        border-bottom: 1px solid #e2e8f0;
    }

    .student-table td {
        padding: 6px 10px;
        border-bottom: 1px solid #e2e8f0;
    }

    .student-checkbox {
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .badge-hostel {
        background: #dcfce7;
        color: #15803d;
        padding: 2px 6px;
        border-radius: 15px;
        font-size: 0.6rem;
    }

    .badge-nonhostel {
        background: #fff3e3;
        color: #b45309;
        padding: 2px 6px;
        border-radius: 15px;
        font-size: 0.6rem;
    }

    .no-data {
        text-align: center;
        padding: 40px;
        color: #94a3b8;
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
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal.show {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 450px;
        padding: 0;
    }

    .modal-header {
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
    }

    .modal-header h3 {
        font-size: 0.9rem;
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

    .modal-footer {
        padding: 12px 16px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .btn-cancel {
        background: #e2e8f0;
        border: none;
        padding: 6px 16px;
        border-radius: 6px;
        cursor: pointer;
    }

    .btn-confirm-remove {
        background: #ef4444;
        color: white;
        border: none;
        padding: 6px 16px;
        border-radius: 6px;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .filter-row {
            flex-direction: column;
        }
        .fees-cards-grid {
            grid-template-columns: 1fr;
        }
        .assigned-table {
            font-size: 0.6rem;
        }
    }
</style>

<div class="fees-manager-container">
    <div class="fees-header">
        <h2><i class="fas fa-rupee-sign"></i> Fees Manager</h2>
        <div class="session-badge">
            <i class="fas fa-calendar-alt"></i> Session: <strong>{{ $currentSession->session_name ?? 'No Active Session' }}</strong>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-row">
            <div class="filter-group">
                <label><i class="fas fa-chalkboard-user"></i> Select Class</label>
                <select id="classSelect">
                    <option value="">-- Select Class --</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <button class="btn-filter" onclick="filterClass()">
                    <i class="fas fa-filter"></i> Apply
                </button>
            </div>
        </div>
    </div>

    @if($selectedClass)
    <!-- Fees Cards -->
    <div class="fees-cards-section">
        <div class="fees-cards-header">
            <h3><i class="fas fa-tags"></i> Select Fees Type</h3>
        </div>
        <div class="fees-cards-grid" id="feesCards">
            @foreach($feesMasters as $feesMaster)
            <div class="fees-card" data-fees-id="{{ $feesMaster->id }}" onclick="selectFeesCard({{ $feesMaster->id }}, '{{ addslashes($feesMaster->feesType->name) }}', {{ $feesMaster->amount }})">
                <div class="fees-card-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="fees-card-info">
                    <h4>{{ $feesMaster->feesType->name ?? 'N/A' }}</h4>
                    <div class="fees-card-amount">₹ {{ number_format($feesMaster->amount, 2) }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Already Assigned Section -->
    <div class="assigned-section" id="assignedSection">
        <div class="assigned-header">
            <h3><i class="fas fa-check-circle" style="color: #10b981;"></i> Already Assigned Students</h3>
            <button class="btn-remove-assigned" id="removeBtn" onclick="openRemoveModal()">
                <i class="fas fa-trash"></i> Remove Selected
            </button>
        </div>
        <div id="assignedContainer">
            <div class="no-data">Please select a fees type to view assigned students</div>
        </div>
    </div>

    <!-- Student List -->
    <div class="table-section">
        <div class="table-header">
            <h3><i class="fas fa-users"></i> Student List</h3>
            <div>
                <span class="selected-fees-info" id="selectedFeesInfo">
                    <i class="fas fa-check-circle"></i> <span id="selectedFeesName">-</span> | ₹<span id="selectedFeesAmount">0</span>
                </span>
                <button class="assign-fees-btn" id="assignBtn" onclick="assignFees()" disabled>
                    <i class="fas fa-forward"></i> Assign
                </button>
            </div>
        </div>
        <div style="overflow-x: auto;">
            <table class="student-table">
                <thead>
                    <tr>
                        <th width="35"><input type="checkbox" id="selectAll" onclick="toggleSelectAll()"></th>
                        <th width="40">#</th>
                        <th>Student Name</th>
                        <th>Father Name</th>
                        <th>DOB</th>
                        <th>Gender</th>
                        <th>Mobile</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody id="studentTableBody">
                    @forelse($students as $key => $student)
                    <tr>
                        <td><input type="checkbox" class="student-checkbox" data-student-id="{{ $student->id }}" data-student-name="{{ $student->full_name }}" onchange="updateAssignButton()"></td>
                        <td>{{ $key + 1 }}</td>
                        <td><i class="fas fa-user-graduate"></i> {{ $student->full_name }}</td>
                        <td>{{ $student->father_name ?? '-' }}</td>
                        <td>{{ $student->dob ? date('d-m-Y', strtotime($student->dob)) : '-' }}</td>
                        <td>{{ $student->gender ?? '-' }}</td>
                        <td>{{ $student->mobile }}</td>
                        <td><span class="{{ $student->is_hosteler ? 'badge-hostel' : 'badge-nonhostel' }}">{{ $student->is_hosteler ? 'Hosteler' : 'Day Scholar' }}</span></td>
                    </tr>
                    @empty
                    <td><td colspan="8" class="no-data">No students found</td></td>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="table-section">
        <div class="no-data" style="padding: 50px;">
            <i class="fas fa-chalkboard-user" style="font-size: 2rem;"></i>
            <p>Select a class to view students</p>
        </div>
    </div>
    @endif
</div>

<!-- Remove Modal -->
<div class="modal" id="removeModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Remove Fees Assignment</h3>
            <button class="modal-close" onclick="closeRemoveModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p id="removeMessage"></p>
            <div id="removeStudentsList"></div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeRemoveModal()">Cancel</button>
            <button class="btn-confirm-remove" onclick="confirmRemove()">Remove</button>
        </div>
    </div>
</div>

<script>
    let selectedFeesId = null;
    let selectedFeesName = null;
    let selectedFeesAmount = null;
    let pendingRemoveStudents = [];

    function filterClass() {
        let classId = document.getElementById('classSelect').value;
        if (classId) {
            window.location.href = '{{ route("fees.manager") }}?class_id=' + classId;
        }
    }

    function selectFeesCard(id, name, amount) {
        selectedFeesId = id;
        selectedFeesName = name;
        selectedFeesAmount = amount;
        
        // Update card UI
        document.querySelectorAll('.fees-card').forEach(card => {
            card.classList.remove('selected');
        });
        document.querySelector(`.fees-card[data-fees-id="${id}"]`).classList.add('selected');
        
        // Show selected info
        document.getElementById('selectedFeesInfo').style.display = 'inline-flex';
        document.getElementById('selectedFeesName').innerText = name;
        document.getElementById('selectedFeesAmount').innerText = amount;
        
        // Enable assign button
        updateAssignButton();
        
        // Load assigned students
        loadAssignedStudents();
    }

    function loadAssignedStudents() {
        if (!selectedFeesId) return;
        
        let classId = document.getElementById('classSelect').value;
        
        fetch(`/fees-assigned?class_id=${classId}&fees_type_id=${selectedFeesId}`)
            .then(response => response.json())
            .then(data => {
                let assignedSection = document.getElementById('assignedSection');
                let removeBtn = document.getElementById('removeBtn');
                let container = document.getElementById('assignedContainer');
                
                if (data.length === 0) {
                    assignedSection.style.display = 'none';
                    return;
                }
                
                assignedSection.style.display = 'block';
                removeBtn.style.display = 'inline-block';
                
                let html = `
                    <div style="overflow-x: auto;">
                        <table class="assigned-table">
                            <thead>
                                <tr>
                                    <th width="30"><input type="checkbox" id="selectAllAssigned" onclick="toggleSelectAllAssigned()"></th>
                                    <th>Student Name</th>
                                    <th>Father Name</th>
                                    <th>Mobile</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                data.forEach(item => {
                    html += `
                        <tr>
                            <td><input type="checkbox" class="assigned-checkbox" data-student-id="${item.student_id}" data-student-name="${item.student?.full_name || 'N/A'}"></td>
                            <td><strong>${item.student?.full_name || 'N/A'}</strong></td>
                            <td>${item.student?.father_name || '-'}</td>
                            <td>${item.student?.mobile || '-'}</td>
                            <td>₹ ${parseFloat(item.amount).toLocaleString()}</td>
                            <td><span class="badge-assigned">Assigned</span></td>
                        </tr>
                    `;
                });
                
                html += `
                            </tbody>
                        </table>
                    </div>
                `;
                
                container.innerHTML = html;
            });
    }

    function toggleSelectAllAssigned() {
        let selectAll = document.getElementById('selectAllAssigned');
        if (selectAll) {
            document.querySelectorAll('.assigned-checkbox').forEach(cb => {
                cb.checked = selectAll.checked;
            });
        }
    }

    function openRemoveModal() {
        let selected = [];
        document.querySelectorAll('.assigned-checkbox:checked').forEach(cb => {
            selected.push({
                id: cb.getAttribute('data-student-id'),
                name: cb.getAttribute('data-student-name')
            });
        });
        
        if (selected.length === 0) {
            Swal.fire('Error!', 'Please select at least one student', 'error');
            return;
        }
        
        pendingRemoveStudents = selected;
        
        let studentList = '<ul style="margin: 10px 0 0 20px;">';
        selected.forEach(s => {
            studentList += `<li>${s.name}</li>`;
        });
        studentList += '</ul>';
        
        document.getElementById('removeMessage').innerHTML = `<strong>Remove ${selectedFeesName} (₹${selectedFeesAmount}) from following students?</strong>`;
        document.getElementById('removeStudentsList').innerHTML = studentList;
        document.getElementById('removeModal').classList.add('show');
    }

    function closeRemoveModal() {
        document.getElementById('removeModal').classList.remove('show');
        pendingRemoveStudents = [];
    }

    function confirmRemove() {
        let studentIds = pendingRemoveStudents.map(s => s.id);
        
        Swal.fire({
            title: 'Removing...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        fetch('{{ route("fees.remove") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                student_ids: studentIds,
                fees_master_id: selectedFeesId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Removed!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    closeRemoveModal();
                    loadAssignedStudents();
                    document.querySelectorAll('.student-checkbox').forEach(cb => {
                        if (studentIds.includes(cb.getAttribute('data-student-id'))) {
                            cb.checked = false;
                        }
                    });
                    updateAssignButton();
                });
            } else {
                Swal.fire('Error!', data.error, 'error');
            }
        });
    }

    function toggleSelectAll() {
        let selectAll = document.getElementById('selectAll');
        document.querySelectorAll('.student-checkbox').forEach(cb => {
            cb.checked = selectAll.checked;
        });
        updateAssignButton();
    }

    function updateAssignButton() {
        let selectedStudents = document.querySelectorAll('.student-checkbox:checked');
        let assignBtn = document.getElementById('assignBtn');
        
        if (selectedStudents.length > 0 && selectedFeesId) {
            assignBtn.disabled = false;
            assignBtn.innerHTML = `<i class="fas fa-forward"></i> Assign (${selectedStudents.length})`;
        } else if (selectedStudents.length === 0) {
            assignBtn.disabled = true;
            assignBtn.innerHTML = `<i class="fas fa-forward"></i> Select Students`;
        } else if (!selectedFeesId) {
            assignBtn.disabled = true;
            assignBtn.innerHTML = `<i class="fas fa-forward"></i> Select Fees`;
        }
    }

    function assignFees() {
        let selectedStudents = document.querySelectorAll('.student-checkbox:checked');
        let studentIds = Array.from(selectedStudents).map(cb => cb.getAttribute('data-student-id'));
        
        if (studentIds.length === 0) {
            Swal.fire('Error!', 'Please select at least one student', 'error');
            return;
        }
        
        Swal.fire({
            title: 'Confirm',
            text: `Assign ${selectedFeesName} (₹${selectedFeesAmount}) to ${studentIds.length} student(s)?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            confirmButtonText: 'Yes, Assign!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Assigning...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                
                fetch('{{ route("fees.assign") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        student_ids: studentIds,
                        fees_master_id: selectedFeesId,
                        amount: selectedFeesAmount
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Assigned!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            document.getElementById('selectAll').checked = false;
                            document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = false);
                            updateAssignButton();
                            loadAssignedStudents();
                        });
                    } else {
                        Swal.fire('Error!', data.error, 'error');
                    }
                });
            }
        });
    }
</script>
@endsection