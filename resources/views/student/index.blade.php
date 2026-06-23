@extends('layouts.app')

@section('title', 'Student Management')

@section('content')
<style>
    .student-container {
        animation: fadeIn 0.3s ease;
    }

    /* Header Card */
    .header-card {
        background: white;
        border-radius: 1rem;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .title-section h2 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e3c72;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .title-section p {
        color: #5a6e7c;
        font-size: 0.7rem;
        margin-top: 4px;
    }

    .stats-badge {
        background: #f0f4fe;
        padding: 0.4rem 1rem;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.8rem;
        color: #1e4a76;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 1rem;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }

    .filter-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
    }

    .filter-item {
        flex: 1;
        min-width: 160px;
    }

    .filter-item label {
        display: block;
        font-weight: 600;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #4b6a8b;
        margin-bottom: 0.4rem;
    }

    .filter-item select, .filter-item input {
        width: 100%;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        font-size: 0.8rem;
        background: #fefefe;
    }

    .filter-item select:focus, .filter-item input:focus {
        border-color: #1e3c72;
        outline: none;
    }

    .search-btn {
        background: linear-gradient(105deg, #1e3c72, #2b4c7c);
        border: none;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 600;
        color: white;
        font-size: 0.8rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        justify-content: center;
    }

    .search-btn:hover {
        background: linear-gradient(105deg, #143256, #1f3f62);
    }

    /* Table Wrapper */
    .table-wrapper {
        background: white;
        border-radius: 1rem;
        /*overflow-x: auto;*/
        border: 1px solid #e2e8f0;
    }

    .student-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.75rem;
        min-width: 900px;
    }

    .student-table th {
        text-align: left;
        padding: 0.5rem 1rem;
        background: #f8fafd;
        font-weight: 700;
        color: #1e4663;
        border-bottom: 1px solid #e2e8f0;
    }

    .student-table td {
        padding: 1px 1rem;
        border-bottom: 1px solid #ecf3f9;
        vertical-align: middle;
        color: #1f3448;
    }

    .student-table tr:hover {
        background: #fafcff;
    }

    /* Action Cell - Three Lines */
    .action-cell {
        position: relative;
        text-align: center;
    }

    .three-lines {
        background: none;
        border: none;
        font-size: 1.1rem;
        cursor: pointer;
        color: #5f7f9e;
        padding: 8px 12px;
        border-radius: 8px;
        transition: 0.2s;
    }

    .three-lines:hover {
        background: #eef2fa;
        color: #1e3c72;
    }

    .dropdown-menu {
        position: absolute;
        right: 0;
        top: 40px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        width: 140px;
        z-index: 1000;
        display: none;
        /*overflow: hidden;*/
        border: 1px solid #e2e8f0;
    }

    .dropdown-menu.show {
        display: block;
        animation: fadeIn 0.15s ease;
    }

    .dropdown-menu a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0.6rem 1rem;
        text-decoration: none;
        font-size: 0.75rem;
        font-weight: 500;
        color: #2c3e66;
        transition: 0.1s;
        border-bottom: 1px solid #f0f4fa;
        cursor: pointer;
    }

    .dropdown-menu a:last-child {
        border-bottom: none;
    }

    .dropdown-menu a i {
        width: 18px;
        font-size: 0.8rem;
    }

    .dropdown-menu a:hover {
        background: #eef3fc;
        color: #0f2b4d;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-6px);}
        to { opacity: 1; transform: translateY(0);}
    }

    /* Badges */
    .type-badge {
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-block;
    }

    .type-hostel {
        background: #dcfce7;
        color: #15803d;
    }

    .type-nonhostel {
        background: #fff3e3;
        color: #b45309;
    }

    .student-id-badge {
        background: #e0e7ff;
        color: #4338ca;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.65rem;
        font-family: monospace;
    }

    .pagination {
        margin-top: 1rem;
        padding: 1rem;
        display: flex;
        justify-content: center;
    }

    /* Loading state */
    .search-loading {
        opacity: 0.6;
        pointer-events: none;
    }

    @media (max-width: 768px) {
        .table-wrapper{
            overflow-x: auto;}
        .filter-grid {
            flex-direction: column;
        }
        .search-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="student-container">
    
    <!-- Filter Section -->
    <div class="filter-card">
        <div class="filter-grid">
            <div class="filter-item">
                <label><i class="fas fa-layer-group"></i> Class Name</label>
                <select id="filterClass">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-item">
                <label><i class="fas fa-user-graduate"></i> Student Name</label>
                <input type="text" id="filterName" placeholder="Search by name..." autocomplete="off">
            </div>
            <div class="filter-item">
                <label><i class="fas fa-building"></i> Type</label>
                <select id="filterType">
                    <option value="all">All (Hosteler / Non Hostel)</option>
                    <option value="hosteler">Hosteler</option>
                    <option value="nonhostel">Non Hostel</option>
                </select>
            </div>
            <div class="filter-item">
                <button class="search-btn" id="searchBtn">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
             
        </div>
    </div>

    <!-- Table Wrapper -->
    <div id="tableWrapper">
        @include('student.table', ['students' => $students])
    </div>
</div>

<script>
    function toggleMenu(id) {
        event.stopPropagation();
        const menu = document.getElementById(`menu-${id}`);
        if (menu) {
            // Close all other menus
            document.querySelectorAll('.dropdown-menu').forEach(m => {
                if (m.id !== `menu-${id}`) m.classList.remove('show');
            });
            menu.classList.toggle('show');
        }
    }

    function viewProfile(id) {
        window.open(`/student/profile/${id}`, '_blank');
    }

    function editStudent(id) {
        window.location.href = `/student/edit/${id}`;
    }

    function feesStudent(id) {
        window.location.href = `{{ route("fees.collection") }}?student_id=${id}`;
    }

    function deleteStudent(id, name) {
        Swal.fire({
            title: 'Are you sure?',
            text: `Delete ${name}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#1e3c72',
            confirmButtonText: 'Yes, delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/student/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', data.success, 'success');
                        performSearch(); // Refresh the table
                    }
                })
                .catch(error => {
                    Swal.fire('Error!', 'Something went wrong!', 'error');
                });
            }
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.action-cell')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });

    // Search functionality - No extra space
    let searchTimeout;
    
    function performSearch() {
        const classId = document.getElementById('filterClass').value;
        const search = document.getElementById('filterName').value;
        const type = document.getElementById('filterType').value;
        const searchBtn = document.getElementById('searchBtn');
        
        // Add loading state
        searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching...';
        searchBtn.disabled = true;
        
        fetch(`/student/list?class_id=${encodeURIComponent(classId)}&search=${encodeURIComponent(search)}&type=${encodeURIComponent(type)}&ajax=1`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Replace only the table wrapper content
            document.getElementById('tableWrapper').innerHTML = html;
            
            // Update student count
            const newCount = document.querySelector('#tableWrapper .student-table tbody tr:not(.no-data)').length;
            document.getElementById('studentCount').innerText = newCount || 0;
        })
        .catch(error => {
            console.error('Search error:', error);
        })
        .finally(() => {
            // Remove loading state
            searchBtn.innerHTML = '<i class="fas fa-search"></i> Search';
            searchBtn.disabled = false;
        });
    }

    // Debounced search for better performance
    document.getElementById('filterName').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch();
        }, 500);
    });

    // Search on button click
    document.getElementById('searchBtn').addEventListener('click', function() {
        clearTimeout(searchTimeout);
        performSearch();
    });

    // Search on Enter key
    document.getElementById('filterName').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            clearTimeout(searchTimeout);
            performSearch();
        }
    });

    // Filter on class or type change
    document.getElementById('filterClass').addEventListener('change', function() {
        performSearch();
    });
    
    document.getElementById('filterType').addEventListener('change', function() {
        performSearch();
    });
</script>
@endsection