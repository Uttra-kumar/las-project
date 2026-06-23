@extends('layouts.app')

@section('title', 'Edit Logs')

@section('content')
<style>
    .logs-container {
        animation: fadeIn 0.3s ease;
        max-width: 1400px;
        margin: 0 auto;
    }

    .logs-header {
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

    .logs-header h2 {
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
        min-width: 140px;
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

    .btn-filter, .btn-reset {
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

    .btn-reset {
        background: #e2e8f0;
        color: #475569;
    }

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

    .badge-edited {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
        background: #dbeafe;
        color: #1e40af;
    }

    .receipt-badge {
        font-family: arial;
        background: #f1f5f9;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .view-details {
        background: none;
        border: none;
        color: #1e3c72;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 4px;
    }
    .view-details:hover {
        background: #e2e8f0;
    }

    /* Modal - Side by Side */
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
        max-width: 850px;
        max-height: 85vh;
        overflow-y: auto;
        padding: 0;
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 16px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        position: sticky;
        top: 0;
    }
    .modal-header h3 { margin: 0; font-size: 0.85rem; }
    .modal-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
    }
    .modal-body { padding: 16px; }

    /* Side by Side Comparison - Compact */
    .compare-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: center;
    }
    .compare-card {
        flex: 1;
        min-width: 240px;
        max-width: 380px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    .compare-header {
        padding: 6px 10px;
        font-size: 0.7rem;
        font-weight: 600;
        color: white;
        text-align: center;
    }
    .compare-header.old { background: #dc2626; }
    .compare-header.new { background: #10b981; }
    .compare-body {
        padding: 8px 10px;
        background: #fafcff;
    }
    .compare-row {
        display: flex;
        padding: 5px 0;
        border-bottom: 1px solid #eef2f8;
    }
    .compare-row:last-child {
        border-bottom: none;
    }
    .compare-label {
        font-weight: 600;
        width: 90px;
        color: #64748b;
        font-size: 0.65rem;
    }
    .compare-value {
        flex: 1;
        color: #1e293b;
        font-size: 0.65rem;
        word-break: break-word;
        text-align: right;
    }
    .highlight {
        background: #dbeafe;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: 600;
        display: inline-block;
    }
    .footer-info {
        background: #f8fafc;
        padding: 8px 12px;
        border-radius: 6px;
        margin-top: 12px;
        font-size: 0.6rem;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: space-between;
    }

    @media (max-width: 640px) {
        .compare-wrapper {
            flex-direction: column;
            align-items: center;
        }
        .compare-card {
            max-width: 100%;
            width: 100%;
        }
        .compare-value {
            text-align: left;
        }
        .footer-info {
            flex-direction: column;
            gap: 5px;
        }
    }
</style>

<div class="logs-container">
    <div class="logs-header">
        <h2>
            <i class="fas fa-edit"></i> Edit Logs
        </h2>
        <div class="session-badge">
            <i class="fas fa-calendar-alt"></i> Session: <strong>{{ $currentSession->session_name ?? 'No Active Session' }}</strong>
        </div>
    </div>

    <div class="filter-section">
        <div class="filter-row">
            <div class="filter-group">
                <label><i class="fas fa-calendar"></i> From Date</label>
                <input type="date" id="from_date" value="{{ request('from_date') }}">
            </div>
            <div class="filter-group">
                <label><i class="fas fa-calendar"></i> To Date</label>
                <input type="date" id="to_date" value="{{ request('to_date') }}">
            </div>
            <div class="filter-group">
                <button class="btn-filter" onclick="applyFilter()">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
            <div class="filter-group">
                <button class="btn-reset" onclick="resetFilter()">
                    <i class="fas fa-undo-alt"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Student Name</th>
                    <th>Receipt No</th>
                    <th>Fee Type</th>
                    <th>Edited By</th>
                    <th width="50">View</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                    <td>
                        <i class="fas fa-user-graduate"></i>
                        <strong>{{ $log->student->full_name ?? 'N/A' }}</strong>
                    </td>
                    <td>
                        @php
                            $receiptNo = $log->new_data['receipt_no'] ?? $log->old_data['receipt_no'] ?? '-';
                        @endphp
                        <span class="receipt-badge">{{ $receiptNo }}</span>
                    </td>
                    <td>{{ $log->feesType->name ?? 'N/A' }}</td>
                    <td>
                        <i class="fas fa-user"></i>
                        {{ $log->editor->name ?? 'N/A' }}
                    </td>
                    <td>
                        <button class="view-details" onclick="viewDetails({{ $log->id }})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:40px;">No edit logs found</td>
                </tr>
                @endforelse
            </tbody>
        <table>
    </div>

    @if($logs->hasPages())
    <div class="pagination">
        {{ $logs->links() }}
    </div>
    @endif
</div>

<!-- Modal -->
<div class="modal" id="detailsModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-code-branch"></i> Data Comparison (Old vs New)</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body" id="modalBody">
            Loading...
        </div>
    </div>
</div>

<script>
    function applyFilter() {
        let fromDate = document.getElementById('from_date').value;
        let toDate = document.getElementById('to_date').value;
        let url = '{{ route("edit.logs") }}?';
        if(fromDate) url += `from_date=${fromDate}&`;
        if(toDate) url += `to_date=${toDate}&`;
        window.location.href = url;
    }

    function resetFilter() {
        window.location.href = '{{ route("edit.logs") }}';
    }

   
    function viewDetails(id) {
    document.getElementById('modalBody').innerHTML = '<div style="text-align:center; padding:20px;">Loading...</div>';
    document.getElementById('detailsModal').classList.add('show');
    
    fetch(`/admin/edit-log-details/${id}`)
        .then(response => response.json())
        .then(data => {
            let oldData = data.old_data || {};
            let newData = data.new_data || {};
            
            // Fields to display in order
            let fields = [
                { key: 'payment_date', label: 'Payment Date', type: 'date' },
                { key: 'amount', label: 'Amount', type: 'amount' },
                { key: 'discount', label: 'Discount', type: 'amount' },
                { key: 'fine', label: 'Fine', type: 'amount' },
                { key: 'paid_amount', label: 'Paid Amount', type: 'amount' },
                { key: 'payment_mode', label: 'Mode', type: 'text' },
                { key: 'remarks', label: 'Remarks', type: 'text' }
            ];
            
            let studentName = data.student?.full_name || 'N/A';
            let feeTypeName = data.fees_type?.name || 'N/A';
            let editorName = data.editor?.name || 'N/A';
            let editedAt = new Date(data.created_at).toLocaleString();
            let receiptNo = newData.receipt_no || oldData.receipt_no || 'N/A';
            
            let html = `
                <div class="compare-wrapper">
                    <!-- Old Data Card -->
                    <div class="compare-card">
                        <div class="compare-header old">
                            <i class="fas fa-history"></i> Before Change
                        </div>
                        <div class="compare-body">
                            <div class="compare-row">
                                <span class="compare-label">Student:</span>
                                <span class="compare-value">${studentName}</span>
                            </div>
                            <div class="compare-row">
                                <span class="compare-label">Receipt:</span>
                                <span class="compare-value">${receiptNo}</span>
                            </div>
                            <div class="compare-row">
                                <span class="compare-label">Fee Type:</span>
                                <span class="compare-value">${feeTypeName}</span>
                            </div>
            `;
            
            for(let field of fields) {
                let oldVal = oldData[field.key];
                if(oldVal === undefined || oldVal === null) oldVal = '-';
                
                if(field.type === 'date' && oldVal !== '-') {
                    oldVal = new Date(oldVal).toLocaleDateString();
                }
                if(field.type === 'amount' && oldVal !== '-') {
                    oldVal = '₹ ' + parseFloat(oldVal).toLocaleString();
                }
                if(field.key === 'payment_mode' && oldVal !== '-') {
                    oldVal = String(oldVal).toUpperCase();
                }
                
                html += `
                    <div class="compare-row">
                        <span class="compare-label">${field.label}:</span>
                        <span class="compare-value">${oldVal}</span>
                    </div>
                `;
            }
            
            html += `
                        </div>
                    </div>
                    
                    <!-- New Data Card -->
                    <div class="compare-card">
                        <div class="compare-header new">
                            <i class="fas fa-check-circle"></i> After Change
                        </div>
                        <div class="compare-body">
                            <div class="compare-row">
                                <span class="compare-label">Student:</span>
                                <span class="compare-value">${studentName}</span>
                            </div>
                            <div class="compare-row">
                                <span class="compare-label">Receipt:</span>
                                <span class="compare-value">${receiptNo}</span>
                            </div>
                            <div class="compare-row">
                                <span class="compare-label">Fee Type:</span>
                                <span class="compare-value">${feeTypeName}</span>
                            </div>
            `;
            
            for(let field of fields) {
                let newVal = newData[field.key];
                if(newVal === undefined || newVal === null) newVal = '-';
                
                if(field.type === 'date' && newVal !== '-') {
                    newVal = new Date(newVal).toLocaleDateString();
                }
                if(field.type === 'amount' && newVal !== '-') {
                    newVal = '₹ ' + parseFloat(newVal).toLocaleString();
                }
                if(field.key === 'payment_mode' && newVal !== '-') {
                    newVal = String(newVal).toUpperCase();
                }
                
                let oldVal = oldData[field.key];
                let isChanged = (oldVal !== undefined && String(newVal) !== String(oldVal) && oldVal !== '-');
                let highlightClass = isChanged ? 'highlight' : '';
                
                html += `
                    <div class="compare-row">
                        <span class="compare-label">${field.label}:</span>
                        <span class="compare-value ${highlightClass}">${newVal}</span>
                    </div>
                `;
            }
            
            html += `
                        </div>
                    </div>
                </div>
                
                <div class="footer-info">
                    <span><i class="fas fa-user-edit"></i> <strong>By:</strong> ${editorName}</span>
                    <span><i class="fas fa-clock"></i> <strong>At:</strong> ${editedAt}</span>
                    <span><i class="fas fa-comment"></i> <strong>Remarks:</strong> ${data.remarks || '-'}</span>
                </div>
            `;
            
            document.getElementById('modalBody').innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('modalBody').innerHTML = '<div style="text-align:center; padding:20px; color:red;">Error loading details</div>';
        });
}

    function closeModal() {
        document.getElementById('detailsModal').classList.remove('show');
    }

    document.getElementById('from_date').addEventListener('change', applyFilter);
    document.getElementById('to_date').addEventListener('change', applyFilter);
</script>
@endsection