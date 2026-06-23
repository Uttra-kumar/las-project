@extends('layouts.app')

@section('title', 'Delete Logs')

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
    .btn-restore {
    background: #10b981;
    color: white;
    border: none;
    padding: 4px 10px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.6rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.2s;
}
.btn-restore:hover {
    background: #059669;
    transform: translateY(-1px);
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
        min-width: 1000px;
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

    .badge-deleted {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
        background: #fee2e2;
        color: #dc2626;
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
        max-width: 600px;
        max-height: 85vh;
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
    }
    .modal-header h3 { margin: 0; font-size: 0.9rem; }
    .modal-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
    }
    .modal-body { padding: 16px; }
    
    /* Two Column Layout for Modal */
    .compare-section {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 15px;
    }
    .compare-box {
        flex: 1;
        min-width: 250px;
        background: #f8fafc;
        border-radius: 10px;
        overflow: hidden;
    }
    .compare-title {
        background: #1e3c72;
        color: white;
        padding: 8px 12px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .compare-content {
        padding: 10px;
        font-size: 0.7rem;
    }
    .compare-row {
        display: flex;
        padding: 5px 0;
        border-bottom: 1px solid #e2e8f0;
    }
    .compare-label {
        font-weight: 600;
        width: 100px;
        color: #64748b;
    }
    .compare-value {
        flex: 1;
        color: #1e293b;
    }

    .pagination {
        margin-top: 15px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .filter-row { flex-direction: column; }
        .btn-filter, .btn-reset { width: 100%; justify-content: center; }
        .compare-section { flex-direction: column; }
    }
</style>

<div class="logs-container">
    <div class="logs-header">
        <h2><i class="fas fa-trash-alt"></i> Delete Logs</h2>
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
                    <th>Deleted At</th>
                    <th>Receipt No</th>
                    <th>Student Name</th>
                    <th>Fee Type</th>
                    <th>Amount</th>
                    <th>Paid</th>
                    <th>Deleted By</th>
                    <th>Reason</th>
                    <th width="100">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                    <td><span class="receipt-badge">{{ $log->receipt_no ?? '-' }}</span></td>
                    <td>
                        <i class="fas fa-user-graduate"></i>
                        <strong>{{ $log->student->full_name ?? 'N/A' }}</strong>
                    </td>
                    <td>{{ $log->feesType->name ?? 'N/A' }}</td>
                    <td>₹ {{ number_format($log->amount ?? 0, 2) }}</td>
                    <td>₹ {{ number_format($log->paid_amount ?? 0, 2) }}</td>
                    <td>
                        <i class="fas fa-user"></i>
                        {{ $log->deleter->name ?? 'N/A' }}
                    </td>
                    <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ Str::limit($log->delete_reason, 30) }}
                    </td>
                    <td>
                        <button class="view-details" onclick="viewDetails({{ $log->id }})">
                            <i class="fas fa-eye"></i>
                        </button>
                          <button class="btn-restore" onclick="restorePayment({{ $log->id }})" title="Restore">
                            <i class="fas fa-undo-alt"></i> 
                            </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding:40px;">No delete logs found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
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
            <h3><i class="fas fa-info-circle"></i> Deleted Record Details</h3>
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
        let url = '{{ route("delete.logs") }}?';
        if(fromDate) url += `from_date=${fromDate}&`;
        if(toDate) url += `to_date=${toDate}&`;
        window.location.href = url;
    }

    function resetFilter() {
        window.location.href = '{{ route("delete.logs") }}';
    }

    function viewDetails(id) {
        document.getElementById('modalBody').innerHTML = '<div style="text-align:center; padding:20px;">Loading...</div>';
        document.getElementById('detailsModal').classList.add('show');
        
        fetch(`/admin/delete-log-details/${id}`)
            .then(response => response.json())
            .then(data => {
                let html = `
                    <div class="compare-section">
                        <!-- Record Details -->
                        <div class="compare-box">
                            <div class="compare-title">
                                <i class="fas fa-receipt"></i> Payment Details
                            </div>
                            <div class="compare-content">
                                <div class="compare-row">
                                    <span class="compare-label">Receipt No:</span>
                                    <span class="compare-value"><strong>${data.receipt_no || '-'}</strong></span>
                                </div>
                                <div class="compare-row">
                                    <span class="compare-label">Student:</span>
                                    <span class="compare-value">${data.student?.full_name || 'N/A'}</span>
                                </div>
                                <div class="compare-row">
                                    <span class="compare-label">Class:</span>
                                    <span class="compare-value">${data.class?.class_name || 'N/A'}</span>
                                </div>
                                <div class="compare-row">
                                    <span class="compare-label">Fee Type:</span>
                                    <span class="compare-value">${data.fees_type?.name || 'N/A'}</span>
                                </div>
                                <div class="compare-row">
                                    <span class="compare-label">Payment Date:</span>
                                    <span class="compare-value">${data.payment_date ? new Date(data.payment_date).toLocaleDateString() : '-'}</span>
                                </div>
                                <div class="compare-row">
                                    <span class="compare-label">Amount:</span>
                                    <span class="compare-value">₹ ${(data.amount || 0).toLocaleString()}</span>
                                </div>
                                <div class="compare-row">
                                    <span class="compare-label">Discount:</span>
                                    <span class="compare-value">₹ ${(data.discount || 0).toLocaleString()}</span>
                                </div>
                                <div class="compare-row">
                                    <span class="compare-label">Fine:</span>
                                    <span class="compare-value">₹ ${(data.fine || 0).toLocaleString()}</span>
                                </div>
                                <div class="compare-row">
                                    <span class="compare-label">Paid Amount:</span>
                                    <span class="compare-value"><strong>₹ ${(data.paid_amount || 0).toLocaleString()}</strong></span>
                                </div>
                                <div class="compare-row">
                                    <span class="compare-label">Payment Mode:</span>
                                    <span class="compare-value">${data.payment_mode ? data.payment_mode.toUpperCase() : '-'}</span>
                                </div>
                                <div class="compare-row">
                                    <span class="compare-label">Remarks:</span>
                                    <span class="compare-value">${data.remarks || '-'}</span>
                                </div>
                                <div class="compare-row">
                                    <span class="compare-label">Status:</span>
                                    <span class="compare-value">${data.status || '-'}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Deletion Info -->
                        <div class="compare-box">
                            <div class="compare-title" style="background: #dc2626;">
                                <i class="fas fa-trash-alt"></i> Deletion Info
                            </div>
                            <div class="compare-content">
                                <div class="compare-row">
                                    <span class="compare-label">Created By:</span>
                                    <span class="compare-value">${data.creator?.name || 'N/A'}</span>
                                </div>
                                <div class="compare-row">
                                    <span class="compare-label">Deleted By:</span>
                                    <span class="compare-value">${data.deleter?.name || 'N/A'}</span>
                                </div>
                                <div class="compare-row">
                                    <span class="compare-label">Deleted At:</span>
                                    <span class="compare-value">${new Date(data.created_at).toLocaleString()}</span>
                                </div>
                                <div class="compare-row">
                                    <span class="compare-label">Delete Reason:</span>
                                    <span class="compare-value">${data.delete_reason || '-'}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                document.getElementById('modalBody').innerHTML = html;
            })
            .catch(error => {
                document.getElementById('modalBody').innerHTML = '<div style="text-align:center; padding:20px; color:red;">Error loading details</div>';
            });
    }

    function closeModal() {
        document.getElementById('detailsModal').classList.remove('show');
    }
    function restorePayment(id) {
    Swal.fire({
        title: 'Restore Payment?',
        text: 'This will restore the payment record. The deleted log will be removed.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#dc2626',
        confirmButtonText: 'Yes, Restore!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Restoring...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch(`/admin/restore-payment/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Restored!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong!'
                });
            });
        }
    });
}
    document.getElementById('from_date').addEventListener('change', applyFilter);
    document.getElementById('to_date').addEventListener('change', applyFilter);
</script>
@endsection