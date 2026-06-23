@extends('layouts.app')

@section('title', 'Exam Schedule')

@section('content')
<style>
    /* ===== ANIMATIONS ===== */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .schedule-container {
        animation: fadeInUp 0.35s ease;
        max-width: 100%;
    }
    
    /* ===== HEADER ===== */
    .schedule-header {
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
    .schedule-header h2 {
        font-size: 0.95rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }
    .schedule-header h2 i {
        color: #fbbf24;
    }
    .header-actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    
    /* ===== FILTER CARD ===== */
    .filter-card {
        background: white;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .filter-row {
        display: flex;
        gap: 10px;
        align-items: flex-end;
        flex-wrap: wrap;
    }
    .filter-item {
        flex: 1;
        min-width: 120px;
    }
    .filter-item label {
        display: block;
        font-size: 0.6rem;
        font-weight: 700;
        color: #1e3c72;
        margin-bottom: 3px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .filter-item label i {
        color: #3b82f6;
        margin-right: 3px;
    }
    .filter-item input,
    .filter-item select {
        width: 100%;
        padding: 5px 10px;
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
        background: #fafbfc;
        transition: all 0.25s ease;
        height: 34px;
        font-family: inherit;
        font-weight: 500;
    }
    .filter-item input:focus,
    .filter-item select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        background: white;
    }
    
    .filter-actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    
    /* ===== BUTTONS ===== */
    .btn-apply, .btn-reset, .btn-print, .btn-csv, .btn-save {
        border: none;
        color: white;
        padding: 5px 14px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.65rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-weight: 600;
        height: 34px;
        transition: all 0.3s ease;
        white-space: nowrap;
        text-decoration: none;
    }
    .btn-apply {
        background: linear-gradient(135deg, #1e3c72, #2d4a7a);
        box-shadow: 0 2px 6px rgba(30, 60, 114, 0.2);
    }
    .btn-apply:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(30, 60, 114, 0.3);
    }
    .btn-reset {
        background: linear-gradient(135deg, #64748b, #475569);
        box-shadow: 0 2px 6px rgba(100, 116, 139, 0.2);
    }
    .btn-reset:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(100, 116, 139, 0.3);
    }
    .btn-print {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        box-shadow: 0 2px 6px rgba(245, 158, 11, 0.2);
    }
    .btn-print:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(245, 158, 11, 0.3);
    }
    .btn-csv {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        box-shadow: 0 2px 6px rgba(139, 92, 246, 0.2);
    }
    .btn-csv:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(139, 92, 246, 0.3);
    }
    .btn-save {
        background: linear-gradient(135deg, #10b981, #059669);
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.2);
    }
    .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(16, 185, 129, 0.3);
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
        padding: 0 2px;
    }
    .schedule-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.75rem;
        min-width: 500px;
    }
    .schedule-table th {
        text-align: left;
        padding: 8px 12px;
        background: #f8fafc;
        font-weight: 700;
        border-bottom: 2px solid #e2e8f0;
        text-transform: uppercase;
        font-size: 0.6rem;
        color: #1e3c72;
        letter-spacing: 0.3px;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .schedule-table td {
        padding: 6px 12px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .schedule-table tbody tr:hover {
        background: #f8fafc;
    }
    
    /* ===== TABLE INPUTS ===== */
    .schedule-table input,
    .schedule-table select {
        width: 100%;
        padding: 4px 8px;
        border: 1.5px solid #e2e8f0;
        border-radius: 4px;
        font-size: 0.7rem;
        background: #fafbfc;
        transition: all 0.25s ease;
        height: 30px;
        font-family: inherit;
        font-weight: 500;
    }
    .schedule-table input:focus,
    .schedule-table select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        background: white;
    }
    
    /* ===== TABLE FOOTER ===== */
    .table-footer {
        padding: 8px 12px;
        text-align: center;
        border-top: 1px solid #e2e8f0;
        background: #fafbfc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .btn-add-row {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border: none;
        color: #1e40af;
        padding: 4px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s ease;
        height: 30px;
    }
    .btn-add-row:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
    }
    
    .btn-delete-row {
        background: #fee2e2;
        border: none;
        color: #dc2626;
        padding: 2px 10px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.6rem;
        font-weight: 600;
        transition: all 0.2s ease;
        height: 26px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }
    .btn-delete-row:hover {
        background: #fecaca;
        transform: scale(1.05);
    }
    
    .remarks-section {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
        flex: 1;
    }
    .remarks-section label {
        font-size: 0.6rem;
        font-weight: 700;
        color: #1e3c72;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }
    .remarks-section label i {
        color: #3b82f6;
        margin-right: 3px;
    }
    .remarks-section input {
        flex: 1;
        min-width: 150px;
        padding: 5px 10px;
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
        height: 34px;
        font-family: inherit;
        background: #fafbfc;
        transition: all 0.25s ease;
    }
    .remarks-section input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        background: white;
    }
    
    .save-section {
        display: flex;
        gap: 6px;
        align-items: center;
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
    
    /* ============================================
       ✅ PRINT HEADER - Only visible in print
       ============================================ */
    .print-header {
        display: none;
        text-align: center;
        padding: 15px 0 12px 0;
        border-bottom: 3px solid #1e3c72;
        margin-bottom: 15px;
    }
    .print-header h3 {
        font-size: 22px;
        color: #1e3c72;
        margin: 0 0 8px 0;
        font-weight: 700;
    }
    .print-header .print-subtitle {
        font-size: 16px;
        color: #475569;
        margin: 0 0 5px 0;
    }
    .print-header .print-details {
        font-size: 13px;
        color: #64748b;
        margin-top: 5px;
    }
    .print-header .exam-badge {
        display: inline-block;
        background: #1e3c72;
        color: white;
        padding: 4px 20px;
        border-radius: 20px;
        font-size: 16px;
        font-weight: 600;
        margin-top: 5px;
    }
    .print-header .print-date {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 3px;
    }
    
    /* ============================================
       PRINT STYLES
       ============================================ */
    @media print {
        /* Hide sidebar, header, filters, buttons */
        .sidebar, .top-header, .filter-card, .btn-print, .btn-csv, .btn-save,
        .btn-add-row, .btn-delete-row, .table-footer, .no-print,
        .header-actions, .filter-actions, .save-section, .schedule-header {
            display: none !important;
        }
        
        /* Show print header */
        .print-header {
            display: block !important;
        }
        
        /* Main content */
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .page-content {
            padding: 0 !important;
            margin: 0 !important;
        }
        .schedule-container {
            padding: 0 !important;
            margin: 0 !important;
            animation: none !important;
        }
        
        /* Table wrapper */
        .table-wrapper {
            border: 1px solid #000 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }
        
        /* Table */
        .schedule-table {
            font-size: 11px !important;
            min-width: auto !important;
            width: 100% !important;
        }
        .schedule-table th {
            background: #f0f0f0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            border: 1px solid #000 !important;
            padding: 8px 10px !important;
            font-size: 10px !important;
        }
        .schedule-table td {
            border: 1px solid #000 !important;
            padding: 6px 10px !important;
            font-size: 10px !important;
        }
        .schedule-table tbody tr:hover {
            background: transparent !important;
        }
        
        /* Inputs in print */
        .schedule-table input,
        .schedule-table select {
            border: none !important;
            background: transparent !important;
            padding: 0 !important;
            height: auto !important;
            font-size: 10px !important;
            font-weight: 500 !important;
        }
        .schedule-table select option {
            display: none !important;
        }
        
        /* No data */
        .no-data {
            padding: 20px !important;
        }
        .no-data i {
            font-size: 1.5rem !important;
        }
        .no-data p {
            font-size: 10px !important;
        }
        .no-data small {
            font-size: 8px !important;
        }
    }
    
    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .schedule-header {
            padding: 10px 14px;
            flex-direction: column;
            text-align: center;
        }
        .schedule-header h2 {
            font-size: 0.85rem;
        }
        .filter-card {
            padding: 10px 12px;
        }
        .filter-row {
            flex-direction: column;
            gap: 6px;
        }
        .filter-item {
            min-width: 100%;
            width: 100%;
        }
        .filter-actions {
            width: 100%;
            display: flex;
            gap: 6px;
        }
        .filter-actions .btn-apply,
        .filter-actions .btn-reset {
            flex: 1;
            justify-content: center;
        }
        .header-actions {
            width: 100%;
            justify-content: center;
        }
        .schedule-table {
            font-size: 0.65rem;
            min-width: 400px;
        }
        .schedule-table th,
        .schedule-table td {
            padding: 4px 8px;
        }
        .schedule-table input,
        .schedule-table select {
            font-size: 0.65rem;
            padding: 3px 6px;
            height: 28px;
        }
        .table-footer {
            flex-direction: column;
            align-items: stretch;
        }
        .remarks-section {
            flex-direction: column;
            align-items: stretch;
        }
        .remarks-section input {
            min-width: auto;
        }
        .save-section {
            width: 100%;
        }
        .save-section .btn-save {
            width: 100%;
            justify-content: center;
        }
        .btn-add-row {
            width: 100%;
            justify-content: center;
        }
    }
    
    @media (max-width: 480px) {
        .schedule-table {
            font-size: 0.6rem;
            min-width: 340px;
        }
        .schedule-table th,
        .schedule-table td {
            padding: 3px 6px;
        }
        .schedule-table input,
        .schedule-table select {
            font-size: 0.6rem;
            padding: 2px 4px;
            height: 24px;
        }
        .btn-apply, .btn-reset, .btn-print, .btn-csv, .btn-save {
            font-size: 0.6rem;
            padding: 4px 10px;
            height: 30px;
        }
        .filter-actions {
            flex-direction: column;
        }
        .filter-actions .btn-apply,
        .filter-actions .btn-reset {
            width: 100%;
        }
        .header-actions {
            flex-direction: column;
            align-items: stretch;
        }
        .header-actions .btn-print,
        .header-actions .btn-csv {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="schedule-container">
    <!-- ===== HEADER ===== -->
    <div class="schedule-header">
        <h2>
            <i class="fas fa-pencil-alt"></i>
            Exam Schedule
        </h2>
        <div class="header-actions">
            <button class="btn-print" onclick="window.print()">
                <i class="fas fa-print"></i> Print
            </button>
            <a href="{{ route('exam.schedule.export', request()->query()) }}" class="btn-csv">
                <i class="fas fa-file-csv"></i> CSV
            </a>
        </div>
    </div>

    <!-- ===== PRINT HEADER (Only visible in print) ===== -->
    <div class="print-header">
        <h3>
            <i class="fas fa-pencil-alt"></i> Exam Schedule
        </h3>
        <div class="print-subtitle">
            @if($classId && $examTitle)
                <span class="exam-badge">{{ $examTitle }}</span>
            @endif
        </div>
        <div class="print-details">
            @if($classId)
                <strong>Class:</strong> {{ $classes->where('id', $classId)->first()->class_name ?? 'N/A' }}
                @if($streamId && $streams->where('id', $streamId)->first())
                    | <strong>Stream:</strong> {{ $streams->where('id', $streamId)->first()->stream_name }}
                @endif
                | <strong>Session:</strong> {{ $currentSession->session_name ?? 'N/A' }}
            @endif
        </div>
        <div class="print-date">
            Printed on: {{ date('d-m-Y H:i:s') }}
        </div>
    </div>

    <!-- ===== FILTER ===== -->
    <div class="filter-card">
        <form id="filterForm" method="GET" action="{{ route('exam.schedule') }}">
            <div class="filter-row">
                <div class="filter-item">
                    <label><i class="fas fa-school"></i> Class</label>
                    <select name="class_id" id="class_id" onchange="loadStreams(this.value)">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>
                            {{ $class->class_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label><i class="fas fa-code-branch"></i> Stream</label>
                    <select name="stream_id" id="stream_id" onchange="this.form.submit()">
                        <option value="">-- Select Stream --</option>
                        @foreach($streams as $stream)
                        <option value="{{ $stream->id }}" {{ $streamId == $stream->id ? 'selected' : '' }}>
                            {{ $stream->stream_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label><i class="fas fa-tag"></i> Exam Title</label>
                    <select name="exam_title" id="exam_title" onchange="this.form.submit()">
                        <option value="">-- Select Exam --</option>
                        <option value="Monthly Test" {{ $examTitle == 'Monthly Test' ? 'selected' : '' }}>
                            📝 Monthly Test
                        </option>
                        <option value="Quarterly Exam" {{ $examTitle == 'Quarterly Exam' ? 'selected' : '' }}>
                            📊 Quarterly Exam
                        </option>
                        <option value="Half Yearly Exam" {{ $examTitle == 'Half Yearly Exam' ? 'selected' : '' }}>
                            📈 Half Yearly Exam
                        </option>
                        <option value="Pre-Final Exam" {{ $examTitle == 'Pre-Final Exam' ? 'selected' : '' }}>
                            🎯 Pre-Final Exam
                        </option>
                        <option value="Final Exam" {{ $examTitle == 'Final Exam' ? 'selected' : '' }}>
                            🏆 Final Exam
                        </option>
                    </select>
                </div>
                <div class="filter-item filter-actions">
                    <button type="submit" class="btn-apply">
                        <i class="fas fa-search"></i> Apply
                    </button>
                    <a href="{{ route('exam.schedule') }}" class="btn-reset">
                        <i class="fas fa-undo-alt"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- ===== TABLE ===== -->
    <div class="table-wrapper">
        @if($classId && $examTitle)
        <form id="scheduleForm">
            <input type="hidden" name="class_id" value="{{ $classId }}">
            <input type="hidden" name="stream_id" value="{{ $streamId }}">
            <input type="hidden" name="exam_title" value="{{ $examTitle }}">
            
            <div class="table-scroll">
                <table class="schedule-table" id="scheduleTable">
                    <thead>
                        <tr>
                            <th width="40">#</th>
                            <th width="130">Date</th>
                            <th width="100">Start Time</th>
                            <th width="100">End Time</th>
                            <th>Subject</th>
                            <th width="80">Action</th>
                        </tr>
                    </thead>
                    <tbody id="scheduleBody">
                        @if(count($scheduleData) > 0)
                            @foreach($scheduleData as $index => $row)
                            <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <input type="date" name="schedule_data[{{ $index }}][date]" 
                                               value="{{ $row['date'] ?? '' }}" required>
                                    </td>
                                    <td>
                                        <input type="time" name="schedule_data[{{ $index }}][start_time]" 
                                               value="{{ $row['start_time'] ?? '' }}" required>
                                    </td>
                                    <td>
                                        <input type="time" name="schedule_data[{{ $index }}][end_time]" 
                                               value="{{ $row['end_time'] ?? '' }}" required>
                                    </td>
                                    <td>
                                        <select name="schedule_data[{{ $index }}][subject_id]" required onchange="updateSubjectName(this, {{ $index }})">
                                            <option value="">-- Select --</option>
                                            @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" 
                                                data-subject-name="{{ $subject->subject_name }}"
                                                data-teacher-name="{{ $subject->teacher_name }}"
                                                {{ ($row['subject_id'] ?? '') == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->subject_name }} - {{ $subject->teacher_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="schedule_data[{{ $index }}][subject_name]" id="subject_name_{{ $index }}" value="{{ $row['subject_name'] ?? '' }}">
                                        <input type="hidden" name="schedule_data[{{ $index }}][teacher_name]" id="teacher_name_{{ $index }}" value="{{ $row['teacher_name'] ?? '' }}">
                                    </td>
                                    <td>
                                        <button type="button" class="btn-delete-row" onclick="deleteRow(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr id="emptyRow">
                                <td colspan="5" class="no-data" style="padding:30px;">
                                    <i class="fas fa-plus-circle"></i>
                                    <p>Add your first exam schedule</p>
                                    <small>Click "Add Row" below to start</small>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <div class="table-footer">
                <div class="remarks-section">
                    <label><i class="fas fa-comment"></i> Remarks</label>
                    <input type="text" name="remarks" value="{{ $schedule->remarks ?? '' }}" 
                           placeholder="Enter remarks...">
                </div>
                <div class="save-section">
                    <button type="button" class="btn-add-row" onclick="addRow()">
                        <i class="fas fa-plus-circle"></i> Add Row
                    </button>
                    <button type="button" class="btn-save" onclick="saveSchedule()">
                        <i class="fas fa-save"></i> Save
                    </button>
                </div>
            </div>
        </form>
        @else
        <div class="no-data">
            <i class="fas fa-search"></i>
            <p>Select class and exam to manage schedule</p>
            <small>Use the filter above to get started</small>
        </div>
        @endif
    </div>
</div>

<script>
let rowCount = {{ count($scheduleData) }};

function loadStreams(classId) {
    if (!classId) {
        document.getElementById('stream_id').innerHTML = '<option value="">-- Select Stream --</option>';
        return;
    }
    
    fetch(`/exam-schedule/get-streams?class_id=${classId}`)
        .then(response => response.json())
        .then(data => {
            let options = '<option value="">-- Select Stream --</option>';
            if (data.success && data.streams.length > 0) {
                data.streams.forEach(stream => {
                    options += `<option value="${stream.id}">${stream.stream_name}</option>`;
                });
            } else {
                options += '<option value="">No Stream Available</option>';
            }
            document.getElementById('stream_id').innerHTML = options;
            document.getElementById('filterForm').submit();
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function addRow() {
    const tbody = document.getElementById('scheduleBody');
    const emptyRow = document.getElementById('emptyRow');
    if (emptyRow) emptyRow.remove();
    
    const index = rowCount++;
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>${index + 1}</td>
        <td>
            <input type="date" name="schedule_data[${index}][date]" required>
        </td>
        <td>
            <input type="time" name="schedule_data[${index}][start_time]" required>
        </td>
        <td>
            <input type="time" name="schedule_data[${index}][end_time]" required>
        </td>
        <td>
            <select name="schedule_data[${index}][subject_id]" required onchange="updateSubjectName(this, ${index})">
                <option value="">-- Select --</option>
                @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" 
                    data-subject-name="{{ $subject->subject_name }}"
                    data-teacher-name="{{ $subject->teacher_name }}">
                    {{ $subject->subject_name }} - {{ $subject->teacher_name }}
                </option>
                @endforeach
            </select>
            <input type="hidden" name="schedule_data[${index}][subject_name]" id="subject_name_${index}">
            <input type="hidden" name="schedule_data[${index}][teacher_name]" id="teacher_name_${index}">
        </td>
        <td>
            <button type="button" class="btn-delete-row" onclick="deleteRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    updateRowNumbers();
}

function deleteRow(btn) {
    const row = btn.closest('tr');
    if (row) {
        row.style.transition = 'all 0.3s ease';
        row.style.opacity = '0';
        row.style.transform = 'translateX(-20px)';
        
        setTimeout(() => {
            row.remove();
            updateRowNumbers();
            if (document.querySelectorAll('#scheduleBody tr').length === 0) {
                const tbody = document.getElementById('scheduleBody');
                tbody.innerHTML = `
                    <tr id="emptyRow">
                        <td colspan="5" class="no-data" style="padding:30px;">
                            <i class="fas fa-plus-circle"></i>
                            <p>Add your first exam schedule</p>
                            <small>Click "Add Row" below to start</small>
                        </td>
                    </tr>
                `;
            }
        }, 300);
    }
}

function updateRowNumbers() {
    const rows = document.querySelectorAll('#scheduleBody tr');
    rows.forEach((row, index) => {
        const td = row.querySelector('td:first-child');
        if (td && !td.classList.contains('no-data')) {
            td.textContent = index + 1;
        }
    });
}

function updateSubjectName(select, index) {
    const selected = select.options[select.selectedIndex];
    const subjectName = selected.getAttribute('data-subject-name') || '';
    const teacherName = selected.getAttribute('data-teacher-name') || '';
    
    document.getElementById(`subject_name_${index}`).value = subjectName;
    document.getElementById(`teacher_name_${index}`).value = teacherName;
}

function saveSchedule() {
    const form = document.getElementById('scheduleForm');
    const formData = new FormData(form);
    
    // ✅ Collect data properly
    let scheduleData = [];
    let hasData = false;
    
    // Get all rows
    const rows = document.querySelectorAll('#scheduleBody tr');
    rows.forEach((row, index) => {
        // Skip empty row
        if (row.id === 'emptyRow') return;
        
        const date = row.querySelector(`input[name="schedule_data[${index}][date]"]`)?.value || '';
        const startTime = row.querySelector(`input[name="schedule_data[${index}][start_time]"]`)?.value || '';
        const endTime = row.querySelector(`input[name="schedule_data[${index}][end_time]"]`)?.value || '';
        const subjectId = row.querySelector(`select[name="schedule_data[${index}][subject_id]"]`)?.value || '';
        const subjectName = row.querySelector(`input[name="schedule_data[${index}][subject_name]"]`)?.value || '';
        const teacherName = row.querySelector(`input[name="schedule_data[${index}][teacher_name]"]`)?.value || '';
        
        // ✅ Only add if date and subject are filled
        if (date && subjectId) {
            scheduleData.push({
                date: date,
                start_time: startTime,
                end_time: endTime,
                subject_id: subjectId,
                subject_name: subjectName,
                teacher_name: teacherName
            });
            hasData = true;
        }
    });
    
    if (!hasData) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Please add at least one exam schedule!',
            confirmButtonColor: '#1e3c72'
        });
        return;
    }
    
    // ✅ Get other form data
    const classId = document.querySelector('input[name="class_id"]')?.value;
    const streamId = document.querySelector('input[name="stream_id"]')?.value;
    const examTitle = document.querySelector('input[name="exam_title"]')?.value;
    const remarks = document.querySelector('input[name="remarks"]')?.value || '';
    
    const data = {
        class_id: classId,
        stream_id: streamId,
        exam_title: examTitle,
        schedule_data: scheduleData,
        remarks: remarks,
        _token: '{{ csrf_token() }}'
    };
    
    console.log('✅ Sending Data:', data);
    
    Swal.fire({
        title: 'Saving...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });
    
    fetch('{{ route("exam.schedule.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            Swal.fire({
                icon: 'success',
                title: 'Saved! 🎉',
                text: response.message,
                timer: 1500,
                showConfirmButton: false
            });
            setTimeout(() => location.reload(), 1500);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: response.message || 'Something went wrong',
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
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('select[name*="[subject_id]"]').forEach((select, index) => {
        updateSubjectName(select, index);
    });
});
</script>
@endsection