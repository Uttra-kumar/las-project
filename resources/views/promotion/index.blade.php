@extends('layouts.app')

@section('title', 'Student Promotion')

@section('content')
<style>
    /* ===== ANIMATIONS ===== */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .promotion-container {
        animation: fadeInUp 0.35s ease;
    }

    /* ===== HEADER ===== */
    .promotion-header {
        background: linear-gradient(135deg, #1a365d 0%, #2d4a7a 50%, #1a365d 100%);
        color: white;
        padding: 12px 18px;
        border-radius: 10px;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(26, 54, 93, 0.25);
    }
    .promotion-header h2 {
        font-size: 0.95rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }
    .promotion-header h2 i {
        color: #fbbf24;
    }
    .session-badge {
        background: rgba(255,255,255,0.12);
        padding: 3px 14px;
        border-radius: 16px;
        font-size: 0.65rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: 1px solid rgba(255,255,255,0.1);
    }
    .session-badge i {
        color: #10b981;
    }

    /* ===== FILTER SECTION ===== */
    .filter-section {
        background: white;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .filter-row {
        display: flex;
        gap: 12px;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 140px;
    }
    .filter-group label {
        display: block;
        font-size: 0.6rem;
        font-weight: 700;
        color: #1e3c72;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .filter-group label i {
        color: #3b82f6;
        margin-right: 3px;
    }
    .filter-group label .required {
        color: #dc2626;
        font-weight: 700;
    }
    .filter-group select {
        width: 100%;
        padding: 6px 10px;
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
        background: #fafbfc;
        transition: all 0.25s ease;
        height: 34px;
        font-family: inherit;
    }
    .filter-group select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        background: white;
    }

    .filter-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .btn-filter {
        background: linear-gradient(135deg, #1e3c72, #2d4a7a);
        border: none;
        color: white;
        padding: 6px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s ease;
        height: 34px;
        box-shadow: 0 2px 6px rgba(30, 60, 114, 0.2);
    }
    .btn-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(30, 60, 114, 0.3);
    }
    .btn-filter i {
        font-size: 0.7rem;
    }

    .btn-reset-filter {
        background: #64748b;
        border: none;
        color: white;
        padding: 6px 18px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s ease;
        height: 34px;
    }
    .btn-reset-filter:hover {
        background: #475569;
        transform: translateY(-1px);
    }

    /* ===== TABLE WRAPPER ===== */
    .table-wrapper {
        background: white;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .table-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.7rem;
        min-width: 650px;
    }

    .data-table th {
        padding: 10px 12px;
        background: #f8fafc;
        font-weight: 700;
        border-bottom: 2px solid #e2e8f0;
        color: #1e3c72;
        text-transform: uppercase;
        font-size: 0.6rem;
        letter-spacing: 0.3px;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .data-table td {
        padding: 8px 12px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .data-table tbody tr {
        transition: background 0.2s ease;
    }
    .data-table tbody tr:hover {
        background: #f8fafc;
    }

    /* ===== CHECKBOX ===== */
    .student-checkbox,
    #selectAll {
        width: 16px;
        height: 16px;
        cursor: pointer;
        accent-color: #1e3c72;
    }

    /* ===== PROMOTE BUTTON ===== */
    .promote-section {
        margin-top: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 12px 16px;
        background: #fafbfc;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }

    .selected-count {
        font-size: 0.7rem;
        color: #64748b;
    }
    .selected-count strong {
        color: #1e3c72;
        font-size: 0.85rem;
    }

    .btn-promote {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        padding: 8px 28px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        height: 38px;
    }
    .btn-promote:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4);
        background: linear-gradient(135deg, #059669, #047857);
    }
    .btn-promote:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }
    .btn-promote i {
        font-size: 0.85rem;
    }

    .btn-promote-small {
        display: none;
    }

    /* ===== NO DATA ===== */
    .no-data {
        text-align: center;
        padding: 50px 20px;
        color: #94a3b8;
    }
    .no-data i {
        font-size: 2.5rem;
        margin-bottom: 12px;
        display: block;
        color: #cbd5e1;
    }
    .no-data p {
        font-size: 0.85rem;
        margin-bottom: 4px;
        color: #475569;
    }
    .no-data small {
        font-size: 0.7rem;
        color: #94a3b8;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .filter-row {
            gap: 10px;
        }
        .filter-group {
            min-width: 120px;
        }
        .data-table {
            font-size: 0.65rem;
            min-width: 600px;
        }
        .data-table th,
        .data-table td {
            padding: 8px 10px;
        }
    }

    @media (max-width: 768px) {
        .promotion-header {
            padding: 10px 14px;
            flex-direction: column;
            text-align: center;
        }
        .promotion-header h2 {
            font-size: 0.85rem;
        }
        .session-badge {
            font-size: 0.6rem;
        }
        .filter-section {
            padding: 12px 14px;
        }
        .filter-row {
            flex-direction: column;
            gap: 8px;
        }
        .filter-group {
            min-width: 100%;
            width: 100%;
        }
        .filter-actions {
            width: 100%;
            display: flex;
            gap: 8px;
        }
        .filter-actions .btn-filter,
        .filter-actions .btn-reset-filter {
            flex: 1;
            justify-content: center;
        }
        .promote-section {
            flex-direction: column;
            text-align: center;
            padding: 12px 14px;
        }
        .btn-promote {
            width: 100%;
            justify-content: center;
        }
        .data-table {
            font-size: 0.6rem;
            min-width: 520px;
        }
        .data-table th,
        .data-table td {
            padding: 6px 8px;
        }
        .data-table th {
            font-size: 0.55rem;
        }
    }

    @media (max-width: 480px) {
        .promotion-header {
            padding: 8px 12px;
        }
        .promotion-header h2 {
            font-size: 0.8rem;
        }
        .session-badge {
            font-size: 0.55rem;
            padding: 2px 10px;
        }
        .filter-section {
            padding: 10px 12px;
        }
        .filter-group label {
            font-size: 0.55rem;
        }
        .filter-group select {
            font-size: 0.7rem;
            padding: 5px 8px;
            height: 30px;
        }
        .btn-filter,
        .btn-reset-filter {
            font-size: 0.65rem;
            padding: 5px 14px;
            height: 30px;
        }
        .data-table {
            font-size: 0.55rem;
            min-width: 440px;
        }
        .data-table th,
        .data-table td {
            padding: 4px 6px;
        }
        .data-table th {
            font-size: 0.5rem;
        }
        .student-checkbox,
        #selectAll {
            width: 14px;
            height: 14px;
        }
        .promote-section {
            padding: 10px 12px;
        }
        .selected-count {
            font-size: 0.65rem;
        }
        .selected-count strong {
            font-size: 0.75rem;
        }
        .btn-promote {
            font-size: 0.7rem;
            padding: 6px 20px;
            height: 34px;
        }
        .btn-promote i {
            font-size: 0.75rem;
        }
        .no-data {
            padding: 30px 15px;
        }
        .no-data i {
            font-size: 2rem;
        }
        .no-data p {
            font-size: 0.75rem;
        }
        .no-data small {
            font-size: 0.65rem;
        }
        .filter-actions {
            flex-direction: column;
        }
        .filter-actions .btn-filter,
        .filter-actions .btn-reset-filter {
            width: 100%;
        }
    }

    @media (max-width: 380px) {
        .data-table {
            min-width: 360px;
        }
        .data-table th,
        .data-table td {
            padding: 3px 5px;
            font-size: 0.5rem;
        }
        .data-table th {
            font-size: 0.45rem;
        }
        .btn-promote {
            font-size: 0.65rem;
            padding: 5px 16px;
            height: 30px;
        }
    }
</style>

<div class="promotion-container">
    <!-- ===== HEADER ===== -->
    <div class="promotion-header">
        <h2>
            <i class="fas fa-arrow-up"></i>
            Student Promotion
        </h2>
        <div class="session-badge">
            <i class="fas fa-calendar-alt"></i>
            Current: {{ $currentSession->session_name ?? 'N/A' }}
        </div>
    </div>

    <!-- ===== FILTER SECTION ===== -->
    <div class="filter-section">
        <div class="filter-row">
            <div class="filter-group">
                <label><i class="fas fa-calendar-alt"></i> From Session <span class="required">*</span></label>
                <select id="fromSession">
                    <option value="">-- Select Session --</option>
                    @foreach($sessions as $session)
                    <option value="{{ $session->session_id }}" {{ $selectedSession == $session->session_id ? 'selected' : '' }}>
                        {{ $session->session_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label><i class="fas fa-chalkboard-user"></i> From Class <span class="required">*</span></label>
                <select id="fromClass">
                    <option value="">-- Select Class --</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>
                        {{ $class->class_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label><i class="fas fa-calendar-alt"></i> To Session</label>
                <select id="toSession">
                    <option value="">-- Select Session --</option>
                    @foreach($sessions as $session)
                    <option value="{{ $session->session_id }}">{{ $session->session_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label><i class="fas fa-chalkboard-user"></i> To Class</label>
                <select id="toClass">
                    <option value="">-- Select Class --</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group filter-actions">
                <button class="btn-filter" onclick="loadStudents()">
                    <i class="fas fa-search"></i> Search
                </button>
                <button class="btn-reset-filter" onclick="resetFilter()">
                    <i class="fas fa-undo-alt"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- ===== STUDENTS CONTAINER ===== -->
    <div id="studentsContainer">
        @if($selectedSession && $selectedClass)
            @if(isset($students) && $students->count() > 0)
            <div class="table-wrapper">
                <div class="table-scroll">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                                </th>
                                <th>#</th>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Father Name</th>
                                <th>Mobile</th>
                                <th>Current Class</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $key => $student)
                            <tr>
                                <td>
                                    <input type="checkbox" class="student-checkbox" 
                                           data-student-id="{{ $student->id }}" 
                                           data-student-name="{{ $student->full_name }}">
                                </td>
                                <td>{{ $key + 1 }}</td>
                                <td><strong>{{ $student->student_id }}</strong></td>
                                <td>{{ $student->full_name }}</td>
                                <td>{{ $student->father_name ?? '-' }}</td>
                                <td>{{ $student->mobile }}</td>
                                <td>
                                    <span style="background:#dbeafe; color:#1e40af; padding:2px 10px; border-radius:12px; font-size:0.6rem; font-weight:600;">
                                        {{ $student->class->class_name ?? 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ===== PROMOTE SECTION ===== -->
            <div class="promote-section">
                <div class="selected-count">
                    <i class="fas fa-users"></i>
                    Selected: <strong id="selectedCount">0</strong> student(s)
                </div>
                <button class="btn-promote" onclick="promoteStudents()">
                    <i class="fas fa-arrow-up"></i> Promote Selected Students
                </button>
            </div>
            @else
            <div class="table-wrapper">
                <div class="no-data">
                    <i class="fas fa-users-slash"></i>
                    <p>No students found in this class</p>
                    <small>Try selecting a different session or class</small>
                </div>
            </div>
            @endif
        @else
        <div class="table-wrapper">
            <div class="no-data">
                <i class="fas fa-filter"></i>
                <p>Select session and class to load students</p>
                <small>Use the filter above to search</small>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    // ===== UPDATE SELECTED COUNT =====
   // ===== UPDATE SELECTED COUNT =====
document.addEventListener('DOMContentLoaded', function() {
    // Check if selectAll exists
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            document.querySelectorAll('.student-checkbox').forEach(cb => {
                cb.checked = this.checked;
            });
            updateSelectedCount();
        });
    }
    
    // Check if checkboxes exist
    const checkboxes = document.querySelectorAll('.student-checkbox');
    if (checkboxes.length > 0) {
        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateSelectedCount);
        });
        updateSelectedCount();
    }
});

function updateSelectedCount() {
    const selectedCountEl = document.getElementById('selectedCount');
    if (selectedCountEl) {
        let count = document.querySelectorAll('.student-checkbox:checked').length;
        selectedCountEl.textContent = count;
    }
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    if (!selectAll) return;
    
    document.querySelectorAll('.student-checkbox').forEach(cb => {
        cb.checked = selectAll.checked;
    });
    updateSelectedCount();
}

function loadStudents() {
    let fromSession = document.getElementById('fromSession').value;
    let fromClass = document.getElementById('fromClass').value;
    
    if(!fromSession || !fromClass) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Please select both session and class',
            confirmButtonColor: '#1e3c72'
        });
        return;
    }
    
    let url = '{{ route("promotion.index") }}?session_id=' + fromSession + '&class_id=' + fromClass;
    window.location.href = url;
}

function resetFilter() {
    window.location.href = '{{ route("promotion.index") }}';
}

function promoteStudents() {
    let selected = [];
    let selectedNames = [];
    document.querySelectorAll('.student-checkbox:checked').forEach(cb => {
        selected.push(cb.getAttribute('data-student-id'));
        selectedNames.push(cb.getAttribute('data-student-name'));
    });
    
    if(selected.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'No Selection',
            text: 'Please select at least one student',
            confirmButtonColor: '#1e3c72'
        });
        return;
    }
    
    let fromSession = document.getElementById('fromSession').value;
    let fromClass = document.getElementById('fromClass').value;
    let toSession = document.getElementById('toSession').value;
    let toClass = document.getElementById('toClass').value;
    
    if(!toSession || !toClass) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Please select target session and class',
            confirmButtonColor: '#1e3c72'
        });
        return;
    }
    
    // Get class names for display
    let fromClassName = document.querySelector('#fromClass option:checked')?.text || 'Current';
    let toClassName = document.querySelector('#toClass option:checked')?.text || 'Next';
    
    Swal.fire({
        title: 'Promote Students?',
        html: `
            <div style="text-align:left; font-size:0.8rem;">
                <p><strong>${selected.length}</strong> student(s) will be promoted:</p>
                <ul style="max-height:150px; overflow-y:auto; padding-left:20px; margin:8px 0;">
                    ${selectedNames.map(name => `<li>${name}</li>`).join('')}
                </ul>
                <hr style="border:1px dashed #e2e8f0; margin:8px 0;">
                <p><i class="fas fa-arrow-right" style="color:#10b981;"></i> 
                   From <strong>${fromClassName}</strong> to <strong>${toClassName}</strong></p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Promote!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if(result.isConfirmed) {
            Swal.fire({
                title: 'Promoting...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            
            fetch('{{ route("promotion.promote") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    student_ids: selected,
                    from_session: fromSession,
                    from_class: fromClass,
                    to_session: toSession,
                    to_class: toClass
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Promoted! 🎉',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    }).then(() => {
                        window.location.href = '{{ route("promotion.history") }}';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.error || 'Something went wrong',
                        confirmButtonColor: '#1e3c72'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong!',
                    confirmButtonColor: '#1e3c72'
                });
            });
        }
    });
}

// ===== ENTER KEY SUPPORT =====
document.addEventListener('DOMContentLoaded', function() {
    const fromClass = document.getElementById('fromClass');
    const fromSession = document.getElementById('fromSession');
    
    if (fromClass) {
        fromClass.addEventListener('keydown', function(e) {
            if(e.key === 'Enter') {
                loadStudents();
            }
        });
    }
    
    if (fromSession) {
        fromSession.addEventListener('keydown', function(e) {
            if(e.key === 'Enter') {
                loadStudents();
            }
        });
    }
});
</script>
@endsection