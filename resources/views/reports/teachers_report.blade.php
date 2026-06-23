@extends('layouts.app')

@section('title', 'Teacher Report')

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
        min-width: 150px;
    }

    .filter-item label {
        display: block;
        font-size: 0.65rem;
        font-weight: 600;
        color: #1e3c72;
        margin-bottom: 4px;
        text-transform: uppercase;
    }

    .filter-item select {
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
        color: #1e3c72;
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

    .badge-active {
        background: #dcfce7;
        color: #15803d;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #dc2626;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .badge-male {
        background: #dbeafe;
        color: #1e40af;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }

    .badge-female {
        background: #fce7f3;
        color: #be185d;
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

    .no-data i {
        font-size: 3rem;
        margin-bottom: 10px;
    }

    /* Print Styles */
    @media print {
        /* Hide unwanted elements */
        .sidebar, .top-header, .filter-card, .btn-print, .btn-csv, .btn-reset, .btn-filter,
        .menu-toggle, .user-dropdown, .session-badge, .no-print, .pagination,
        .dropdown-menu, .stats-summary {
            display: none !important;
        }
        
        /* Main content full width */
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            left: 0 !important;
            position: relative !important;
        }
        
        .page-content {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }
        
        .report-container {
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
        }
        
        /* Header */
        .report-header {
            background: #1e3c72 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            padding: 6px 10px !important;
            margin-bottom: 10px !important;
            border-radius: 0 !important;
            width: 100% !important;
        }
        
        .report-header h2 {
            font-size: 12px !important;
            margin: 0 !important;
        }
        
        /* Table - Full width with borders */
        .table-wrapper {
            overflow-x: visible !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .data-table {
            font-size: 8px !important;
            width: 100% !important;
            border-collapse: collapse !important;
            table-layout: auto !important;
            margin: 0 !important;
        }
        
        /* All cells with borders */
        .data-table th, .data-table td {
            padding: 4px 4px !important;
            border: 1px solid #000 !important;
            text-align: left !important;
            vertical-align: top !important;
            word-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
        }
        
        .data-table th {
            background: #e0e0e0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            font-weight: 700 !important;
        }
        
        /* Column widths */
        .data-table th:nth-child(1) { width: 5%; }   /* S.No */
        .data-table th:nth-child(2) { width: 8%; }   /* Teacher ID */
        .data-table th:nth-child(3) { width: 15%; }  /* Teacher Name */
        .data-table th:nth-child(4) { width: 12%; }  /* Father Name */
        .data-table th:nth-child(5) { width: 8%; }   /* DOB */
        .data-table th:nth-child(6) { width: 6%; }   /* Gender */
        .data-table th:nth-child(7) { width: 10%; }  /* Mobile */
        .data-table th:nth-child(8) { width: 8%; }   /* Qualification */
        .data-table th:nth-child(9) { width: 8%; }   /* Experience */
        .data-table th:nth-child(10) { width: 8%; }  /* Status */
        
        /* Force everything in one page */
        body, .report-container, .main-content, .page-content {
            height: auto !important;
            overflow: visible !important;
        }
        
        /* Avoid page breaks */
        .table-wrapper, .report-header {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        
        .page-content {
            min-height: auto !important;
        }
    }
</style>

<div class="report-container">
    <div class="report-header">
        <h2><i class="fas fa-chalkboard-user"></i> Teacher Report</h2>
        <div class="session-badge">
            <i class="fas fa-calendar-alt"></i> Session: <strong>{{ $currentSession->session_name ?? 'N/A' }}</strong>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="filter-card">
        <div class="filter-group">
            <div class="filter-item">
                <label><i class="fas fa-user-circle"></i> Status</label>
                <select id="status">
                    <option value="">All Teachers</option>
                    <option value="active" {{ $selectedStatus == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $selectedStatus == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="filter-item">
                <button class="btn-filter" onclick="applyFilter()">
                    <i class="fas fa-search"></i> Generate
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
    @if($teachers->count() > 0)
    <div class="stats-summary">
        <div class="stats-info">
            <span>📋 Total Teachers: <strong>{{ $teachers->count() }}</strong></span>
            <span>🟢 Active: <strong>{{ $teachers->where('is_deleted', 0)->count() }}</strong></span>
            <span>🔴 Inactive: <strong>{{ $teachers->where('is_deleted', 1)->count() }}</strong></span>
            <span>👨‍🏫 Male: <strong>{{ $teachers->where('gender', 'Male')->count() }}</strong></span>
            <span>👩‍🏫 Female: <strong>{{ $teachers->where('gender', 'Female')->count() }}</strong></span>
        </div>
    </div>
    @endif

    <!-- Data Table -->
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Teacher ID</th>
                    <th>Teacher Name</th>
                    <th>Father Name</th>
                    <th>DOB</th>
                    <th>Gender</th>
                    <th>Mobile</th>
                    <th>Qualification</th>
                    <th>Experience</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $index => $teacher)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $teacher->teacher_id }}</strong></td>
                    <td>{{ $teacher->full_name }}</td>
                    <td>{{ $teacher->father_name ?? '-' }}</td>
                    <td>{{ $teacher->dob ? date('d-m-Y', strtotime($teacher->dob)) : '-' }}</td>
                    <td>
                        <span class="{{ $teacher->gender == 'Male' ? 'badge-male' : 'badge-female' }}">
                            {{ $teacher->gender ?? '-' }}
                        </span>
                    </td>
                    <td>{{ $teacher->mobile }}</td>
                    <td>{{ $teacher->highest_qualification ?? '-' }}</td>
                    <td>
                        @if($teacher->experience > 0)
                            {{ $teacher->experience }} {{ $teacher->experience > 1 ? 'Years' : 'Year' }}
                        @else
                            <span style="color: #94a3b8;">Fresher</span>
                        @endif
                    </td>
                    <td>
                        @if($teacher->is_deleted == 0)
                            <span class="badge-active">
                                <i class="fas fa-circle" style="font-size: 0.3rem;"></i> Active
                            </span>
                        @else
                            <span class="badge-inactive">
                                <i class="fas fa-circle" style="font-size: 0.3rem;"></i> Inactive
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="no-data">
                        <i class="fas fa-chalkboard-user"></i>
                        <p>No teachers found. Please generate report.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function applyFilter() {
        let status = document.getElementById('status').value;
        
        let url = '{{ route("reports.teacher") }}?';
        if(status) url += `status=${status}&`;
        
        window.location.href = url;
    }

    function resetFilter() {
        window.location.href = '{{ route("reports.teacher") }}';
    }

    function exportCSV() {
        let status = document.getElementById('status').value;
        
        let url = '{{ route("reports.teacher.export") }}?';
        if(status) url += `status=${status}&`;
        
        window.location.href = url;
    }
</script>
@endsection