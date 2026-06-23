@extends('layouts.app')

@section('title', 'Transaction Details Report')

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
        min-width: 1000px;
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

    .badge-mode {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    .badge-cash { background: #dcfce7; color: #15803d; }
    .badge-upi { background: #dbeafe; color: #1e40af; }
    .badge-cheque { background: #fed7aa; color: #9a3412; }
    .badge-card { background: #e0e7ff; color: #4338ca; }
    .badge-online { background: #fae8ff; color: #9333ea; }

    .no-data {
        text-align: center;
        padding: 40px;
        color: #94a3b8;
    }

    @media print {
    /* Hide unwanted elements */
    .sidebar, .top-header, .filter-card, .btn-print, .btn-csv, .stats-summary .btn-print,
    .menu-toggle, .user-dropdown, .session-badge, .no-print, .pagination,
    .action-cell, .dropdown-menu, .btn-reset, .btn-filter {
        display: none !important;
    }
    
    /* Main content full width */
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
        max-width: 100% !important;
    }
    
    /* Header */
    .report-header {
        background: #1e3c72 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        padding: 6px 10px !important;
        margin-bottom: 10px !important;
        border-radius: 4px !important;
    }
    
    .report-header h2 {
        font-size: 12px !important;
        margin: 0 !important;
    }
    
    /* Stats Summary */
    .stats-summary {
        padding: 6px 10px !important;
        margin-bottom: 10px !important;
        border: 1px solid #ddd !important;
        break-inside: avoid !important;
    }
    
    .stats-info {
        gap: 15px !important;
    }
    
    .stats-info span {
        font-size: 9px !important;
    }
    
    .stats-info strong {
        font-size: 10px !important;
    }
    
    /* Table - Full width with borders */
    .table-wrapper {
        overflow-x: visible !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        width: 100% !important;
    }
    
    .data-table {
        font-size: 8px !important;
        width: 100% !important;
        border-collapse: collapse !important;
        table-layout: auto !important;
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
    
    /* Make sure all columns are visible */
    .data-table th:nth-child(1) { width: 5%; }   /* # */
    .data-table th:nth-child(2) { width: 8%; }   /* Date */
    .data-table th:nth-child(3) { width: 10%; }  /* Receipt No */
    .data-table th:nth-child(4) { width: 8%; }   /* Class */
    .data-table th:nth-child(5) { width: 12%; }  /* Student Name */
    .data-table th:nth-child(6) { width: 10%; }  /* Father Name */
    .data-table th:nth-child(7) { width: 8%; }   /* Fee Type */
    .data-table th:nth-child(8) { width: 8%; }   /* Amount */
    .data-table th:nth-child(9) { width: 8%; }   /* Discount */
    .data-table th:nth-child(10) { width: 6%; }  /* Fine */
    .data-table th:nth-child(11) { width: 8%; }  /* Paid */
    .data-table th:nth-child(12) { width: 6%; }  /* Mode */
    .data-table th:nth-child(13) { width: 8%; }  /* User */
    
    /* Force everything in one page */
    body, .report-container, .main-content, .page-content {
        height: auto !important;
        overflow: visible !important;
    }
    
    /* Avoid page breaks */
    .table-wrapper, .report-header, .stats-summary {
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
        <h2><i class="fas fa-list-ul"></i> Transaction Details Report</h2>
        <div class="session-badge">
            <i class="fas fa-calendar-alt"></i> Session: <strong>{{ $currentSession->session_name ?? 'N/A' }}</strong>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="filter-card">
        <div class="filter-group">
            <div class="filter-item">
                <label><i class="fas fa-calendar"></i> From Date</label>
                <input type="date" id="fromDate" value="{{ $fromDate }}">
            </div>
            <div class="filter-item">
                <label><i class="fas fa-calendar"></i> To Date</label>
                <input type="date" id="toDate" value="{{ $toDate }}">
            </div>
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
                <label><i class="fas fa-credit-card"></i> Mode</label>
                <select id="mode">
                    <option value="">All Modes</option>
                    <option value="cash" {{ $selectedMode == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="upi" {{ $selectedMode == 'upi' ? 'selected' : '' }}>UPI</option>
                    <option value="cheque" {{ $selectedMode == 'cheque' ? 'selected' : '' }}>Cheque</option>
                    <option value="card" {{ $selectedMode == 'card' ? 'selected' : '' }}>Card</option>
                    <option value="online" {{ $selectedMode == 'online' ? 'selected' : '' }}>Online</option>
                </select>
            </div>
            <div class="filter-item">
                <label><i class="fas fa-user"></i> User</label>
                <select id="user_id">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $selectedUser == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
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
        <table class="data-table" id="transactionTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Receipt No</th>
                    <th>Class</th>
                    <th>Student Name</th>
                    <th>Father Name</th>
                    <th>Fee Type</th>
                    <th>Amount</th>
                    <th>Discount</th>
                    <th>Paid</th>
                    <th>Mode</th>
                    <th>User</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @foreach($transactions as $index => $t)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ date('d-m-Y', strtotime($t->payment_date)) }}</td>
                    <td><strong>{{ $t->receipt_no }}</strong></td>
                    <td>{{ $t->class->class_name ?? 'N/A' }}</td>
                    <td>{{ $t->student->full_name ?? 'N/A' }}</td>
                    <td>{{ $t->student->father_name ?? '-' }}</td>
                    <td>{{ $t->feesType->name ?? 'N/A' }}</td>
                    <td>₹ {{ number_format($t->amount, 2) }}</td>
                    <td>₹ {{ number_format($t->discount, 2) }}</td>
                    <td><strong>₹ {{ number_format($t->paid_amount, 2) }}</strong></td>
                    <td><span class="badge-mode badge-{{ $t->payment_mode }}">{{ strtoupper($t->payment_mode) }}</span></td>
                    <td>{{ $t->creator->name ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($transactions->isEmpty())
        <div class="no-data">No transactions found</div>
        @endif
    </div>
</div>

<script>
    function applyFilter() {
        let fromDate = document.getElementById('fromDate').value;
        let toDate = document.getElementById('toDate').value;
        let classId = document.getElementById('class_id').value;
        let mode = document.getElementById('mode').value;
        let userId = document.getElementById('user_id').value;
        
        let url = '{{ route("reports.transactions") }}?';
        if(fromDate) url += `from_date=${fromDate}&`;
        if(toDate) url += `to_date=${toDate}&`;
        if(classId) url += `class_id=${classId}&`;
        if(mode) url += `mode=${mode}&`;
        if(userId) url += `user_id=${userId}&`;
        
        window.location.href = url;
    }

    function resetFilter() {
        window.location.href = '{{ route("reports.transactions") }}';
    }

    function exportCSV() {
        let fromDate = document.getElementById('fromDate').value;
        let toDate = document.getElementById('toDate').value;
        let classId = document.getElementById('class_id').value;
        let mode = document.getElementById('mode').value;
        let userId = document.getElementById('user_id').value;
        
        let url = '{{ route("reports.transactions.export") }}?';
        if(fromDate) url += `from_date=${fromDate}&`;
        if(toDate) url += `to_date=${toDate}&`;
        if(classId) url += `class_id=${classId}&`;
        if(mode) url += `mode=${mode}&`;
        if(userId) url += `user_id=${userId}&`;
        
        window.location.href = url;
    }
</script>
@endsection