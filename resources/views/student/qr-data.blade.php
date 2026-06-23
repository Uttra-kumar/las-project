<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student QR Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .qr-card {
            max-width: 420px;
            width: 100%;
            background: white;
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: fadeIn 0.5s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .qr-header {
            text-align: center;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .qr-header .avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 2rem;
            color: white;
        }
        .qr-header h2 {
            font-size: 20px;
            color: #1e3c72;
        }
        .qr-header p {
            font-size: 12px;
            color: #64748b;
        }
        .qr-badge {
            display: inline-block;
            background: #dcfce7;
            color: #15803d;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .info-item {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #eef2f8;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-label {
            width: 100px;
            font-weight: 600;
            color: #64748b;
            font-size: 13px;
        }
        .info-value {
            flex: 1;
            color: #1e293b;
            font-size: 14px;
            font-weight: 500;
        }
        .qr-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
            font-size: 11px;
            color: #94a3b8;
        }
        .qr-footer i {
            color: #667eea;
        }
        .qr-status {
            text-align: center;
            margin-top: 15px;
            padding: 10px;
            background: #f0fdf4;
            border-radius: 10px;
        }
        .qr-status span {
            color: #15803d;
            font-weight: 600;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="qr-card">
        <div class="qr-header">
            <div class="avatar">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h2>{{ $student->full_name }}</h2>
            <p><i class="fas fa-id-card"></i> {{ $student->student_id }}</p>
            <span class="qr-badge"><i class="fas fa-check-circle"></i> Active</span>
        </div>

        <div class="info-item">
            <span class="info-label"><i class="fas fa-chalkboard-user"></i> Class</span>
            <span class="info-value">{{ $student->class->class_name ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label"><i class="fas fa-user"></i> Father</span>
            <span class="info-value">{{ $student->father_name ?? '-' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label"><i class="fas fa-user"></i> Mother</span>
            <span class="info-value">{{ $student->mother_name ?? '-' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label"><i class="fas fa-birthday-cake"></i> DOB</span>
            <span class="info-value">{{ $student->dob ? date('d-m-Y', strtotime($student->dob)) : '-' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label"><i class="fas fa-venus-mars"></i> Gender</span>
            <span class="info-value">{{ $student->gender ?? '-' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label"><i class="fas fa-phone"></i> Mobile</span>
            <span class="info-value">{{ $student->mobile }}</span>
        </div>
        <div class="info-item">
            <span class="info-label"><i class="fas fa-map-marker-alt"></i> Address</span>
            <span class="info-value">{{ $student->address }}, {{ $student->city }}, {{ $student->state }}</span>
        </div>
        @if($student->is_stream == 1 && $student->stream)
        <div class="info-item">
            <span class="info-label"><i class="fas fa-code-branch"></i> Stream</span>
            <span class="info-value">{{ $student->stream->stream_name ?? 'N/A' }}</span>
        </div>
        @endif
        <div class="info-item">
            <span class="info-label"><i class="fas fa-calendar-alt"></i> Session</span>
            <span class="info-value">{{ $student->session->session_name ?? $student->session_id ?? 'N/A' }}</span>
        </div>

        <div class="qr-status">
            <span><i class="fas fa-check-circle"></i> Verified Student</span>
        </div>

        <div class="qr-footer">
            <i class="fas fa-qrcode"></i> Scanned from QR Code
        </div>
    </div>
</body>
</html>