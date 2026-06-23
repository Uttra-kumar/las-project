@extends('layouts.app')

@section('title', 'Teacher Attendance Report')

@section('content')
<style>
    .report-container { animation: fadeIn 0.3s ease; }
    
    .report-header {
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
    .report-header h2 { font-size: 1rem; margin: 0; display: flex; align-items: center; gap: 6px; }
    .session-badge {
        background: rgba(255,255,255,0.2);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
    }
    
    .filter-card {
        background: white;
        border-radius: 10px;
        padding: 12px 15px;
        margin-bottom: 18px;
        border: 1px solid #e2e8f0;
    }
    .filter-group {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: flex-end;
    }
    .filter-item {
        flex: 1;
        min-width: 140px;
    }
    .filter-item label {
        display: block;
        font-size: 0.65rem;
        font-weight: 600;
        color: #1e3c72;
        margin-bottom: 4px;
        text-transform: uppercase;
    }
    .filter-item input, .filter-item select {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
    }
    .btn-filter, .btn-reset, .btn-print, .btn-csv {
        background: #1e3c72;
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
    .btn-reset { background: #64748b; }
    .btn-print { background: #f59e0b; }
    .btn-csv { background: #10b981; }
    
    .stats-summary {
        background: white;
        border-radius: 10px;
        padding: 10px 15px;
        margin-bottom: 18px;
        border: 1px solid #e2e8f0;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        align-items: center;
    }
    .stats-summary span { font-size: 0.75rem; }
    .stats-summary strong { color: #1e3c72; font-size: 0.9rem; }
    .stats-present strong { color: #10b981; }
    .stats-absent strong { color: #ef4444; }
    .stats-leave strong { color: #f59e0b; }
    
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
        padding: 8px 10px;
        background: #f8fafc;
        font-weight: 600;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }
    .data-table td {
        padding: 6px 10px;
        border-bottom: 1px solid #e2e8f0;
    }
    .data-table tr:hover { background: #f8fafc; }
    
    .badge-present {
        background: #dcfce7;
        color: #15803d;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    .badge-absent {
        background: #fee2e2;
        color: #dc2626;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    .badge-leave {
        background: #fef3c7;
        color: #b45309;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    .badge-percentage {
        background: #dbeafe;
        color: #1e40af;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    .no-data {
        text-align: center;
        padding: 50px;
        color: #94a3b8;
    }
    .no-data i { font-size: 3rem; margin-bottom: 10px; }
    
    .report-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .report-type-badge.summary {
        background: #ede9fe;
        color: #5b21b6;
    }
    .report-type-badge.detailed {
        background: #dbeafe;
        color: #1e40af;
    }
    
    @media print {
        .filter-card, .btn-print, .btn-csv, .btn-reset, .btn-filter,
        .no-print, .sidebar, .top-header, .user-dropdown {
            display: none !important;
        }
        .main-content { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        .page-content { padding: 0 !important; }
        .report-container { max-width: 100% !important; }
        .report-header { background: #1e3c72 !important; -webkit-print-color-adjust: exact; }
        .data-table th { background: #e0e0e0 !important; -webkit-print-color-adjust: exact; }
        .badge-present, .badge-absent, .badge-leave, .badge-percentage { -webkit-print-color-adjust: exact; }
        .stats-summary { border: 1px solid #000; }
    }
</style>

<div class="report-container">
    <!-- Header -->
    <div class="report-header">
        <h2><i class="fas fa-clipboard-list"></i> Teacher Attendance Report</h2>
        <div class="session-badge">
            <i class="fas fa-calendar-alt"></i> {{ $currentSession->session_name ?? 'N/A' }}
        </div>
    </div>

    <!-- Filter Card -->
    <div class="filter-card">
        <form action="{{ route('teacher.attendance.report') }}" method="GET">
            <div class="filter-group">
                <div class="filter-item">
                    <label><i class="fas fa-calendar-alt"></i> From Date</label>
                    <input type="date" name="from_date" value="{{ $fromDate }}">
                </div>
                <div class="filter-item">
                    <label><i class="fas fa-calendar-alt"></i> To Date</label>
                    <input type="date" name="to_date" value="{{ $toDate }}">
                </div>
                <div class="filter-item">
                    <label><i class="fas fa-chalkboard-user"></i> Teacher</label>
                    <select name="teacher_id">
                        <option value="">All Teachers</option>
                        @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ $teacherId == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->full_name }} ({{ $teacher->teacher_id }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label><i class="fas fa-file-alt"></i> Report Type</label>
                    <select name="report_type">
                        <option value="summary" {{ $reportType == 'summary' ? 'selected' : '' }}>
                            📊 Summary (Teacher-wise)
                        </option>
                        <option value="detailed" {{ $reportType == 'detailed' ? 'selected' : '' }}>
                            📋 Detailed (All Records)
                        </option>
                    </select>
                </div>
                <div class="filter-item">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-search"></i> Generate
                    </button>
                </div>
                <div class="filter-item">
                    <a href="{{ route('teacher.attendance.report') }}" class="btn-reset" style="text-decoration:none; color:white;">
                        <i class="fas fa-undo-alt"></i> Reset
                    </a>
                </div>
                <div class="filter-item">
                    <button type="button" class="btn-print" onclick="window.print()">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
                <div class="filter-item">
                    <a href="{{ route('teacher.attendance.export', array_merge(request()->query(), ['report_type' => $reportType])) }}" 
                       class="btn-csv" style="text-decoration:none; color:white;">
                        <i class="fas fa-file-csv"></i> CSV
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Stats Summary -->
    @if($reportType == 'summary' && count($summaryData) > 0)
    <div class="stats-summary">
        <span>📊 Report Type: <strong class="report-type-badge summary">Summary</strong></span>
        <span>📅 Period: <strong>{{ date('d-m-Y', strtotime($fromDate)) }} to {{ date('d-m-Y', strtotime($toDate)) }}</strong></span>
        <span>📅 Total Days: <strong style="color:#1e3c72; font-size:1rem;">{{ $totalDaysInDuration }}</strong></span>
        <span>👨‍🏫 Teachers: <strong>{{ count($summaryData) }}</strong></span>
        <span>✅ Total Present: <strong style="color:#10b981;">{{ array_sum(array_column($summaryData, 'present')) }}</strong></span>
        <span>❌ Total Absent: <strong style="color:#ef4444;">{{ array_sum(array_column($summaryData, 'absent')) }}</strong></span>
        <span>⏳ Total Leave: <strong style="color:#f59e0b;">{{ array_sum(array_column($summaryData, 'leave')) }}</strong></span>
    </div>
    @elseif($reportType == 'detailed' && count($detailedData) > 0)
    <div class="stats-summary">
        <span>📋 Report Type: <strong class="report-type-badge detailed">Detailed</strong></span>
        <span>📅 Period: <strong>{{ date('d-m-Y', strtotime($fromDate)) }} to {{ date('d-m-Y', strtotime($toDate)) }}</strong></span>
        <span>📅 Total Days: <strong style="color:#1e3c72; font-size:1rem;">{{ $totalDaysInDuration }}</strong></span>
        <span>📋 Total Records: <strong>{{ count($detailedData) }}</strong></span>
        <span class="stats-present">✅ Present: <strong>{{ $summaryTotals['total_present'] }}</strong></span>
        <span class="stats-absent">❌ Absent: <strong>{{ $summaryTotals['total_absent'] }}</strong></span>
        <span class="stats-leave">⏳ Leave: <strong>{{ $summaryTotals['total_leave'] }}</strong></span>
    </div>
    @endif

    <!-- Table -->
    <div class="table-wrapper">
        @if($reportType == 'summary')
        <!-- ============================================ -->
        <!-- ✅ SUMMARY TABLE -->
        <!-- ============================================ -->
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Teacher ID</th>
                    <th>Teacher Name</th>
                    <th style="text-align:center;">✅ Present</th>
                    <th style="text-align:center;">❌ Absent</th>
                    <th style="text-align:center;">⏳ Leave</th>
                    <th style="text-align:center;">📅 Total Days</th>
                    <th style="text-align:center;">📋 Working Days</th>
                    <th style="text-align:center;">Attendance %</th>
                </tr>
            </thead>
            <tbody>
                @forelse($summaryData as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $data['teacher_id'] }}</strong></td>
                    <td>{{ $data['teacher_name'] }}</td>
                    <td style="text-align:center; font-weight:600; color:#10b981;">{{ $data['present'] }}</td>
                    <td style="text-align:center; font-weight:600; color:#ef4444;">{{ $data['absent'] }}</td>
                    <td style="text-align:center; font-weight:600; color:#f59e0b;">{{ $data['leave'] }}</td>
                    <td style="text-align:center; font-weight:700; color:#1e3c72;">
                        {{ $data['total_days'] }}
                    </td>
                    <td style="text-align:center; font-weight:600; color:#64748b;">
                        {{ $data['working_days'] }}
                    </td>
                    <td style="text-align:center;">
                        <span class="badge-percentage" 
                              style="background: {{ $data['attendance_percentage'] >= 80 ? '#dcfce7' : ($data['attendance_percentage'] >= 60 ? '#fef3c7' : '#fee2e2') }};
                                     color: {{ $data['attendance_percentage'] >= 80 ? '#15803d' : ($data['attendance_percentage'] >= 60 ? '#b45309' : '#dc2626') }};">
                            {{ $data['attendance_percentage'] }}%
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="no-data">
                        <i class="fas fa-clipboard-list"></i>
                        <p>No attendance records found for the selected filters</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @else
        <!-- ============================================ -->
        <!-- ✅ DETAILED TABLE - ALL RECORDS -->
        <!-- ============================================ -->
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Teacher ID</th>
                    <th>Teacher Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detailedData as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data['date'] }}</td>
                    <td>{{ $data['day'] }}</td>
                    <td><strong>{{ $data['teacher_id'] }}</strong></td>
                    <td>{{ $data['teacher_name'] }}</td>
                    <td>
                        @if($data['status'] == 'Present')
                            <span class="badge-present"><i class="fas fa-check-circle"></i> Present</span>
                        @elseif($data['status'] == 'Absent')
                            <span class="badge-absent"><i class="fas fa-times-circle"></i> Absent</span>
                        @else
                            <span class="badge-leave"><i class="fas fa-clock"></i> Leave</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="no-data">
                        <i class="fas fa-clipboard-list"></i>
                        <p>No attendance records found for the selected filters</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection