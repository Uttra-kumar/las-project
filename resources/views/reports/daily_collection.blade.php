@extends('layouts.app')

@section('title', 'Daily Collection Report')

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

    /* Filter Section */
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

    .filter-item input {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
    }

    .btn-filter, .btn-export, .btn-print {
        background: linear-gradient(135deg, #1e3c72, #2b4c7c);
        border: none;
        color: white;
        padding: 6px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-export {
        background: #10b981;
    }

    .btn-print {
        background: #f59e0b;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 10px;
        text-align: center;
        border: 1px solid #e2e8f0;
        border-top: 3px solid;
    }

    .stat-card.cash { border-top-color: #10b981; }
    .stat-card.upi { border-top-color: #3b82f6; }
    .stat-card.cheque { border-top-color: #f59e0b; }
    .stat-card.card { border-top-color: #8b5cf6; }
    .stat-card.total { border-top-color: #ef4444; }

    .stat-label {
        font-size: 0.65rem;
        color: #64748b;
        text-transform: uppercase;
    }

    .stat-amount {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e3c72;
        margin-top: 5px;
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
        min-width: 800px;
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

    .view-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: #1e3c72;
        font-size: 0.9rem;
    }

    .view-btn:hover {
        color: #f59e0b;
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

    .grand-total {
        background: #f8fafc;
        font-weight: 700;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .modal.show { display: flex; }
    .modal-content {
        background: white;
        border-radius: 12px;
        width: 95%;
        max-width: 800px;
        max-height: 80vh;
        overflow-y: auto;
        padding: 0;
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        position: sticky;
        top: 0;
    }
    .modal-body { padding: 16px; }
    .modal-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.7rem;
    }
    .modal-table th, .modal-table td {
        padding: 8px 10px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

   @media print {
    /* Hide unwanted elements */
    .sidebar, .top-header, .filter-card, .btn-print, .btn-export, .no-print, .pagination,
    .menu-toggle, .user-dropdown, .session-badge, .view-btn, .action-cell, .dropdown-menu,
    .stats-grid {
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
        margin-bottom: 8px !important;
        border-radius: 4px !important;
    }
    
    .report-header h2 {
        font-size: 12px !important;
        margin: 0 !important;
    }
    
    .session-badge {
        display: none !important;
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
        table-layout: fixed !important;
    }
    
    /* All cells with borders */
    .data-table th, .data-table td {
        padding: 2px 2px !important;
        border: 1px solid #000 !important;
        text-align: left !important;
        font-size: 13px !important;
        vertical-align: top !important;
        word-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
    }
    
    /* Column widths */
    .data-table th:nth-child(1) { width: 10%; }  /* Date */
    .data-table th:nth-child(2) { width: 8%; }   /* Transactions */
    .data-table th:nth-child(3) { width: 12%; }  /* Cash */
    .data-table th:nth-child(4) { width: 12%; }  /* UPI */
    .data-table th:nth-child(5) { width: 12%; }  /* Cheque */
    .data-table th:nth-child(6) { width: 12%; }  /* Card */
    .data-table th:nth-child(7) { width: 12%; }  /* Total */
    .data-table th:nth-child(8) { width: 22%; }  /* Action (will be hidden) */
    
    /* Hide action column in print */
    .data-table th:last-child, .data-table td:last-child {
        display: none !important;
    }
    
    .data-table th {
        background: #e0e0e0 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        font-weight: 700 !important;
    }
    
    /* Grand total row */
    .grand-total td {
        font-weight: 700 !important;
        background: #f0f0f0 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
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
        <h2><i class="fas fa-chart-line"></i> Daily Collection Report</h2>
        <div class="session-badge">
            <i class="fas fa-calendar-alt"></i> Session: <strong>{{ $currentSession->session_name ?? 'N/A' }}</strong>
        </div>
    </div>

    <!-- Filter Section -->
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
                <button class="btn-filter" onclick="applyFilter()">
                    <i class="fas fa-search"></i> Generate
                </button>
            </div>
            <div class="filter-item">
                <button onclick=" window.location.href = `{{ route("reports.daily") }}`;" class="btn-filter">
                    <i class="fas fa-search"></i>Reset
                </button>
            </div>
            <div class="filter-item">
                <button class="btn-export" onclick="exportCSV()">
                    <i class="fas fa-file-csv"></i> CSV
                </button>
            </div>
            <div class="filter-item">
                <button class="btn-print" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card cash">
            <div class="stat-label"><i class="fas fa-money-bill"></i> Cash</div>
            <div class="stat-amount">₹ {{ number_format($stats['cash'], 2) }}</div>
        </div>
        <div class="stat-card upi">
            <div class="stat-label"><i class="fas fa-mobile-alt"></i> UPI</div>
            <div class="stat-amount">₹ {{ number_format($stats['upi'], 2) }}</div>
        </div>
        <div class="stat-card cheque">
            <div class="stat-label"><i class="fas fa-money-check"></i> Cheque</div>
            <div class="stat-amount">₹ {{ number_format($stats['cheque'], 2) }}</div>
        </div>
        <div class="stat-card card">
            <div class="stat-label"><i class="fas fa-credit-card"></i> Card</div>
            <div class="stat-amount">₹ {{ number_format($stats['card'], 2) }}</div>
        </div>
        <div class="stat-card total">
            <div class="stat-label"><i class="fas fa-chart-line"></i> Grand Total</div>
            <div class="stat-amount">₹ {{ number_format($stats['total'], 2) }}</div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="table-wrapper">
        <table class="data-table" id="reportTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Transaction</th>
                    <th>Cash (₹)</th>
                    <th>UPI (₹)</th>
                    <th>Cheque (₹)</th>
                    <th>Card (₹)</th>
                    <th>Total (₹)</th>
                    <th width="50">Action</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @php
                    $totalCash = 0;
                    $totalUpi = 0;
                    $totalCheque = 0;
                    $totalCard = 0;
                    $overallTotal = 0;
                @endphp
                @foreach($groupedByDate as $date => $txns)
                @php
                    $dayCash = $txns->where('payment_mode', 'cash')->sum('paid_amount');
                    $dayUpi = $txns->where('payment_mode', 'upi')->sum('paid_amount');
                    $dayCheque = $txns->where('payment_mode', 'cheque')->sum('paid_amount');
                    $dayCard = $txns->where('payment_mode', 'card')->sum('paid_amount');
                    $dayTotal = $dayCash + $dayUpi + $dayCheque + $dayCard;
                    
                    $totalCash += $dayCash;
                    $totalUpi += $dayUpi;
                    $totalCheque += $dayCheque;
                    $totalCard += $dayCard;
                    $overallTotal += $dayTotal;
                @endphp
                <tr>
                    <td><strong>{{ date('d-m-Y', strtotime($date)) }}</strong></td>
                    <td>{{ $txns->count() }}</td>
                    <td>₹ {{ number_format($dayCash, 2) }}</td>
                    <td>₹ {{ number_format($dayUpi, 2) }}</td>
                    <td>₹ {{ number_format($dayCheque, 2) }}</td>
                    <td>₹ {{ number_format($dayCard, 2) }}</td>
                    <td><strong>₹ {{ number_format($dayTotal, 2) }}</strong></td>
                    <td>
                        <button class="view-btn" onclick="showDetails('{{ $date }}')" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="grand-total">
                    <td><strong>Grand Total</strong></td>
                    <td><strong>{{ $transactions->count() }}</strong></td>
                    <td><strong>₹ {{ number_format($totalCash, 2) }}</strong></td>
                    <td><strong>₹ {{ number_format($totalUpi, 2) }}</strong></td>
                    <td><strong>₹ {{ number_format($totalCheque, 2) }}</strong></td>
                    <td><strong>₹ {{ number_format($totalCard, 2) }}</strong></td>
                    <td><strong>₹ {{ number_format($overallTotal, 2) }}</strong></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Modal for Date-wise Details -->
<div class="modal" id="detailsModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Transaction Details</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div style="overflow-x: auto;">
                <table class="modal-table">
                    <thead>
                        <tr>
                            <th>Receipt No</th>
                            <th>Student Name</th>
                            <th>Fee Type</th>
                            <th>Amount</th>
                            <th>Discount</th>
                            <th>Paid</th>
                            <th>Mode</th>
                        </tr>
                    </thead>
                    <tbody id="modalTableBody">
                        <tr><td colspan="7" style="text-align:center">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function applyFilter() {
        let fromDate = document.getElementById('fromDate').value;
        let toDate = document.getElementById('toDate').value;
        window.location.href = `{{ route("reports.daily") }}?from_date=${fromDate}&to_date=${toDate}`;
    }

    function exportCSV() {
        let fromDate = document.getElementById('fromDate').value;
        let toDate = document.getElementById('toDate').value;
        window.location.href = `{{ route("reports.export.csv") }}?from_date=${fromDate}&to_date=${toDate}`;
    }

    function showDetails(date) {
        document.getElementById('modalTitle').innerHTML = `Transaction Details - ${date}`;
        document.getElementById('modalTableBody').innerHTML = '<tr><td colspan="7" style="text-align:center">Loading...</td></tr>';
        document.getElementById('detailsModal').classList.add('show');
        
        fetch(`{{ route("reports.date.transactions") }}?date=${date}`)
            .then(response => response.json())
            .then(data => {
                let html = '';
                data.forEach(t => {
                    html += `
                        <tr>
                            <td>${t.receipt_no}</td>
                            <td>${t.student ? t.student.full_name : 'N/A'}</td>
                            <td>${t.fees_type ? t.fees_type.name : 'N/A'}</td>
                            <td>₹ ${parseFloat(t.amount).toLocaleString()}</td>
                            <td>₹ ${parseFloat(t.discount).toLocaleString()}</td>
                            <td><strong>₹ ${parseFloat(t.paid_amount).toLocaleString()}</strong></td>
                            <td><span class="badge-mode badge-${t.payment_mode}">${t.payment_mode.toUpperCase()}</span></td>
                        </tr>
                    `;
                });
                if (data.length === 0) {
                    html = '<tr><td colspan="7" style="text-align:center">No transactions found</td></tr>';
                }
                document.getElementById('modalTableBody').innerHTML = html;
            })
            .catch(error => {
                document.getElementById('modalTableBody').innerHTML = '<tr><td colspan="7" style="text-align:center">Error loading data</td></tr>';
            });
    }

    function closeModal() {
        document.getElementById('detailsModal').classList.remove('show');
    }

    // Close modal on outside click
    window.onclick = function(event) {
        let modal = document.getElementById('detailsModal');
        if (event.target === modal) {
            closeModal();
        }
    }
</script>
@endsection