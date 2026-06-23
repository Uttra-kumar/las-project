@extends('layouts.app')

@section('title', 'Other Staff List')

@section('content')
<style>
    .staff-container { animation: fadeIn 0.3s ease; }
    .staff-header {
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
    .staff-header h2 { font-size: 1rem; margin: 0; display: flex; align-items: center; gap: 6px; }
    .btn-add {
        background: #10b981;
        border: none;
        color: white;
        padding: 6px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .btn-add:hover { background: #059669; color: white; }
    
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
        font-size: 0.6rem;
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
    .btn-filter, .btn-reset {
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
        min-width: 800px;
    }
    .data-table th {
        text-align: left;
        padding: 8px 10px;
        background: #f8fafc;
        font-weight: 600;
        border-bottom: 2px solid #e2e8f0;
    }
    .data-table td {
        padding: 6px 10px;
        border-bottom: 1px solid #e2e8f0;
    }
    .data-table tr:hover { background: #f8fafc; }
    
    .badge-active {
        background: #dcfce7;
        color: #15803d;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    .badge-inactive {
        background: #fee2e2;
        color: #dc2626;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    .badge-dept {
        background: #dbeafe;
        color: #1e40af;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.55rem;
        font-weight: 600;
    }
    
    .action-btns { display: flex; gap: 6px; }
    .btn-view, .btn-edit, .btn-delete {
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px 6px;
        border-radius: 4px;
    }
    .btn-view { color: #3b82f6; }
    .btn-edit { color: #f59e0b; }
    .btn-delete { color: #ef4444; }
    .btn-view:hover, .btn-edit:hover, .btn-delete:hover { background: #f1f5f9; }
    
    .pagination { margin-top: 15px; display: flex; justify-content: center; }
    
    @media (max-width: 768px) {
        .filter-group { flex-direction: column; }
        .filter-item { min-width: 100%; }
    }
</style>

<div class="staff-container">
    <div class="staff-header">
        <h2><i class="fas fa-users"></i> Other Staff List</h2>
        <a href="{{ route('management.create') }}" class="btn-add">
            <i class="fas fa-plus"></i> Add Staff
        </a>
    </div>

    <!-- Filter -->
    <div class="filter-card">
        <form method="GET">
            <div class="filter-group">
                <div class="filter-item">
                    <label><i class="fas fa-search"></i> Search</label>
                    <input type="text" name="search" placeholder="Name, ID, Mobile..." value="{{ request('search') }}">
                </div>
                <div class="filter-item">
                    <label><i class="fas fa-building"></i> Department</label>
                    <select name="department">
                        <option value="">All</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                            {{ ucfirst($dept) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label><i class="fas fa-circle"></i> Status</label>
                    <select name="status">
                        <option value="">All</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="filter-item" style="flex:0 0 auto;">
                    <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Filter</button>
                </div>
                <div class="filter-item" style="flex:0 0 auto;">
                    <a href="{{ route('management.index') }}" class="btn-reset" style="text-decoration:none; color:white;">
                        <i class="fas fa-undo-alt"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Emp ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Mobile</th>
                    <th>Salary</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff as $index => $member)
                <tr>
                    <td>{{ $staff->firstItem() + $index }}</td>
                    <td><span style="background:#e0e7ff; color:#4338ca; padding:2px 6px; border-radius:4px; font-size:0.65rem;">{{ $member->emp_id }}</span></td>
                    <td>
                        <strong>{{ $member->full_name }}</strong>
                        @if($member->image)
                            <span style="font-size:0.5rem; color:#94a3b8; display:block;">
                                <i class="fas fa-image"></i> Has Photo
                            </span>
                        @endif
                    </td>
                    <td><span class="badge-dept">{{ ucfirst($member->department) }}</span></td>
                    <td>{{ $member->mobile }}</td>
                    <td>₹{{ number_format($member->salary, 2) }}</td>
                    <td>
                        @if($member->status == 'active')
                            <span class="badge-active"><i class="fas fa-circle" style="font-size:0.3rem;"></i> Active</span>
                        @else
                            <span class="badge-inactive"><i class="fas fa-circle" style="font-size:0.3rem;"></i> Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('management.show', $member->id) }}" class="btn-view" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('management.edit', $member->id) }}" class="btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteStaff({{ $member->id }})" class="btn-delete" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:40px; color:#94a3b8;">
                        <i class="fas fa-users" style="font-size:2rem; display:block; margin-bottom:10px;"></i>
                        No staff found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $staff->appends(request()->query())->links() }}
    </div>
</div>

<script>
function deleteStaff(id) {
    Swal.fire({
        title: 'Delete Staff?',
        text: 'Are you sure you want to delete this staff member?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Delete'
    }).then(result => {
        if (result.isConfirmed) {
            fetch(`/management/staff/${id}`, {
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