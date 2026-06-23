@extends('layouts.app')

@section('title', 'Student Report')

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

    .badge-hostel {
        background: #dcfce7;
        color: #15803d;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }

    .badge-nonhostel {
        background: #fff3e3;
        color: #b45309;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    
    /* ✅ Status Badge */
    .badge-active {
        background: #dcfce7;
        color: #15803d;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    
    .badge-inactive {
        background: #fee2e2;
        color: #dc2626;
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

    @media print {
        .sidebar, .top-header, .filter-card, .btn-print, .btn-csv, .btn-reset, .btn-filter,
        .menu-toggle, .user-dropdown, .session-badge, .no-print, .pagination,
        .action-cell, .dropdown-menu, .stats-summary {
            display: none !important;
        }
        
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
        
        .badge-active, .badge-inactive, .badge-hostel, .badge-nonhostel {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>

<div class="report-container">
    <div class="report-header">
        <h2><i class="fas fa-users"></i> Student Report</h2>
        <div class="session-badge">
            <i class="fas fa-calendar-alt"></i> Session: <strong>{{ $currentSession->session_name ?? 'N/A' }}</strong>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="filter-card">
        <div class="filter-group">
            <div class="filter-item">
                <label><i class="fas fa-chalkboard-user"></i> Class</label>
                <select id="class_id">
                    <option value="">-- Select Class --</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>
                        {{ $class->class_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-item">
                <label><i class="fas fa-building"></i> Student Type</label>
                <select id="type">
                    <option value="">-- Select Type --</option>
                    <option value="hosteler" {{ $selectedType == 'hosteler' ? 'selected' : '' }}>🏠 Hosteler</option>
                    <option value="nonhostel" {{ $selectedType == 'nonhostel' ? 'selected' : '' }}>🏡 Day Scholar</option>
                </select>
            </div>
            
            <!-- ✅ CORRECT Status Filter -->
            <div class="filter-item">
                <label><i class="fas fa-circle"></i> Status</label>
                <select id="status_type">
                    <option value="">-- Select Status --</option>
                    <option value="1" {{ $selectedStatus == '1' ? 'selected' : '' }}>✅ Active</option>
                    <option value="0" {{ $selectedStatus == '0' ? 'selected' : '' }}>❌ Inactive</option>
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

    <!-- Stats Summary (only show if data exists) -->
    @if($students->count() > 0)
    <div class="stats-summary">
        <div class="stats-info">
            <span>📋 Total Students: <strong>{{ $students->count() }}</strong></span>
            <span>🏠 Hosteler: <strong>{{ $students->where('is_hosteler', 1)->count() }}</strong></span>
            <span>🏡 Day Scholar: <strong>{{ $students->where('is_hosteler', 0)->count() }}</strong></span>
            <span>👨‍🎓 Boys: <strong>{{ $students->where('gender', 'Male')->count() }}</strong></span>
            <span>👩‍🎓 Girls: <strong>{{ $students->where('gender', 'Female')->count() }}</strong></span>
            <span>
                ✅ Active: <strong>{{ $students->where('is_deleted', 0)->count() }}</strong>
            </span>
            <span>
                ❌ Inactive: <strong>{{ $students->where('is_deleted', 1)->count() }}</strong>
            </span>
        </div>
    </div>
    @endif

    <!-- Data Table -->
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Class</th>
                    <th>Student Name</th>
                    <th>Father Name</th>
                    <th>DOB</th>
                    <th>Gender</th>
                    <th>Mobile</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Admission Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $student->class->class_name ?? 'N/A' }}</strong></td>
                    <td>{{ $student->full_name }}</td>
                    <td>{{ $student->father_name ?? '-' }}</td>
                    <td>{{ $student->dob ? date('d-m-Y', strtotime($student->dob)) : '-' }}</td>
                    <td>{{ $student->gender ?? '-' }}</td>
                    <td>{{ $student->mobile }}</td>
                    <td>
                        <span class="{{ $student->is_hosteler ? 'badge-hostel' : 'badge-nonhostel' }}">
                            {{ $student->is_hosteler ? '🏠 Hosteler' : '🏡 Day Scholar' }}
                        </span>
                    </td>
                    <td>
                        @if($student->is_deleted == 0)
                            <span class="badge-active">✅ Active</span>
                        @else
                            <span class="badge-inactive">❌ Inactive</span>
                        @endif
                    </td>
                    <td>{{ $student->created_at ? date('d-m-Y', strtotime($student->created_at)) : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="no-data">
                        <i class="fas fa-users"></i>
                        <p>Please select filters to generate report</p>
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
        let type = document.getElementById('type').value;
        let status = document.getElementById('status_type').value;
        
        let url = '{{ route("reports.student") }}?';
        if(classId) url += `class_id=${classId}&`;
        if(type) url += `type=${type}&`;
        if(status) url += `status_type=${status}&`;
        
        window.location.href = url;
    }

    function resetFilter() {
        window.location.href = '{{ route("reports.student") }}';
    }

    function exportCSV() {
        let classId = document.getElementById('class_id').value;
        let type = document.getElementById('type').value;
        let status = document.getElementById('status_type').value;
        
        let url = '{{ route("reports.student.export") }}?';
        if(classId) url += `class_id=${classId}&`;
        if(type) url += `type=${type}&`;
        if(status) url += `status_type=${status}&`;
        
        window.location.href = url;
    }
</script>
@endsection