@extends('layouts.app')

@section('title', 'School Settings')

@section('content')
<style>
    .settings-container {
        animation: fadeIn 0.3s ease;
        max-width: 100%;
    }

    /* Compact Header */
    .settings-header {
        background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
        color: white;
        padding: 12px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .settings-header h2 {
        font-size: 1.1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .settings-header p {
        font-size: 0.7rem;
        margin: 0;
        opacity: 0.9;
    }

    /* Compact Form */
    .settings-form {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-group.full-width {
        grid-column: span 3;
    }

    .form-group label {
        display: block;
        margin-bottom: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        color: #4a5568;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .form-group label i {
        color: #f59e0b;
        width: 20px;
        font-size: 0.7rem;
    }

    .form-group input, 
    .form-group textarea {
        width: 100%;
        padding: 8px 10px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.8rem;
        outline: none;
        transition: all 0.2s;
        background: #fafbfc;
    }

    .form-group input:focus, 
    .form-group textarea:focus {
        border-color: #f59e0b;
        background: white;
        box-shadow: 0 0 0 3px rgba(245,158,11,0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 60px;
    }

    /* Logo Section - Compact */
    .logo-section {
        display: flex;
        gap: 20px;
        margin: 15px 0;
        padding: 15px;
        background: #fef3c7;
        border-radius: 10px;
        border: 1px solid #fde68a;
    }

    .logo-box {
        flex: 1;
        text-align: center;
        padding: 10px;
        background: white;
        border-radius: 10px;
        border: 1px solid #fde68a;
    }

    .logo-preview {
        width: 80px;
        height: 80px;
        margin: 0 auto 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fef3c7;
        border-radius: 10px;
        border: 2px dashed #f59e0b;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.2s;
    }

    .logo-preview:hover {
        border-color: #ef4444;
        background: #fef3c7;
    }

    .logo-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .logo-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f59e0b;
        font-size: 1.8rem;
    }

    .logo-box label {
        font-size: 0.7rem;
        font-weight: 600;
        color: #4a5568;
        display: block;
        margin-bottom: 5px;
    }

    .logo-box input {
        font-size: 0.7rem;
        padding: 5px;
        width: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        background: #fafbfc;
    }

    .logo-box small {
        font-size: 0.6rem;
        color: #94a3b8;
    }

    /* Buttons */
    .form-buttons {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-submit, .btn-cancel {
        padding: 8px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-submit {
        background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
        color: white;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245,158,11,0.4);
    }

    .btn-cancel {
        background: #e2e8f0;
        color: #4a5568;
    }

    .btn-cancel:hover {
        background: #cbd5e1;
    }

    /* Current Logo Badge */
    .current-badge {
        display: inline-block;
        background: #dcfce7;
        color: #16a34a;
        font-size: 0.6rem;
        padding: 2px 6px;
        border-radius: 10px;
        margin-top: 5px;
    }

    @media (max-width: 900px) {
        .form-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .form-group.full-width {
            grid-column: span 2;
        }
    }

    @media (max-width: 600px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .form-group.full-width {
            grid-column: span 1;
        }
        .logo-section {
            flex-direction: column;
        }
    }
</style>

<div class="settings-container">
    <div class="settings-header">
        <div>
            <h2>
                <i class="fas fa-school"></i> School Settings
            </h2>
            <p>Manage school information & configuration</p>
        </div>
        <div>
            <i class="fas fa-cog" style="font-size: 1.2rem;"></i>
        </div>
    </div>

    <div class="settings-form">
        <form id="settingsForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="settingId" name="setting_id" value="{{ $setting->id ?? '' }}">
            
            <div class="form-grid">
                <div class="form-group">
                    <label><i class="fas fa-school"></i> School Name</label>
                    <input type="text" id="school_name" name="school_name" required 
                           value="{{ old('school_name', $setting->school_name ?? '') }}" 
                           placeholder="Enter school name">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-mobile-alt"></i> Mobile</label>
                    <input type="text" id="mobile" name="mobile" required 
                           value="{{ old('mobile', $setting->mobile ?? '') }}" 
                           placeholder="Mobile number">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" required 
                           value="{{ old('email', $setting->email ?? '') }}" 
                           placeholder="Email address">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-id-card"></i> UDIC Code</label>
                    <input type="text" id="udic" name="udic" required 
                           value="{{ old('udic', $setting->udic ?? '') }}" 
                           placeholder="UDIC code">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-key"></i> License Key</label>
                    <input type="text" id="license" name="license" required 
                           value="{{ old('license', $setting->license ?? '') }}" 
                           placeholder="License key">
                </div>

                <div class="form-group full-width">
                    <label><i class="fas fa-map-marker-alt"></i> Address</label>
                    <textarea id="address" name="address" required placeholder="Complete address">{{ old('address', $setting->address ?? '') }}</textarea>
                </div>
            </div>

            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo-box">
                    <label><i class="fas fa-image"></i> Logo 1 (Main)</label>
                    <div class="logo-preview" onclick="document.getElementById('logo_1').click()">
                        @if(isset($setting) && $setting->logo_1)
                            <img src="{{ asset('storage/' . $setting->logo_1) }}" alt="Logo 1" id="logo1Preview">
                        @else
                            <div class="logo-placeholder" id="logo1Placeholder">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                        @endif
                    </div>
                    <input type="file" id="logo_1" name="logo_1" accept="image/*" style="display:none" onchange="previewLogo(1)">
                    @if(isset($setting) && $setting->logo_1)
                        <span class="current-badge">
                            <i class="fas fa-check-circle"></i> Current
                        </span>
                    @endif
                    <small>Click to upload (Max 2MB)</small>
                </div>

                <div class="logo-box">
                    <label><i class="fas fa-image"></i> Logo 2 (Footer)</label>
                    <div class="logo-preview" onclick="document.getElementById('logo_2').click()">
                        @if(isset($setting) && $setting->logo_2)
                            <img src="{{ asset('storage/' . $setting->logo_2) }}" alt="Logo 2" id="logo2Preview">
                        @else
                            <div class="logo-placeholder" id="logo2Placeholder">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                        @endif
                    </div>
                    <input type="file" id="logo_2" name="logo_2" accept="image/*" style="display:none" onchange="previewLogo(2)">
                    @if(isset($setting) && $setting->logo_2)
                        <span class="current-badge">
                            <i class="fas fa-check-circle"></i> Current
                        </span>
                    @endif
                    <small>Click to upload (Max 2MB)</small>
                </div>
            </div>

            <div class="form-buttons">
                <button type="button" class="btn-cancel" onclick="resetForm()">
                    <i class="fas fa-undo-alt"></i> Reset
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> {{ isset($setting) ? 'Update Settings' : 'Save Settings' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewLogo(logoNumber) {
        const input = document.getElementById(`logo_${logoNumber}`);
        const previewImg = document.getElementById(`logo${logoNumber}Preview`);
        const placeholder = document.getElementById(`logo${logoNumber}Placeholder`);
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (previewImg) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                }
                if (placeholder) {
                    placeholder.style.display = 'none';
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.getElementById('settingsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const settingId = document.getElementById('settingId').value;
        const url = settingId ? `/admin/settings/update/${settingId}` : '/admin/settings/store';
        
        const formData = new FormData();
        formData.append('school_name', document.getElementById('school_name').value);
        formData.append('mobile', document.getElementById('mobile').value);
        formData.append('email', document.getElementById('email').value);
        formData.append('address', document.getElementById('address').value);
        formData.append('udic', document.getElementById('udic').value);
        formData.append('license', document.getElementById('license').value);
        formData.append('_token', '{{ csrf_token() }}');
        
        if (settingId) {
            formData.append('_method', 'PUT');
        }
        
        const logo1 = document.getElementById('logo_1').files[0];
        const logo2 = document.getElementById('logo_2').files[0];
        
        if (logo1) formData.append('logo_1', logo1);
        if (logo2) formData.append('logo_2', logo2);
        
        Swal.fire({
            title: 'Saving...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.success,
                    timer: 1500,
                    showConfirmButton: false
                });
                setTimeout(() => location.reload(), 1500);
            } else if (data.errors) {
                let errorMsg = '';
                for (let key in data.errors) {
                    errorMsg += data.errors[key][0] + '\n';
                }
                Swal.fire({ icon: 'error', title: 'Error!', text: errorMsg });
            }
        })
        .catch(error => {
            Swal.fire({ icon: 'error', title: 'Error!', text: 'Something went wrong!' });
        });
    });
    
    function resetForm() {
        Swal.fire({
            title: 'Reset Form?',
            text: 'All unsaved changes will be lost!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Yes, reset'
        }).then((result) => {
            if (result.isConfirmed) {
                location.reload();
            }
        });
    }
</script>
@endsection