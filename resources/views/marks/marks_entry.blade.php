@extends('layouts.app')

@section('title', 'Marks Entry')

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
    
    .marks-container {
        animation: fadeInUp 0.35s ease;
        max-width: 100%;
    }
    
    /* ===== HEADER ===== */
    .marks-header {
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
    .marks-header h2 {
        font-size: 0.95rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        flex-wrap: wrap;
    }
    .marks-header h2 i {
        color: #fbbf24;
    }
    .marks-header .teacher-badge {
        font-size: 0.6rem;
        background: rgba(255,255,255,0.15);
        padding: 2px 12px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
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
    .filter-item select,
    .filter-item input {
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
    .filter-item select:focus,
    .filter-item input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        background: white;
    }
    .filter-item select option {
        padding: 4px;
    }
    
    .filter-actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    
    /* ===== BUTTONS ===== */
    .btn-load, .btn-save, .btn-approve, .btn-print, .btn-csv, .btn-reset {
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
    .btn-load {
        background: linear-gradient(135deg, #1e3c72, #2d4a7a);
        box-shadow: 0 2px 6px rgba(30, 60, 114, 0.2);
    }
    .btn-load:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(30, 60, 114, 0.3);
    }
    .btn-save {
        background: linear-gradient(135deg, #10b981, #059669);
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.2);
    }
    .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(16, 185, 129, 0.3);
    }
    .btn-approve {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        box-shadow: 0 2px 6px rgba(245, 158, 11, 0.2);
    }
    .btn-approve:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(245, 158, 11, 0.3);
    }
    .btn-print {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        box-shadow: 0 2px 6px rgba(139, 92, 246, 0.2);
    }
    .btn-print:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(139, 92, 246, 0.3);
    }
    .btn-csv {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        box-shadow: 0 2px 6px rgba(59, 130, 246, 0.2);
    }
    .btn-csv:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(59, 130, 246, 0.3);
    }
    .btn-reset {
        background: linear-gradient(135deg, #64748b, #475569);
        box-shadow: 0 2px 6px rgba(100, 116, 139, 0.2);
    }
    .btn-reset:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(100, 116, 139, 0.3);
    }
    .btn-load i, .btn-save i, .btn-approve i, .btn-print i, .btn-csv i, .btn-reset i {
        font-size: 0.7rem;
    }
    
    /* ===== STATUS BADGE ===== */
    .status-badge {
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 0.55rem;
        font-weight: 600;
        display: inline-block;
    }
    .status-badge.pending {
        background: #fef3c7;
        color: #b45309;
    }
    .status-badge.approved {
        background: #dcfce7;
        color: #15803d;
    }
    
    /* ===== TABLE ===== */
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
    .marks-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.7rem;
        min-width: 550px;
    }
    .marks-table th {
        text-align: center;
        padding: 8px 10px;
        background: #f8fafc;
        font-weight: 700;
        border: 1px solid #e2e8f0;
        font-size: 0.6rem;
        text-transform: uppercase;
        color: #1e3c72;
        letter-spacing: 0.3px;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .marks-table td {
        padding: 4px 8px;
        border: 1px solid #e2e8f0;
        text-align: center;
        vertical-align: middle;
    }
    .marks-table .student-name {
        text-align: center;
        font-weight: 600;
        font-size: 0.7rem;
        color: #1e293b;
    }
    .marks-table .student-sno {
        font-weight: 600;
        color: #94a3b8;
    }
    .marks-table input[type="number"] {
        width: 65px;
        padding: 3px 6px;
        border: 1.5px solid #e2e8f0;
        border-radius: 4px;
        font-size: 0.7rem;
        text-align: center;
        transition: all 0.25s ease;
        font-weight: 600;
        font-family: inherit;
    }
    .marks-table input[type="number"]:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }
    .marks-table input[type="number"]:disabled {
        background: #f1f5f9;
        cursor: not-allowed;
        opacity: 0.7;
    }
    .marks-table input[type="number"]::-webkit-inner-spin-button,
    .marks-table input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .marks-table input[type="number"] {
        -moz-appearance: textfield;
    }
    
    /* ===== TABLE HEADER INFO ===== */
    .table-header-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        padding: 10px 16px;
        background: #fafbfc;
        border-bottom: 1px solid #e2e8f0;
    }
    .table-header-info .info-left {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }
    .table-header-info .info-left span {
        font-size: 0.7rem;
        font-weight: 600;
        color: #1e3c72;
    }
    .table-header-info .info-left span i {
        color: #3b82f6;
        margin-right: 4px;
    }
    .table-header-info .info-right {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
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
            gap: 8px;
        }
        .filter-item {
            min-width: 100px;
        }
        .marks-table {
            font-size: 0.65rem;
            min-width: 500px;
        }
        .marks-table input[type="number"] {
            width: 55px;
        }
    }
    
    @media (max-width: 768px) {
        .marks-header {
            padding: 10px 14px;
            flex-direction: column;
            text-align: center;
        }
        .marks-header h2 {
            font-size: 0.85rem;
            justify-content: center;
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
        .filter-actions .btn-load,
        .filter-actions .btn-reset {
            flex: 1;
            justify-content: center;
        }
        .header-actions {
            width: 100%;
            justify-content: center;
        }
        .table-header-info {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .table-header-info .info-left {
            justify-content: center;
        }
        .table-header-info .info-right {
            width: 100%;
            justify-content: center;
        }
        .marks-table {
            font-size: 0.6rem;
            min-width: 450px;
        }
        .marks-table th,
        .marks-table td {
            padding: 4px 6px;
        }
        .marks-table .student-name {
            font-size: 0.6rem;
        }
        .marks-table input[type="number"] {
            width: 45px;
            padding: 2px 4px;
            font-size: 0.6rem;
        }
        .status-badge {
            font-size: 0.5rem;
            padding: 1px 8px;
        }
    }
    
    @media (max-width: 480px) {
        .marks-header {
            padding: 8px 12px;
        }
        .marks-header h2 {
            font-size: 0.8rem;
        }
        .marks-header .teacher-badge {
            font-size: 0.5rem;
            padding: 1px 8px;
        }
        .filter-card {
            padding: 8px 10px;
        }
        .filter-item label {
            font-size: 0.55rem;
        }
        .filter-item select,
        .filter-item input {
            font-size: 0.7rem;
            padding: 4px 8px;
            height: 30px;
        }
        .btn-load, .btn-save, .btn-approve, .btn-print, .btn-csv, .btn-reset {
            font-size: 0.6rem;
            padding: 4px 10px;
            height: 30px;
        }
        .marks-table {
            font-size: 0.55rem;
            min-width: 380px;
        }
        .marks-table th,
        .marks-table td {
            padding: 3px 4px;
        }
        .marks-table th {
            font-size: 0.5rem;
        }
        .marks-table .student-name {
            font-size: 0.55rem;
        }
        .marks-table input[type="number"] {
            width: 38px;
            padding: 2px 3px;
            font-size: 0.55rem;
        }
        .table-header-info .info-left span {
            font-size: 0.6rem;
        }
        .filter-actions {
            flex-direction: column;
        }
        .filter-actions .btn-load,
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
        .table-header-info .info-right {
            flex-direction: column;
            align-items: stretch;
        }
        .table-header-info .info-right .btn-save,
        .table-header-info .info-right .btn-approve {
            width: 100%;
            justify-content: center;
        }
        .no-data {
            padding: 30px 15px;
        }
        .no-data i {
            font-size: 1.5rem;
        }
        .no-data p {
            font-size: 0.65rem;
        }
        .no-data small {
            font-size: 0.55rem;
        }
    }
    
    /* ===== PRINT ===== */
    @media print {
        .sidebar, .top-header, .filter-card, .btn-print, .btn-csv, .btn-save,
        .btn-approve, .btn-load, .btn-reset, .header-actions, .filter-actions,
        .table-header-info .info-right, .no-print {
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
        .marks-container {
            padding: 0 !important;
            margin: 0 !important;
            animation: none !important;
        }
        .marks-header {
            background: #1e3c72 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            padding: 8px 12px !important;
        }
        .marks-header h2 {
            font-size: 12px !important;
        }
        .table-wrapper {
            border: 1px solid #000 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }
        .marks-table th {
            background: #f0f0f0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            border: 1px solid #000 !important;
            font-size: 8px !important;
            padding: 4px 6px !important;
        }
        .marks-table td {
            border: 1px solid #000 !important;
            padding: 3px 6px !important;
            font-size: 7px !important;
        }
        .marks-table {
            font-size: 7px !important;
            min-width: auto !important;
            width: 100% !important;
        }
        .marks-table input[type="number"] {
            border: none !important;
            background: transparent !important;
            padding: 0 !important;
            width: auto !important;
            font-size: 7px !important;
            font-weight: 600 !important;
        }
        .marks-table tbody tr:hover {
            background: transparent !important;
        }
        .status-badge {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .table-header-info {
            border-bottom: 1px solid #000 !important;
        }
        .table-header-info .info-left span {
            font-size: 8px !important;
        }
        .no-data {
            padding: 20px !important;
        }
        .no-data i {
            font-size: 1.5rem !important;
        }
        .no-data p {
            font-size: 10px !important;
        }
    }
</style>

<div class="marks-container">
    <!-- ===== HEADER ===== -->
    <div class="marks-header">
        <h2>
            <i class="fas fa-pen"></i>
            Marks Entry
            @if(!$isAdmin && $teacher)
            <span class="teacher-badge">
                <i class="fas fa-user"></i> {{ $teacher->full_name }}
            </span>
            @endif
            @if($isAdmin)
            <span class="teacher-badge">
                <i class="fas fa-user-shield"></i> Admin
            </span>
            @endif
        </h2>
        @if(Auth::user()->role=='admin')
        <div class="header-actions">
            <button class="btn-print" onclick="window.print()">
                <i class="fas fa-print"></i> Print
            </button>
            <a href="{{ route('marks.marks.export', request()->query()) }}" class="btn-csv">
                <i class="fas fa-file-csv"></i> CSV
            </a>
        </div>
        @endif
    </div>

    <!-- ===== FILTER ===== -->
    <div class="filter-card">
        <form id="filterForm" method="GET" action="{{ route('marks.marks.entry') }}">
            <div class="filter-row">
                <div class="filter-item">
                    <label><i class="fas fa-school"></i> Class</label>
                    <select name="class_id" id="class_id" onchange="loadStreams()">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>
                            {{ $class->class_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label><i class="fas fa-code-branch"></i> Stream</label>
                    <select name="stream_id" id="stream_id">
                        <option value="">-- Select Stream --</option>
                        @foreach($streams as $stream)
                        <option value="{{ $stream->id }}" {{ $selectedStream == $stream->id ? 'selected' : '' }}>
                            {{ $stream->stream_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label><i class="fas fa-tag"></i> Exam Title</label>
                    <select name="exam_title" id="exam_title">
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
                    <button type="submit" class="btn-load">
                        <i class="fas fa-sync-alt"></i> Load
                    </button>
                    <a href="{{ route('marks.marks.entry') }}" class="btn-reset">
                        <i class="fas fa-undo-alt"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- ===== MARKS TABLE ===== -->
    <div class="table-wrapper">
        @if($selectedClass && $examTitle && $students->isNotEmpty() && $subjects->isNotEmpty())
        <form id="marksForm">
            <input type="hidden" name="class_id" value="{{ $selectedClass }}">
            <input type="hidden" name="stream_id" value="{{ $selectedStream }}">
            <input type="hidden" name="exam_title" value="{{ $examTitle }}">
            <input type="hidden" name="exam_date" value="{{ date('Y-m-d') }}">
            
            <!-- Table Header Info -->
            <div class="table-header-info">
                <div class="info-left">
                    <span><i class="fas fa-users"></i> Students: {{ $students->count() }}</span>
                    <span><i class="fas fa-book"></i> Subjects: {{ $subjects->count() }}</span>
                    @if($marksData && count($marksData) > 0)
                        @php
                            $firstStudent = reset($marksData);
                            $firstMark = $firstStudent && $firstStudent['marks'] ? $firstStudent['marks'] : null;
                        @endphp
                        @if($firstMark)
                            <span>
                                <i class="fas fa-info-circle"></i> Status:
                                <span class="status-badge {{ $firstMark->status ?? 'pending' }}">
                                    {{ ucfirst($firstMark->status ?? 'pending') }}
                                </span>
                            </span>
                        @endif
                    @endif
                </div>
                <div class="info-right">
                    @if($isAdmin)
                    <button type="button" class="btn-approve" onclick="approveAll()">
                        <i class="fas fa-check-double"></i> Approve All
                    </button>
                    @endif
                    <button type="button" class="btn-save" onclick="saveMarks()">
                        <i class="fas fa-save"></i> Save Marks
                    </button>
                </div>
            </div>
            
            <!-- Table -->
            <div class="table-scroll">
                <table class="marks-table" id="marksTable">
                    <thead>
                        <tr>
                            <th width="30">#</th>
                            <th width="150">Student Name</th>
                            @foreach($subjects as $subject)
                            <th>
                                {{ $subject->subject_name }}
                                <br>
                               
                            </th>
                            @endforeach
                            <th width="200">Status</th>
                        </tr>
                    </thead>
                   <tbody>
                        @foreach($students as $index => $student)
                        @php
                            $studentMarks = $marksData[$student->id] ?? null;
                            $marksArray = $studentMarks && $studentMarks['marks'] ? $studentMarks['marks']->marks_data : [];
                            $isApproved = $studentMarks && $studentMarks['marks'] && $studentMarks['marks']->status == 'approved';
                            $markStatus = $studentMarks && $studentMarks['marks'] ? $studentMarks['marks']->status : 'pending';
                            
                            // Calculate totals for this student
                            $totalObtained = 0;
                            $totalMax = 0;
                            if ($studentMarks && $studentMarks['marks']) {
                                $totalObtained = $studentMarks['marks']->total_obtained ?? 0;
                                $totalMax = $studentMarks['marks']->total_max ?? 0;
                            }
                            $percentage = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="student-name">{{ $student->full_name }}</td>
                            @foreach($subjects as $subject)
                            <td>
                                @php
                                    $subjectData = $marksArray[$subject->id] ?? null;
                                    $obtained = $subjectData['obtained'] ?? '';
                                    $max = $subjectData['max'] ?? 100;
                                @endphp
                                <div style="display:flex; gap:4px; align-items:center; justify-content:center;">
                                    <input type="number" 
                                           name="marks[{{ $student->id }}][{{ $subject->id }}][obtained]" 
                                           value="{{ $obtained }}"
                                           step="0.01"
                                           min="0"
                                           max="999"
                                           style="width:40px; padding:3px 4px; border:1px solid #e2e8f0; border-radius:4px; font-size:0.7rem; text-align:center;"
                                           {{ $isApproved && !$isAdmin ? 'disabled' : '' }}
                                           onchange="updateTotals()">
                                    <span style="font-size:0.6rem; color:#94a3b8;">/</span>
                                    <input type="number" 
                                           name="marks[{{ $student->id }}][{{ $subject->id }}][max]" 
                                           value="{{ $max }}"
                                           step="0.01"
                                           min="0"
                                           max="999"
                                           style="width:40px; padding:3px 4px; border:1px solid #e2e8f0; border-radius:4px; font-size:0.7rem; text-align:center;"
                                           {{ $isApproved && !$isAdmin ? 'disabled' : '' }}
                                           onchange="updateTotals()">
                                </div>
                            </td>
                            @endforeach
                            <td>
                                <div style="font-size:0.65rem;">
                                    <div><strong>Total:</strong> {{ $totalObtained }} / {{ $totalMax }} |<strong> {{ $percentage }}%</strong></div>
                                    
                                    
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <input type="hidden" name="status" id="formStatus" value="pending">
        </form>
        
        @elseif($selectedClass && $examTitle && $students->isEmpty())
        <div class="no-data">
            <i class="fas fa-users-slash"></i>
            <p>No students found for this class</p>
            <small>Please check if students are enrolled in this class</small>
        </div>
        
        @elseif($selectedClass && $examTitle && $subjects->isEmpty())
        <div class="no-data">
            <i class="fas fa-book-open"></i>
            <p>No subjects allocated for this class</p>
            <small>Please allocate subjects to this class first</small>
        </div>
        
        @else
        <div class="no-data">
            <i class="fas fa-search"></i>
            <p>Select class and exam title to load marks</p>
            <small>Use the filter above to select class and exam</small>
        </div>
        @endif
    </div>
</div>

<script>
// ============================================
// LOAD STREAMS ON CLASS CHANGE
// ============================================
function loadStreams() {
    const classId = document.getElementById('class_id').value;
    const streamSelect = document.getElementById('stream_id');
    
    streamSelect.innerHTML = '<option value="">-- Select Stream --</option>';
    
    if (classId) {
        fetch(`/streams/by-class?class_id=${classId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.streams) {
                    data.streams.forEach(stream => {
                        const option = document.createElement('option');
                        option.value = stream.id;
                        option.textContent = stream.stream_name;
                        streamSelect.appendChild(option);
                    });
                }
            })
            .catch(error => console.error('Error:', error));
    }
}
function updateTotals() {
    // Calculate totals for each student row
    const rows = document.querySelectorAll('#marksTable tbody tr');
    
    rows.forEach(row => {
        let totalObtained = 0;
        let totalMax = 0;
        
        const inputs = row.querySelectorAll('input[name*="[obtained]"]');
        const maxInputs = row.querySelectorAll('input[name*="[max]"]');
        
        inputs.forEach((input, index) => {
            const val = parseFloat(input.value) || 0;
            const maxVal = parseFloat(maxInputs[index]?.value) || 0;
            totalObtained += val;
            totalMax += maxVal;
        });
        
        const statusCell = row.querySelector('td:last-child');
        if (statusCell) {
            const totalDiv = statusCell.querySelector('div');
            if (totalDiv) {
                const percentage = totalMax > 0 ? ((totalObtained / totalMax) * 100).toFixed(2) : 0;
                totalDiv.innerHTML = `
                    <div><strong>Total:</strong> ${totalObtained.toFixed(2)} / ${totalMax.toFixed(2)} |<strong>${percentage}%</strong>  </div>
                    
                    
                `;
            }
        }
    });
}
// ============================================
// UPDATE STATUS ON MARKS CHANGE
// ============================================
function updateStatus() {
    document.getElementById('formStatus').value = 'pending';
}

// ============================================
// SAVE MARKS
// ============================================
function saveMarks() {
    const form = document.getElementById('marksForm');
    const formData = new FormData(form);
    
    // Check if any marks entered
    let hasMarks = false;
    for (let pair of formData.entries()) {
        if (pair[0].startsWith('marks[') && pair[1]) {
            hasMarks = true;
            break;
        }
    }
    
    if (!hasMarks) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Please enter at least one mark',
            confirmButtonColor: '#1e3c72'
        });
        return;
    }
    
    Swal.fire({
        title: 'Saving...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });
    
    fetch('{{ route("marks.marks.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            Swal.fire({
                icon: 'success',
                title: 'Saved! 🎉',
                text: response.message,
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
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

// ============================================
// APPROVE ALL MARKS
// ============================================
function approveAll() {
    const form = document.getElementById('marksForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    
    if (!data.class_id || !data.exam_title) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Please load marks first',
            confirmButtonColor: '#1e3c72'
        });
        return;
    }
    
    Swal.fire({
        title: 'Approve All Marks?',
        text: 'This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Approve All',
        cancelButtonText: 'Cancel'
    }).then(result => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Approving...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            
            fetch('{{ route("marks.marks.approve") }}', {
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
                        title: 'Approved! 🎉',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
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
    });
}

// ============================================
// ENTER KEY SUPPORT
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('exam_title')?.addEventListener('keydown', function(e) {
        if(e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('filterForm').submit();
        }
    });
});
</script>
@endsection