@extends('layouts.app')

@section('title', 'Teacher List')

@section('content')
<style>
    .teacher-container { animation: fadeIn 0.3s ease; }
    .teacher-header {
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
    .teacher-header h2 { font-size: 1rem; margin: 0; display: flex; align-items: center; gap: 6px; }
    .btn-add { background: #10b981; border: none; color: white; padding: 6px 16px; border-radius: 6px; cursor: pointer; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 6px; }
    
    .search-section {
        background: white;
        border-radius: 10px;
        padding: 12px 15px;
        margin-bottom: 18px;
        border: 1px solid #e2e8f0;
    }
    
    .search-wrapper {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .search-wrapper input[type="text"] {
        flex: 1;
        min-width: 180px;
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
    }
    
    .search-wrapper select {
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
        background: white;
        min-width: 130px;
        cursor: pointer;
    }
    
    .search-wrapper select:focus {
        outline: none;
        border-color: #1e3c72;
        box-shadow: 0 0 0 2px rgba(30, 60, 114, 0.1);
    }
    
    .search-wrapper button {
        background: #1e3c72;
        border: none;
        color: white;
        padding: 6px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }
    
    .search-wrapper button:hover {
        background: #2b4c7c;
    }
    
    .btn-reset-filter {
        background: #64748b;
        border: none;
        color: white;
        padding: 6px 14px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }
    
    .btn-reset-filter:hover {
        background: #475569;
    }

    .table-wrapper { background: white; border-radius: 10px; border: 1px solid #e2e8f0; overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; font-size: 0.7rem; min-width: 800px; }
    .data-table th { text-align: left; padding: 10px 12px; background: #f8fafc; font-weight: 600; border-bottom: 1px solid #e2e8f0; }
    .data-table td { padding: 8px 12px; border-bottom: 1px solid #e2e8f0; }
    .teacher-id-badge { background: #e0e7ff; color: #4338ca; padding: 2px 6px; border-radius: 4px; font-size: 0.65rem; font-family: monospace; }
    .action-btns { display: flex; gap: 6px; }
    .btn-view, .btn-edit, .btn-delete { background: none; border: none; cursor: pointer; padding: 4px 6px; border-radius: 4px; }
    .btn-view { color: #3b82f6; } .btn-view:hover { background: #dbeafe; }
    .btn-edit { color: #f59e0b; } .btn-edit:hover { background: #fef3c7; }
    .btn-delete { color: #ef4444; } .btn-delete:hover { background: #fee2e2; }
    
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
    
    .badge-hostel { background: #dcfce7; color: #15803d; padding: 2px 8px; border-radius: 20px; font-size: 0.6rem; }
    .badge-nonhostel { background: #fff3e3; color: #b45309; padding: 2px 8px; border-radius: 20px; font-size: 0.6rem; }
    .pagination { margin-top: 15px; display: flex; justify-content: center; }
    
    .status-filter-group {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    
    .status-filter-group label {
        font-size: 0.7rem;
        font-weight: 600;
        color: #1e3c72;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .search-stats {
        font-size: 0.65rem;
        color: #94a3b8;
        padding: 4px 10px;
        background: #f8fafc;
        border-radius: 20px;
    }
</style>

<div class="teacher-container">
    <div class="teacher-header">
        <h2><i class="fas fa-chalkboard-user"></i> Teacher List</h2>
        <button class="btn-add" onclick="window.location.href='{{ route('teacher.create') }}'">
            <i class="fas fa-plus"></i> Add Teacher
        </button>
    </div>

    <div class="search-section">
        <div class="search-wrapper">
            <input type="text" id="searchInput" placeholder="Search by name, ID or mobile..." 
                   value="{{ request('search') }}" autocomplete="off">
            
            <div class="status-filter-group">
                <label><i class="fas fa-user-circle"></i> Status:</label>
                <select id="statusFilter">
                    <option value="">All Teachers</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>🟢 Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>🔴 Inactive</option>
                </select>
            </div>
            
            <button onclick="applyFilters()">
                <i class="fas fa-search"></i> Filter
            </button>
            
            <button class="btn-reset-filter" onclick="resetFilters()">
                <i class="fas fa-undo-alt"></i> Reset
            </button>
        </div>
    </div>

    <div id="teacherTableWrapper">
        @include('teacher.table', ['teachers' => $teachers])
    </div>
</div>

<script>
function applyFilters() {
    let search = document.getElementById('searchInput').value;
    let status = document.getElementById('statusFilter').value;
    
    let url = '{{ route("teacher.list") }}?';
    if(search) url += `search=${encodeURIComponent(search)}&`;
    if(status) url += `status=${status}&`;
    
    window.location.href = url;
}

function resetFilters() {
    window.location.href = '{{ route("teacher.list") }}';
}

// Enter key support
document.getElementById('searchInput').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        applyFilters();
    }
});

// Status filter change triggers auto search
document.getElementById('statusFilter').addEventListener('change', function() {
    applyFilters();
});
</script>
@endsection