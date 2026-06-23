@extends('layouts.app')

@section('title', 'Class Subject Assignment')

@section('content')
<style>
    .cs-container {
        animation: fadeIn 0.3s ease;
    }

    .cs-header {
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

    .cs-header h2 {
        font-size: 1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .session-badge {
        background: rgba(255,255,255,0.2);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
    }

    .btn-print-header {
        background: #f59e0b;
        border: none;
        color: white;
        padding: 5px 14px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-print-header:hover {
        background: #d97706;
        transform: translateY(-1px);
    }

    /* Table Style - Row-wise */
    .subjects-table {
        background: white;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        width: 100%;
    }

    .subjects-table .class-row {
        display: flex;
        align-items: center;
        border-bottom: 1px solid #e2e8f0;
        transition: background 0.2s;
    }

    .subjects-table .class-row:hover {
        background: #f8fafc;
    }

    .subjects-table .class-row:last-child {
        border-bottom: none;
    }

    .class-info {
        width: 180px;
        padding: 12px 8px;
        background: #f8fafc;
        border-right: 1px solid #e2e8f0;
        font-weight: 600;
        color: #1e3c72;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .class-name {
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .assign-btn {
        background: #1e3c72;
        border: none;
        color: white;
        padding: 4px 12px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.65rem;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .assign-btn:hover {
        background: #2b4c7c;
    }

    .subjects-list {
        flex: 1;
        padding: 8px 15px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .subject-tag {
        background: #e0e7ff;
        color: #4338ca;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        white-space: nowrap;
    }

    .no-subjects {
        color: #94a3b8;
        font-size: 0.7rem;
        font-style: italic;
    }

    /* Print Styles */
    @media print {
        .sidebar, .top-header, .cs-header .btn-print-header, .assign-btn, 
        .menu-toggle, .user-dropdown, .no-print, .filter-card, .action-cell {
            display: none !important;
        }
        
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        
        .page-content {
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .cs-container {
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .cs-header {
            background: #1e3c72 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            margin-bottom: 15px !important;
        }
        
        .subjects-table {
            border: 1px solid #000 !important;
        }
        
        .subjects-table .class-row {
            border-bottom: 1px solid #000 !important;
        }
        
        .class-info {
            background: #f0f0f0 !important;
            border-right: 1px solid #000 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .subject-tag {
            background: #e0e7ff !important;
            border: 1px solid #ccc !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .session-badge {
            background: rgba(255,255,255,0.2) !important;
        }
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
        font-size: 0.75rem;
        font-weight: 600;
    }
    .btn-save {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    .btn-save:hover {
        transform: translateY(-1px);
    }
    .btn-cancel {
        background: #e2e8f0;
        color: #475569;
    }

    /* Subject Checkbox Grid */
    .subjects-checkbox-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        max-height: 350px;
        overflow-y: auto;
        padding: 5px;
    }
    .subject-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        background: #f8fafc;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .subject-checkbox:hover {
        background: #e2e8f0;
    }
    .subject-checkbox input {
        width: 16px;
        height: 16px;
        cursor: pointer;
    }
    .subject-checkbox span {
        font-size: 0.75rem;
        cursor: pointer;
        flex: 1;
    }

    @media (max-width: 768px) {
        .subjects-table .class-row {
            flex-direction: column;
            align-items: stretch;
        }
        .class-info {
            width: 100%;
            border-right: none;
            border-bottom: 1px solid #e2e8f0;
            justify-content: space-between;
        }
        .subjects-list {
            padding: 12px 15px;
        }
        .subjects-checkbox-list {
            grid-template-columns: 1fr;
        }
        .header-actions {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>

<div class="cs-container">
    <div class="cs-header">
        <h2>
            <i class="fas fa-chalkboard-teacher"></i> Class Subject Assignment
        </h2>
        <div class="header-actions">
            <div class="session-badge">
                <i class="fas fa-calendar-alt"></i> Session: <strong>{{ $currentSession->session_name ?? 'No Active Session' }}</strong>
            </div>
            <button class="btn-print-header" onclick="printReport()">
                <i class="fas fa-print"></i> Print Report
            </button>
        </div>
    </div>

    <!-- Table Layout - Row wise -->
    <div class="subjects-table" id="printArea">
        <div class="class-row" style="background: #f1f5f9; font-weight: 700;">
            <div class="class-info" style="background: #e2e8f0;">
                <span>Class</span>
                <span>Action</span>
            </div>
            <div class="subjects-list">
                <span>Subjects Assigned</span>
            </div>
        </div>
        
        @foreach($classes as $class)
        <div class="class-row">
            <div class="class-info">
                <div class="class-name">
                    <i class="fas fa-chalkboard-user"></i> {{ $class->class_name }}
                </div>
                <button class="assign-btn" onclick="openAssignModal({{ $class->id }}, '{{ $class->class_name }}')">
                    <i class="fas fa-edit"></i> Assign
                </button>
            </div>
            <div class="subjects-list" id="subjects-list-{{ $class->id }}">
                @php
                    $classData = $assignedData[$class->id] ?? null;
                    $subjectIds = $classData ? $classData->subject_ids : [];
                    $classSubjects = $subjectIds ? \App\Models\Subject::whereIn('id', $subjectIds)->orderBy('subject_name')->get() : [];
                @endphp
                @if(count($classSubjects) > 0)
                    @foreach($classSubjects as $subject)
                    <span class="subject-tag">{{ $subject->subject_name }}</span>
                    @endforeach
                @else
                    <span class="no-subjects">
                        <i class="fas fa-info-circle"></i> No subjects assigned
                    </span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Footer Info for Print -->
    <div style="margin-top: 20px; text-align: center; font-size: 0.6rem; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 15px;" class="no-print">
        <i class="fas fa-print"></i> Click Print button to generate report
    </div>
</div>

<!-- Assign Modal -->
<div class="modal" id="assignModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Assign Subjects</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="class_id">
            <div style="margin-bottom: 15px; padding: 8px 12px; background: #f0fdf4; border-radius: 8px;">
                <i class="fas fa-chalkboard-user"></i> <strong id="classNameDisplay"></strong>
            </div>
            <div class="subjects-checkbox-list" id="subjectsList">
                @foreach($subjects as $subject)
                <label class="subject-checkbox">
                    <input type="checkbox" value="{{ $subject->id }}" class="subject-check">
                    <span>{{ $subject->subject_name }}</span>
                </label>
                @endforeach
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
            <button type="button" class="btn-save" onclick="assignSubjects()">Save Assignment</button>
        </div>
    </div>
</div>

<script>
    let currentClassId = null;
    let currentClassName = null;

    function printReport() {
        // Save original title
        var originalTitle = document.title;
        document.title = "Class Subject Assignment Report - {{ $currentSession->session_name ?? '' }}";
        
        var printContents = document.getElementById('printArea').innerHTML;
        var headerContents = document.querySelector('.cs-header').cloneNode(true);
        
        // Remove assign buttons from print
        var tempDiv = document.createElement('div');
        tempDiv.innerHTML = printContents;
        tempDiv.querySelectorAll('.assign-btn').forEach(btn => btn.remove());
        
        var printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Class Subject Assignment Report</title>
                <meta charset="UTF-8">
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    body {
                        font-family: 'Segoe UI', Arial, sans-serif;
                        padding: 20px;
                        background: white;
                    }
                    .print-header {
                        text-align: center;
                        margin-bottom: 20px;
                        padding-bottom: 15px;
                        border-bottom: 2px solid #1e3c72;
                    }
                    .print-header h1 {
                        color: #1e3c72;
                        font-size: 24px;
                        margin-bottom: 5px;
                    }
                    .print-header p {
                        color: #64748b;
                        font-size: 12px;
                    }
                    .print-session {
                        background: #1e3c72;
                        color: white;
                        display: inline-block;
                        padding: 4px 15px;
                        border-radius: 20px;
                        font-size: 11px;
                        margin-top: 8px;
                    }
                    .subjects-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 15px;
                    }
                    .subjects-table .header-row {
                        background: #f1f5f9;
                        font-weight: bold;
                    }
                    .subjects-table .class-row {
                        border-bottom: 1px solid #e2e8f0;
                    }
                    .subjects-table .class-info {
                        width: 180px;
                        padding: 10px 12px;
                        background: #f8fafc;
                        font-weight: 600;
                        vertical-align: top;
                    }
                    .subjects-table .subjects-list {
                        padding: 10px 12px;
                    }
                    .subject-tag {
                        display: inline-block;
                        background: #e0e7ff;
                        color: #4338ca;
                        padding: 3px 10px;
                        border-radius: 15px;
                        font-size: 15px;
                        margin: 2px 4px;
                        white-space: nowrap;
                        font-weight: 600;
                    }
                    .print-footer {
                        margin-top: 20px;
                        text-align: center;
                        font-size: 10px;
                        color: #94a3b8;
                        border-top: 1px solid #e2e8f0;
                        padding-top: 15px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        border: 2px solid black;
                        padding: 8px;
                        text-align: left;
                    }
                    th {
                        background: #f1f5f9;
                    }
                    @media print {
                        body {
                            padding: 0;
                            margin: 0;
                        }
                        .no-print {
                            display: none;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="print-header">
                    <h1>📚 Class Subject Assignment Report</h1>
                    <p>Academic Year: {{ $currentSession->session_name ?? 'N/A' }}</p>
                    <div class="print-session">
                        <i class="fas fa-calendar-alt"></i> Session: {{ $currentSession->session_name ?? 'N/A' }}
                    </div>
                    <p style="margin-top: 10px;">Generated on: {{ date('d-m-Y h:i:s') }}</p>
                </div>
                
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr style="background: #f1f5f9;">
                                <th width="180">Class</th>
                                <th>Subjects Assigned</th>
                            </tr>
                        </thead>
                        <tbody>
            `);
        
        // Add rows
        @foreach($classes as $class)
            @php
                $classData = $assignedData[$class->id] ?? null;
                $subjectIds = $classData ? $classData->subject_ids : [];
                $classSubjects = $subjectIds ? \App\Models\Subject::whereIn('id', $subjectIds)->orderBy('subject_name')->get() : [];
            @endphp
            printWindow.document.write(`
                <tr>
                    <td style="width: 180px; background: #f8fafc;">
                        <strong>{{ $class->class_name }}</strong>
                    </td>
                    <td>
                        @if(count($classSubjects) > 0)
                            @foreach($classSubjects as $subject)
                            <span class="subject-tag">{{ $subject->subject_name }}</span>
                            @endforeach
                        @else
                            <span style="color: #94a3b8;">No subjects assigned</span>
                        @endif
                    </td>
                </tr>
            `);
        @endforeach
        
        printWindow.document.write(`
                        </tbody>
                    </table>
                </div>
                
                <div class="print-footer">
                    <p>This is a computer generated report. Valid with digital signature.</p>
                    <p>© {{ date('Y') }} School Management System</p>
                </div>
            </body>
            </html>
        `);
        
        printWindow.document.close();
        printWindow.print();
        printWindow.onafterprint = function() {
            printWindow.close();
        };
    }

    function openAssignModal(classId, className) {
        currentClassId = classId;
        currentClassName = className;
        document.getElementById('modalTitle').innerHTML = `Assign Subjects - ${className}`;
        document.getElementById('classNameDisplay').innerHTML = className;
        document.getElementById('class_id').value = classId;
        
        document.querySelectorAll('.subject-check').forEach(cb => cb.checked = false);
        
        fetch(`/admin/class-subject/get-subjects/${classId}`)
            .then(response => response.json())
            .then(data => {
                if (data.assigned) {
                    document.querySelectorAll('.subject-check').forEach(cb => {
                        if (data.assigned.includes(parseInt(cb.value))) {
                            cb.checked = true;
                        }
                    });
                }
            });
        
        document.getElementById('assignModal').classList.add('show');
    }

    function assignSubjects() {
        let selectedSubjects = [];
        document.querySelectorAll('.subject-check:checked').forEach(cb => {
            selectedSubjects.push(cb.value);
        });
        
        if (selectedSubjects.length === 0) {
            Swal.fire('Error!', 'Please select at least one subject', 'error');
            return;
        }
        
        Swal.fire({
            title: 'Saving...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        fetch('{{ route("class.subject.assign") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                class_id: currentClassId,
                subject_ids: selectedSubjects
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Error!', text: data.error });
            }
        });
    }

    function closeModal() {
        document.getElementById('assignModal').classList.remove('show');
    }
</script>
@endsection