@extends('layouts.app')

@section('title', 'Exam List')

@section('content')
<style>
    .exam-container { animation: fadeIn 0.3s ease; }
    .exam-header {
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
    .exam-header h2 { font-size: 1rem; margin: 0; display: flex; align-items: center; gap: 6px; }
    .exam-header h2 i { color: #f59e0b; }
    
    .filter-card {
        background: white;
        border-radius: 10px;
        padding: 12px 15px;
        margin-bottom: 18px;
        border: 1px solid #e2e8f0;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: flex-end;
    }
    .filter-item { flex: 1; min-width: 150px; }
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
    .btn-filter, .btn-reset, .btn-print, .btn-csv, .btn-add {
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
    .btn-add { background: #10b981; text-decoration: none; }
    .btn-add:hover { background: #059669; color: white; }
    .btn-filter { background: #1e3c72; }
    .btn-filter:hover { background: #2b4c7c; }
    .btn-reset { background: #64748b; }
    .btn-reset:hover { background: #475569; }
    .btn-print { background: #f59e0b; }
    .btn-print:hover { background: #d97706; }
    .btn-csv { background: #8b5cf6; }
    .btn-csv:hover { background: #7c3aed; }
    
    .table-wrapper {
        background: white;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow-x: auto;
        padding: 10px;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.7rem;
        min-width: 700px;
    }
    .data-table th {
        text-align: left;
        padding: 8px 10px;
        background: #f8fafc;
        font-weight: 700;
        border-bottom: 2px solid #e2e8f0;
        font-size: 0.6rem;
        text-transform: uppercase;
        color: #1e3c72;
    }
    .data-table td {
        padding: 6px 10px;
        border-bottom: 1px solid #e2e8f0;
    }
    .data-table tr:hover { background: #f8fafc; }
    
    .badge-stream {
        background: #dbeafe;
        color: #1e40af;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    .badge-type {
        background: #fef3c7;
        color: #b45309;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    .subject-tag {
        display: inline-block;
        background: #f1f5f9;
        padding: 1px 6px;
        border-radius: 4px;
        font-size: 0.6rem;
        margin: 1px;
    }
    .action-btns { display: flex; gap: 6px; }
    .btn-edit, .btn-delete {
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px 6px;
        border-radius: 4px;
    }
    .btn-edit { color: #f59e0b; }
    .btn-delete { color: #ef4444; }
    .btn-edit:hover, .btn-delete:hover { background: #f1f5f9; }
    
    .pagination { margin-top: 15px; display: flex; justify-content: center; }
    .no-data {
        text-align: center;
        padding: 40px;
        color: #94a3b8;
    }
    .no-data i { font-size: 2rem; display: block; margin-bottom: 10px; }
    
    @media (max-width: 768px) {
        .filter-card { flex-direction: column; }
        .filter-item { min-width: 100%; }
    }
</style>

<div class="exam-container">
    <div class="exam-header">
        <h2><i class="fas fa-pencil-alt"></i> Exam List</h2>
        <a href="{{ route('exam.create') }}" class="btn-add">
            <i class="fas fa-plus"></i> Add Exam
        </a>
    </div>

    <!-- Filter -->
    <div class="filter-card">
        <form method="GET">
            <div class="filter-item">
                <label><i class="fas fa-calendar-day"></i> Exam Date</label>
                <input type="date" name="exam_date" value="{{ request('exam_date') }}">
            </div>
            <div class="filter-item">
                <label><i class="fas fa-school"></i> Class</label>
                <select name="class_id">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                        {{ $class->class_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-item" style="flex:0 0 auto;">
                <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Filter</button>
            </div>
            <div class="filter-item" style="flex:0 0 auto;">
                <a href="{{ route('exam.index') }}" class="btn-reset" style="text-decoration:none; color:white;">
                    <i class="fas fa-undo-alt"></i> Reset
                </a>
            </div>
            <div class="filter-item" style="flex:0 0 auto;">
                <button type="button" class="btn-print" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
            <div class="filter-item" style="flex:0 0 auto;">
                <a href="{{ route('exam.export', request()->query()) }}" class="btn-csv" style="text-decoration:none; color:white;">
                    <i class="fas fa-file-csv"></i> CSV
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Exam Name</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Class</th>
                    <th>Stream</th>
                    <th>Subjects (Teacher)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($exams as $index => $exam)
                <tr>
                    <td>{{ $exams->firstItem() + $index }}</td>
                    <td><strong>{{ $exam->exam_name }}</strong></td>
                    <td>{{ $exam->exam_date->format('d-m-Y') }}</td>
                    <td><span class="badge-type">{{ $exam->exam_type ?? '-' }}</span></td>
                    <td>{{ $exam->class->class_name ?? 'N/A' }}</td>
                    <td>
                        @if($exam->stream_id)
                            <span class="badge-stream">{{ $exam->stream->stream_name ?? 'N/A' }}</span>
                        @else
                            <span style="color:#94a3b8; font-size:0.6rem;">No Stream</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $subjectData = $exam->getSubjectTeacherData();
                        @endphp
                        @foreach($subjectData as $item)
                            <span class="subject-tag">
                                {{ $item['subject_name'] }} 
                                <span style="color:#64748b;">({{ $item['teacher_name'] }})</span>
                            </span>
                        @endforeach
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('exam.edit', $exam->id) }}" class="btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteExam({{ $exam->id }})" class="btn-delete" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="no-data">
                        <i class="fas fa-pencil-alt"></i>
                        <p>No exams found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $exams->appends(request()->query())->links() }}
    </div>
</div>

<script>
function deleteExam(id) {
    Swal.fire({
        title: 'Delete Exam?',
        text: 'Are you sure you want to delete this exam?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Delete'
    }).then(result => {
        if (result.isConfirmed) {
            fetch(`/exam/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            });
        }
    });
}
</script>
@endsection