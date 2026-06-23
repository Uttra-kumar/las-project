@extends('layouts.app')

@section('title', 'Student Profile')

@section('content')
<style>
    /* ===== PRINT STYLES - PROFESSIONAL A4 FORMAT ===== */
    @media print {
        /* Hide all unnecessary elements */
        .sidebar, .top-header, .menu-toggle, .print-btn, .no-print, 
        .action-btns, .btn-download-qr, header, nav, .navbar, 
        .footer, .breadcrumb, .btn-group, .close-btn {
            display: none !important;
        }
        
        /* Page setup - A4 with professional margins */
        @page {
            size: A4;
            margin: 8mm 6mm;
        }
        
        /* Reset body */
        body {
            background: white !important;
            padding: 0 !important;
            margin: 0 !important;
            display: block !important;
            min-height: auto !important;
            font-family: 'Segoe UI', Arial, sans-serif !important;
        }
        
        /* Main container */
        .main-content, .page-content, .profile-wrapper {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            display: block !important;
        }
        
        /* Profile card - clean professional look */
        .profile-card {
            box-shadow: none !important;
            border: 1px solid #2c3e50 !important;
            border-radius: 0 !important;
            page-break-inside: avoid !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            background: white !important;
        }
        
        /* ===== HEADER ===== */
        .profile-header {
            background: #1a2332 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            padding: 6px 15px !important;
            border-bottom: 3px solid #3498db !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
        }
        
        .profile-header h2 {
            font-size: 11pt !important;
            margin: 0 !important;
            color: white !important;
            font-weight: 600 !important;
            letter-spacing: 0.5px !important;
        }
        
        .profile-header h2 i {
            margin-right: 6px !important;
        }
        
        .profile-badge {
            background: rgba(255,255,255,0.15) !important;
            padding: 2px 12px !important;
            border-radius: 20px !important;
            font-size: 8pt !important;
            color: #ecf0f1 !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
        }
        
        .stream-badge {
            font-size: 8pt !important;
            padding: 1px 8px !important;
            background: rgba(52, 152, 219, 0.3) !important;
            border-radius: 12px !important;
            color: #85c1e9 !important;
            margin-left: 8px !important;
        }
        
        /* ===== GRID LAYOUT ===== */
        .profile-grid {
            display: flex !important;
            flex-wrap: nowrap !important;
            width: 100% !important;
        }
        
        /* Left Column - 28% */
        .profile-left {
            flex: 0 0 28% !important;
            max-width: 28% !important;
            background: #f8f9fa !important;
            padding: 8px 10px !important;
            border-right: 1px solid #dee2e6 !important;
            display: flex !important;
            flex-direction: column !important;
        }
        
        /* Right Column - 72% */
        .profile-right {
            flex: 1 !important;
            padding: 8px 12px !important;
            width: 72% !important;
            background: white !important;
        }
        
        /* ===== IMAGE + QR SECTION ===== */
        .top-right-section {
            display: flex !important;
            gap: 15px !important;
            align-items: center !important;
            margin-bottom: 8px !important;
            padding-bottom: 8px !important;
            border-bottom: 2px solid #e9ecef !important;
        }
        
        .avatar-corner {
            text-align: center !important;
            flex: 0 0 auto !important;
        }
        
        .student-image {
            width: 72px !important;
            height: 72px !important;
            object-fit: cover !important;
            border-radius: 50% !important;
            border: 3px solid #1a2332 !important;
        }
        
        .default-avatar {
            width: 72px !important;
            height: 72px !important;
            background: linear-gradient(135deg, #2c3e50, #3498db) !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 28pt !important;
            color: white !important;
            margin: 0 auto !important;
            border: 3px solid #1a2332 !important;
        }
        
        .qr-section {
            text-align: center !important;
            flex: 0 0 auto !important;
        }
        
        .qr-section .qr-image {
            width: 72px !important;
            height: 72px !important;
            display: block !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 4px !important;
            padding: 3px !important;
            background: white !important;
        }
        
        .qr-section p {
            display: none !important;
        }
        
        /* ===== INFO CARDS ===== */
        .info-card {
            border: 1px solid #e9ecef !important;
            padding: 4px 8px !important;
            margin-bottom: 4px !important;
            page-break-inside: avoid !important;
            background: white !important;
            border-radius: 0 !important;
        }
        
        .info-title {
            font-size: 7pt !important;
            font-weight: 700 !important;
            color: #1a2332 !important;
            text-transform: uppercase !important;
            margin-bottom: 3px !important;
            padding-bottom: 2px !important;
            border-bottom: 1px solid #e9ecef !important;
            letter-spacing: 0.5px !important;
        }
        
        .info-title i {
            margin-right: 4px !important;
            color: #3498db !important;
        }
        
        .info-row {
            display: flex !important;
            justify-content: space-between !important;
            padding: 1.5px 0 !important;
            font-size: 7.5pt !important;
            border-bottom: 1px dashed #f1f3f5 !important;
        }
        
        .info-row:last-child {
            border-bottom: none !important;
        }
        
        .info-label {
            font-weight: 600 !important;
            color: #495057 !important;
            font-size: 7pt !important;
        }
        
        .info-value {
            color: #212529 !important;
            font-weight: 500 !important;
            text-align: right !important;
            font-size: 7.5pt !important;
        }
        
        /* ===== PERSONAL GRID ===== */
        .personal-grid {
            display: flex !important;
            gap: 8px !important;
            margin-bottom: 6px !important;
        }
        
        .personal-grid .info-card {
            flex: 1 !important;
            margin: 0 !important;
        }
        
        /* ===== SUBJECTS TABLE ===== */
        .subjects-section {
            margin-top: 6px !important;
        }
        
        .subjects-title {
            font-size: 7.5pt !important;
            font-weight: 700 !important;
            color: #1a2332 !important;
            margin-bottom: 4px !important;
            padding-bottom: 2px !important;
            border-bottom: 2px solid #1a2332 !important;
            display: flex !important;
            align-items: center !important;
            gap: 6px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }
        
        .subjects-title i {
            color: #3498db !important;
        }
        
        .subjects-table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 7pt !important;
        }
        
        .subjects-table th {
            text-align: left !important;
            padding: 3px 6px !important;
            background: #f1f3f5 !important;
            font-weight: 700 !important;
            border: 1px solid #dee2e6 !important;
            font-size: 6.5pt !important;
            text-transform: uppercase !important;
            color: #495057 !important;
        }
        
        .subjects-table td {
            padding: 2.5px 6px !important;
            border: 1px solid #dee2e6 !important;
            font-size: 7pt !important;
            color: #212529 !important;
        }
        
        .subjects-table tr:nth-child(even) td {
            background: #fafbfc !important;
        }
        
        /* ===== PREVIOUS ACADEMIC ===== */
        .subjects-section + .subjects-section {
            margin-top: 4px !important;
        }
        
        .subjects-section + .subjects-section .info-card {
            padding: 3px 6px !important;
        }
        
        .subjects-section + .subjects-section .info-row {
            padding: 1px 0 !important;
            font-size: 7pt !important;
        }
        
        /* ===== STATUS BADGE ===== */
        .status-badge {
            background: #d4edda !important;
            color: #155724 !important;
            padding: 1px 8px !important;
            border-radius: 12px !important;
            font-size: 6.5pt !important;
            font-weight: 600 !important;
            display: inline-block !important;
        }
        
        .subject-type-badge {
            padding: 1px 8px !important;
            border-radius: 10px !important;
            font-size: 6pt !important;
            font-weight: 600 !important;
        }
        
        .subject-type-core {
            background: #dbeafe !important;
            color: #1e40af !important;
        }
        
        .subject-type-regular {
            background: #d4edda !important;
            color: #155724 !important;
        }
        
        /* ===== ADDRESS WRAP ===== */
        .address-wrap {
            word-wrap: break-word !important;
            max-width: 100px !important;
        }
        
        /* ===== FOOTER LINE ===== */
        .print-footer {
            display: block !important;
            text-align: center !important;
            font-size: 6pt !important;
            color: #868e96 !important;
            padding-top: 4px !important;
            margin-top: 4px !important;
            border-top: 1px solid #e9ecef !important;
            letter-spacing: 0.3px !important;
        }
        
        /* Hide scrollbars */
        ::-webkit-scrollbar {
            display: none !important;
        }
        
        /* Force single page */
        .profile-card, body, html {
            max-height: 100% !important;
            overflow: visible !important;
        }
        
        /* Generate footer */
        .print-footer:after {
            content: "Generated on " attr(data-date) " | This is a computer generated document.";
        }
    }
    
    /* ===== SCREEN STYLES ===== */
    .profile-wrapper {
        max-width: 1200px;
        width: 100%;
        margin: 0 auto;
    }

    .print-btn {
        background: linear-gradient(135deg, #1a2332, #2c3e50);
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        font-weight: 600;
        color: white;
        font-size: 0.75rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
        border: 1px solid #3498db;
        transition: all 0.3s;
    }
    
    .print-btn:hover {
        background: linear-gradient(135deg, #2c3e50, #1a2332);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .profile-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    }

    .profile-header {
        background: linear-gradient(135deg, #1a2332, #2c3e50);
        color: white;
        padding: 14px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .profile-header h2 {
        font-size: 1.1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .profile-badge {
        background: rgba(255,255,255,0.15);
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 0.7rem;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .profile-grid {
        display: flex;
        flex-wrap: wrap;
    }

    .profile-left {
        flex: 0.8;
        min-width: 250px;
        background: #f8fafc;
        padding: 18px;
        border-right: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
    }

    .top-right-section {
        display: flex;
        gap: 25px;
        align-items: center;
        margin-bottom: 18px;
        padding-bottom: 18px;
        border-bottom: 2px solid #e9ecef;
    }

    .avatar-corner {
        text-align: center;
        flex: 0 0 auto;
    }

    .student-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #1a2332;
    }

    .default-avatar {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #2c3e50, #3498db);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        color: white;
        margin: 0 auto;
        border: 3px solid #1a2332;
    }

    .qr-section {
        text-align: center;
        flex: 0 0 auto;
    }

    .qr-section .qr-image {
        width: 100px;
        height: 100px;
        margin: 0 auto;
        display: block;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 5px;
        background: white;
    }

    .qr-section p {
        font-size: 0.6rem;
        color: #94a3b8;
        margin-top: 4px;
    }

    .btn-download-qr {
        background: #1a2332;
        color: white;
        border: none;
        padding: 4px 14px;
        border-radius: 4px;
        font-size: 0.6rem;
        cursor: pointer;
        margin-top: 4px;
        transition: all 0.3s;
    }
    
    .btn-download-qr:hover {
        background: #2c3e50;
    }

    .info-card {
        background: white;
        border-radius: 8px;
        padding: 12px 14px;
        margin-bottom: 10px;
        border: 1px solid #e9ecef;
    }

    .info-title {
        font-size: 0.7rem;
        font-weight: 700;
        color: #1a2332;
        text-transform: uppercase;
        margin-bottom: 8px;
        padding-bottom: 4px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .info-title i {
        color: #3498db;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 4px 0;
        font-size: 0.75rem;
        border-bottom: 1px dashed #f1f3f5;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #495057;
    }

    .info-value {
        color: #212529;
        font-weight: 500;
        text-align: right;
    }

    .profile-right {
        flex: 2.2;
        padding: 18px 20px;
        background: white;
    }

    .stream-badge {
        display: inline-block;
        background: rgba(52, 152, 219, 0.15);
        color: #2471a3;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 0.7rem;
        margin-left: 8px;
    }

    .subjects-section {
        margin-top: 15px;
    }

    .subjects-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: #1a2332;
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 2px solid #1a2332;
        display: flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
    }
    
    .subjects-title i {
        color: #3498db;
    }

    .subjects-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.75rem;
    }

    .subjects-table th {
        text-align: left;
        padding: 8px 12px;
        background: #f1f3f5;
        font-weight: 700;
        border: 1px solid #dee2e6;
        font-size: 0.65rem;
        text-transform: uppercase;
        color: #495057;
    }

    .subjects-table td {
        padding: 6px 12px;
        border: 1px solid #dee2e6;
    }
    
    .subjects-table tbody tr:nth-child(even) {
        background: #fafbfc;
    }

    .status-badge {
        background: #d4edda;
        color: #155724;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .subject-type-badge {
        padding: 2px 10px;
        border-radius: 10px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    
    .subject-type-core {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .subject-type-regular {
        background: #d4edda;
        color: #155724;
    }

    .address-wrap {
        word-wrap: break-word;
        max-width: 150px;
    }
    
    .print-footer {
        display: none;
    }

    @media (max-width: 768px) {
        .profile-grid {
            flex-direction: column;
        }
        .profile-left {
            border-right: none;
            border-bottom: 1px solid #e2e8f0;
        }
        .subjects-table {
            font-size: 0.65rem;
        }
        .top-right-section {
            flex-direction: column;
            align-items: center;
        }
        .personal-grid {
            flex-direction: column;
        }
        .qr-section .qr-image {
            width: 100px;
            height: 100px;
        }
    }
</style>

<div class="profile-wrapper">
    

    <div class="profile-card">
        <!-- Header -->
        <div class="profile-header">
            <h2>
                <i class="fas fa-id-card"></i> Student Profile
                @if($student->is_stream == 1)
                <span class="stream-badge">
                    <i class="fas fa-code-branch"></i> {{ $student->stream->stream_name ?? 'N/A' }}
                </span>
                @endif
            </h2>
            <div class="profile-badge">
                <i class="fas fa-calendar-alt"></i> {{ $student->session->session_name ?? 'N/A' }}
            </div>
        </div>

        <div class="profile-grid">
            <!-- ===== LEFT COLUMN ===== -->
            <div class="profile-left">
                <!-- Basic Info -->
                <div class="info-card">
                    <div class="info-title"><i class="fas fa-id-card"></i> Basic Information</div>
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
                            <span class="status-badge">● Active</span>
                        </span>
                    </div>
                </div>

                <!-- Contact -->
                <div class="info-card">
                    <div class="info-title"><i class="fas fa-phone"></i> Contact Details</div>
                    <div class="info-row">
                        <span class="info-label">Mobile</span>
                        <span class="info-value">{{ $student->mobile }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $student->email ?? '-' }}</span>
                    </div>
                </div>

                <!-- Admission -->
                <div class="info-card">
                    <div class="info-title"><i class="fas fa-calendar-alt"></i> Admission Details</div>
                    <div class="info-row">
                        <span class="info-label">Admission Date</span>
                        <span class="info-value">{{ $student->created_at ? date('d M Y', strtotime($student->created_at)) : 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Hosteler</span>
                        <span class="info-value">{{ $student->is_hosteler ? 'Yes' : 'No' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Category</span>
                        <span class="info-value">{{ $student->category ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- ===== RIGHT COLUMN ===== -->
            <div class="profile-right">
                <!-- Image + QR -->
                <div class="top-right-section">
                    <div class="avatar-corner">
                        @if($student->image)
                        <img src="{{ asset('storage/' . $student->image) }}" alt="Photo" class="student-image">
                        @else
                        <div class="default-avatar">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        @endif
                    </div>

                    <div class="qr-section">
                        @if(isset($qrCode))
                            <img src="{{ $qrCode }}" alt="QR Code" class="qr-image">
                        @else
                            <i class="fas fa-qrcode" style="font-size: 3rem; color: #94a3b8;"></i>
                        @endif
                        <p>Scan to get student details</p>
                        <button class="btn-download-qr no-print" onclick="downloadQR('{{ $qrCode }}', '{{ $student->student_id }}')">
                            <i class="fas fa-download"></i> Download QR
                        </button>
                    </div>
                </div>

                <!-- Personal Details -->
                <div class="personal-grid">
                    <div class="info-card">
                        <div class="info-title"><i class="fas fa-user"></i> Personal Details</div>
                        <div class="info-row">
                            <span class="info-label">Full Name</span>
                            <span class="info-value">{{ $student->full_name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Father's Name</span>
                            <span class="info-value">{{ $student->father_name ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Mother's Name</span>
                            <span class="info-value">{{ $student->mother_name ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Date of Birth</span>
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
                            <span class="info-value address-wrap">{{ $student->address }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">City</span>
                            <span class="info-value">{{ $student->city }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">State</span>
                            <span class="info-value">{{ $student->state }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Pincode</span>
                            <span class="info-value">{{ $student->pincode }}</span>
                        </div>
                    </div>
                </div>

                <!-- Subjects -->
                <div class="subjects-section">
                    <div class="subjects-title">
                        <i class="fas fa-book-open"></i> 
                        @if($student->is_stream == 1)
                            Subjects ({{ $student->stream->stream_name ?? 'Stream' }})
                        @else
                            Subjects ({{ $student->class->class_name ?? 'Class' }})
                        @endif
                    </div>
                    <table class="subjects-table">
                        <thead>
                            <tr>
                                <th width="40">#</th>
                                <th>Subject Name</th>
                                <th width="80">Code</th>
                                <th width="70">Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subjectsList as $key => $subject)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><strong>{{ $subject->subject_name }}</strong></td>
                                <td>{{ $subject->subject_id ?? 'N/A' }}</td>
                                <td>
                                    @if($student->is_stream == 1)
                                        <span class="subject-type-badge subject-type-core">Core</span>
                                    @else
                                        <span class="subject-type-badge subject-type-regular">Regular</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 12px;">
                                    <i class="fas fa-info-circle"></i> No subjects assigned
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Previous Academic -->
                @if($student->previous_institute || $student->previous_class || $student->previous_marks)
                <div class="subjects-section" style="margin-top: 6px;">
                    <div class="subjects-title">
                        <i class="fas fa-graduation-cap"></i> Previous Academic Record
                    </div>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        @if($student->previous_institute)
                        <div class="info-card" style="flex:1; margin:0;">
                            <div class="info-row">
                                <span class="info-label">Institute</span>
                                <span class="info-value">{{ $student->previous_institute }}</span>
                            </div>
                        </div>
                        @endif
                        @if($student->previous_class)
                        <div class="info-card" style="flex:1; margin:0;">
                            <div class="info-row">
                                <span class="info-label">Previous Class</span>
                                <span class="info-value">{{ $student->previous_class }}</span>
                            </div>
                        </div>
                        @endif
                        @if($student->previous_result)
                        <div class="info-card" style="flex:1; margin:0;">
                            <div class="info-row">
                                <span class="info-label">Result</span>
                                <span class="info-value">{{ $student->previous_result }}</span>
                            </div>
                        </div>
                        @endif
                        @if($student->previous_marks)
                        <div class="info-card" style="flex:1; margin:0;">
                            <div class="info-row">
                                <span class="info-label">Marks</span>
                                <span class="info-value">{{ $student->previous_marks }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Print Footer -->
        <div class="print-footer" data-date="{{ date('d-m-Y H:i') }}"></div>
    </div>
</div>

<script>
function downloadQR(qrUrl, studentId) {
    fetch(qrUrl)
        .then(response => response.blob())
        .then(blob => {
            const link = document.createElement('a');
            link.download = `student-${studentId}-qr.png`;
            link.href = URL.createObjectURL(blob);
            link.click();
            URL.revokeObjectURL(link.href);
        });
}
</script>
@endsection