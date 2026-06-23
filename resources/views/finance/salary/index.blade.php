@extends('layouts.app')

@section('title', 'Teacher Salary List')

@section('content')
<style>
    .salary-container { animation: fadeIn 0.3s ease; }
    .salary-header {
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
    .salary-header h2 { font-size: 1rem; margin: 0; display: flex; align-items: center; gap: 6px; }
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
        /*flex: 1;*/
        min-width: 100px;
    }
    .filter-item label {
        display: block;
        font-size: 0.65rem;
        font-weight: 600;
        color: #1e3c72;
        margin-bottom: 4px;
        text-transform: uppercase;
    }
    .filter-item select, .filter-item input {
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
    
    .grand-total {
        background: white;
        border-radius: 10px;
        padding: 10px 15px;
        margin-bottom: 18px;
        border: 2px solid #1e3c72;
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        align-items: center;
    }
    .grand-total span { font-size: 0.75rem; font-weight: 600; }
    .grand-total .amount { color: #1e3c72; font-size: 1.1rem; }
    .grand-total .cash { color: #10b981; }
    .grand-total .bank { color: #3b82f6; }
    
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
    
    .badge-paid {
        background: #dcfce7;
        color: #15803d;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    .badge-hold {
        background: #fee2e2;
        color: #dc2626;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    .badge-bank {
        background: #dbeafe;
        color: #1e40af;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    .badge-cash {
        background: #fef3c7;
        color: #b45309;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.6rem;
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
    
    @media print {
        .filter-card, .btn-print, .btn-csv, .btn-reset, .btn-filter,
        .no-print, .sidebar, .top-header, .user-dropdown, .action-btns {
            display: none !important;
        }
        .main-content { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        .page-content { padding: 0 !important; }
        .salary-container { max-width: 100% !important; }
        .salary-header { background: #1e3c72 !important; -webkit-print-color-adjust: exact; }
        .data-table th { background: #e0e0e0 !important; -webkit-print-color-adjust: exact; }
        .grand-total { border: 2px solid #000 !important; }
    }
</style>

<div class="salary-container">
    <div class="salary-header">
        <h2><i class="fas fa-money-bill-wave"></i> Teacher Salary List</h2>
        <div>
            <span class="session-badge" style="background:rgba(255,255,255,0.2); padding:3px 10px; border-radius:20px; font-size:0.65rem; margin-right:10px;">
                <i class="fas fa-calendar-alt"></i> {{ $currentSession->session_name ?? 'N/A' }}
            </span>
            <a href="{{ route('finance.salary.create') }}" class="btn-add">
                <i class="fas fa-plus"></i> Add Salary
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="filter-card">
        <form method="GET">
            <div class="filter-group">
                 
                    <div class="filter-item">
                    <label><i class="fas fa-user-tie"></i>Teacher</label>
                    <select name="teacher_id" id="teacher_id">
                        <option value="">All Teacher</option>
                        @foreach($allTeacher as $teacher)
                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->full_name }} ({{ $teacher->emp_id }})
                        </option>
                     @endforeach
                    </select>
                </div>

                <div class="filter-item">
                    <label><i class="fas fa-calendar-alt"></i> Month</label>
                    <select name="month">
                        <option value="">All Months</option>
                        @foreach($months as $month)
                        <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                            {{ $month }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label><i class="fas fa-money-bill"></i> Type</label>
                    <select name="salary_type">
                        <option value="">All Types</option>
                        <option value="bank" {{ request('salary_type') == 'bank' ? 'selected' : '' }}>Bank</option>
                        <option value="cash" {{ request('salary_type') == 'cash' ? 'selected' : '' }}>Cash</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label><i class="fas fa-circle"></i> Status</label>
                    <select name="status">
                        <option value="">All Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="hold" {{ request('status') == 'hold' ? 'selected' : '' }}>Hold</option>
                    </select>
                </div>
                <div class="filter-item">
                    <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Filter</button>
                </div>
                <div class="filter-item">
                    <a href="{{ route('finance.salary.index') }}" class="btn-reset" style="text-decoration:none; color:white;">
                        <i class="fas fa-undo-alt"></i> Reset
                    </a>
                </div>
                <div class="filter-item">
                    <button type="button" class="btn-print" onclick="window.print()">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
                <div class="filter-item">
                    <a href="{{ route('finance.salary.export', request()->query()) }}" class="btn-csv" style="text-decoration:none; color:white;">
                        <i class="fas fa-file-csv"></i> CSV
                    </a>
                </div>
            </div>
        </form>
    </div>
    <br>
    <!-- Grand Total -->
    @if($salaries->count() > 0)
    <div class="grand-total">
        <span>💰 Grand Total: <strong class="amount">₹{{ number_format($grandTotal->total_net ?? 0, 2) }}</strong></span>
        <span>🏦 Bank: <strong class="bank">₹{{ number_format($grandTotal->total_bank ?? 0, 2) }}</strong></span>
        <span>💵 Cash: <strong class="cash">₹{{ number_format($grandTotal->total_cash ?? 0, 2) }}</strong></span>
        <span>📋 Total Records: <strong>{{ $salaries->total() }}</strong></span>
    </div>
    @endif

    <!-- Table -->
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Teacher ID</th>
                    <th>Teacher Name</th>
                    <th>Month</th>
                    <th>Salary</th>
                    <th>Net Salary</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Payment Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salaries as $index => $salary)
                <tr>
                    <td>{{ $salaries->firstItem() + $index }}</td>
                    <td><strong>{{ $salary->teacher->teacher_id ?? 'N/A' }}</strong></td>
                    <td>{{ $salary->teacher->full_name ?? 'N/A' }}</td>
                    <td>{{ $salary->month_name }}</td>
                    <td>₹{{ number_format($salary->salary, 2) }}</td>
                    <td><strong>₹{{ number_format($salary->net_salary, 2) }}</strong></td>
                    <td>
                        <span class="badge-{{ $salary->salary_type }}">
                            {{ ucfirst($salary->salary_type) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-{{ $salary->status }}">
                            {{ ucfirst($salary->status) }}
                        </span>
                    </td>
                    <td>{{ $salary->payment_date->format('d-m-Y') }}</td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('finance.salary.edit', $salary->id) }}" class="btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteSalary({{ $salary->id }})" class="btn-delete" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center; padding:50px; color:#94a3b8;">
                        <i class="fas fa-money-bill-wave" style="font-size:3rem; display:block; margin-bottom:10px;"></i>
                        No salary records found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $salaries->appends(request()->query())->links() }}
    </div>
</div>

<script>
function deleteSalary(id) {
    Swal.fire({
        title: 'Delete Salary?',
        text: 'Are you sure you want to delete this salary record?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Delete'
    }).then(result => {
        if (result.isConfirmed) {
            fetch(`/finance/salary/${id}`, {
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