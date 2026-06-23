@extends('layouts.app')

@section('title', 'Deleted Payments Archive')

@section('content')
<style>
    .archive-container { animation: fadeIn 0.3s ease; }
    .archive-header {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        color: white;
        padding: 10px 18px;
        border-radius: 10px;
        margin-bottom: 18px;
    }
    .table-wrapper { background: white; border-radius: 10px; border: 1px solid #e2e8f0; overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; font-size: 0.7rem; }
    .data-table th { padding: 10px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; text-align: left; }
    .data-table td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; }
    .badge-deleted { background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 20px; font-size: 0.6rem; }
    .restore-btn { background: #10b981; color: white; border: none; padding: 4px 12px; border-radius: 5px; cursor: pointer; }
    .restore-btn:hover { background: #059669; }
</style>

<div class="archive-container">
    <div class="archive-header">
        <h2><i class="fas fa-trash-alt"></i> Deleted Payments Archive</h2>
        <p>Records that have been deleted and archived</p>
    </div>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr><th>ID</th><th>Receipt No</th><th>Student</th><th>Amount</th><th>Paid</th><th>Deleted By</th><th>Deleted At</th><th>Reason</th><th>Action</th></tr>
            </thead>
            <tbody>
                @forelse($deletedPayments as $payment)
                <tr>
                    <td>{{ $payment->original_id }}</td>
                    <td><span class="badge-deleted">{{ $payment->receipt_no }}</span></td>
                    <td>{{ $payment->student->full_name ?? 'N/A' }}</td>
                    <td>₹ {{ number_format($payment->amount, 2) }}</td>
                    <td>₹ {{ number_format($payment->paid_amount, 2) }}</td>
                    <td>{{ $payment->deleter->name ?? 'N/A' }}</td>
                    <td>{{ $payment->deleted_at->format('d-m-Y H:i') }}</td>
                    <td>{{ Str::limit($payment->delete_reason, 50) }}</td>
                    <td><button class="restore-btn" onclick="restorePayment({{ $payment->original_id }})"><i class="fas fa-undo"></i> Restore</button></td>
                </tr>
                @empty
                <tr><td colspan="9" style="text-align:center; padding:40px;">No deleted records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $deletedPayments->links() }}
</div>

<script>
function restorePayment(id) {
    Swal.fire({
        title: 'Restore Payment?',
        text: 'This will restore the payment record to active list',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        confirmButtonText: 'Yes, Restore'
    }).then((result) => {
        if(result.isConfirmed) {
            fetch(`/admin/fee-payment/restore/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Swal.fire('Restored!', data.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error!', data.error, 'error');
                }
            });
        }
    });
}
</script>
@endsection