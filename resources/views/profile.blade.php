@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<style>
    /* ===== ANIMATIONS ===== */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .profile-container {
        animation: fadeInUp 0.35s ease;
    }
    
    /* ===== HEADER ===== */
    .profile-header {
        background: linear-gradient(135deg, #1a365d 0%, #2d4a7a 50%, #1a365d 100%);
        color: white;
        padding: 12px 18px;
        border-radius: 10px;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(26, 54, 93, 0.25);
    }
    .profile-header h2 {
        font-size: 0.95rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }
    .profile-header h2 i {
        color: #fbbf24;
    }
    .btn-back {
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.15);
        color: white;
        padding: 4px 14px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        transition: all 0.25s ease;
    }
    .btn-back:hover {
        background: rgba(255,255,255,0.2);
        color: white;
        transform: translateX(-2px);
    }
    
    /* ===== PROFILE CARD ===== */
    .profile-card {
        background: white;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    
    .profile-card-body {
        padding: 16px 18px;
    }
    
    /* ===== PROFILE LAYOUT: LEFT PHOTO + RIGHT DETAILS ===== */
    .profile-layout {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }
    
    /* ===== LEFT - PHOTO ===== */
    .profile-left {
        flex: 0 0 140px;
        text-align: center;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #1e3c72;
        box-shadow: 0 4px 12px rgba(30, 60, 114, 0.2);
        background: #f1f5f9;
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
        margin: 0 auto;
        border: 4px solid #1e3c72;
        box-shadow: 0 4px 12px rgba(30, 60, 114, 0.2);
    }
    
    .photo-upload-btn {
        display: inline-block;
        margin-top: 8px;
        background: #1e3c72;
        color: white;
        padding: 3px 14px;
        border-radius: 6px;
        font-size: 0.6rem;
        cursor: pointer;
        transition: all 0.3s;
        border: none;
        font-weight: 600;
    }
    .photo-upload-btn:hover {
        background: #2a5298;
        transform: translateY(-1px);
    }
    .photo-upload-btn i {
        margin-right: 4px;
        font-size: 0.6rem;
    }
    
    .photo-hint {
        font-size: 0.5rem;
        color: #94a3b8;
        display: block;
        margin-top: 4px;
    }
    
    /* ===== RIGHT - DETAILS ===== */
    .profile-right {
        flex: 1;
        min-width: 0;
    }
    
    /* ===== ROLE & STATUS BADGES ===== */
    .badge-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 12px;
        padding-bottom: 10px;
        border-bottom: 1.5px solid #f1f5f9;
    }
    
    .badge-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.65rem;
        font-weight: 600;
    }
    .badge-item .badge-label {
        color: #64748b;
        font-weight: 400;
        text-transform: uppercase;
        font-size: 0.55rem;
    }
    
    .role-badge {
        background: #dbeafe;
        color: #1e40af;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    .status-badge {
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    .status-badge.active {
        background: #dcfce7;
        color: #15803d;
    }
    .status-badge.inactive {
        background: #fee2e2;
        color: #dc2626;
    }
    
    /* ===== FORM ===== */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px 16px;
    }
    .form-grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 10px 16px;
    }
    .full-width {
        grid-column: 1 / -1;
    }
    
    .form-group {
        margin-bottom: 0;
    }
    .form-group label {
        display: block;
        font-size: 0.6rem;
        font-weight: 700;
        color: #1e3c72;
        margin-bottom: 3px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .form-group label i {
        width: 14px;
        color: #3b82f6;
        font-size: 0.6rem;
    }
    .form-group label .required {
        color: #dc2626;
        font-weight: 700;
    }
    .form-group input {
        width: 100%;
        padding: 5px 10px;
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
        background: #fafbfc;
        transition: all 0.25s ease;
        font-family: inherit;
        height: 32px;
        color: #1e293b;
    }
    .form-group input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        background: white;
    }
    .form-group input[readonly] {
        background: #f1f5f9;
        cursor: not-allowed;
        color: #475569;
    }
    .form-group input[readonly]:focus {
        box-shadow: none;
        border-color: #e2e8f0;
    }
    .form-group input.readonly-bg {
        background: #f1f5f9;
    }
    
    /* ===== DIVIDER ===== */
    .section-divider {
        border: none;
        border-top: 1.5px dashed #e2e8f0;
        margin: 12px 0;
    }
    
    /* ===== PASSWORD SECTION ===== */
    .password-toggle {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin: 10px 0 6px 0;
    }
    .password-toggle .title {
        font-size: 0.7rem;
        font-weight: 600;
        color: #1e3c72;
    }
    .password-toggle .title i {
        color: #3b82f6;
        margin-right: 4px;
    }
    
    .btn-change-password {
        background: #f59e0b;
        border: none;
        color: white;
        padding: 4px 14px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s;
        height: 30px;
    }
    .btn-change-password:hover {
        background: #d97706;
        transform: translateY(-1px);
    }
    .btn-change-password i {
        font-size: 0.6rem;
    }
    
    .password-section {
        background: #fef3c7;
        border: 1px solid #f59e0b;
        border-radius: 8px;
        padding: 12px 14px;
        margin-top: 8px;
        display: none;
        animation: fadeInUp 0.3s ease;
    }
    .password-section.active {
        display: block;
    }
    
    /* ===== BUTTONS ===== */
    .btn-submit {
        background: linear-gradient(135deg, #1e3c72, #2d4a7a);
        border: none;
        color: white;
        padding: 6px 24px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(30, 60, 114, 0.25);
        height: 34px;
    }
    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(30, 60, 114, 0.35);
        background: linear-gradient(135deg, #2d4a7a, #1e3c72);
    }
    .btn-submit i {
        font-size: 0.8rem;
    }
    
    .form-actions {
        text-align: right;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1.5px solid #f1f5f9;
    }
    
    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .profile-layout {
            flex-direction: column;
            align-items: center;
        }
        .profile-left {
            flex: 0 0 auto;
            width: 100%;
        }
        .profile-avatar,
        .profile-avatar-placeholder {
            width: 100px;
            height: 100px;
        }
        .profile-right {
            width: 100%;
        }
        .form-grid {
            grid-template-columns: 1fr 1fr;
        }
        .form-grid-3 {
            grid-template-columns: 1fr 1fr;
        }
        .profile-card-body {
            padding: 12px 14px;
        }
    }
    
    @media (max-width: 480px) {
        .profile-avatar,
        .profile-avatar-placeholder {
            width: 80px;
            height: 80px;
        }
        .form-grid {
            grid-template-columns: 1fr;
        }
        .form-grid-3 {
            grid-template-columns: 1fr;
        }
        .badge-row {
            justify-content: center;
        }
        .password-toggle {
            flex-direction: column;
            align-items: stretch;
        }
        .btn-change-password {
            justify-content: center;
        }
        .form-actions {
            text-align: center;
        }
        .btn-submit {
            width: 100%;
            justify-content: center;
        }
        .profile-card-body {
            padding: 10px 12px;
        }
        .profile-header h2 {
            font-size: 0.85rem;
        }
    }
</style>

<div class="profile-container">
    <!-- ===== HEADER ===== -->
    <div class="profile-header">
        <h2>
            <i class="fas fa-user-circle"></i>
            My Profile
        </h2>
        <a href="{{ url()->previous() }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- ===== PROFILE CARD ===== -->
    <div class="profile-card">
        <div class="profile-card-body">
            <form id="profileForm" onsubmit="updateProfile(event)" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- ===== PROFILE LAYOUT: LEFT PHOTO + RIGHT DETAILS ===== -->
                <div class="profile-layout">
                    
                    <!-- ===== LEFT: PHOTO ===== -->
                    <div class="profile-left">
                        @if(Auth::user()->image)
                            <img src="{{ asset('storage/' . Auth::user()->image) }}" 
                                 alt="Photo" class="profile-avatar" id="avatarPreview">
                        @else
                            <div class="profile-avatar-placeholder" id="avatarPlaceholder">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        
                        <label for="image" style="cursor:pointer;">
                            <span class="photo-upload-btn">
                                <i class="fas fa-camera"></i> Change Photo
                            </span>
                            <input type="file" name="image" id="image" accept="image/*" 
                                   style="display:none;" onchange="previewImage(event)">
                        </label>
                        <span class="photo-hint">Max: 2MB (JPG, PNG)</span>
                    </div>
                    
                    <!-- ===== RIGHT: DETAILS ===== -->
                    <div class="profile-right">
                        
                        <!-- Badges -->
                        <div class="badge-row">
                            <div class="badge-item">
                                <span class="badge-label"><i class="fas fa-user-tag"></i> Role</span>
                                <span class="role-badge">{{ ucfirst(Auth::user()->role ?? 'User') }}</span>
                            </div>
                            <div class="badge-item">
                                <span class="badge-label"><i class="fas fa-circle"></i> Status</span>
                                <span class="status-badge active">Active</span>
                            </div>
                           
                        </div>
                        
                        <!-- Form Fields -->
                        <div class="form-grid">
                            <div class="form-group">
                                <label><i class="fas fa-user"></i> Full Name <span class="required">*</span></label>
                                <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" required>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-envelope"></i> Email <span class="required">*</span></label>
                                <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" required>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-phone"></i> Mobile</label>
                                <input type="text" name="mobile" id="mobile" value="{{ Auth::user()->mobile ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-calendar-alt"></i> Joined</label>
                                <input type="text" value="{{ Auth::user()->created_at->format('d M, Y') }}" readonly>
                            </div>
                        </div>
                        
                        <!-- Password Section -->
                        <div class="password-toggle">
                            <span class="title">
                                <i class="fas fa-lock"></i> Password Settings
                            </span>
                            <button type="button" class="btn-change-password" onclick="togglePasswordSection()">
                                <i class="fas fa-key"></i> Change Password
                            </button>
                        </div>
                        
                        <div class="password-section" id="passwordSection">
                            <div class="form-grid-3">
                                <div class="form-group">
                                    <label><i class="fas fa-lock"></i> Current <span class="required">*</span></label>
                                    <input type="password" name="current_password" id="current_password" 
                                           placeholder="Current password">
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-lock"></i> New <span class="required">*</span></label>
                                    <input type="password" name="new_password" id="new_password" 
                                           placeholder="New (min 6 chars)">
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-check-circle"></i> Confirm <span class="required">*</span></label>
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                                           placeholder="Confirm password">
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <!-- ===== END RIGHT ===== -->
                    
                </div>
                <!-- ===== END LAYOUT ===== -->
                
                <!-- ===== SUBMIT ===== -->
                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Update Profile
                    </button>
                </div>
                
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('avatarPreview');
        const placeholder = document.getElementById('avatarPlaceholder');
        
        if (preview) {
            preview.src = reader.result;
        } else if (placeholder) {
            const img = document.createElement('img');
            img.src = reader.result;
            img.className = 'profile-avatar';
            img.id = 'avatarPreview';
            img.alt = 'Photo';
            placeholder.parentNode.replaceChild(img, placeholder);
        }
    };
    reader.readAsDataURL(event.target.files[0]);
}

function togglePasswordSection() {
    const section = document.getElementById('passwordSection');
    section.classList.toggle('active');
    
    if (!section.classList.contains('active')) {
        document.getElementById('current_password').value = '';
        document.getElementById('new_password').value = '';
        document.getElementById('new_password_confirmation').value = '';
    }
}

function updateProfile(event) {
    event.preventDefault();
    
    const form = document.getElementById('profileForm');
    const formData = new FormData(form);
    
    const currentPass = document.getElementById('current_password').value;
    const newPass = document.getElementById('new_password').value;
    const confirmPass = document.getElementById('new_password_confirmation').value;
    
    if (currentPass || newPass || confirmPass) {
        if (!currentPass) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Please enter current password', confirmButtonColor: '#1e3c72' });
            return;
        }
        if (newPass.length < 6) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'New password must be at least 6 characters', confirmButtonColor: '#1e3c72' });
            return;
        }
        if (newPass !== confirmPass) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'New password and confirm password do not match', confirmButtonColor: '#1e3c72' });
            return;
        }
    }
    
    Swal.fire({
        title: 'Updating...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });
    
    fetch('{{ route("profile.update") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-HTTP-Method-Override': 'PUT'
        },
        body: formData
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            Swal.fire({
                icon: 'success',
                title: 'Updated! 🎉',
                text: response.message,
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            Swal.fire({ icon: 'error', title: 'Error!', text: response.message, confirmButtonColor: '#1e3c72' });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({ icon: 'error', title: 'Error!', text: 'Something went wrong!', confirmButtonColor: '#1e3c72' });
    });
}
</script>
@endsection