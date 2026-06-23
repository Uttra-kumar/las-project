@extends('layouts.app')

@section('title', 'Teacher Profile')

@section('content')
<style>
    .profile-wrapper {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Print Button */
    .print-btn {
        background: linear-gradient(105deg, #1e3c72, #2b4c7c);
        border: none;
        padding: 6px 16px;
        border-radius: 6px;
        font-weight: 600;
        color: white;
        font-size: 0.75rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 15px;
    }

    /* Profile Card */
    .profile-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    /* Two Column Layout */
    .profile-grid {
        display: flex;
        flex-wrap: wrap;
    }

    /* LEFT COLUMN */
    .profile-left {
        flex: 0.8;
        min-width: 250px;
        background: #f8fafc;
        padding: 16px;
        border-right: 1px solid #e2e8f0;
    }

    /* Image */
    .avatar-corner {
        text-align: center;
        margin-bottom: 15px;
    }
    .teacher-image {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #1e3c72;
    }
    .default-avatar {
        width: 120px;
        height: 120px;
        background: linear-gradient(145deg, #2c7da0, #1e4a76);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: white;
        margin: 0 auto;
    }

    /* Left Info Cards */
    .info-card {
        background: white;
        border-radius: 8px;
        padding: 10px 12px;
        margin-bottom: 12px;
        border: 1px solid #e2e8f0;
    }
    .info-title {
        font-size: 0.7rem;
        font-weight: 700;
        color: #1e3c72;
        text-transform: uppercase;
        margin-bottom: 8px;
        padding-bottom: 4px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        font-size: 0.7rem;
        border-bottom: 1px dashed #eef2f8;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        font-weight: 600;
        color: #64748b;
    }
    .info-value {
        color: #1e293b;
        font-weight: 500;
        text-align: right;
    }

    /* RIGHT COLUMN */
    .profile-right {
        flex: 2.2;
        padding: 16px;
        background: white;
    }

    /* Details Grid */
    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 12px;
    }
    .detail-card {
        background: #fafcff;
        border-radius: 8px;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
    }
    .detail-header {
        font-size: 0.7rem;
        font-weight: 700;
        color: #1e3c72;
        text-transform: uppercase;
        margin-bottom: 8px;
        padding-bottom: 4px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .detail-content {
        font-size: 0.7rem;
    }
    .detail-row {
        display: flex;
        padding: 4px 0;
    }
    .detail-label {
        width: 100px;
        font-weight: 600;
        color: #64748b;
    }
    .detail-value {
        flex: 1;
        color: #1e293b;
        word-break: break-word;
    }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    .status-active {
        background: #dcfce7;
        color: #15803d;
    }

    @media (max-width: 768px) {
        .profile-grid {
            flex-direction: column;
        }
        .profile-left {
            border-right: none;
            border-bottom: 1px solid #e2e8f0;
        }
        .details-grid {
            grid-template-columns: 1fr;
        }
    }

    @media print {
        .sidebar, .top-header, .print-btn, .menu-toggle, .user-dropdown, .no-print {
            display: none !important;
        }
        .profile-card {
            border: 1px solid #000;
        }
        .profile-left {
            border-right: 1px solid #000;
        }
        .info-card, .detail-card {
            border: 1px solid #ddd;
        }
    }
</style>

<div class="profile-wrapper">
    <!-- Print Button -->
    

    <!-- Profile Card -->
    <div class="profile-card">
        <div class="profile-grid">
            <!-- LEFT COLUMN -->
            <div class="profile-left">
                <!-- Image -->
                <div class="avatar-corner">
                    @if($teacher->image)
                    <img src="{{ asset('storage/' . $teacher->image) }}" alt="Teacher Photo" class="teacher-image">
                    @else
                    <div class="default-avatar">
                        <i class="fas fa-chalkboard-user"></i>
                    </div>
                    @endif
                </div>

                <!-- Basic Info -->
                <div class="info-card">
                    <div class="info-title">
                        <i class="fas fa-id-card"></i> Basic Info
                    </div>
                    <div class="info-row">
                        <span class="info-label">Teacher ID</span>
                        <span class="info-value">{{ $teacher->teacher_id }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            <span class="status-badge status-active">Active</span>
                        </span>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="info-card">
                    <div class="info-title">
                        <i class="fas fa-phone"></i> Contact
                    </div>
                    <div class="info-row">
                        <span class="info-label">Mobile</span>
                        <span class="info-value">{{ $teacher->mobile }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $teacher->email ?? '-' }}</span>
                    </div>
                </div>

                <!-- Employment Info -->
                <div class="info-card">
                    <div class="info-title">
                        <i class="fas fa-briefcase"></i> Employment
                    </div>
                    <div class="info-row">
                        <span class="info-label">Experience</span>
                        <span class="info-value">{{ $teacher->experience }} years</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Salary Type</span>
                        <span class="info-value">{{ ucfirst($teacher->salary_type) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Salary</span>
                        <span class="info-value">₹ {{ number_format($teacher->salary, 2) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Hosteler</span>
                        <span class="info-value">{{ $teacher->is_hosteler ? 'Yes' : 'No' }}</span>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="profile-right">
                <!-- Personal Details -->
                <div class="details-grid">
                    <div class="detail-card">
                        <div class="detail-header">
                            <i class="fas fa-user"></i> Personal Details
                        </div>
                        <div class="detail-content">
                            <div class="detail-row">
                                <span class="detail-label">Full Name</span>
                                <span class="detail-value">{{ $teacher->full_name }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Father Name</span>
                                <span class="detail-value">{{ $teacher->father_name ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Mother Name</span>
                                <span class="detail-value">{{ $teacher->mother_name ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">DOB</span>
                                <span class="detail-value">{{ $teacher->dob ? date('d-m-Y', strtotime($teacher->dob)) : '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Gender</span>
                                <span class="detail-value">{{ $teacher->gender ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Marital Status</span>
                                <span class="detail-value">{{ $teacher->marital_status ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Details -->
                    <div class="detail-card">
                        <div class="detail-header">
                            <i class="fas fa-university"></i> Bank Details
                        </div>
                        <div class="detail-content">
                            <div class="detail-row">
                                <span class="detail-label">Bank Name</span>
                                <span class="detail-value">{{ $teacher->bank_name ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Account No</span>
                                <span class="detail-value">{{ $teacher->account_no ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">IFSC Code</span>
                                <span class="detail-value">{{ $teacher->ifsc_code ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Details -->
                <div class="detail-card" style="margin-bottom: 12px;">
                    <div class="detail-header">
                        <i class="fas fa-map-marker-alt"></i> Address Details
                    </div>
                    <div class="detail-content">
                        <div class="detail-row">
                            <span class="detail-label">Address</span>
                            <span class="detail-value">{{ $teacher->address ?? '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">City</span>
                            <span class="detail-value">{{ $teacher->city ?? '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Block</span>
                            <span class="detail-value">{{ $teacher->block ?? '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">District</span>
                            <span class="detail-value">{{ $teacher->district ?? '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">State</span>
                            <span class="detail-value">{{ $teacher->state ?? '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Pincode</span>
                            <span class="detail-value">{{ $teacher->pincode ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Educational Details -->
                <div class="detail-card">
                    <div class="detail-header">
                        <i class="fas fa-graduation-cap"></i> Educational Details
                    </div>
                    <div class="detail-content">
                        <div class="detail-row">
                            <span class="detail-label">Qualification</span>
                            <span class="detail-value">{{ $teacher->highest_qualification ?? '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Institute</span>
                            <span class="detail-value">{{ $teacher->institute_name ?? '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Passing Year</span>
                            <span class="detail-value">{{ $teacher->passing_year ?? '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Obtained Marks</span>
                            <span class="detail-value">{{ $teacher->obtained_marks ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection