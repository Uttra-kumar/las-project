<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
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
            padding: 16px;
        }
        .profile-container {
            max-width: 1200px;
            margin: 0 auto;
            animation: fadeIn 0.4s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* ===== HEADER ===== */
        .profile-header {
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
        .profile-header h2 {
            font-size: 1.1rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }
        .profile-header h2 i {
            color: #fbbf24;
            font-size: 1.2rem;
        }
        .back-btn {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.15);
            color: white;
            padding: 6px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
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
        .back-btn i {
            font-size: 0.9rem;
        }
        
        /* ===== PROFILE CARD ===== */
        .profile-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .profile-card-header {
            background: linear-gradient(135deg, #1a2332, #2c3e50);
            color: white;
            padding: 12px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }
        .profile-card-header h3 {
            font-size: 1rem;
            margin: 0;
            font-weight: 600;
        }
        .profile-badge {
            background: rgba(255,255,255,0.12);
            padding: 3px 14px;
            border-radius: 16px;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .profile-badge i {
            color: #fbbf24;
        }
        
        /* ===== PROFILE GRID ===== */
        .profile-grid {
            display: flex;
            flex-wrap: wrap;
        }
        .profile-left {
            flex: 0 0 300px;
            min-width: 250px;
            background: #f8fafc;
            padding: 18px;
            border-right: 1px solid #e2e8f0;
        }
        .profile-right {
            flex: 1;
            padding: 18px;
            min-width: 0;
        }
        
        /* ===== INFO CARDS ===== */
        .info-card {
            background: white;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 12px;
            border: 1px solid #e9ecef;
            transition: all 0.2s;
        }
        .info-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .info-title {
            font-size: 0.75rem;
            font-weight: 700;
            color: #1a2332;
            text-transform: uppercase;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1.5px solid #e9ecef;
            letter-spacing: 0.3px;
        }
        .info-title i {
            color: #3b82f6;
            margin-right: 6px;
            width: 18px;
            font-size: 0.85rem;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 0.85rem;
            border-bottom: 1px dashed #f1f3f5;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #64748b;
            font-size: 0.8rem;
        }
        .info-value {
            color: #1e293b;
            font-weight: 500;
            text-align: right;
            max-width: 60%;
            word-break: break-word;
            font-size: 0.85rem;
        }
        
        /* ===== TOP SECTION ===== */
        .top-section {
            display: flex;
            gap: 18px;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 2px solid #e9ecef;
            flex-wrap: wrap;
        }
        .avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #1a2332;
            background: #f1f5f9;
            flex-shrink: 0;
        }
        .avatar-placeholder {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2c3e50, #3498db);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            border: 3px solid #1a2332;
            flex-shrink: 0;
        }
        .top-info {
            flex: 1;
            min-width: 150px;
        }
        .top-info h3 {
            font-size: 1.15rem;
            color: #1e3c72;
            margin: 0 0 4px 0;
            font-weight: 700;
        }
        .top-info p {
            font-size: 0.85rem;
            color: #64748b;
            margin: 3px 0;
        }
        .top-info p i {
            width: 18px;
            color: #3b82f6;
        }
        
        /* ===== STATUS BADGE ===== */
        .status-badge {
            background: #dcfce7;
            color: #15803d;
            padding: 3px 14px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
        }
        .status-badge i {
            font-size: 0.5rem;
            margin-right: 4px;
        }
        .status-badge.inactive {
            background: #fee2e2;
            color: #dc2626;
        }
        
        /* ===== GRID 2 COLUMNS ===== */
        .info-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 12px;
        }
        
        /* ===== SUBJECTS TABLE ===== */
        .subjects-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
            margin-top: 8px;
        }
        .subjects-table th {
            padding: 8px 12px;
            background: #f1f3f5;
            font-weight: 700;
            border: 1px solid #dee2e6;
            font-size: 0.7rem;
            text-transform: uppercase;
            color: #1e293b;
            text-align: left;
        }
        .subjects-table td {
            padding: 7px 12px;
            border: 1px solid #dee2e6;
            vertical-align: middle;
            font-size: 0.85rem;
        }
        .subjects-table tr:nth-child(even) {
            background: #fafbfc;
        }
        .subjects-table tr:hover {
            background: #f1f5f9;
        }
        
        .subject-type-core {
            background: #dbeafe;
            color: #1e40af;
            padding: 2px 12px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
        }
        .subject-type-regular {
            background: #dcfce7;
            color: #15803d;
            padding: 2px 12px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .no-subjects {
            text-align: center;
            padding: 25px;
            color: #94a3b8;
        }
        .no-subjects i {
            font-size: 2.5rem;
            display: block;
            margin-bottom: 10px;
            color: #cbd5e1;
        }
        .no-subjects p {
            font-size: 0.9rem;
        }
        
        /* ============================================
           MOBILE RESPONSIVE - FONT SIZE BOOST
           ============================================ */
        
        /* ===== TABLET (max-width: 992px) ===== */
        @media (max-width: 992px) {
            .profile-left {
                flex: 0 0 260px;
                min-width: 200px;
                padding: 15px;
            }
            .info-row {
                font-size: 0.8rem;
            }
            .info-value {
                font-size: 0.8rem;
            }
            .subjects-table {
                font-size: 0.8rem;
            }
            .subjects-table td {
                font-size: 0.8rem;
            }
        }
        
        /* ===== MOBILE (max-width: 768px) ===== */
        @media (max-width: 768px) {
            body {
                padding: 12px;
            }
            
            .profile-header h2 {
                font-size: 1rem;
            }
            .back-btn {
                font-size: 0.8rem;
                height: 34px;
                padding: 5px 14px;
            }
            
            .profile-grid {
                flex-direction: column;
            }
            .profile-left {
                flex: 1;
                border-right: none;
                border-bottom: 1px solid #e2e8f0;
                padding: 15px;
            }
            .profile-right {
                padding: 15px;
            }
            
            .info-grid-2 {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .top-section {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 12px;
            }
            .top-info h3 {
                text-align: center;
                font-size: 1.1rem;
            }
            .top-info p {
                text-align: center;
                font-size: 0.85rem;
            }
            
            .avatar, .avatar-placeholder {
                width: 100px;
                height: 100px;
            }
            .avatar-placeholder {
                font-size: 2.8rem;
            }
            
            .profile-card-header h3 {
                font-size: 0.9rem;
            }
            .profile-badge {
                font-size: 0.7rem;
                padding: 3px 12px;
            }
            
            .info-title {
                font-size: 0.7rem;
            }
            .info-title i {
                font-size: 0.8rem;
            }
            .info-row {
                font-size: 0.85rem;
                padding: 5px 0;
            }
            .info-label {
                font-size: 0.8rem;
            }
            .info-value {
                font-size: 0.85rem;
                max-width: 55%;
            }
            
            .subjects-table {
                font-size: 0.85rem;
            }
            .subjects-table th {
                font-size: 0.7rem;
                padding: 7px 10px;
            }
            .subjects-table td {
                font-size: 0.85rem;
                padding: 6px 10px;
            }
            .subject-type-core,
            .subject-type-regular {
                font-size: 0.7rem;
                padding: 2px 10px;
            }
            
            .status-badge {
                font-size: 0.7rem;
                padding: 3px 12px;
            }
        }
        
        /* ===== SMALL MOBILE (max-width: 480px) ===== */
        @media (max-width: 480px) {
            body {
                padding: 8px;
            }
            
            .profile-header {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
                padding: 12px 14px;
            }
            .profile-header h2 {
                justify-content: center;
                font-size: 0.95rem;
            }
            .profile-header h2 i {
                font-size: 1rem;
            }
            .back-btn {
                justify-content: center;
                width: 100%;
                font-size: 0.8rem;
                height: 36px;
            }
            
            .profile-left {
                padding: 12px;
            }
            .profile-right {
                padding: 12px;
            }
            
            .info-card {
                padding: 10px 12px;
                margin-bottom: 10px;
            }
            .info-title {
                font-size: 0.7rem;
                margin-bottom: 6px;
            }
            .info-title i {
                font-size: 0.75rem;
                width: 16px;
            }
            .info-row {
                font-size: 0.8rem;
                padding: 4px 0;
            }
            .info-label {
                font-size: 0.75rem;
            }
            .info-value {
                font-size: 0.8rem;
                max-width: 55%;
            }
            
            .avatar, .avatar-placeholder {
                width: 85px;
                height: 85px;
            }
            .avatar-placeholder {
                font-size: 2.2rem;
            }
            
            .top-info h3 {
                font-size: 1rem;
            }
            .top-info p {
                font-size: 0.8rem;
            }
            
            .subjects-table {
                font-size: 0.8rem;
            }
            .subjects-table th {
                font-size: 0.65rem;
                padding: 5px 8px;
            }
            .subjects-table td {
                font-size: 0.8rem;
                padding: 5px 8px;
            }
            .subject-type-core,
            .subject-type-regular {
                font-size: 0.65rem;
                padding: 1px 8px;
            }
            
            .profile-card-header h3 {
                font-size: 0.85rem;
            }
            .profile-badge {
                font-size: 0.65rem;
                padding: 2px 10px;
            }
            
            .status-badge {
                font-size: 0.65rem;
                padding: 2px 10px;
            }
            
            .no-subjects i {
                font-size: 2rem;
            }
            .no-subjects p {
                font-size: 0.8rem;
            }
        }
        
        /* ===== EXTRA SMALL (max-width: 380px) ===== */
        @media (max-width: 380px) {
            body {
                padding: 6px;
            }
            
            .profile-header {
                padding: 10px 12px;
            }
            .profile-header h2 {
                font-size: 0.85rem;
            }
            .back-btn {
                font-size: 0.75rem;
                height: 32px;
                padding: 4px 12px;
            }
            
            .profile-left {
                padding: 10px;
            }
            .profile-right {
                padding: 10px;
            }
            
            .info-card {
                padding: 8px 10px;
                margin-bottom: 8px;
            }
            .info-title {
                font-size: 0.65rem;
            }
            .info-row {
                font-size: 0.75rem;
                flex-direction: column;
                align-items: flex-start;
                padding: 5px 0;
            }
            .info-value {
                text-align: left;
                max-width: 100%;
                width: 100%;
                font-size: 0.75rem;
            }
            .info-label {
                font-size: 0.7rem;
            }
            
            .avatar, .avatar-placeholder {
                width: 70px;
                height: 70px;
            }
            .avatar-placeholder {
                font-size: 1.8rem;
            }
            
            .top-info h3 {
                font-size: 0.9rem;
            }
            .top-info p {
                font-size: 0.75rem;
            }
            
            .subjects-table {
                font-size: 0.75rem;
            }
            .subjects-table th {
                font-size: 0.6rem;
                padding: 4px 6px;
            }
            .subjects-table td {
                font-size: 0.75rem;
                padding: 4px 6px;
            }
            .subject-type-core,
            .subject-type-regular {
                font-size: 0.6rem;
                padding: 1px 6px;
            }
            
            .profile-card-header h3 {
                font-size: 0.8rem;
            }
            .profile-badge {
                font-size: 0.6rem;
                padding: 2px 8px;
            }
            
            .status-badge {
                font-size: 0.6rem;
                padding: 2px 8px;
            }
        }
        
        /* ===== PRINT ===== */
        @media print {
            .profile-header {
                background: #1a365d !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                padding: 8px 14px !important;
            }
            .back-btn {
                display: none !important;
            }
            .profile-card {
                border: 1px solid #000 !important;
                box-shadow: none !important;
            }
            .profile-card-header {
                background: #1a2332 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .info-card {
                border: 1px solid #000 !important;
                break-inside: avoid !important;
            }
            .subjects-table th {
                background: #e0e0e0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .status-badge,
            .subject-type-core,
            .subject-type-regular {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .profile-left {
                background: #f8fafc !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .avatar {
                border: 2px solid #000 !important;
            }
            .avatar-placeholder {
                border: 2px solid #000 !important;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <!-- ===== HEADER ===== -->
        <div class="profile-header">
            <h2>
                <i class="fas fa-user-graduate"></i>
                Student Profile
            </h2>
            <button class="back-btn" onclick="history.back()">
                <i class="fas fa-arrow-left"></i> Back
            </button>
        </div>

        <!-- ===== PROFILE CARD ===== -->
        <div class="profile-card">
            <div class="profile-card-header">
                <h3>{{ $student->full_name }}</h3>
                <div class="profile-badge">
                    <i class="fas fa-calendar-alt"></i>
                    {{ $student->session->session_name ?? 'N/A' }}
                </div>
            </div>

            <div class="profile-grid">
                <!-- ===== LEFT COLUMN ===== -->
                <div class="profile-left">
                    <!-- Basic Info -->
                    <div class="info-card">
                        <div class="info-title"><i class="fas fa-id-card"></i> Basic Info</div>
                        <div class="info-row">
                            <span class="info-label">Student ID</span>
                            <span class="info-value">{{ $student->student_id }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Class</span>
                            <span class="info-value">{{ $student->class->class_name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Session</span>
                            <span class="info-value">{{ $student->session->session_name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status</span>
                            <span class="info-value">
                                <span class="status-badge">
                                    <i class="fas fa-circle"></i> Active
                                </span>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Contact -->
                    <div class="info-card">
                        <div class="info-title"><i class="fas fa-phone"></i> Contact</div>
                        <div class="info-row">
                            <span class="info-label">Mobile</span>
                            <span class="info-value">{{ $student->mobile }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $student->email ?? '-' }}</span>
                        </div>
                    </div>

                    <!-- Hostel -->
                    <div class="info-card">
                        <div class="info-title"><i class="fas fa-building"></i> Hostel Info</div>
                        <div class="info-row">
                            <span class="info-label">Hosteler</span>
                            <span class="info-value">
                                {{ $student->is_hosteler ? '🏠 Yes' : '🏡 No' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- ===== RIGHT COLUMN ===== -->
                <div class="profile-right">
                    <!-- Photo & Name -->
                    <div class="top-section">
                        @if($student->image)
                            <img src="{{ asset('storage/' . $student->image) }}" alt="Photo" class="avatar">
                        @else
                            <div class="avatar-placeholder">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                        @endif
                        <div class="top-info">
                            <h3>{{ $student->full_name }}</h3>
                            <p>
                                <i class="fas fa-chalkboard-user"></i>
                                {{ $student->class->class_name ?? 'N/A' }}
                            </p>
                            <p>
                                <i class="fas fa-calendar-alt"></i>
                                {{ $student->dob ? date('d-m-Y', strtotime($student->dob)) : '-' }}
                            </p>
                            <p>
                                <i class="fas fa-venus-mars"></i>
                                {{ $student->gender ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <!-- Personal Details - 2 Column Grid -->
                    <div class="info-grid-2">
                        <div class="info-card">
                            <div class="info-title"><i class="fas fa-user"></i> Personal</div>
                            <div class="info-row">
                                <span class="info-label">Father</span>
                                <span class="info-value">{{ $student->father_name ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Mother</span>
                                <span class="info-value">{{ $student->mother_name ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">DOB</span>
                                <span class="info-value">{{ $student->dob ? date('d-m-Y', strtotime($student->dob)) : '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Gender</span>
                                <span class="info-value">{{ $student->gender ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-title"><i class="fas fa-map-marker-alt"></i> Address</div>
                            <div class="info-row">
                                <span class="info-label">Address</span>
                                <span class="info-value">{{ $student->address ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">City</span>
                                <span class="info-value">{{ $student->city ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">State</span>
                                <span class="info-value">{{ $student->state ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Pincode</span>
                                <span class="info-value">{{ $student->pincode ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Subjects -->
                    <div class="info-card">
                        <div class="info-title"><i class="fas fa-book-open"></i> Subjects</div>
                        @if($subjectsList->count() > 0)
                        <div style="overflow-x:auto;">
                            <table class="subjects-table">
                                <thead>
                                    <tr>
                                        <th width="40">#</th>
                                        <th>Subject</th>
                                        <th width="100">Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subjectsList as $key => $subject)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td><strong>{{ $subject->subject_name }}</strong></td>
                                        <td>
                                            @if($student->is_stream == 1)
                                                <span class="subject-type-core">Core</span>
                                            @else
                                                <span class="subject-type-regular">Regular</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="no-subjects">
                            <i class="fas fa-book-open"></i>
                            <p>No subjects assigned</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>