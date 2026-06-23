<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ===== RESET & BASE ===== */
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
        
        .parent-container {
            animation: fadeIn 0.3s ease;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* ===== HEADER ===== */
        .parent-header {
            background: linear-gradient(135deg, #1a365d 0%, #2d4a7a 50%, #1a365d 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            box-shadow: 0 2px 8px rgba(26, 54, 93, 0.25);
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .header-left .school-logo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255,255,255,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            border: 2px solid rgba(255,255,255,0.15);
            flex-shrink: 0;
        }
        .header-left .school-logo img {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            object-fit: cover;
        }
        .header-left .school-info .school-name {
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .header-left .school-info .school-tagline {
            font-size: 0.85rem;
            opacity: 0.8;
        }
        
        .header-right {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }
        .header-right .session-badge {
            background: rgba(255,255,255,0.12);
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.7rem;
            border: 1px solid rgba(255,255,255,0.08);
            font-weight: 600;
        }
        .header-right .logout-btn {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.15);
            color: white;
            padding: 6px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
            font-weight: 600;
        }
        .header-right .logout-btn:hover {
            background: rgba(255,255,255,0.25);
            transform: translateX(-2px);
        }
        
        /* ===== STUDENT INFO CARD ===== */
        .student-info-card {
            background: white;
            border-radius: 12px;
            padding: 14px 20px;
            margin-bottom: 16px;
            border: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            border-left: 4px solid #1e3c72;
        }
        .student-info-card .student-left {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }
        .student-info-card .student-avatar {
            width: 48px;
            height: 48px;
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
        .student-info-card .student-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        .student-info-card .student-details .student-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1e3c72;
        }
        .student-info-card .student-details .student-class {
            font-size: 0.8rem;
            color: #64748b;
        }
        .student-info-card .student-details .student-class i {
            color: #3b82f6;
            margin-right: 4px;
        }
        .student-info-card .student-badge {
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
        
        /* ===== STATS ROW ===== */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.06);
        }
        .stat-card .icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .stat-card .icon.primary { background: #dbeafe; color: #1e40af; }
        .stat-card .icon.success { background: #dcfce7; color: #15803d; }
        .stat-card .icon.warning { background: #fef3c7; color: #b45309; }
        .stat-card .icon.info { background: #e0e7ff; color: #4338ca; }
        .stat-card .icon.purple { background: #f3e8ff; color: #7c3aed; }
        
        .stat-card .info .number { font-size: 1.2rem; font-weight: 700; color: #1e3c72; }
        .stat-card .info .label { font-size: 0.55rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; }
        
        /* ===== MENU GRID ===== */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 10px;
            margin-bottom: 16px;
        }
        .menu-card {
            background: white;
            border-radius: 10px;
            padding: 12px 14px;
            border: 1px solid #e2e8f0;
            text-align: center;
            text-decoration: none;
            color: #1e3c72;
            transition: all 0.3s;
            cursor: pointer;
        }
        .menu-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            border-color: #1e3c72;
        }
        .menu-card i { font-size: 1.6rem; color: #1e3c72; display: block; margin-bottom: 6px; }
        .menu-card span { font-size: 0.65rem; font-weight: 600; }
        
        /* ============================================
           NOTICES SECTION - IMPROVED
           ============================================ */
        .notices-wrapper {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            margin-bottom: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        
        .notices-header {
            background: #f8fafc;
            padding: 12px 18px;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .notices-header h4 {
            font-size: 0.85rem;
            color: #1e3c72;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
        }
        .notices-header h4 i {
            color: #f59e0b;
        }
        .notices-header .notice-count {
            font-size: 0.55rem;
            background: #1e3c72;
            color: white;
            padding: 1px 12px;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .notices-body {
            padding: 8px 0;
        }
        
        .notice-item {
            padding: 12px 18px;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.2s;
        }
        .notice-item:last-child {
            border-bottom: none;
        }
        .notice-item:hover {
            background: #fafbfc;
        }
        
        .notice-item .notice-date {
           font-size: 0.6rem;
            color: #fff;
            font-weight: 600;
            display: inline-block;
            background: #000;
            padding: 1px 10px;
            border-radius: 12px;
            margin-bottom: 4px;
            font-weight: bold;
        }
        .notice-item .notice-date i {
            margin-right: 3px;
            color: #3b82f6;
        }
        
        .notice-item .notice-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #1e293b;
            display: block;
            margin-bottom: 4px;
        }
        
        .notice-item .notice-desc {
            font-size: 0.85rem;
            color: #64748b;
            line-height: 1.5;
            display: block;
        }
        
        .notice-item .notice-desc .read-more {
            color: #3b82f6;
            cursor: pointer;
            font-weight: 600;
            margin-left: 4px;
        }
        .notice-item .notice-desc .read-more:hover {
            text-decoration: underline;
        }
        
        .no-notices {
            text-align: center;
            padding: 30px 20px;
            color: #94a3b8;
        }
        .no-notices i {
            font-size: 2rem;
            display: block;
            margin-bottom: 8px;
            color: #cbd5e1;
        }
        .no-notices p {
            font-size: 0.8rem;
        }
        
        /* ===== FOOTER ===== */
        .footer-info {
            margin-top: 16px;
            background: white;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 0.65rem;
            color: #94a3b8;
            text-align: center;
            border: 1px solid #e2e8f0;
        }
        .footer-info i {
            color: #3b82f6;
            margin-right: 4px;
        }
        
        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 768px) {
            .parent-container {
                padding: 12px;
            }
            .parent-header {
                flex-direction: column;
                align-items: flex-start;
                padding: 12px 16px;
            }
            .header-left .school-info .school-name {
                font-size: 0.9rem;
            }
            .header-left .school-logo {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }
            .header-right {
                width: 100%;
                justify-content: space-between;
            }
            .student-info-card {
                flex-direction: column;
                align-items: flex-start;
            }
            .stats-row {
                grid-template-columns: 1fr 1fr;
            }
            .menu-grid {
                grid-template-columns: 1fr 1fr 1fr;
            }
            .notice-item .notice-title {
                font-size: 0.8rem;
            }
            .notice-item .notice-desc {
                font-size: 0.75rem;
            }
        }
        
        @media (max-width: 480px) {
            .parent-container {
                padding: 8px;
            }
            .stats-row {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }
            .stat-card {
                padding: 10px 12px;
            }
            .stat-card .info .number {
                font-size: 1rem;
            }
            .stat-card .icon {
                width: 34px;
                height: 34px;
                font-size: 0.85rem;
            }
            .menu-grid {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }
            .menu-card {
                padding: 10px 10px;
            }
            .menu-card i {
                font-size: 1.3rem;
            }
            .menu-card span {
                font-size: 0.6rem;
            }
            .student-info-card .student-details .student-name {
                font-size: 0.9rem;
            }
            .student-info-card .student-avatar {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
            .notices-header {
                padding: 10px 14px;
            }
            .notices-header h4 {
                font-size: 0.75rem;
                font-weight: bold;
            }
            .notice-item {
                padding: 10px 14px;
            }
            .notice-item .notice-title {
                font-size: 0.75rem;
                font-weight: bold;
            }
            .notice-item .notice-desc {
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="parent-container">
        
        <!-- ===== HEADER ===== -->
        <div class="parent-header">
            <div class="header-left">
                <div class="school-logo">
                    @if(isset($school) && $school->logo_1)
                        <img src="{{ asset('storage/' . $school->logo_1) }}" alt="School Logo">
                    @else
                        <i class="fas fa-school"></i>
                    @endif
                </div>
                <div class="school-info">
                    <div class="school-name">
                        {{ $school->school_name ?? 'School Management System' }}
                    </div>
                    <div class="school-tagline">
                        <i class="fas fa-map-marker-alt"></i> 
                        {{ $school->address ?? 'School Address' }}
                    </div>
                </div>
            </div>
            <div class="header-right">
                <span class="session-badge">
                    <i class="fas fa-calendar-alt"></i> {{ $sessionName ?? 'N/A' }}
                </span>
                <form method="POST" action="{{ route('parent.logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- ===== STUDENT INFO ===== -->
        @if(isset($student))
        <div class="student-info-card">
            <div class="student-left">
                <div class="student-avatar">
                    @if($student->image)
                        <img src="{{ asset('storage/' . $student->image) }}" alt="{{ $student->full_name }}">
                    @else
                        {{ substr($student->full_name, 0, 1) }}
                    @endif
                </div>
                <div class="student-details">
                    <div class="student-name">{{ $student->full_name }}</div>
                    <div class="student-class">
                        <i class="fas fa-school"></i> {{ $student->class->class_name ?? 'N/A' }}
                        @if($student->stream)
                            | <i class="fas fa-code-branch"></i> {{ $student->stream->stream_name }}
                        @endif
                    </div>
                </div>
            </div>
            <div>
                <span class="student-badge">
                    <i class="fas fa-id-card"></i> {{ $student->student_id }}
                </span>
            </div>
        </div>
        @endif

        <!-- ===== STATS ===== -->
       

        <!-- ===== MENU ===== -->
        <div class="menu-grid">
            <a href="{{ route('parent.fees') }}" class="menu-card">
                <i class="fas fa-money-bill-wave"></i>
                <span>Fees</span>
            </a>
            <a href="{{ route('parent.exam') }}" class="menu-card">
                <i class="fas fa-pencil-alt"></i>
                <span>Exam Schedule</span>
            </a>
            <a href="{{ route('parent.result') }}" class="menu-card">
                <i class="fas fa-chart-bar"></i>
                <span>Result</span>
            </a>
            <a href="{{ url('/parent/profile/'. session('student_id')) }}" class="menu-card">
                <i class="fas fa-user-circle"></i>
                <span>Profile</span>
            </a>
           <!--  <a href="javascript:void(0)" onclick="showComingSoon()" class="menu-card">
                <i class="fas fa-calendar-alt"></i>
                <span>Attendance</span>
            </a> -->
        </div>

        <!-- ============================================
        NOTICES SECTION - IMPROVED
        ============================================ -->
        <div class="notices-wrapper">
            <div class="notices-header">
                <h4>
                    <i class="fas fa-bullhorn"></i> Notices
                    <span class="notice-count">{{ count($notices) }}</span>
                </h4>
            </div>
            
            <div class="notices-body">
                @if(count($notices) > 0)
                    @foreach($notices as $notice)
                    <div class="notice-item">
                        <span class="notice-date">
                            <i class="fas fa-calendar-day"></i> {{ $notice['date'] }}
                        </span>
                        <span class="notice-title">{{ $notice['title'] }}</span>
                        @if(isset($notice['description']) && $notice['description'])
                            <span class="notice-desc">
                                {{ Str::limit($notice['description'], 120) }}
                                @if(strlen($notice['description']) > 120)
                                    <span class="read-more" onclick="viewNotice('{{ addslashes($notice['title']) }}', '{{ addslashes($notice['description']) }}')">
                                        Read More
                                    </span>
                                @endif
                            </span>
                        @endif
                    </div>
                    @endforeach
                @else
                    <div class="no-notices">
                        <i class="fas fa-bullhorn"></i>
                        <p>No notices available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- ===== FOOTER ===== -->
        <div class="footer-info">
            <i class="fas fa-info-circle"></i> 
            Welcome to Parent Dashboard. Your child's academic journey is here.
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showComingSoon() {
            Swal.fire({
                title: 'Coming Soon!',
                text: 'This feature is under development.',
                icon: 'info',
                confirmButtonColor: '#1e3c72',
                timer: 1500,
                showConfirmButton: false
            });
        }

        function viewNotice(title, description) {
            Swal.fire({
                title: title,
                text: description,
                icon: 'info',
                confirmButtonColor: '#1e3c72',
                confirmButtonText: 'OK'
            });
        }
    </script>
</body>
</html>