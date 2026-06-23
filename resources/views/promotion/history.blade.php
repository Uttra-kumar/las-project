@extends('layouts.app')

@section('title', 'Promotion History')

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
    
    .history-container {
        animation: fadeInUp 0.35s ease;
    }

    /* ===== HEADER ===== */
    .history-header {
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
    .history-header h2 {
        font-size: 0.95rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }
    .history-header h2 i {
        color: #fbbf24;
    }
    .history-header .header-badge {
        background: rgba(255,255,255,0.12);
        padding: 2px 12px;
        border-radius: 16px;
        font-size: 0.6rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
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
    .filter-group select,
    .filter-group input {
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
    .filter-group select:focus,
    .filter-group input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        background: white;
    }

    .filter-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
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
        min-width: 700px;
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

    /* Promoted row style */
    .promoted-row {
        background: #dcfce7 !important;
    }
    .promoted-row:hover {
        background: #bbf7d0 !important;
    }

    /* ===== CHECKBOX ===== */
    .bulk-checkbox {
        width: 16px;
        height: 16px;
        cursor: pointer;
        accent-color: #1e3c72;
    }

    /* ===== ACTION HEADER ===== */
    .action-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        padding: 12px 16px;
        background: #fafbfc;
        border-bottom: 1px solid #e2e8f0;
    }

    .select-all-area {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .select-all-area label {
        font-size: 0.7rem;
        font-weight: 600;
        color: #1e293b;
        cursor: pointer;
    }

    .info-badge {
        font-size: 0.6rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .info-badge i {
        color: #10b981;
    }

    /* ===== BUTTONS ===== */
    .btn-revert {
        background: #ef4444;
        color: white;
        border: none;
        padding: 4px 12px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.6rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
        height: 28px;
        white-space: nowrap;
    }
    .btn-revert:hover {
        background: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }
    .btn-revert i {
        font-size: 0.6rem;
    }

    .btn-bulk-revert {
        background: #f59e0b;
        color: white;
        border: none;
        padding: 5px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
        height: 32px;
    }
    .btn-bulk-revert:hover {
        background: #d97706;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    }
    .btn-bulk-revert i {
        font-size: 0.65rem;
    }

    .promoted-badge {
        background: #10b981;
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.5rem;
        font-weight: 600;
        display: inline-block;
        margin-left: 4px;
        white-space: nowrap;
    }

    .not-promoted-text {
        color: #94a3b8;
        font-size: 0.6rem;
        font-weight: 500;
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

    /* ===== PAGINATION ===== */
    .pagination-wrapper {
        margin-top: 16px;
        display: flex;
        justify-content: center;
        padding: 8px 0;
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
            min-width: 650px;
        }
        .data-table th,
        .data-table td {
            padding: 8px 10px;
        }
    }

    @media (max-width: 768px) {
        .history-header {
            padding: 10px 14px;
        }
        .history-header h2 {
            font-size: 0.85rem;
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
        .action-header {
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
            padding: 10px 14px;
        }
        .select-all-area {
            justify-content: center;
        }
        .info-badge {
            justify-content: center;
        }
        .data-table {
            font-size: 0.6rem;
            min-width: 580px;
        }
        .data-table th,
        .data-table td {
            padding: 6px 8px;
        }
        .btn-revert {
            font-size: 0.55rem;
            padding: 3px 10px;
            height: 24px;
        }
        .promoted-badge {
            font-size: 0.45rem;
            padding: 1px 6px;
        }
    }

    @media (max-width: 480px) {
        .history-header {
            padding: 8px 12px;
            flex-direction: column;
            text-align: center;
        }
        .history-header .header-badge {
            font-size: 0.5rem;
        }
        .filter-section {
            padding: 10px 12px;
        }
        .filter-group label {
            font-size: 0.55rem;
        }
        .filter-group select,
        .filter-group input {
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
            min-width: 500px;
        }
        .data-table th,
        .data-table td {
            padding: 4px 6px;
        }
        .data-table th {
            font-size: 0.5rem;
        }
        .action-header {
            padding: 8px 12px;
        }
        .select-all-area label {
            font-size: 0.6rem;
        }
        .bulk-checkbox {
            width: 14px;
            height: 14px;
        }
        .btn-bulk-revert {
            font-size: 0.6rem;
            padding: 4px 12px;
            height: 28px;
        }
        .btn-revert {
            font-size: 0.5rem;
            padding: 2px 8px;
            height: 22px;
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
    }

    @media (max-width: 380px) {
        .data-table {
            min-width: 420px;
        }
        .data-table th,
        .data-table td {
            padding: 3px 5px;
            font-size: 0.5rem;
        }
        .btn-revert {
            font-size: 0.45rem;
            padding: 2px 6px;
            height: 20px;
        }
        .btn-revert i {
            font-size: 0.45rem;
        }
        .select-all-area {
            gap: 6px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .filter-actions {
            flex-direction: column;
        }
        .filter-actions .btn-filter,
        .filter-actions .btn-reset-filter {
            width: 100%;
        }
    }
</style>

<div class="history-container">
    <!-- ===== HEADER ===== -->
    <div class="history-header">
        <h2>
            <i class="fas fa-history"></i>
            Promotion History
            <span class="header-badge">
                <i class="fas fa-calendar-alt"></i>
                {{ date('Y') }}
            </span>
        </h2>
        <div>
            @if($selectedSession && $selectedClass && isset($students) && $students->count() > 0)
            <span style="font-size:0.65rem; background:rgba(255,255,255,0.15); padding:3px 12px; border-radius:16px;">
                <i class="fas fa-users"></i> {{ $students->total() }} Students
            </span>
            @endif
        </div>
    </div>

    <!-- ===== FILTER SECTION ===== -->
    <div class="filter-section">
        <div class="filter-row">
            <div class="filter-group">
                <label><i class="fas fa-calendar-alt"></i> Session <span style="color:#dc2626;">*</span></label>
                <select id="session_id">
                    <option value="">-- Select Session --</option>
                    @foreach($sessions as $session)
                    <option value="{{ $session->session_id }}" {{ $selectedSession == $session->session_id ? 'selected' : '' }}>
                        {{ $session->session_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label><i class="fas fa-chalkboard-user"></i> Class <span style="color:#dc2626;">*</span></label>
                <select id="class_id">
                    <option value="">-- Select Class --</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>
                        {{ $class->class_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group filter-actions">
                <button class="btn-filter" onclick="applyFilter()">
                    <i class="fas fa-search"></i> Filter
                </button>
                <button class="btn-reset-filter" onclick="resetFilter()">
                    <i class="fas fa-undo-alt"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- ===== TABLE ===== -->
    <div class="table-wrapper">
        @if($selectedSession && $selectedClass)
            @if(isset($students) && $students->count() > 0)
            <div class="action-header">
                <div class="select-all-area">
                    <input type="checkbox" id="selectAll" class="bulk-checkbox" onclick="toggleSelectAll()">
                    <label for="selectAll">Select All</label>
                    <button class="btn-bulk-revert" onclick="bulkRevert()">
                        <i class="fas fa-undo-alt"></i> Revert Selected
                    </button>
                </div>
                <div class="info-badge">
                    <i class="fas fa-circle" style="color:#10b981; font-size:0.5rem;"></i>
                    Green rows = Promoted students
                </div>
            </div>
            
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="30"><input type="checkbox" id="selectAllHeader" class="bulk-checkbox" onclick="toggleSelectAll()"></th>
                            <th>#</th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Father Name</th>
                            <th>Mobile</th>
                            <th>Class</th>
                            <th>Session</th>
                            <th width="90">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $key => $student)
                        <tr class="{{ $student->is_promoted == 1 ? 'promoted-row' : '' }}">
                            <td>
                                <input type="checkbox" class="student-checkbox bulk-checkbox" 
                                       data-student-id="{{ $student->id }}" 
                                       data-student-name="{{ $student->full_name }}" 
                                       {{ $student->is_promoted == 1 ? 'checked' : '' }}>
                            </td>
                            <td>{{ $students->firstItem() + $key }}</td>
                            <td><strong>{{ $student->student_id }}</strong></td>
                            <td>
                                {{ $student->full_name }}
                                @if($student->is_promoted == 1)
                                <span class="promoted-badge">
                                    <i class="fas fa-check-circle"></i> Promoted
                                </span>
                                @endif
                            </td>
                            <td>{{ $student->father_name ?? '-' }}</td>
                            <td>{{ $student->mobile }}</td>
                            <td>{{ $student->class->class_name ?? 'N/A' }}</td>
                            <td>{{ $student->session->session_name ?? 'N/A' }}</td>
                            <td>
                                @if($student->is_promoted == 1)
                                <button class="btn-revert" onclick="revertStudent({{ $student->id }}, '{{ $student->full_name }}')">
                                    <i class="fas fa-undo-alt"></i> Revert
                                </button>
                                @else
                                <span class="not-promoted-text">
                                    <i class="fas fa-minus-circle"></i> Not Promoted
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="no-data">
                <i class="fas fa-users"></i>
                <p>No students found in this session and class</p>
                <small>Try selecting a different session or class</small>
            </div>
            @endif
        @else
        <div class="no-data">
            <i class="fas fa-filter"></i>
            <p>Please select session and class to view promotion history</p>
            <small>Use the filter above to search</small>
        </div>
        @endif
    </div>

    <!-- ===== PAGINATION ===== -->
    @if(isset($students) && method_exists($students, 'hasPages') && $students->hasPages())
    <div class="pagination-wrapper">
        {{ $students->links() }}
    </div>
    @endif
</div>

<script>
    function toggleSelectAll() {
        let selectAll = document.getElementById('selectAll');
        let selectAllHeader = document.getElementById('selectAllHeader');
        let checkboxes = document.querySelectorAll('.student-checkbox');
        
        checkboxes.forEach(cb => {
            cb.checked = selectAll.checked;
        });
        
        if(selectAllHeader) {
            selectAllHeader.checked = selectAll.checked;
        }
    }
    
    // Sync header checkbox with main checkbox
    document.addEventListener('DOMContentLoaded', function() {
        let selectAll = document.getElementById('selectAll');
        let selectAllHeader = document.getElementById('selectAllHeader');
        
        if(selectAll && selectAllHeader) {
            selectAll.addEventListener('change', function() {
                selectAllHeader.checked = this.checked;
            });
            selectAllHeader.addEventListener('change', function() {
                selectAll.checked = this.checked;
            });
        }
    });
    
    function revertStudent(id, name) {
        Swal.fire({
            title: 'Revert Promotion?',
            html: `Revert promotion for <strong>${name}</strong>?<br><small>This will mark student as not promoted.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Revert!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if(result.isConfirmed) {
                Swal.fire({
                    title: 'Reverting...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                
                fetch('{{ route("promotion.revert") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ student_ids: [id] })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Reverted!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', data.error || 'Something went wrong', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error!', 'Something went wrong!', 'error');
                });
            }
        });
    }
    
    function bulkRevert() {
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
        
        Swal.fire({
            title: 'Bulk Revert?',
            html: `Revert promotion for <strong>${selected.length}</strong> student(s)?<br>
                   <small>${selectedNames.join(', ')}</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Revert All!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if(result.isConfirmed) {
                Swal.fire({
                    title: 'Reverting...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                
                fetch('{{ route("promotion.revert") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ student_ids: selected })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Reverted!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', data.error || 'Something went wrong', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error!', 'Something went wrong!', 'error');
                });
            }
        });
    }
    
    function applyFilter() {
        let sessionId = document.getElementById('session_id').value;
        let classId = document.getElementById('class_id').value;
        
        if(!sessionId || !classId) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please select both session and class',
                confirmButtonColor: '#1e3c72'
            });
            return;
        }
        
        let url = '{{ route("promotion.history") }}?';
        if(sessionId) url += 'session_id=' + sessionId;
        if(classId) url += '&class_id=' + classId;
        window.location.href = url;
    }
    
    function resetFilter() {
        window.location.href = '{{ route("promotion.history") }}';
    }
    
    // Enter key support for filter
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('class_id').addEventListener('keydown', function(e) {
            if(e.key === 'Enter') {
                applyFilter();
            }
        });
        document.getElementById('session_id').addEventListener('keydown', function(e) {
            if(e.key === 'Enter') {
                applyFilter();
            }
        });
    });
</script>
@endsection