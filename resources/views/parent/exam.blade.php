<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Schedule</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
        .exam-container {
            max-width: 1100px;
            margin: 0 auto;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== HEADER ===== */
        .exam-header {
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
        .exam-header h2 {
            font-size: 1.1rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
        }
        .exam-header h2 i {
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

        /* ============================================
           EXAM CARD - DATE WISE TABLE
           ============================================ */
        .exam-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            margin-bottom: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: all 0.3s;
        }
        .exam-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }

        .exam-card-header {
            background: linear-gradient(135deg, #1a365d, #2d4a7a);
            color: white;
            padding: 12px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }
        .exam-card-header h3 {
            font-size: 0.95rem;
            margin: 0;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .exam-card-header h3 i {
            color: #fbbf24;
        }
        .exam-card-header .exam-badge {
            background: rgba(255,255,255,0.12);
            padding: 3px 14px;
            border-radius: 20px;
            font-size: 0.65rem;
            border: 1px solid rgba(255,255,255,0.1);
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .exam-card-header .exam-badge i {
            color: #fbbf24;
        }

        /* ===== TABLE INSIDE CARD ===== */
        .exam-table-wrapper {
            overflow-x: auto;
            padding: 0;
        }
        .exam-schedule-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
            min-width: 500px;
        }
        .exam-schedule-table thead {
            background: #f8fafc;
        }
        .exam-schedule-table th {
            padding: 10px 14px;
            text-align: left;
            font-weight: 700;
            font-size: 0.65rem;
            text-transform: uppercase;
            color: #1e3c72;
            border-bottom: 2px solid #e2e8f0;
            letter-spacing: 0.5px;
        }
        .exam-schedule-table td {
            padding: 8px 14px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .exam-schedule-table tbody tr {
            transition: background 0.2s;
        }
        .exam-schedule-table tbody tr:hover {
            background: #f8fafc;
        }
        .exam-schedule-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* ===== TABLE COLORS ===== */
        .exam-schedule-table .date-cell {
            font-weight: 600;
            color: #1e293b;
        }
        .exam-schedule-table .time-cell {
            color: #475569;
        }
        .exam-schedule-table .time-cell i {
            color: #3b82f6;
            margin-right: 4px;
        }
        .exam-schedule-table .subject-cell {
            font-weight: 600;
            color: #1e3c72;
        }
        .exam-schedule-table .teacher-cell {
            color: #64748b;
            font-size: 0.8rem;
        }
        .exam-schedule-table .teacher-cell i {
            color: #8b5cf6;
            margin-right: 4px;
        }

        /* ===== S.No ===== */
        .sno-cell {
            font-weight: 600;
            color: #94a3b8;
            font-size: 0.8rem;
            width: 40px;
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
           RESPONSIVE
           ============================================ */
        @media (max-width: 768px) {
            body {
                padding: 12px;
            }

            .exam-header {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
                padding: 12px 16px;
            }
            .exam-header h2 {
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

            .exam-card-header {
                flex-direction: column;
                text-align: center;
            }
            .exam-card-header h3 {
                justify-content: center;
                font-size: 0.85rem;
            }

            .exam-schedule-table {
                font-size: 0.75rem;
                min-width: 420px;
            }
            .exam-schedule-table th,
            .exam-schedule-table td {
                padding: 6px 10px;
            }
            .exam-schedule-table th {
                font-size: 0.6rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 8px;
            }

            .exam-header {
                padding: 10px 12px;
            }
            .exam-header h2 {
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

            .exam-card {
                border-radius: 8px;
                margin-bottom: 12px;
            }
            .exam-card-header {
                padding: 10px 14px;
            }
            .exam-card-header h3 {
                font-size: 0.8rem;
            }
            .exam-card-header .exam-badge {
                font-size: 0.55rem;
                padding: 2px 10px;
            }

            .exam-schedule-table {
                font-size: 0.65rem;
                min-width: 360px;
            }
            .exam-schedule-table th,
            .exam-schedule-table td {
                padding: 4px 8px;
            }
            .exam-schedule-table th {
                font-size: 0.55rem;
            }
            .exam-schedule-table .teacher-cell {
                font-size: 0.6rem;
            }

            .sno-cell {
                font-size: 0.65rem;
                width: 30px;
            }
        }

        @media (max-width: 380px) {
            .exam-schedule-table {
                font-size: 0.6rem;
                min-width: 300px;
            }
            .exam-schedule-table th,
            .exam-schedule-table td {
                padding: 3px 6px;
            }
            .exam-schedule-table th {
                font-size: 0.5rem;
            }
            .exam-schedule-table .teacher-cell {
                font-size: 0.55rem;
            }
        }

        /* ============================================
           PRINT
           ============================================ */
        @media print {
            .exam-header {
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
            .exam-card {
                border: 1px solid #000 !important;
                box-shadow: none !important;
                break-inside: avoid !important;
            }
            .exam-card-header {
                background: #1a365d !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .exam-schedule-table th {
                background: #e0e0e0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                border: 1px solid #000 !important;
            }
            .exam-schedule-table td {
                border: 1px solid #000 !important;
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
    <div class="exam-container">
        <!-- ===== HEADER ===== -->
        <div class="exam-header">
            <h2>
                <i class="fas fa-calendar-alt"></i>
                Exam Schedule
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

        <!-- ===== EXAM SCHEDULE ===== -->
        @if(count($processedSchedules) > 0)
            @foreach($processedSchedules as $schedule)
                @if(!empty($schedule['date_wise_data']))
                <div class="exam-card">
                    <!-- Card Header -->
                    <div class="exam-card-header">
                        <h3>
                            <i class="fas fa-pencil-alt"></i>
                            {{ $schedule['exam_title'] }}
                        </h3>
                        <span class="exam-badge">
                            <i class="fas fa-calendar-alt"></i>
                            {{ count($schedule['date_wise_data']) }} Days
                        </span>
                    </div>

                    <!-- Date-wise Table -->
                    <div class="exam-table-wrapper">
                        <table class="exam-schedule-table">
                            <thead>
                                <tr>
                                    <th width="40">#</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Subject</th>
                                    <th>Teacher</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedule['date_wise_data'] as $index => $data)
                                <tr>
                                    <td class="sno-cell">{{ $index + 1 }}</td>
                                    <td class="date-cell">
                                        <i class="fas fa-calendar-day" style="color:#3b82f6; margin-right:6px;"></i>
                                        {{ date('d-m-Y', strtotime($data['date'])) }}
                                    </td>
                                    <td class="time-cell">
                                        <i class="fas fa-clock"></i>
                                        {{ date('h:i A', strtotime($data['start_time'])) }}
                                        -
                                        {{ date('h:i A', strtotime($data['end_time'])) }}
                                    </td>
                                    <td class="subject-cell">
                                        <i class="fas fa-book-open" style="color:#f59e0b; margin-right:6px;"></i>
                                        {{ $data['subject_name'] }}
                                    </td>
                                    <td class="teacher-cell">
                                        <i class="fas fa-chalkboard-user"></i>
                                        {{ $data['teacher_name'] ?? 'N/A' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            @endforeach
        @else
        <div class="exam-card">
            <div class="no-data">
                <i class="fas fa-calendar-alt"></i>
                <p>No exam schedules found</p>
                <small>Exam schedules will appear here once created</small>
            </div>
        </div>
        @endif

        <!-- ===== FOOTER ===== -->
        <div style="margin-top:16px; background:#f8fafc; padding:10px 18px; border-radius:8px; font-size:0.7rem; color:#94a3b8; text-align:center; border:1px solid #e2e8f0;">
            <i class="fas fa-info-circle" style="color:#3b82f6;"></i>
            Exam schedule shows all upcoming and past exams for your reference.
        </div>
    </div>
</body>
</html>