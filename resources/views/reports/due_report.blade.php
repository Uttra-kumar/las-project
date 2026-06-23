@extends('layouts.app')

@section('title', 'Due Report')

@section('content')
<style>
    .report-container {
        animation: fadeIn 0.3s ease;
    }

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

    .report-header h2 {
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

    /* Filter Card */
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
        background: linear-gradient(135deg, #1e3c72, #2b4c7c);
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

    .btn-reset {
        background: #64748b;
    }

    .btn-print {
        background: #f59e0b;
    }

    .btn-csv {
        background: #10b981;
    }

    /* Stats Summary */
    .stats-summary {
        background: white;
        border-radius: 10px;
        padding: 10px 15px;
        margin-bottom: 18px;
        border: 1px solid #e2e8f0;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .stats-info {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .stats-info span {
        font-size: 0.7rem;
    }

    .stats-info strong {
        color: #dc2626;
        font-size: 0.85rem;
    }

    /* Table */
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
        min-width: 900px;
    }

    .data-table th {
        text-align: left;
        padding: 8px 10px;
        background: #f8fafc;
        font-weight: 600;
        color: #1e293b;
        border-bottom: 1px solid #e2e8f0;
        border-right: 1px solid #eef2f8;
    }

    .data-table td {
        padding: 6px 10px;
        border-bottom: 1px solid #e2e8f0;
        border-right: 1px solid #eef2f8;
    }

    .data-table th:last-child, .data-table td:last-child {
        border-right: none;
    }

    .due-amount {
        color: #dc2626;
        font-weight: 700;
    }

    .no-data {
        text-align: center;
        padding: 40px;
        color: #94a3b8;
    }

    @media print {
        .sidebar, .top-header, .filter-card, .btn-print, .btn-csv, .btn-reset, .btn-filter {
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
        }
        .data-table th, .data-table td {
            border: 1px solid #000 !important;
        }
        .stats-summary {
            border: 1px solid #000 !important;
        }
        
    }
</style>

<div class="report-container">
    <div class="report-header">
        <h2><i class="fas fa-exclamation-triangle"></i> Due Report</h2>
        <div class="session-badge">
            <i class="fas fa-calendar-alt"></i> Session: <strong>{{ $currentSession->session_name ?? 'N/A' }}</strong>
        </div>
    </div>

            <div class="filter-card">
                <div class="filter-group">
                    <div class="filter-item">
                        <label><i class="fas fa-chalkboard-user"></i> Class</label>
                        <select id="class_id">
                            <option value="">All Classes</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-item">
                        <label><i class="fas fa-tag"></i> Fee Type</label>
                        <select id="fee_type_id">
                            <option value="">All Fee Types</option>
                            @foreach($feeTypes as $type)
                            <option value="{{ $type->id }}" {{ $selectedFeeType == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-item">
                        <button class="btn-filter" onclick="applyFilter()">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                    <div class="filter-item">
                        <button class="btn-reset" onclick="resetFilter()">
                            <i class="fas fa-undo-alt"></i> Reset
                        </button>
                    </div>
                    <div class="filter-item">
                        <button class="btn-print" onclick="window.print()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                    <div class="filter-item">
                        <button class="btn-csv" onclick="exportCSV()">
                            <i class="fas fa-file-csv"></i> CSV
                        </button>
                    </div>
                </div>
</div>

    <!-- Stats Summary -->
    

    <!-- Data Table -->
    <div class="table-wrapper">
        <table class="data-table" id="dueTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Class</th>
                    <th>Student Name</th>
                    <th>Father Name</th>
                    <th>Fee Type</th>
                    <th>Amount (₹)</th>
                    <th>Paid (₹)</th>
                    <th>Discount (₹)</th>
                    <th>Due (₹)</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($dueRecords as $index => $record)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $record['class']->class_name ?? 'N/A' }}</td>
                    <td><strong>{{ $record['student']->full_name }}</strong></td>
                    <td>{{ $record['student']->father_name ?? '-' }}</td>
                    <td>{{ $record['fee_type']->name ?? 'N/A' }}</td>
                    <td>₹ {{ number_format($record['total_fees'], 2) }}</td>
                    <td>₹ {{ number_format($record['paid'], 2) }}</td>
                    <td>₹ {{ number_format($record['discount'], 2) }}</td>
                    <td class="due-amount">₹ {{ number_format($record['due'], 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="no-data">
                        <i class="fas fa-check-circle" style="font-size: 2rem; color: #10b981;"></i>
                        <p>No due records found. All fees are cleared!</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function applyFilter() {
        let classId = document.getElementById('class_id').value;
        let feeTypeId = document.getElementById('fee_type_id').value;
        
        let url = '{{ route("reports.due") }}?';
        if(classId) url += `class_id=${classId}&`;
        if(feeTypeId) url += `fee_type_id=${feeTypeId}&`;
        
        window.location.href = url;
    }

    function resetFilter() {
        window.location.href = '{{ route("reports.due") }}';
    }

    function exportCSV() {
        let classId = document.getElementById('class_id').value;
        let feeTypeId = document.getElementById('fee_type_id').value;
        
        let url = '{{ route("reports.due.export") }}?';
        if(classId) url += `class_id=${classId}&`;
        if(feeTypeId) url += `fee_type_id=${feeTypeId}&`;
        
        window.location.href = url;
    }
</script>
@endsection