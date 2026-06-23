@extends('layouts.app')

@section('title', 'Staff Profile')

@section('content')
<style>
    .profile-container { animation: fadeIn 0.3s ease; }
    
    .profile-header {
        background: linear-gradient(135deg, #1e3c72 0%, #0f2b4d 100%);
        color: white;
        padding: 10px 18px;
        border-radius: 10px;
        margin-bottom: 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .profile-header h2 { font-size: 1rem; margin: 0; display: flex; align-items: center; gap: 6px; }
    .btn-back {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.2);
        color: white;
        padding: 5px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .btn-back:hover { background: rgba(255,255,255,0.25); color: white; }
    .btn-edit {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.2);
        color: white;
        padding: 5px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .btn-edit:hover { background: rgba(255,255,255,0.25); color: white; }
    
    .profile-card {
        background: white;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .profile-top {
        display: flex;
        gap: 25px;
        padding: 20px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        align-items: center;
        flex-wrap: wrap;
    }
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #1e3c72;
    }
    .profile-avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: white;
        border: 4px solid #1e3c72;
    }
    .profile-info h3 {
        font-size: 1.1rem;
        color: #1e3c72;
        margin: 0;
    }
    .profile-info .emp-id {
        background: #e0e7ff;
        color: #4338ca;
        padding: 2px 10px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-family: monospace;
        display: inline-block;
        margin-top: 4px;
    }
    .profile-info .dept-badge {
        background: #dbeafe;
        color: #1e40af;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .profile-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        padding: 20px;
    }
    .profile-section {
        background: #f8fafc;
        border-radius: 8px;
        padding: 15px;
    }
    .profile-section h4 {
        font-size: 0.75rem;
        font-weight: 700;
        color: #1e3c72;
        margin: 0 0 10px 0;
        padding-bottom: 6px;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .profile-section h4 i { color: #f59e0b; }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 4px 0;
        font-size: 0.7rem;
        border-bottom: 1px dashed #e2e8f0;
    }
    .info-row:last-child { border-bottom: none; }
    .info-label { font-weight: 600; color: #64748b; }
    .info-value { color: #1e293b; font-weight: 500; }
    
    .badge-status {
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
    }
    .badge-active { background: #dcfce7; color: #15803d; }
    .badge-inactive { background: #fee2e2; color: #dc2626; }
    
    .full-width { grid-column: 1 / -1; }
    
    @media (max-width: 768px) {
        .profile-grid { grid-template-columns: 1fr; }
        .profile-top { flex-direction: column; text-align: center; }
    }
</style>

<div class="profile-container">
    <div class="profile-header">
        <h2>
            <i class="fas fa-user-circle"></i> Staff Profile
            <span style="font-size:0.6rem; background:rgba(255,255,255,0.15); padding:2px 10px; border-radius:20px;">
                <i class="fas fa-id-card"></i> {{ $staff->emp_id }}
            </span>
        </h2>
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
            <a href="{{ route('management.edit', $staff->id) }}" class="btn-edit">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('management.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="profile-card">
        <!-- Top Section -->
        <div class="profile-top">
            <div>
                @if($staff->image)
                    <img src="{{ asset('storage/' . $staff->image) }}" alt="Photo" class="profile-avatar">
                @else
                    <div class="profile-avatar-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
            </div>
            <div class="profile-info">
                <h3>{{ $staff->full_name }}</h3>
                <div class="emp-id"><i class="fas fa-id-card"></i> {{ $staff->emp_id }}</div>
                <div style="margin-top:6px;">
                    <span class="dept-badge">
                        <i class="fas fa-building"></i> {{ ucfirst($staff->department) }}
                    </span>
                    <span class="badge-status badge-{{ $staff->status }}" style="margin-left:8px;">
                        {{ ucfirst($staff->status) }}
                    </span>
                </div>
                <div style="margin-top:5px; font-size:0.7rem; color:#64748b;">
                    <i class="fas fa-phone"></i> {{ $staff->mobile }}
                    @if($staff->email)
                        | <i class="fas fa-envelope"></i> {{ $staff->email }}
                    @endif
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="profile-grid">
            <!-- Personal Details -->
            <div class="profile-section">
                <h4><i class="fas fa-user"></i> Personal Details</h4>
                <div class="info-row">
                    <span class="info-label">Full Name</span>
                    <span class="info-value">{{ $staff->full_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Father Name</span>
                    <span class="info-value">{{ $staff->father_name ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Mother Name</span>
                    <span class="info-value">{{ $staff->mother_name ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date of Birth</span>
                    <span class="info-value">{{ $staff->dob ? date('d-m-Y', strtotime($staff->dob)) : '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Gender</span>
                    <span class="info-value">{{ $staff->gender ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Marital Status</span>
                    <span class="info-value">{{ $staff->marital_status ?? '-' }}</span>
                </div>
            </div>

            <!-- Contact & Address -->
            <div class="profile-section">
                <h4><i class="fas fa-address-card"></i> Contact & Address</h4>
                <div class="info-row">
                    <span class="info-label">Mobile</span>
                    <span class="info-value">{{ $staff->mobile }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $staff->email ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Address</span>
                    <span class="info-value">{{ $staff->address ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">City</span>
                    <span class="info-value">{{ $staff->city ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Block</span>
                    <span class="info-value">{{ $staff->block ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">District</span>
                    <span class="info-value">{{ $staff->district ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">State</span>
                    <span class="info-value">{{ $staff->state ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Pincode</span>
                    <span class="info-value">{{ $staff->pincode ?? '-' }}</span>
                </div>
            </div>

            <!-- Employment -->
            <div class="profile-section">
                <h4><i class="fas fa-briefcase"></i> Employment Details</h4>
                <div class="info-row">
                    <span class="info-label">Employee ID</span>
                    <span class="info-value">{{ $staff->emp_id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Department</span>
                    <span class="info-value">{{ ucfirst($staff->department) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Experience</span>
                    <span class="info-value">{{ $staff->experience }} Years</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Qualification</span>
                    <span class="info-value">{{ $staff->highest_qualification ?? '-' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        <span class="badge-status badge-{{ $staff->status }}">
                            {{ ucfirst($staff->status) }}
                        </span>
                    </span>
                </div>
            </div>

            <!-- Bank & Salary -->
            <div class="profile-section">
                <h4><i class="fas fa-university"></i> Bank & Salary</h4>
                <div class="info-row">
                    <span class="info-label">Bank Name</span>
                    <span class="info-value">{{ $staff->bank_name ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Account No.</span>
                    <span class="info-value">{{ $staff->account_no ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">IFSC Code</span>
                    <span class="info-value">{{ $staff->ifsc_code ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Salary Type</span>
                    <span class="info-value">{{ ucfirst($staff->salary_type) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Salary</span>
                    <span class="info-value">
                        <strong>₹{{ number_format($staff->salary, 2) }}</strong>
                    </span>
                </div>
            </div>

            <!-- Session & Timestamps -->
            <div class="profile-section full-width">
                <h4><i class="fas fa-clock"></i> System Info</h4>
                <div class="info-row">
                    <span class="info-label">Session</span>
                    <span class="info-value">{{ $staff->session->session_name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Created By</span>
                    <span class="info-value">{{ $staff->creator->name ?? 'System' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Registration</span>
                    <span class="info-value">{{ $staff->created_at ? date('d-m-Y h:i A', strtotime($staff->created_at)) : '-' }}</span>
                </div>
               
            </div>
        </div>
    </div>
</div>
@endsection