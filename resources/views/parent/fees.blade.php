<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
        }
        
        .fees-container {
            animation: fadeIn 0.3s ease;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Header */
        .fees-header {
            background: linear-gradient(135deg, #1e3c72 0%, #0f2b4d 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        .fees-header h2 {
            font-size: 1.1rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .fees-header h2 i { color: #f59e0b; }
        .fees-header .session-badge {
            background: rgba(255,255,255,0.15);
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.65rem;
            border: 1px solid rgba(255,255,255,0.1);
            font-weight: bold;
        }
        .fees-header .logout-btn {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            padding: 6px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
            font-weight: bold;
        }
        .fees-header .logout-btn:hover {
            background: rgba(255,255,255,0.25);
        }
        
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
        .info-label { font-weight: 600; color: #64748b; min-width: 85px; font-size:12px; }
        .info-value { color: #1e293b; font-weight: 500; font-size: 13px; }
        
        /* Fee Cards */
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
        
        .print-btn {
            background: #dbeafe;
            color: #1e40af;
            border: none;
            padding: 4px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.6rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-weight: 600;
            margin-top: 6px;
            width: 100%;
            justify-content: center;
        }
        .print-btn:hover { background: #bfdbfe; }
        
        /* Table */
        .table-section {
            background: white;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        .table-header {
            padding: 8px 15px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.7rem;
        }
        .data-table th {
            padding: 8px 12px;
            text-align: left;
            background: #f8fafc;
            font-weight: 600;
            border-bottom: 1px solid #e2e8f0;
        }
        .data-table td {
            padding: 6px 12px;
            border-bottom: 1px solid #ecf3f9;
        }
        .badge-mode { padding: 2px 8px; border-radius: 20px; font-size: 0.6rem; }
        .badge-cash { background: #dcfce7; color: #15803d; }
        .badge-upi { background: #dbeafe; color: #1e40af; }
        .badge-cheque { background: #fed7aa; color: #9a3412; }
        .badge-card { background: #e0e7ff; color: #4338ca; }
        
        .badge-paid { background: #dcfce7; color: #15803d; padding: 2px 12px; border-radius: 20px; font-size: 0.6rem; font-weight: 600; }
        .badge-due { background: #fee2e2; color: #dc2626; padding: 2px 12px; border-radius: 20px; font-size: 0.6rem; font-weight: 600; }
        .badge-pending { background: #fef3c7; color: #b45309; padding: 2px 12px; border-radius: 20px; font-size: 0.6rem; font-weight: 600; }
        
        /* Summary */
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
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #94a3b8;
        }
        .no-data i { font-size: 3rem; display: block; margin-bottom: 10px; }
        
        @media (max-width: 480px) {
            .stats-row { grid-template-columns: 1fr 1fr; }
            .fee-cards-grid { grid-template-columns: 1fr; }
            .fees-header { flex-direction: column; align-items: flex-start; }
            .fees-header h2 { font-size: 0.9rem; }
        }
        
        @media print {
            .logout-btn { display: none !important; }
            .no-print { display: none !important; }
            .fees-header { background: #1e3c72 !important; -webkit-print-color-adjust: exact; }
            .card-header { background: #1e3c72 !important; -webkit-print-color-adjust: exact; }
            .student-card-header { background: #1e3c72 !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="fees-container">
        <!-- Header -->
        <div class="fees-header">
            <h2>
                <i class="fas fa-money-bill-wave"></i> 
                Fee Details
                <span style="font-size:0.6rem; background:rgba(255,255,255,0.15); padding:2px 12px; border-radius:20px;">
                    <i class="fas fa-child"></i> {{ $student->full_name ?? 'Student' }}
                </span>
            </h2>
            <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                <span class="session-badge">
                    <i class="fas fa-calendar-alt"></i> Session: {{ $sessionName ?? 'N/A' }}
                </span>
                <button type="button" class="logout-btn" onclick="history.back()">
                    <i class="fas fa-arrow-left"></i> Back
                    </button>
                <form method="POST" action="{{ route('parent.logout') }}" class="no-print" style="display:inline;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
        
        @if($student)
        <!-- Student Info -->
        <div class="student-card">
            <div class="student-card-header">
                <h4 style="margin:0;"><i class="fas fa-user-graduate"></i> Student Details</h4>
            </div>
            <div class="student-info-grid">
                <div class="info-item"><span class="info-label">Student Name:</span><span class="info-value">{{ $student->full_name }}</span></div>
                <div class="info-item"><span class="info-label">Father Name:</span><span class="info-value">{{ $student->father_name ?? '-' }}</span></div>
                <div class="info-item"><span class="info-label">Class:</span><span class="info-value">{{ $student->class->class_name ?? 'N/A' }}</span></div>
                <div class="info-item"><span class="info-label">Mobile:</span><span class="info-value">{{ $student->mobile }}</span></div>
                <div class="info-item"><span class="info-label">Session:</span><span class="info-value">{{ $sessionName ?? 'N/A' }}</span></div>
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
                    <div style="margin-top:6px; border-top:1px solid #eef2f8; padding-top:6px;">
                        <button class="print-btn" onclick="printStatement({{ $student->id }})">
                            <i class="fas fa-print"></i> Print Statement
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- All Transactions -->
        <div class="table-section">
            <div class="table-header">
                <i class="fas fa-list"></i> All Transactions
            </div>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Receipt No</th>
                            <th>Fee Type</th>
                            <th>Amount</th>
                            <th>Discount</th>
                            <th>Fine</th>
                            <th>Paid</th>
                            <th>Mode</th>
                            <th>Status</th>
                        </tr>
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
                            <td><button class="action-btn" onclick="printReceipt({{ $trans['id'] }})" title="Print Receipt">
                                <i class="fas fa-print"></i>
                            </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="no-data">
                                <i class="fas fa-receipt"></i>
                                <p>No transactions yet</p>
                            </td>
                        </tr>
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
                $totalRemaining = collect($feeCards)->sum('remaining');
            @endphp
            <div class="total-box">Total: ₹{{ number_format($totalAmount, 2) }}</div>
            <div class="total-box">Paid: ₹{{ number_format($totalPaid, 2) }}</div>
            <div class="total-box">Discount: ₹{{ number_format($totalDiscount, 2) }}</div>
            <div class="total-box">Fine: ₹{{ number_format($totalFine, 2) }}</div>
            <div class="total-box">Remaining: ₹{{ number_format($totalRemaining, 2) }}</div>
        </div>

        @else
        <div class="no-data">
            <i class="fas fa-users"></i>
            <p>No student found. Please login again.</p>
        </div>
        @endif
    </div>

    <script>
        function printStatement(studentId) {
            window.open(`/admin/fee-statement/print/${studentId}`, '_blank');
        }
        function printReceipt(id) {
        window.open(`/admin/fee-payment/print/${id}`, '_blank');
    }
    </script>
</body>
</html>