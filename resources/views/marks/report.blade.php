@extends('layouts.app')

@section('title', 'Marks Report')

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
    
    .report-container {
        animation: fadeInUp 0.35s ease;
        max-width: 100%;
    }
    
    /* ===== HEADER ===== */
    .report-header {
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
    .report-header h2 {
        font-size: 0.95rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }
    .report-header h2 i {
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
    .filter-item select:focus {
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
    .btn-generate, .btn-print, .btn-csv, .btn-reset {
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
    .btn-generate {
        background: linear-gradient(135deg, #1e3c72, #2d4a7a);
        box-shadow: 0 2px 6px rgba(30, 60, 114, 0.2);
    }
    .btn-generate:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(30, 60, 114, 0.3);
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
        background: linear-gradient(135deg, #10b981, #059669);
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.2);
    }
    .btn-csv:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(16, 185, 129, 0.3);
    }
    .btn-reset {
        background: linear-gradient(135deg, #64748b, #475569);
        box-shadow: 0 2px 6px rgba(100, 116, 139, 0.2);
    }
    .btn-reset:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(100, 116, 139, 0.3);
    }
    .btn-generate i, .btn-print i, .btn-csv i, .btn-reset i {
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
    
    /* ===== REPORT INFO ===== */
    .report-info {
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        padding: 10px 16px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }
    .report-info .info-left {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }
    .report-info .info-left span {
        font-size: 0.75rem;
        font-weight: 600;
        color: #1e3c72;
    }
    .report-info .info-left span i {
        color: #3b82f6;
        margin-right: 4px;
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
    .report-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.7rem;
        min-width: 550px;
    }
    .report-table th {
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
    .report-table td {
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        text-align: center;
        vertical-align: middle;
    }
    .report-table .student-name {
        text-align: center;
        font-weight: 600;
        font-size: 0.7rem;
        color: #1e293b;
    }
    .report-table .student-sno {
        font-weight: 600;
        color: #94a3b8;
    }
    
    /* ===== GRAND TOTAL ROW ===== */
    .report-table .grand-total {
        background: #f1f5f9;
        font-weight: 700;
    }
    .report-table .grand-total td {
        border-top: 2px solid #1e3c72;
        padding: 8px 10px;
    }
    .report-table .grand-total .total-label {
        text-align: right;
        color: #1e3c72;
        text-transform: uppercase;
        font-size: 0.65rem;
    }
    .report-table .grand-total .total-value {
        font-size: 0.8rem;
        color: #1e3c72;
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
        .report-table {
            font-size: 0.65rem;
            min-width: 500px;
        }
        .report-table th,
        .report-table td {
            padding: 6px 8px;
        }
    }
    
    @media (max-width: 768px) {
        .report-header {
            padding: 10px 14px;
            flex-direction: column;
            text-align: center;
        }
        .report-header h2 {
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
        .filter-actions .btn-generate,
        .filter-actions .btn-reset {
            flex: 1;
            justify-content: center;
        }
        .header-actions {
            width: 100%;
            justify-content: center;
        }
        .report-info {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .report-info .info-left {
            justify-content: center;
        }
        .report-table {
            font-size: 0.6rem;
            min-width: 450px;
        }
        .report-table th,
        .report-table td {
            padding: 4px 6px;
        }
        .report-table .student-name {
            font-size: 0.6rem;
        }
        .status-badge {
            font-size: 0.5rem;
            padding: 1px 8px;
        }
    }
    
    @media (max-width: 480px) {
        .report-header {
            padding: 8px 12px;
        }
        .report-header h2 {
            font-size: 0.8rem;
        }
        .filter-card {
            padding: 8px 10px;
        }
        .filter-item label {
            font-size: 0.55rem;
        }
        .filter-item select {
            font-size: 0.7rem;
            padding: 4px 8px;
            height: 30px;
        }
        .btn-generate, .btn-print, .btn-csv, .btn-reset {
            font-size: 0.6rem;
            padding: 4px 10px;
            height: 30px;
        }
        .report-table {
            font-size: 0.55rem;
            min-width: 380px;
        }
        .report-table th,
        .report-table td {
            padding: 3px 4px;
        }
        .report-table th {
            font-size: 0.5rem;
        }
        .report-table .student-name {
            font-size: 0.55rem;
        }
        .filter-actions {
            flex-direction: column;
        }
        .filter-actions .btn-generate,
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
        .report-info .info-left span {
            font-size: 0.65rem;
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
        .sidebar, .top-header, .filter-card, .btn-print, .btn-csv,
        .btn-generate, .btn-reset, .header-actions, .filter-actions,
        .no-print, .report-header .header-actions {
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
        .report-container {
            padding: 0 !important;
            margin: 0 !important;
            animation: none !important;
        }
        .report-header {
            background: #1e3c72 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            padding: 8px 12px !important;
        }
        .report-header h2 {
            font-size: 12px !important;
        }
        .report-info {
            border: 1px solid #000 !important;
            background: #f0f0f0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .table-wrapper {
            border: 1px solid #000 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }
        .report-table th {
            background: #e0e0e0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            border: 1px solid #000 !important;
            font-size: 8px !important;
            padding: 4px 6px !important;
        }
        .report-table td {
            border: 1px solid #000 !important;
            padding: 3px 6px !important;
            font-size: 7px !important;
        }
        .report-table {
            font-size: 7px !important;
            min-width: auto !important;
            width: 100% !important;
        }
        .report-table .grand-total {
            background: #f0f0f0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .report-table .grand-total td {
            border-top: 2px solid #000 !important;
        }
        .status-badge {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
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

<div class="report-container">
    <!-- ===== HEADER ===== -->
    <div class="report-header">
        <h2>
            <i class="fas fa-chart-bar"></i>
            Marks Report
        </h2>
        @if(Auth::user()->role=="admin")
        <div class="header-actions no-print">
            <button class="btn-print" onclick="window.print()">
                <i class="fas fa-print"></i> Print
            </button>
            <a href="{{ route('marks.report.export', request()->query()) }}" class="btn-csv">
                <i class="fas fa-file-csv"></i> CSV
            </a>
        </div>
        @endif
    </div>

    <!-- ===== FILTER ===== -->
    <div class="filter-card">
        <form method="GET" action="{{ route('marks.report') }}">
            <div class="filter-row">
                <div class="filter-item">
                    <label><i class="fas fa-school"></i> Class</label>
                    <select name="class_id" id="class_id" required onchange="this.form.submit()">
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
                    <select name="stream_id" id="stream_id" onchange="this.form.submit()">
                        <option value="">-- Select Stream --</option>
                        @foreach($streams as $stream)
                        <option value="{{ $stream->id }}" {{ $selectedStream == $stream->id ? 'selected' : '' }}>
                            {{ $stream->stream_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label><i class="fas fa-tag"></i> Exam</label>
                    <select name="exam_title" id="exam_title" required onchange="this.form.submit()">
                        <option value="">-- Select Exam --</option>
                        <option value="Monthly Test" {{ $selectedExam == 'Monthly Test' ? 'selected' : '' }}>
                            📝 Monthly Test
                        </option>
                        <option value="Quarterly Exam" {{ $selectedExam == 'Quarterly Exam' ? 'selected' : '' }}>
                            📊 Quarterly Exam
                        </option>
                        <option value="Half Yearly Exam" {{ $selectedExam == 'Half Yearly Exam' ? 'selected' : '' }}>
                            📈 Half Yearly Exam
                        </option>
                        <option value="Pre-Final Exam" {{ $selectedExam == 'Pre-Final Exam' ? 'selected' : '' }}>
                            🎯 Pre-Final Exam
                        </option>
                        <option value="Final Exam" {{ $selectedExam == 'Final Exam' ? 'selected' : '' }}>
                            🏆 Final Exam
                        </option>
                    </select>
                </div>
                <div class="filter-item filter-actions">
                    <button type="submit" class="btn-generate">
                        <i class="fas fa-sync-alt"></i> Generate
                    </button>
                    <a href="{{ route('marks.report') }}" class="btn-reset">
                        <i class="fas fa-undo-alt"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- ===== REPORT ===== -->
    @if($selectedClass && $selectedExam && !empty($reportData))
    <div class="report-info">
        <div class="info-left">
            <span><i class="fas fa-school"></i> {{ $classes->where('id', $selectedClass)->first()->class_name ?? 'N/A' }}
                @if($selectedStream)
                - {{ $streams->where('id', $selectedStream)->first()->stream_name ?? '' }}
                @endif
            </span>
            <span><i class="fas fa-tag"></i> {{ $selectedExam }}</span>
            <span><i class="fas fa-users"></i> Students: {{ count($reportData) }}</span>
            @if(!empty($subjects))
            <span><i class="fas fa-book"></i> Subjects: {{ $subjects->count() }}</span>
            @endif
        </div>
    </div>

    <div class="table-wrapper">
        <div class="table-scroll">
            <table class="report-table">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th width="150">Student Name</th>
                        @foreach($subjects as $subject)
                        <th>{{ $subject->subject_name }}</th>
                        @endforeach
                        <th>Obtain</th>
                        <th>Total</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandTotalObtained = 0;
                        $grandTotalMax = 0;
                    @endphp
                    
                    @foreach($reportData as $index => $row)
                    @php
                        $grandTotalObtained += $row['total_obtained'];
                        $grandTotalMax += $row['total_max'];
                    @endphp
                    <tr>
                        <td class="student-sno">{{ $index + 1 }}</td>
                        <td class="student-name">{{ $row['student']->full_name }}</td>
                        @foreach($subjects as $subject)
                        <td>
                            @php
                                $marks = $row['subject_marks'][$subject->id] ?? null;
                            @endphp
                            @if($marks && $marks['obtained'] !== '-')
                                {{ $marks['obtained'] }}
                            @else
                                <span style="color:#94a3b8;">-</span>
                            @endif
                        </td>
                        @endforeach
                        <td><strong>{{ $row['total_obtained'] }}</strong></td>
                        <td><strong>{{ $row['total_max'] }}</strong></td>
                        <td>
                            <strong style="color: {{ $row['percentage'] >= 60 ? '#10b981' : ($row['percentage'] >= 40 ? '#f59e0b' : '#ef4444') }}">
                                {{ $row['percentage'] }}%
                            </strong>
                        </td>
                    </tr>
                    @endforeach
                    
                 
                   
                </tbody>
            </table>
        </div>
    </div>
    
    @elseif($selectedClass && $selectedExam)
    <div class="table-wrapper">
        <div class="no-data">
            <i class="fas fa-search"></i>
            <p>No marks found for this exam</p>
            <small>Please check if marks are entered for this exam</small>
        </div>
    </div>
    
    @else
    <div class="table-wrapper">
        <div class="no-data">
            <i class="fas fa-filter"></i>
            <p>Select class and exam to view report</p>
            <small>Use the filter above to select class and exam</small>
        </div>
    </div>
    @endif
</div>

<script>
// ============================================
// AUTO-SUBMIT ON CHANGE
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const classSelect = document.getElementById('class_id');
    const streamSelect = document.getElementById('stream_id');
    const examSelect = document.getElementById('exam_title');
    
    if (classSelect) {
        classSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }
    
    if (streamSelect) {
        streamSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }
    
    if (examSelect) {
        examSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }
});

// ============================================
// LOAD STREAMS ON CLASS CHANGE (for dynamic loading)
// ============================================
function loadStreams() {
    const classId = document.getElementById('class_id').value;
    const streamSelect = document.getElementById('stream_id');
    
    if (!streamSelect) return;
    
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
</script>
@endsection