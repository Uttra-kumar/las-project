<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ===== RESET & BASE ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            padding: 16px;
        }
        .result-container {
            max-width: 1100px;
            margin: 0 auto;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== HEADER ===== */
        .result-header {
            background: linear-gradient(135deg, #1a365d 0%, #2d4a7a 50%, #1a365d 100%);
            color: white;
            padding: 14px 20px;
            border-radius: 10px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            box-shadow: 0 2px 8px rgba(26, 54, 93, 0.25);
        }
        .result-header h2 {
            font-size: 1.1rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
        }
        .result-header h2 i {
            color: #fbbf24;
        }
        .back-btn {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.15);
            color: white;
            padding: 6px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            height: 36px;
        }
        .back-btn:hover {
            background: rgba(255,255,255,0.2);
            transform: translateX(-2px);
        }

        /* ===== STUDENT CARD ===== */
        .student-card {
            background: white;
            border-radius: 10px;
            padding: 14px 20px;
            margin-bottom: 16px;
            border: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: all 0.3s;
        }
        .student-card:hover {
            border-color: #cbd5e1;
        }
        .student-card .student-left {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }
        .student-card .avatar-sm {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e3c72, #2d4a7a);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .student-card .avatar-sm img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        .student-card .name {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1e3c72;
        }
        .student-card .class {
            font-size: 0.8rem;
            color: #64748b;
        }
        .student-card .class i {
            color: #3b82f6;
            margin-right: 4px;
        }
        .student-card .badge {
            background: #dbeafe;
            color: #1e40af;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* ===== SECTION TITLE ===== */
        .section-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1e3c72;
            margin: 18px 0 12px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title i {
            color: #f59e0b;
            font-size: 1rem;
        }
        .section-title .title-badge {
            background: #1e3c72;
            color: white;
            font-size: 0.6rem;
            padding: 2px 12px;
            border-radius: 12px;
            font-weight: 600;
        }
        .section-title .title-badge i {
            color: white;
            font-size: 0.5rem;
            margin-right: 4px;
        }

        /* ============================================
           RESULT CARDS
           ============================================ */
        .result-card {
            background: white;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 14px 16px;
            margin-bottom: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: all 0.3s;
        }
        .result-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        /* ===== EXAM TITLE ===== */
        .exam-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            padding-bottom: 10px;
            margin-bottom: 10px;
            border-bottom: 2px solid #f1f5f9;
        }
        .exam-title .exam-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .exam-title .exam-name {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1e3c72;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .exam-title .exam-name i {
            color: #f59e0b;
        }
        .exam-title .exam-date {
            font-size: 0.65rem;
            color: #64748b;
        }
        .exam-title .exam-date i {
            color: #3b82f6;
            margin-right: 4px;
        }
        .exam-title .exam-percentage {
            font-size: 1.1rem;
            font-weight: 700;
            padding: 2px 16px;
            border-radius: 20px;
            min-width: 60px;
            text-align: center;
        }
        .exam-percentage.good {
            background: #dcfce7;
            color: #15803d;
        }
        .exam-percentage.average {
            background: #fef3c7;
            color: #b45309;
        }
        .exam-percentage.low {
            background: #fee2e2;
            color: #dc2626;
        }

        /* ===== SUBJECT HEADINGS - FIXED ===== */
        .subject-headings {
            display: grid;
            grid-template-columns: 1.8fr 1fr 1fr 0.8fr 0.8fr;
            gap: 6px;
            padding: 6px 10px;
            background: #f8fafc;
            border-radius: 6px;
            margin-bottom: 6px;
            font-size: 0.6rem;
            font-weight: 700;
            color: #1e3c72;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .subject-headings .sub-head {
            text-align: left;
        }
        .subject-headings .obtains-head,
        .subject-headings .total-head,
        .subject-headings .percent-head,
        .subject-headings .grade-head {
            text-align: center;
        }

        /* ===== SUBJECT ROW - FIXED ===== */
        .subject-row {
            display: grid;
            grid-template-columns: 1.8fr 1fr 1fr 0.8fr 0.8fr;
            gap: 6px;
            padding: 5px 10px;
            border-bottom: 1px dashed #f1f5f9;
            font-size: 0.8rem;
            align-items: center;
            transition: background 0.2s;
        }
        .subject-row:hover {
            background: #fafbfc;
        }
        .subject-row:last-child {
            border-bottom: none;
        }
        .subject-row .sub {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.8rem;
        }
        .subject-row .obtained {
            text-align: center;
            font-weight: 700;
            color: #1e3c72;
            font-size: 0.8rem;
        }
        .subject-row .total {
            text-align: center;
            color: #64748b;
            font-weight: 500;
            font-size: 0.8rem;
        }
        .subject-row .percent {
            text-align: center;
            font-weight: 600;
            font-size: 0.8rem;
        }
        .subject-row .grade {
            text-align: center;
            font-weight: 700;
        }

        /* ===== GRADE BADGES ===== */
        .grade-a {
            color: #10b981;
            background: #dcfce7;
            padding: 2px 12px;
            border-radius: 12px;
            font-size: 0.7rem;
            display: inline-block;
        }
        .grade-b {
            color: #f59e0b;
            background: #fef3c7;
            padding: 2px 12px;
            border-radius: 12px;
            font-size: 0.7rem;
            display: inline-block;
        }
        .grade-c {
            color: #f97316;
            background: #ffedd5;
            padding: 2px 12px;
            border-radius: 12px;
            font-size: 0.7rem;
            display: inline-block;
        }
        .grade-d {
            color: #ef4444;
            background: #fee2e2;
            padding: 2px 12px;
            border-radius: 12px;
            font-size: 0.7rem;
            display: inline-block;
        }

        /* ===== TOTAL ROW - FIXED ===== */
        .total-row {
            display: grid;
            grid-template-columns: 1.8fr 1fr 1fr 0.8fr 0.8fr;
            gap: 6px;
            padding: 8px 10px;
            margin-top: 8px;
            background: #f1f5f9;
            border-radius: 6px;
            font-size: 0.8rem;
            border-top: 2px solid #e2e8f0;
        }
        .total-row span {
            text-align: center;
        }
        .total-row span:first-child {
            text-align: left;
            font-weight: 700;
            color: #1e3c72;
        }
        .total-row .total-label {
            text-align: left;
        }
        .total-row .total-obtained {
            font-weight: 700;
            color: #1e3c72;
        }
        .total-row .total-max {
            font-weight: 700;
            color: #1e3c72;
        }
        .total-row .total-percent {
            font-weight: 700;
            color: #1e3c72;
        }

        /* ===== STATUS BADGE ===== */
        .badge-status {
            padding: 2px 14px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 600;
            display: inline-block;
        }
        .badge-status.pending {
            background: #fef3c7;
            color: #b45309;
        }
        .badge-status.approved {
            background: #dcfce7;
            color: #15803d;
        }

        /* ===== GRAND TOTAL CARD ===== */
        .grand-total-card {
            background: linear-gradient(135deg, #1a365d 0%, #2d4a7a 50%, #1a365d 100%);
            color: white;
            border-radius: 10px;
            padding: 16px 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 16px;
            box-shadow: 0 2px 8px rgba(26, 54, 93, 0.25);
            transition: all 0.3s;
        }
        .grand-total-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(26, 54, 93, 0.35);
        }
        .grand-total-left {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.95rem;
        }
        .grand-total-left i {
            color: #fbbf24;
            font-size: 1.3rem;
        }
        .grand-total-right {
            display: flex;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
        }
        .grand-marks {
            font-size: 1rem;
            font-weight: 700;
        }
        .grand-percentage {
            font-size: 1.3rem;
            font-weight: 700;
            padding: 4px 20px;
            border-radius: 20px;
            min-width: 70px;
            text-align: center;
        }
        .grand-percentage.good {
            background: #dcfce7;
            color: #15803d;
        }
        .grand-percentage.average {
            background: #fef3c7;
            color: #b45309;
        }
        .grand-percentage.low {
            background: #fee2e2;
            color: #dc2626;
        }

        /* ===== NO DATA ===== */
        .no-data {
            text-align: center;
            padding: 45px 20px;
            color: #94a3b8;
        }
        .no-data i {
            font-size: 2.8rem;
            display: block;
            margin-bottom: 12px;
            color: #cbd5e1;
        }
        .no-data p {
            font-size: 0.95rem;
            margin-bottom: 4px;
            color: #475569;
        }
        .no-data small {
            font-size: 0.8rem;
            color: #94a3b8;
        }

        /* ============================================
           RESPONSIVE - MOBILE FIRST
           ============================================ */

        @media (max-width: 992px) {
            .subject-headings,
            .subject-row,
            .total-row {
                grid-template-columns: 1.5fr 1fr 1fr 0.8fr 0.8fr;
                font-size: 0.75rem;
            }
            .subject-headings {
                font-size: 0.55rem;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 12px;
            }

            .result-header {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
                padding: 12px 16px;
            }
            .result-header h2 {
                justify-content: center;
                font-size: 1rem;
            }
            .back-btn {
                justify-content: center;
                width: 100%;
                font-size: 0.8rem;
                height: 36px;
            }

            .student-card {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
                padding: 12px 16px;
            }
            .student-card .student-left {
                justify-content: center;
            }
            .student-card .badge {
                justify-content: center;
            }

            .section-title {
                font-size: 0.85rem;
                justify-content: center;
            }

            .exam-title {
                flex-direction: column;
                align-items: flex-start;
            }
            .exam-title .exam-percentage {
                font-size: 1rem;
                min-width: 50px;
                padding: 2px 14px;
            }

            .subject-headings,
            .subject-row,
            .total-row {
                grid-template-columns: 1.2fr 1fr 1fr 0.7fr 0.7fr;
                gap: 4px;
                font-size: 0.7rem;
                padding: 4px 8px;
            }
            .subject-headings {
                font-size: 0.5rem;
            }
            .subject-row .sub {
                font-size: 0.7rem;
            }
            .grade-a, .grade-b, .grade-c, .grade-d {
                font-size: 0.6rem;
                padding: 1px 8px;
            }
            .badge-status {
                font-size: 0.6rem;
                padding: 1px 10px;
            }

            .grand-total-card {
                flex-direction: column;
                text-align: center;
                padding: 14px 18px;
            }
            .grand-total-right {
                justify-content: center;
            }
            .grand-percentage {
                font-size: 1.1rem;
                padding: 3px 16px;
            }
            .grand-marks {
                font-size: 0.9rem;
            }
            .grand-total-left {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 8px;
            }

            .result-header {
                padding: 10px 12px;
            }
            .result-header h2 {
                font-size: 0.9rem;
            }
            .back-btn {
                font-size: 0.75rem;
                height: 32px;
                padding: 4px 12px;
            }

            .student-card {
                padding: 10px 12px;
            }
            .student-card .name {
                font-size: 0.95rem;
            }
            .student-card .class {
                font-size: 0.7rem;
            }
            .student-card .avatar-sm {
                width: 38px;
                height: 38px;
                font-size: 1rem;
            }
            .student-card .badge {
                font-size: 0.6rem;
                padding: 2px 10px;
            }

            .section-title {
                font-size: 0.8rem;
                margin: 14px 0 10px 0;
            }
            .section-title .title-badge {
                font-size: 0.55rem;
                padding: 1px 10px;
            }

            .result-card {
                padding: 10px 12px;
                margin-bottom: 10px;
            }
            .exam-title .exam-name {
                font-size: 0.8rem;
            }
            .exam-title .exam-date {
                font-size: 0.6rem;
            }
            .exam-title .exam-percentage {
                font-size: 0.9rem;
                padding: 2px 12px;
            }

            .subject-headings,
            .subject-row,
            .total-row {
                grid-template-columns: 1fr 0.8fr 0.8fr 0.6fr 0.6fr;
                gap: 4px;
                font-size: 0.65rem;
                padding: 3px 6px;
            }
            .subject-headings {
                font-size: 0.45rem;
                padding: 4px 6px;
            }
            .subject-row .sub {
                font-size: 0.65rem;
            }
            .grade-a, .grade-b, .grade-c, .grade-d {
                font-size: 0.55rem;
                padding: 1px 6px;
            }
            .badge-status {
                font-size: 0.55rem;
                padding: 1px 8px;
            }

            .grand-total-card {
                padding: 10px 14px;
                margin-top: 12px;
            }
            .grand-total-left {
                font-size: 0.8rem;
                gap: 8px;
            }
            .grand-total-left i {
                font-size: 1rem;
            }
            .grand-percentage {
                font-size: 0.95rem;
                padding: 2px 12px;
                min-width: 60px;
            }
            .grand-marks {
                font-size: 0.8rem;
            }

            .no-data {
                padding: 30px 15px;
            }
            .no-data i {
                font-size: 2rem;
            }
            .no-data p {
                font-size: 0.8rem;
            }
            .no-data small {
                font-size: 0.65rem;
            }
        }

        @media (max-width: 380px) {
            .subject-headings,
            .subject-row,
            .total-row {
                grid-template-columns: 0.8fr 0.7fr 0.7fr 0.5fr 0.5fr;
                font-size: 0.6rem;
                gap: 2px;
                padding: 2px 4px;
            }
            .subject-headings {
                font-size: 0.4rem;
            }
            .subject-row .sub {
                font-size: 0.6rem;
            }
            .exam-title .exam-percentage {
                font-size: 0.8rem;
                padding: 2px 10px;
                min-width: 45px;
            }
            .grand-percentage {
                font-size: 0.85rem;
                padding: 2px 10px;
                min-width: 50px;
            }
            .grade-a, .grade-b, .grade-c, .grade-d {
                font-size: 0.5rem;
                padding: 1px 4px;
            }
        }

        /* ============================================
           PRINT
           ============================================ */
        @media print {
            .result-header {
                background: #1a365d !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                padding: 8px 14px !important;
            }
            .back-btn {
                display: none !important;
            }
            .student-card {
                border: 1px solid #000 !important;
                box-shadow: none !important;
            }
            .result-card {
                border: 1px solid #000 !important;
                box-shadow: none !important;
                break-inside: avoid !important;
            }
            .exam-title {
                border-bottom: 2px solid #000 !important;
            }
            .subject-headings {
                background: #e0e0e0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .total-row {
                background: #e0e0e0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                border-top: 2px solid #000 !important;
            }
            .grand-total-card {
                background: #1a365d !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                border: 1px solid #000 !important;
            }
            .exam-percentage,
            .grand-percentage,
            .badge-status,
            .grade-a, .grade-b, .grade-c, .grade-d {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-data {
                border: 1px solid #000 !important;
            }
            .section-title .title-badge {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>
    <div class="result-container">
        <!-- ===== HEADER ===== -->
        <div class="result-header">
            <h2>
                <i class="fas fa-chart-bar"></i>
                Results
            </h2>
            <button class="back-btn" onclick="history.back()">
                <i class="fas fa-arrow-left"></i> Back
            </button>
        </div>

        <!-- ===== STUDENT INFO ===== -->
        <div class="student-card">
            <div class="student-left">
                <div class="avatar-sm">
                    @if($student->image)
                        <img src="{{ asset('storage/' . $student->image) }}" alt="{{ $student->full_name }}">
                    @else
                        {{ substr($student->full_name, 0, 1) }}
                    @endif
                </div>
                <div>
                    <div class="name">{{ $student->full_name }}</div>
                    <div class="class">
                        <i class="fas fa-school"></i> {{ $student->class->class_name ?? 'N/A' }}
                        @if($student->stream)
                            | <i class="fas fa-code-branch"></i> {{ $student->stream->stream_name }}
                        @endif
                    </div>
                </div>
            </div>
            <div>
                <span class="badge">
                    <i class="fas fa-calendar-alt"></i> {{ $student->session->session_name ?? 'N/A' }}
                </span>
            </div>
        </div>

        <!-- ===== RESULTS SECTION ===== -->
        <div class="section-title">
            <i class="fas fa-chart-bar"></i>
            Exam Results
            <span class="title-badge">
                <i class="fas fa-file-alt"></i> {{ count($resultData) }}
            </span>
        </div>

        @if(count($resultData) > 0)
            @php
                $grandTotalObtained = 0;
                $grandTotalMax = 0;
            @endphp

            @foreach($resultData as $result)
            <div class="result-card">
                <!-- Exam Title -->
                <div class="exam-title">
                    <div class="exam-left">
                        <span class="exam-name">
                            <i class="fas fa-pencil-alt"></i> {{ $result['exam_title'] }}
                        </span>
                        <span class="exam-date">
                            <i class="fas fa-calendar-day"></i> 
                            {{ $result['exam_date'] ? date('d-m-Y', strtotime($result['exam_date'])) : '-' }}
                        </span>
                    </div>
                    <span class="exam-percentage 
                        {{ $result['percentage'] >= 80 ? 'good' : ($result['percentage'] >= 60 ? 'average' : 'low') }}">
                        {{ $result['percentage'] }}%
                    </span>
                </div>

                <!-- Subject Headings -->
                <div class="subject-headings">
                    <span class="sub-head">Subject</span>
                    <span class="obtains-head">Obtained</span>
                    <span class="total-head">Total</span>
                   
                </div>

                <!-- Subject Rows -->
                @foreach($result['subjects'] as $subject)
                <div class="subject-row">
                    <span class="sub">{{ $subject['subject_name'] }}</span>
                    <span class="obtained">{{ $subject['obtained'] }}</span>
                    <span class="total">{{ $subject['max'] }}</span>
                    
                </div>
                @endforeach

                <!-- Total Row -->
                <div class="total-row">
                    <span class="total-label"><strong>Total</strong></span>
                    <span class="total-obtained"><strong>{{ $result['total_obtained'] }}</strong></span>
                    <span class="total-max"><strong>{{ $result['total_max'] }}</strong></span>
                    <span class="total-percent"><strong>{{ $result['percentage'] }}%</strong></span>
                    <span>
                        <span class="badge-status {{ $result['status'] }}">
                            {{ ucfirst($result['status']) }}
                        </span>
                    </span>
                </div>

                @php
                    $grandTotalObtained += $result['total_obtained'];
                    $grandTotalMax += $result['total_max'];
                @endphp
            </div>
            @endforeach

            <!-- Grand Total -->
            

        @else
        <div class="result-card">
            <div class="no-data">
                <i class="fas fa-chart-bar"></i>
                <p>No results available yet</p>
                <small>Results will appear here once marks are approved</small>
            </div>
        </div>
        @endif

        <!-- ===== FOOTER ===== -->
        <div style="margin-top:16px; background:#f8fafc; padding:10px 18px; border-radius:8px; font-size:0.7rem; color:#94a3b8; text-align:center; border:1px solid #e2e8f0;">
            <i class="fas fa-info-circle" style="color:#3b82f6;"></i>
            Results show all approved exam marks for your reference.
        </div>
    </div>
</body>
</html>