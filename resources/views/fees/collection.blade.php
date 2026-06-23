@extends('layouts.app')

@section('title', 'Fees Collection')

@section('content')
<style>
    .fees-container { animation: fadeIn 0.3s ease; max-width: 1400px; margin: 0 auto; }

    /* Student Card */
    .student-card {
        background: white;
        border-radius: 10px;
        margin-bottom: 15px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .student-card-header {
        background: linear-gradient(135deg, #1e3c72, #2b4c7c);
        color: white;
        padding: 8px 15px;
    }
    .student-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 6px;
        padding: 10px 15px;
    }
    .info-item { display: flex; gap: 8px; font-size: 0.7rem; }
    .info-label { font-weight: 600; color: #64748b; min-width: 85px;font-size:12px; }
    .info-value { color: #1e293b; font-weight: 500; font-size: 13px;}

    /* Fee Cards Grid - Compact */
    .fee-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 12px;
        margin-bottom: 15px;
    }
    .fee-card {
        background: white;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .card-header {
        background: linear-gradient(135deg, #1e3c72, #2b4c7c);
        color: white;
        padding: 6px 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-header h3 { font-size: 0.75rem; margin: 0; font-weight: 600; display: flex; align-items: center; gap: 5px; }
    .card-header span { font-size: 0.8rem; font-weight: bold; }
    .card-body { padding: 8px 12px; }
    .amount-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 4px;
        padding-bottom: 3px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    .amount-label { color: #64748b; }
    .amount-value { font-weight: 700; }
    .text-success { color: #10b981; }
    .text-warning { color: #f59e0b; }
    .text-danger { color: #ef4444; }
    .text-primary { color: #1e3c72; }
    .card-actions { display: flex; gap: 6px; margin-top: 8px; padding-top: 6px; border-top: 1px solid #eef2f8; }
    .btn-add, .btn-print {
        flex: 1;
        padding: 4px;
        border-radius: 6px;
        border: none;
        font-size: 0.6rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        font-weight: 600;
    }
    .btn-add { background: #dcfce7; color: #15803d; }
    .btn-print { background: #dbeafe; color: #1e40af; }

    /* Global Table */
    .global-table-section {
        background: white;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .global-header {
        padding: 8px 15px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .global-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.7rem;
    }
    .global-table th {
        padding: 8px 12px;
        text-align: left;
        background: #f8fafc;
        font-weight: 600;
        border-bottom: 1px solid #e2e8f0;
    }
    .global-table td { padding: 6px 12px; border-bottom: 1px solid #ecf3f9; }
    .badge-mode { padding: 2px 8px; border-radius: 20px; font-size: 0.6rem; }
    .badge-cash { background: #dcfce7; color: #15803d; }
    .badge-upi { background: #dbeafe; color: #1e40af; }
    .badge-cheque { background: #fed7aa; color: #9a3412; }

    /* Summary Footer */
    .summary-footer {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 15px;
        padding: 8px 15px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }
    .total-box {
        background: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
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
        width: 90%;
        max-width: 500px;
        padding: 15px;
        font-family: Arial;
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 8px;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 12px;
    }
    .form-group { margin-bottom: 10px; }
    .form-group label { display: block; font-size: 0.65rem; font-weight: 600; margin-bottom: 3px; }
    .form-group input, .form-group select {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
        font-family: Arial;
        font-weight: 700;
    }
    .modal-row { display: flex; gap: 10px; }
    .modal-footer { display: flex; justify-content: flex-end; gap: 8px; margin-top: 12px; }
    .btn-save { background: #10b981; color: white; border: none; padding: 6px 20px; border-radius: 6px; cursor: pointer; }
    .btn-cancel { background: #e2e8f0; border: none; padding: 6px 20px; border-radius: 6px; cursor: pointer; }

    .action-btns { display: flex; gap: 6px; }
    .action-btn { background: none; border: none; cursor: pointer; padding: 2px 5px; border-radius: 4px; }
    .action-btn:hover { background: #e2e8f0; }
</style>

<div class="fees-container">
    @if($student)
    <!-- Student Info -->
    <div class="student-card">
        <div class="student-card-header">
            <h4 style="margin:0;"><i class="fas fa-user-graduate"></i> Student Details</h4>
        </div>
        <div class="student-info-grid">
            <div class="info-item"><span class="info-label">Student Name:</span><span class="info-value">{{ $student->full_name }}</span></div>
            <!-- <div class="info-item"><span class="info-label">Student ID:</span><span class="info-value">{{ $student->student_id }}</span></div> -->
            <div class="info-item"><span class="info-label">Father Name:</span><span class="info-value">{{ $student->father_name ?? '-' }}</span></div>
            <div class="info-item"><span class="info-label">Class:</span><span class="info-value">{{ $student->class->class_name ?? 'N/A' }}</span></div>
            <div class="info-item"><span class="info-label">Mobile:</span><span class="info-value">{{ $student->mobile }}</span></div>
            <div class="info-item"><span class="info-label">Session:</span><span class="info-value">{{ $currentSession->session_name ?? 'N/A' }}</span></div>
        </div>
    </div>

    <!-- Fee Cards -->
    <div class="fee-cards-grid">
        @foreach($feeCards as $card)
        <div class="fee-card">
            <div class="card-header">
                <h3><i class="fas {{ $card['icon'] }}"></i> {{ $card['name'] }}</h3>
                <span>₹{{ number_format($card['total_amount'], 2) }}</span>
            </div>
            <div class="card-body">
                <div class="amount-row"><span class="amount-label">Total Paid:</span><span class="amount-value text-success">₹{{ number_format($card['total_paid'], 2) }}</span></div>
                <div class="amount-row"><span class="amount-label">Discount:</span><span class="amount-value text-warning">-₹{{ number_format($card['total_discount'], 2) }}</span></div>
                <div class="amount-row"><span class="amount-label">Fine:</span><span class="amount-value text-danger">+₹{{ number_format($card['total_fine'], 2) }}</span></div>
                <div class="amount-row"><span class="amount-label">Remaining:</span><span class="amount-value text-warning">₹{{ number_format($card['remaining'], 2) }}</span></div>
                <div class="card-actions">
                    <button class="btn-add" onclick="openCollectModal({{ $card['id'] }}, '{{ $card['name'] }}', {{ $card['total_amount'] }}, {{ $card['remaining'] }})">
                        <i class="fas fa-plus-circle"></i> Collect
                    </button>
                    <button class="btn-print" onclick="printStatement({{ $student->id }})">
                        <i class="fas fa-print"></i> Statement
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- All Transactions -->
    <div class="global-table-section">
        <div class="global-header">
            <i class="fas fa-list"></i> All Transactions
        </div>
        <div style="overflow-x: auto;">
            <table class="global-table">
                <thead>
                    <tr><th>Date</th><th>Receipt No</th><th>Fee Type</th><th>Amount</th><th>Discount</th><th>Fine</th><th>Paid</th><th>Mode</th><th>Action</th></tr>
                </thead>
                <tbody>
                    @php
                        $allTransactions = [];
                        foreach($feeCards as $card) {
                            foreach($card['transactions'] as $trans) {
                                $allTransactions[] = [
                                    'date' => $trans->payment_date,
                                    'receipt_no' => $trans->receipt_no,
                                    'fee_name' => $card['name'],
                                    'amount' => $trans->amount,
                                    'discount' => $trans->discount,
                                    'fine' => $trans->fine,
                                    'paid' => $trans->paid_amount,
                                    'mode' => $trans->payment_mode,
                                    'id' => $trans->id
                                ];
                            }
                        }
                        $allTransactions = collect($allTransactions)->sortByDesc('date');
                    @endphp
                    @forelse($allTransactions as $trans)
                    <tr>
                        <td>{{ date('d-m-Y', strtotime($trans['date'])) }}</td>
                        <td><strong>{{ $trans['receipt_no'] }}</strong></td>
                        <td>{{ $trans['fee_name'] }}</td>
                        <td>₹{{ number_format($trans['amount'], 2) }}</td>
                        <td class="text-warning">-₹{{ number_format($trans['discount'], 2) }}</td>
                        <td class="text-danger">+₹{{ number_format($trans['fine'], 2) }}</td>
                        <td><strong>₹{{ number_format($trans['paid'], 2) }}</strong></td>
                        <td><span class="badge-mode badge-{{ $trans['mode'] }}">{{ ucfirst($trans['mode']) }}</span></td>
                        <td class="action-btns">
                            <button class="action-btn" onclick="printReceipt({{ $trans['id'] }})" title="Print Receipt">
                                <i class="fas fa-print"></i>
                            </button>
                            @if(Auth::user()->role=='admin')
                            <button class="action-btn" onclick="editTransaction({{ $trans['id'] }})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn" onclick="deleteTransaction({{ $trans['id'] }})" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" style="text-align:center; padding:40px;">No transactions yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Summary -->
    <div class="summary-footer">
        @php
            $totalAmount = collect($feeCards)->sum('total_amount');
            $totalPaid = collect($feeCards)->sum('total_paid');
            $totalDiscount = collect($feeCards)->sum('total_discount');
            $totalFine = collect($feeCards)->sum('total_fine');
            $totalRemaining = collect($feeCards)->sum('remaining')  ;
        @endphp
        <div class="total-box">Total: ₹{{ number_format($totalAmount, 2) }}</div>
        <div class="total-box">Paid: ₹{{ number_format($totalPaid, 2) }}</div>
        <div class="total-box">Discount: ₹{{ number_format($totalDiscount, 2) }}</div>
        <div class="total-box">Fine: ₹{{ number_format($totalFine, 2) }}</div>
        <div class="total-box">Remaining: ₹{{ number_format($totalRemaining, 2) }}</div>
    </div>

    @else
    <div style="text-align:center; padding:50px; background:white; border-radius:10px;">
        <i class="fas fa-users" style="font-size:3rem; color:#cbd5e1;"></i>
        <p style="margin-top:15px;">Please select a student from <a href="{{ route('fees.student.list') }}">Student Fees List</a></p>
    </div>
    @endif
</div>

<!-- Collect Fee Modal -->
<div class="modal" id="collectModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Collect Fee</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="collectForm">
            <input type="hidden" id="collect_fees_type_id">
            <input type="hidden" id="max_allowed">
            <div class="form-group">
                <label>Receipt No</label>
                <input type="text" id="receipt_preview" readonly style="background:#f1f5f9;">
            </div>
            <div class="form-group">
                <label>Payment Date *</label>
                <input type="date" id="payment_date" required>
            </div>
            <div class="form-group">
                <label>Total Amount (₹)</label>
                <input type="text" id="total_amount" readonly style="background:#f1f5f9;">
            </div>
            <div class="modal-row">
                <div class="form-group">
                    <label>Discount (-) ₹</label>
                    <input type="number" id="discount_amount" step="1" value="0" onchange="calculateNet()">
                </div>
                <div class="form-group">
                    <label>Fine (+) ₹</label>
                    <input type="number" id="fine_amount" step="1" value="0" onchange="calculateNet()">
                </div>
            </div>
            <div class="form-group">
                <label>Paid Amount (₹) *</label>
                <input type="number" id="paid_amount" step="1" required onchange="validatePayment()">
                <small id="payment_error" style="color:#dc2626; display:none;"></small>
            </div>
            <div class="form-group">
                <label>Payment Mode</label>
                <select id="payment_mode">
                    <option value="cash">Cash</option>
                    <option value="upi">UPI</option>
                    <option value="cheque">Cheque</option>
                    <option value="card">Card</option>
                </select>
            </div>
            <div class="form-group">
                <label>Remarks</label>
                <input id="remarks" rows="2" style="width:100%;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-save">Collect Fee</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Transaction</h3>
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="editForm">
            <input type="hidden" id="edit_id">
            <div class="form-group">
                <label>Payment Date</label>
                <input type="date" id="edit_date" required>
            </div>
            <div class="modal-row">
                <div class="form-group">
                    <label>Discount (-)</label>
                    <input type="number" id="edit_discount" step="1">
                </div>
                <div class="form-group">
                    <label>Fine (+)</label>
                    <input type="number" id="edit_fine" step="1">
                </div>
            </div>
            <div class="form-group">
                <label>Paid Amount</label>
                <input type="number" id="edit_paid" step="1" required>
            </div>
            <div class="form-group">
                <label>Payment Mode</label>
                <select id="edit_mode">
                    <option value="cash">Cash</option>
                    <option value="upi">UPI</option>
                    <option value="cheque">Cheque</option>
                    <option value="card">Card</option>
                </select>
            </div>
            <div class="form-group">
                <label>Remarks</label>
                <input id="edit_remarks" rows="2" type="text">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn-save">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    let receiptSetting = @json($receiptSetting);
    let nextReceiptNo = {{ ($receiptSetting->last_receipt_no ?? 0) + 1 }};
    let studentId = {{ $student->id ?? 0 }};
    let currentRemaining = 0;

    function formatReceipt() {
        if(receiptSetting) {
            let formatted = nextReceiptNo.toString().padStart(2, '0');
            return `${receiptSetting.prefix}-${receiptSetting.year}-${formatted}`;
        }
        return 'REC-0000-01';
    }

    function calculateNet() {
        let amount = parseFloat(document.getElementById('total_amount').value) || 0;
        let discount = parseFloat(document.getElementById('discount_amount').value) || 0;
        let fine = parseFloat(document.getElementById('fine_amount').value) || 0;
        let net = amount - discount + fine;
        document.getElementById('paid_amount').value = net > 0 ? net : 0;
    }

    function validatePayment() {
        let paid = parseFloat(document.getElementById('paid_amount').value) || 0;
        let maxAllowed = parseFloat(document.getElementById('max_allowed').value) || 0;
        let errorSpan = document.getElementById('payment_error');
        
        if(paid > maxAllowed) {
            errorSpan.style.display = 'block';
            errorSpan.innerText = `Maximum allowed payment is ₹${maxAllowed.toFixed(2)}`;
            return false;
        } else {
            errorSpan.style.display = 'none';
            return true;
        }
    }

    function openCollectModal(feesTypeId, feesName, totalAmount, remaining) {
        currentRemaining = remaining;
        document.getElementById('modalTitle').innerHTML = `Collect Fee - ${feesName}`;
        document.getElementById('collect_fees_type_id').value = feesTypeId;
        document.getElementById('max_allowed').value = remaining;
        document.getElementById('receipt_preview').value = formatReceipt();
        document.getElementById('payment_date').value = new Date().toISOString().slice(0,10);
        document.getElementById('total_amount').value = totalAmount;
        document.getElementById('discount_amount').value = 0;
        document.getElementById('fine_amount').value = 0;
        document.getElementById('paid_amount').value = remaining;
        document.getElementById('payment_mode').value = 'cash';
        document.getElementById('remarks').value = '';
        document.getElementById('payment_error').style.display = 'none';
        document.getElementById('collectModal').classList.add('show');
    }

    function closeModal() {
        document.getElementById('collectModal').classList.remove('show');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
    }

    document.getElementById('collectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if(!validatePayment()) return;
        
        let formData = {
            student_id: studentId,
            fees_type_id: document.getElementById('collect_fees_type_id').value,
            payment_date: document.getElementById('payment_date').value,
            amount: document.getElementById('total_amount').value,
            discount: document.getElementById('discount_amount').value,
            fine: document.getElementById('fine_amount').value,
            paid_amount: document.getElementById('paid_amount').value,
            payment_mode: document.getElementById('payment_mode').value,
            remarks: document.getElementById('remarks').value,
            _token: '{{ csrf_token() }}'
        };
        
        Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        
        fetch('{{ route("fees.collect") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                Swal.fire({ icon: 'success', title: 'Success!', text: data.message, timer: 1500, showConfirmButton: false })
                .then(() => location.reload());
            } else {
                Swal.fire('Error!', data.error, 'error');
            }
        });
    });

    function printReceipt(id) {
        window.open(`/admin/fee-payment/print/${id}`, '_blank');
    }

    function printStatement(studentId) {
        window.open(`/admin/fee-statement/print/${studentId}`, '_blank');
    }

    function editTransaction(id) {
        fetch(`/admin/fee-payment/edit/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('edit_id').value = data.id;
                document.getElementById('edit_date').value = data.payment_date;
                document.getElementById('edit_discount').value = data.discount;
                document.getElementById('edit_fine').value = data.fine;
                document.getElementById('edit_paid').value = data.paid_amount;
                document.getElementById('edit_mode').value = data.payment_mode;
                document.getElementById('edit_remarks').value = data.remarks || '';
                document.getElementById('editModal').classList.add('show');
            });
    }

    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let id = document.getElementById('edit_id').value;
        let formData = {
            payment_date: document.getElementById('edit_date').value,
            discount: document.getElementById('edit_discount').value,
            fine: document.getElementById('edit_fine').value,
            paid_amount: document.getElementById('edit_paid').value,
            payment_mode: document.getElementById('edit_mode').value,
            remarks: document.getElementById('edit_remarks').value,
            _token: '{{ csrf_token() }}',
            _method: 'PUT'
        };
        
        Swal.fire({ title: 'Updating...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        
        fetch(`/admin/fee-payment/update/${id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                Swal.fire('Updated!', data.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Error!', data.error, 'error');
            }
        });
        closeEditModal();
    });

    function deleteTransaction(id) {
        Swal.fire({
            title: 'Delete Payment?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if(result.isConfirmed) {
                fetch(`/admin/fee-payment/delete/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire('Deleted!', data.message, 'success').then(() => location.reload());
                    }
                });
            }
        });
    }
</script>
@endsection