@extends('layouts.app')

@section('title', 'Student Fees List')

@section('content')
<style>
    .student-fees-container {
        animation: fadeIn 0.3s ease;
    }

    /* Header */
    .page-header {
        background: linear-gradient(135deg, #1e3c72 0%, #0f2b4d 100%);
        color: white;
        padding: 10px 15px;
        border-radius: 10px;
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .page-header h2 {
        font-size: 0.9rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .session-badge {
        background: rgba(255,255,255,0.2);
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 0.6rem;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 10px;
        padding: 12px;
        margin-bottom: 15px;
        border: 1px solid #e2e8f0;
    }

    .filter-row {
        display: flex;
        gap: 10px;
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
        font-weight: 600;
        color: #1e3c72;
        margin-bottom: 3px;
        text-transform: uppercase;
    }

    .filter-group select, .filter-group input {
        width: 100%;
        padding: 6px 8px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.7rem;
    }

    .btn-filter {
        background: linear-gradient(135deg, #1e3c72, #2b4c7c);
        border: none;
        color: white;
        padding: 6px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-reset {
        background: #e2e8f0;
        color: #475569;
        border: none;
        padding: 6px 14px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
    }

    /* Table - Mobile Responsive */
    .table-wrapper {
        background: white;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    /* Desktop Table View */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.7rem;
    }

    .data-table th {
        text-align: left;
        padding: 10px 12px;
        background: #f8fafc;
        font-weight: 600;
        color: #1e293b;
        border-bottom: 1px solid #e2e8f0;
    }

    .data-table td {
        padding: 8px 12px;
        border-bottom: 1px solid #e2e8f0;
        color: #475569;
    }

    .data-table tr:hover {
        background: #f8fafc;
    }

    /* Mobile Card View */
    .mobile-cards {
        display: none;
        padding: 10px;
    }

    .student-card-item {
        background: white;
        border-radius: 10px;
        padding: 12px;
        margin-bottom: 10px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .student-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px solid #eef2f8;
    }

    .student-name {
        font-weight: 700;
        font-size: 0.85rem;
        color: #1e3c72;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .student-sno {
        font-size: 0.65rem;
        color: #64748b;
        background: #f1f5f9;
        padding: 2px 8px;
        border-radius: 15px;
    }

    .student-detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        font-size: 0.7rem;
        border-bottom: 1px dashed #eef2f8;
    }

    .student-detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #64748b;
    }

    .detail-value {
        color: #1e293b;
        font-weight: 500;
    }

    .collect-btn-mobile {
        width: 100%;
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        color: white;
        padding: 8px;
        border-radius: 8px;
        margin-top: 10px;
        cursor: pointer;
        font-size: 0.7rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    /* Badges */
    .badge-hostel, .badge-nonhostel {
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    .badge-hostel { background: #dcfce7; color: #15803d; }
    .badge-nonhostel { background: #fff3e3; color: #b45309; }

    /* Action Button Desktop */
    .btn-collect {
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        color: white;
        padding: 4px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.65rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-collect:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(16,185,129,0.3);
    }

    /* No Data */
    .no-data {
        text-align: center;
        padding: 40px;
        color: #94a3b8;
    }

    /* Pagination */
    .pagination {
        margin-top: 15px;
        display: flex;
        justify-content: center;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .filter-row {
            flex-direction: column;
            gap: 8px;
        }
        .filter-group {
            min-width: 100%;
        }
        .btn-filter, .btn-reset {
            width: 100%;
            justify-content: center;
            padding: 8px;
        }
        
        /* Hide desktop table on mobile */
        .desktop-table {
            display: none;
        }
        
        /* Show mobile cards */
        .mobile-cards {
            display: block;
        }
        
        .page-header {
            flex-direction: column;
            text-align: center;
        }
    }

    @media (min-width: 769px) {
        .desktop-table {
            display: block;
        }
        .mobile-cards {
            display: none;
        }
    }
</style>

<div class="student-fees-container">
    <div class="page-header">
        <h2>
            <i class="fas fa-rupee-sign"></i> Student Fees List
        </h2>
        <div class="session-badge">
            <i class="fas fa-calendar-alt"></i> Session: <strong>{{ $currentSession->session_name ?? 'No Active Session' }}</strong>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-row">
            <div class="filter-group">
                <label><i class="fas fa-chalkboard-user"></i> Select Class</label>
                <select id="filterClass">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label><i class="fas fa-user-graduate"></i> Student Name</label>
                <input type="text" id="filterName" placeholder="Search by name..." value="{{ request('search') }}" autocomplete="off">
            </div>
            <div class="filter-group">
                <button class="btn-filter" onclick="applyFilter()">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
            <div class="filter-group">
                <button class="btn-reset" onclick="resetFilter()">
                    <i class="fas fa-undo-alt"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="table-wrapper desktop-table">
        <table class="data-table">
            <thead>
                <tr>
                    <th width="50">S.No</th>
                    <th>Class</th>
                    <th>Student Name</th>
                    <th>Father Name</th>
                    <th>DOB</th>
                    <th>Gender</th>
                    <th>Mobile</th>
                    <th>Type</th>
                    <th width="100">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $key => $student)
                <tr>
                    <td>{{ $students->firstItem() + $key }} </td>
                    <td><strong>{{ $student->class->class_name ?? 'N/A' }}</strong></td>
                    <td>
                        <i class="fas fa-user-graduate" style="color: #1e3c72; margin-right: 5px;"></i>
                        {{ $student->full_name }}
                    </td>
                    <td>{{ $student->father_name ?? '-' }}</td>
                    <td>{{ $student->dob ? date('d-m-Y', strtotime($student->dob)) : '-' }}</td>
                    <td>{{ $student->gender ?? '-' }}</td>
                    <td>{{ $student->mobile }}</td>
                    <td>
                        <span class="{{ $student->is_hosteler ? 'badge-hostel' : 'badge-nonhostel' }}">
                            {{ $student->is_hosteler ? 'Hosteler' : 'Day Scholar' }}
                        </span>
                    </td>
                    <td>
                        <button class="btn-collect" onclick="collectFee({{ $student->id }})">
                            <i class="fas fa-rupee-sign"></i> Collect
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="no-data">
                        <i class="fas fa-users" style="font-size: 2rem;"></i>
                        <p>No students found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="mobile-cards">
        @forelse($students as $key => $student)
        <div class="student-card-item">
            <div class="student-header">
                <div class="student-name">
                    <i class="fas fa-user-graduate" style="color: #1e3c72;"></i>
                    {{ $student->full_name }}
                </div>
                <div class="student-sno">#{{ $students->firstItem() + $key }}</div>
            </div>
            <div class="student-detail-row">
                <span class="detail-label"><i class="fas fa-chalkboard-user"></i> Class:</span>
                <span class="detail-value">{{ $student->class->class_name ?? 'N/A' }}</span>
            </div>
            <div class="student-detail-row">
                <span class="detail-label"><i class="fas fa-user"></i> Father:</span>
                <span class="detail-value">{{ $student->father_name ?? '-' }}</span>
            </div>
            <div class="student-detail-row">
                <span class="detail-label"><i class="fas fa-birthday-cake"></i> DOB:</span>
                <span class="detail-value">{{ $student->dob ? date('d-m-Y', strtotime($student->dob)) : '-' }}</span>
            </div>
            <div class="student-detail-row">
                <span class="detail-label"><i class="fas fa-venus-mars"></i> Gender:</span>
                <span class="detail-value">{{ $student->gender ?? '-' }}</span>
            </div>
            <div class="student-detail-row">
                <span class="detail-label"><i class="fas fa-phone"></i> Mobile:</span>
                <span class="detail-value">{{ $student->mobile }}</span>
            </div>
            <div class="student-detail-row">
                <span class="detail-label"><i class="fas fa-building"></i> Type:</span>
                <span class="detail-value">
                    <span class="{{ $student->is_hosteler ? 'badge-hostel' : 'badge-nonhostel' }}">
                        {{ $student->is_hosteler ? 'Hosteler' : 'Day Scholar' }}
                    </span>
                </span>
            </div>
            <button class="collect-btn-mobile" onclick="collectFee({{ $student->id }})">
                <i class="fas fa-rupee-sign"></i> Collect Fee
            </button>
        </div>
        @empty
        <div class="no-data">
            <i class="fas fa-users" style="font-size: 2rem;"></i>
            <p>No students found</p>
        </div>
        @endforelse
    </div>

    @if($students->hasPages())
    <div class="pagination">
        {{ $students->links() }}
    </div>
    @endif
</div>

<script>
    function applyFilter() {
        let classId = document.getElementById('filterClass').value;
        let search = document.getElementById('filterName').value;
        let url = '{{ route("fees.student.list") }}?';
        
        if(classId) url += `class_id=${classId}&`;
        if(search) url += `search=${encodeURIComponent(search)}&`;
        
        window.location.href = url;
    }

    function resetFilter() {
        window.location.href = '{{ route("fees.student.list") }}';
    }

    function collectFee(studentId) {
        window.location.href = `{{ route("fees.collection") }}?student_id=${studentId}`;
    }

    // Enter key search
    document.getElementById('filterName').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilter();
        }
    });
</script>
@endsection