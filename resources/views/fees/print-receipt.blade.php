<!DOCTYPE html>
<html>
<head>
    <title>Receipt - {{ $payment->receipt_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body { 
            font-family: Arial, Helvetica, sans-serif; 
            background: #f5f5f5;
            padding: 2px 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .receipt { 
            min-width: 230mm;
            width: 100%;
            min-height: 140mm;
            margin: 0px;
            background: white;
            padding:20px 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-radius: 4px;
            border: 2px solid black;
            
        }
        
        /* ===== HEADER ===== */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        .logo-left img, .logo-right img {
            max-height: 80px;
            max-width: 80px;
        }
        .logo-placeholder {
            width: 80px;
            height: 80px;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #999;
            background: #fafafa;
        }
        .school-info {
            text-align: center;
            flex: 1;
            padding: 0 15px;
        }
        .school-name {
            font-size: 35px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .school-address {
            font-size: 22px;
            color: #333;
            margin-top: 2px;
        }
        .school-contact {
            font-size: 17px;
            color: #555;
            margin-top: 2px;
        }
        .receipt-title {
            font-size: 19px;
            font-weight: 700;
            margin-top: 6px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .receipt-title .session {
            font-size: 14px;
            font-weight: normal;
            letter-spacing: 1px;
        }
        
        /* ===== RECEIPT BAR ===== */
        .receipt-bar {
            background: #000;
            color: white;
            padding: 6px 14px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            font-size: 19px;
            font-weight: 600;
        }
        .receipt-bar span:last-child {
            font-weight: 400;
        }
        
        /* ===== STUDENT DETAILS ===== */
        .student-row {
            display: flex;
            flex-wrap: wrap;
            border: 1px solid #000;
            margin-bottom: 8px;
        }
        .student-item {
            flex: 1;
            padding: 6px 10px;
            border-right: 1px solid #000;
            font-size: 13px;
            min-width: 120px;
        }
        .student-item:last-child {
            border-right: none;
        }
        .student-label {
            font-weight: 500;
            margin-right: 5px;
            font-size: 16px;
        }
        .student-value {
            font-weight: 600;
            font-size: 18px;
        }
        
        /* ===== FEES TABLE ===== */
        .fees-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 13px;
        }
        .fees-table th {
            border: 1px solid #000;
            padding: 8px 12px;
            text-align: center;
            background: #e8e8e8;
            font-size: 15px;
            text-transform: uppercase;
            font-weight: 700;
        }
        .fees-table td {
            border: 1px solid #000;
            padding: 8px 12px;
            text-align: center;
            font-size: 17px;
        }
        .fees-table .total-row td {
            font-weight: 700;
            background: #f5f5f5;
            font-size: 14px;
        }
        
        /* ===== PAYMENT ROW ===== */
        .payment-row {
            display: flex;
            flex-wrap: wrap;
            border: 1px solid #000;
            margin-bottom: 10px;
        }
        .payment-item {
            flex: 1;
            padding: 6px 10px;
            border-right: 1px solid #000;
            font-size: 13px;
            min-width: 120px;
        }
        .payment-item:last-child {
            border-right: none;
        }
        
        /* ===== FOOTER ===== */
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 20px;
            padding-top: 12px;
            border-top: 1px solid #000;
        }
        .signature {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 25px;
            padding-top: 6px;
            font-size: 10px;
            color: #333;
        }
        .footer-info {
            text-align: right;
            font-size: 12px;
            color: #555;
            line-height: 1.8;
        }
        .footer-info .user {
            font-weight: 700;
            color: #000;
            font-size: 13px;
        }
        .print-date {
            text-align: center;
            font-size: 11px;
            color: #777;
            margin-top: 12px;
            border-top: 1px solid #eee;
            padding-top: 8px;
        }
        
        /* ===== PRINT ===== */
        @media print {
           
            
            body {
                background: white !important;
                padding: 10px !important;
                margin: 0 !important;
                width: 100% !important;
                display: block !important;
                min-height: 100vh !important;
            }
            
            .receipt {
                max-width: 100% !important;
                width: 100% !important;
                min-height: 100% !important;
                margin: 0 !important;
                padding: 10px 10px !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                border: 2px solid black !important;
                background: white !important;
                height: 100% !important;

            }
             
            .no-print {
                display: none !important;
            }
            
            .receipt-bar {
                background: #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .fees-table th {
                background: #e8e8e8 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                font-size: 12px !important;
            }
            
            .fees-table  td {
                background: #f5f5f5 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                 font-size: 13px !important;
            }
        }
        
        /* ===== BUTTONS ===== */
        .btn-group {
            text-align: center;
            margin-top: 15px;
        }
        .btn-group button {
            background: #1a1a2e;
            color: white;
            border: none;
            padding: 10px 28px;
            margin: 0 8px;
            cursor: pointer;
            font-size: 14px;
            border-radius: 4px;
            font-weight: 600;
        }
        .btn-group button:hover {
            background: #16213e;
        }
        .btn-group button.close-btn {
            background: #666;
        }
        .btn-group button.close-btn:hover {
            background: #555;
        }
    </style>
</head>
<body>
    <div class="receipt">
        
        @php
            use App\Models\SchoolSetting;
            $school = SchoolSetting::first();
        @endphp
        
        <!-- ===== HEADER ===== -->
        <div class="header">
            <div class="logo-left">
                @if($school && $school->logo_1)
                    <img src="{{ asset('storage/' . $school->logo_1) }}" alt="Logo" style="max-height:55px;">
                @else
                    <div class="logo-placeholder">LOGO</div>
                @endif
            </div>
            <div class="school-info">
                <div class="school-name">{{ $school->school_name ?? 'School Management System' }}</div>
                <div class="school-address">{{ $school->address ?? 'School Address, City - Pincode' }}</div>
                <div class="school-contact">
                    📞 {{ $school->mobile ?? '0000000000' }} &nbsp;|&nbsp; ✉ {{ $school->email ?? 'info@school.com' }}
                </div>
                <div class="receipt-title">
                    FEE RECEIPT
                    <span class="session">(Session: {{ $payment->student->session->session_name ?? $payment->session_id ?? 'N/A' }})</span>
                </div>
            </div>
            <div class="logo-right">
                @if($school && $school->logo_2)
                    <img src="{{ asset('storage/' . $school->logo_2) }}" alt="Logo" style="max-height:55px;">
                @else
                    <div class="logo-placeholder">LOGO</div>
                @endif
            </div>
        </div>
        
        <!-- ===== RECEIPT BAR ===== -->
        <div class="receipt-bar">
            <span>📄 Receipt No: {{ $payment->receipt_no }}</span>
            <span>📅 Date: {{ date('d-m-Y', strtotime($payment->payment_date)) }}</span>
        </div>
        
        <!-- ===== STUDENT DETAILS ===== -->
        <div class="student-row">
            <div class="student-item">
                <span class="student-label">Student:</span> <span class="student-value">{{ $payment->student->full_name ?? 'N/A' }}</span>
            </div>
            <div class="student-item">
                <span class="student-label">Father:</span> <span class="student-value">{{ $payment->student->father_name ?? '-' }}</span>
            </div>
            <div class="student-item">
                <span class="student-label">Student ID:</span> <span class="student-value">{{ $payment->student->student_id ?? 'N/A' }}</span>
            </div>
        </div>
        <div class="student-row">
            <div class="student-item">
                <span class="student-label">Class:</span> <span class="student-value">{{ $payment->class->class_name ?? 'N/A' }}</span>
            </div>
            <div class="student-item">
                <span class="student-label">Session:</span> <span class="student-value">{{ $payment->student->session->session_name ?? $payment->session_id ?? 'N/A' }}</span>
            </div>
            <div class="student-item">
                <span class="student-label">Mobile:</span> <span class="student-value">{{ $payment->student->mobile ?? '-' }}</span>
            </div>
        </div>
        
        <!-- ===== FEES TABLE ===== -->
        <table class="fees-table">
            <thead>
                <tr>
                    <th width="18%">Receipt No</th>
                    <th width="18%">Date</th>
                    <th width="29%">Fee Type</th>
                    <th width="15%">Discount</th>
                    <th width="20%">Paid (₹)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>{{ $payment->receipt_no }}</strong></td>
                    <td>{{ date('d-m-Y', strtotime($payment->payment_date)) }}</td>
                    <td><strong>{{ $payment->feesType->name ?? 'Fee' }}</strong></td>
                    <td>{{ $payment->discount ? number_format($payment->discount, 2) : '-' }}</td>
                    <td><strong>₹ {{ number_format($payment->paid_amount, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
        
        <!-- ===== PAYMENT DETAILS ===== -->
        <div class="payment-row">
            <div class="payment-item">
                <span class="student-label">Payment Mode:</span> 
                <strong>{{ strtoupper($payment->payment_mode) }}</strong>
            </div>
            <div class="payment-item">
                <span class="student-label">Collected By:</span> 
                {{ $payment->creator->name ?? 'Admin' }}
            </div>
            <div class="payment-item">
                <span class="student-label">Remarks:</span> 
                {{ $payment->remarks ?? '-' }}
            </div>
        </div>
        
        <!-- ===== FOOTER ===== -->
        <div class="footer">
            <div class="footer-info">
                <div class="user">👤 {{ $payment->creator->name ?? 'Admin' }}</div>
                <div>🕐 {{ date('d-m-Y h:i:s A') }}</div>
            </div>
            <div class="signature">
                <div class="signature-line"></div>
                <div>Authority Signature</div>
            </div>
        </div>
        
        <div class="print-date">
            This is a computer generated receipt. Valid without signature.
        </div>
        
        <!-- ===== BUTTONS ===== -->
        <div class="btn-group no-print">
            <button onclick="window.print()">🖨️ Print Receipt</button>
            <button class="close-btn" onclick="window.close()">✕ Close</button>
        </div>
    </div>
    
    <script>
        // Auto print (uncomment to auto print)
        // window.onload = function() { 
        //     window.print(); 
        // }
    </script>
</body>
</html>