@extends('layouts.app')

@section('title', 'Stream Allocation')

@section('content')
<style>
    .sa-container {
        animation: fadeIn 0.3s ease;
    }

    .sa-header {
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

    .sa-header h2 {
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

    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 10px;
        padding: 12px 15px;
        margin-bottom: 18px;
        border: 1px solid #e2e8f0;
    }

    .filter-row {
        display: flex;
        gap: 12px;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 150px;
    }

    .filter-group label {
        display: block;
        font-size: 0.65rem;
        font-weight: 600;
        color: #1e3c72;
        margin-bottom: 4px;
        text-transform: uppercase;
    }

    .filter-group select, .filter-group input {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
    }

    .btn-filter, .btn-reset, .btn-csv {
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

    .btn-csv {
        background: #10b981;
    }

    /* Cards Row */
    .stream-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    .stream-card {
        background: white;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .stream-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .stream-card.selected {
        border: 2px solid #10b981;
        background: #f0fdf4;
    }

    .stream-card h4 {
        font-size: 0.85rem;
        margin-bottom: 5px;
        color: #1e3c72;
    }

    .stream-card p {
        font-size: 0.65rem;
        color: #64748b;
    }

    /* Table Section */
    .table-section {
        background: white;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .table-header {
        padding: 10px 15px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .table-header h3 {
        font-size: 0.8rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-assign, .btn-remove, .btn-change {
        padding: 4px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.65rem;
        font-weight: 600;
    }

    .btn-assign {
        background: #10b981;
        color: white;
    }

    .btn-remove {
        background: #ef4444;
        color: white;
    }

    .btn-change {
        background: #f59e0b;
        color: white;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.7rem;
    }

    .data-table th {
        text-align: left;
        padding: 8px 12px;
        background: #f8fafc;
        font-weight: 600;
        border-bottom: 1px solid #e2e8f0;
    }

    .data-table td {
        padding: 6px 12px;
        border-bottom: 1px solid #e2e8f0;
    }

    .student-checkbox {
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .select-all {
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .stream-badge {
        background: #e0e7ff;
        color: #4338ca;
        padding: 2px 8px;
        border-radius: 15px;
        font-size: 0.6rem;
    }

    .no-data {
        text-align: center;
        padding: 40px;
        color: #94a3b8;
    }

    @media (max-width: 768px) {
        .filter-row {
            flex-direction: column;
        }
        .table-header {
            flex-direction: column;
        }
        .action-buttons {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="sa-container">
    <div class="sa-header">
        <h2><i class="fas fa-code-branch"></i> Stream Allocation</h2>
        <div class="session-badge">
            <i class="fas fa-calendar-alt"></i> Session: <strong>{{ $currentSession->session_name ?? 'No Active Session' }}</strong>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-row">
            <div class="filter-group">
                <label><i class="fas fa-chalkboard-user"></i> Select Class</label>
                <select id="class_id" onchange="applyFilter()">
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label><i class="fas fa-search"></i> Search Student</label>
                <input type="text" id="search" placeholder="Search by name or ID..." value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <button class="btn-filter" onclick="applyFilter()">
                    <i class="fas fa-search"></i> Apply
                </button>
            </div>
            <div class="filter-group">
                <button class="btn-reset" onclick="resetFilter()">
                    <i class="fas fa-undo-alt"></i> Reset
                </button>
            </div>
            <div class="filter-group">
                <button class="btn-csv" onclick="exportCSV()">
                    <i class="fas fa-file-csv"></i> CSV
                </button>
            </div>
        </div>
    </div>

    @if($selectedClass)
    <!-- Stream Cards -->
    <div class="stream-cards">
        @foreach($streams as $stream)
        <div class="stream-card {{ $selectedStream == $stream->id ? 'selected' : '' }}" 
             data-stream-id="{{ $stream->id }}" onclick="selectStream({{ $stream->id }})">
            <h4><i class="fas fa-code-branch"></i> {{ $stream->stream_name }}</h4>
            <p>Click to assign this stream</p>
        </div>
        @endforeach
    </div>

    <!-- Unassigned Students Table -->
    <div class="table-section">
        <div class-table-header">
            <h3><i class="fas fa-users"></i> Unassigned Students (Without Stream)</h3>
            <div class="action-buttons">
                <button class="btn-assign" onclick="assignStream()">
                    <i class="fas fa-check"></i> Assign to Selected Stream
                </button>
            </div>
        </div>
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="30"><input type="checkbox" class="select-all" id="selectAllUnassigned" onchange="toggleAllUnassigned()"></th>
                        <th>S.No</th>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Father Name</th>
                        <th>Mobile</th>
                    </tr>
                </thead>
                <tbody id="unassignedTableBody">
                    @forelse($students as $index => $student)
                    <tr>
                        <td><input type="checkbox" class="student-checkbox unassigned-checkbox" data-student-id="{{ $student->id }}" data-student-name="{{ $student->full_name }}"></td>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student->student_id }}</td>
                        <td>{{ $student->full_name }}</td>
                        <td>{{ $student->father_name ?? '-' }}</td>
                        <td>{{ $student->mobile }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="no-data">No unassigned students found</td>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Allocated Students Table -->
    @if($selectedStream)
    <div class="table-section">
        <div class="table-header">
            <h3><i class="fas fa-check-circle"></i> Allocated Students - {{ $streams->where('id', $selectedStream)->first()->stream_name ?? '' }}</h3>
            <div class="action-buttons">
                <button class="btn-remove" onclick="removeStream()">
                    <i class="fas fa-trash"></i> Remove from Stream
                </button>
               
            </div>
        </div>
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="30"><input type="checkbox" class="select-all" id="selectAllAllocated" onchange="toggleAllAllocated()"></th>
                        <th>S.No</th>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Father Name</th>
                        <th>Mobile</th>
                        <th>Current Stream</th>
                    </tr>
                </thead>
                <tbody id="allocatedTableBody">
                    @forelse($allocatedStudents as $index => $student)
                    <tr>
                        <td><input type="checkbox" class="student-checkbox allocated-checkbox" data-student-id="{{ $student->id }}" data-student-name="{{ $student->full_name }}"></td>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student->student_id }}</td>
                        <td>{{ $student->full_name }}</td>
                        <td>{{ $student->father_name ?? '-' }}</td>
                        <td>{{ $student->mobile }}</td>
                        <td><span class="stream-badge">{{ $student->stream->stream_name ?? 'N/A' }}</span></td>
                    </tr>
                    @empty
                    <td><td colspan="7" class="no-data">No students allocated to this stream</td>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
    @else
    <div class="table-section">
        <div class="no-data" style="padding: 50px;">
            <i class="fas fa-chalkboard-user" style="font-size: 2rem;"></i>
            <p>Please select a class to view students</p>
        </div>
    </div>
    @endif
</div>

<script>
    let selectedStreamId = {{ $selectedStream ?? 'null' }};
    
    function selectStream(streamId) {
        selectedStreamId = streamId;
        
        // Update UI
        document.querySelectorAll('.stream-card').forEach(card => {
            card.classList.remove('selected');
            if(card.getAttribute('data-stream-id') == streamId) {
                card.classList.add('selected');
            }
        });
        
        // Reload page with selected stream
        let classId = document.getElementById('class_id').value;
        let search = document.getElementById('search').value;
        let url = '{{ route("stream.allocation") }}?class_id=' + classId + '&stream_id=' + streamId;
        if(search) url += '&search=' + search;
        window.location.href = url;
    }
    
    function applyFilter() {
        let classId = document.getElementById('class_id').value;
        let search = document.getElementById('search').value;
        let url = '{{ route("stream.allocation") }}?class_id=' + classId;
        if(search) url += '&search=' + search;
        if(selectedStreamId) url += '&stream_id=' + selectedStreamId;
        window.location.href = url;
    }
    
    function resetFilter() {
        window.location.href = '{{ route("stream.allocation") }}';
    }
    
    function exportCSV() {
        let classId = document.getElementById('class_id').value;
        let streamId = selectedStreamId;
        let url = '{{ route("stream.allocation.export") }}?class_id=' + classId;
        if(streamId) url += '&stream_id=' + streamId;
        window.location.href = url;
    }
    
    function toggleAllUnassigned() {
        let selectAll = document.getElementById('selectAllUnassigned');
        document.querySelectorAll('.unassigned-checkbox').forEach(cb => {
            cb.checked = selectAll.checked;
        });
    }
    
    function toggleAllAllocated() {
        let selectAll = document.getElementById('selectAllAllocated');
        document.querySelectorAll('.allocated-checkbox').forEach(cb => {
            cb.checked = selectAll.checked;
        });
    }
    
    function assignStream() {
        let selectedStudents = [];
        document.querySelectorAll('.unassigned-checkbox:checked').forEach(cb => {
            selectedStudents.push(cb.getAttribute('data-student-id'));
        });
        
        if(selectedStudents.length === 0) {
            Swal.fire('Error!', 'Please select at least one student', 'error');
            return;
        }
        
        if(!selectedStreamId) {
            Swal.fire('Error!', 'Please select a stream first', 'error');
            return;
        }
        
        let classId = document.getElementById('class_id').value;
        
        Swal.fire({
            title: 'Assign Students?',
            text: `Assign ${selectedStudents.length} student(s) to this stream?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            confirmButtonText: 'Yes, Assign!'
        }).then((result) => {
            if(result.isConfirmed) {
                Swal.fire({ title: 'Assigning...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                
                fetch('{{ route("stream.assign") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        student_ids: selectedStudents,
                        stream_id: selectedStreamId,
                        class_id: classId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire('Success!', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error!', data.error, 'error');
                    }
                });
            }
        });
    }
    
    function removeStream() {
        let selectedStudents = [];
        document.querySelectorAll('.allocated-checkbox:checked').forEach(cb => {
            selectedStudents.push(cb.getAttribute('data-student-id'));
        });
        
        if(selectedStudents.length === 0) {
            Swal.fire('Error!', 'Please select at least one student', 'error');
            return;
        }
        
        Swal.fire({
            title: 'Remove Students?',
            text: `Remove ${selectedStudents.length} student(s) from stream?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Yes, Remove!'
        }).then((result) => {
            if(result.isConfirmed) {
                Swal.fire({ title: 'Removing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                
                fetch('{{ route("stream.remove") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ student_ids: selectedStudents })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire('Success!', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error!', data.error, 'error');
                    }
                });
            }
        });
    }
    
    
</script>
@endsection