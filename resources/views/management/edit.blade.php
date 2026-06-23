@extends('layouts.app')

@section('title', 'Edit Staff')

@section('content')
<style>
    .form-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #e2e8f0;
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        gap: 14px;
        margin-bottom: 12px;
    }
    .form-group { margin-bottom: 0; }
    .form-group label {
        display: block;
        font-size: 0.6rem;
        font-weight: 700;
        color: #1e3c72;
        margin-bottom: 3px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .form-group label .required { color: #ef4444; }
    .form-group input, .form-group select, .form-group textarea {
        width: 100%;
        padding: 6px 10px;
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
        background: #f8fafc;
        transition: all 0.3s;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        outline: none;
        border-color: #1e3c72;
        box-shadow: 0 0 0 3px rgba(30,60,114,0.1);
        background: white;
    }
    .form-group input[readonly] {
        background: #f1f5f9;
        cursor: not-allowed;
    }
    .form-group textarea { min-height: 50px; resize: vertical; }
    
    .btn-submit {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        border: none;
        color: white;
        padding: 7px 28px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
    }
    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(30,60,114,0.3);
    }
    
    .staff-header {
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
    .staff-header h2 { font-size: 1rem; margin: 0; display: flex; align-items: center; gap: 6px; }
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
    
    .full-width { grid-column: 1 / -1; }
    .emp-badge {
        background: #e0e7ff;
        color: #4338ca;
        padding: 2px 10px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-family: monospace;
    }
    
    .image-preview {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        object-fit: cover;
    }
    
    @media (max-width: 768px) {
        .form-row { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 480px) {
        .form-row { grid-template-columns: 1fr; }
        .staff-header { flex-direction: column; align-items: flex-start; }
    }
</style>

<div class="container">
    <div class="staff-header">
        <h2>
            <i class="fas fa-edit"></i> Edit Staff
            <span class="emp-badge" style="margin-left:10px;">
                <i class="fas fa-id-card"></i> {{ $staff->emp_id }}
            </span>
        </h2>
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
            <a href="{{ route('management.show', $staff->id) }}" class="btn-back">
                <i class="fas fa-eye"></i> View
            </a>
            <a href="{{ route('management.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="form-card">
        <form id="staffForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Personal Details -->
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Full Name <span class="required">*</span></label>
                    <input type="text" name="full_name" id="full_name" value="{{ $staff->full_name }}" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Father Name</label>
                    <input type="text" name="father_name" id="father_name" value="{{ $staff->father_name }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Mother Name</label>
                    <input type="text" name="mother_name" id="mother_name" value="{{ $staff->mother_name }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-calendar-alt"></i> DOB</label>
                    <input type="date" name="dob" id="dob" value="{{ $staff->dob }}">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-venus-mars"></i> Gender</label>
                    <select name="gender" id="gender">
                        <option value="">Select</option>
                        @foreach($genders as $gender)
                        <option value="{{ $gender }}" {{ $staff->gender == $gender ? 'selected' : '' }}>
                            {{ $gender }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-ring"></i> Marital Status</label>
                    <select name="marital_status" id="marital_status">
                        <option value="">Select</option>
                        @foreach($maritalStatuses as $status)
                        <option value="{{ $status }}" {{ $staff->marital_status == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Mobile <span class="required">*</span></label>
                    <input type="text" name="mobile" id="mobile" value="{{ $staff->mobile }}" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" id="email" value="{{ $staff->email }}">
                </div>
            </div>
            
            <!-- Department & Experience -->
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-building"></i> Department <span class="required">*</span></label>
                    <select name="department" id="department" required>
                        @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ $staff->department == $dept ? 'selected' : '' }}>
                            {{ ucfirst($dept) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-briefcase"></i> Experience (Years)</label>
                    <input type="number" name="experience" id="experience" min="0" value="{{ $staff->experience }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-calendar-day"></i> Registration Date</label>
                    <input type="date" name="registration_date" id="registration_date" value="{{ $staff->registration_date }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-circle"></i> Status</label>
                    <select name="status" id="status">
                        <option value="active" {{ $staff->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $staff->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            
            <!-- Address -->
            <div class="form-row">
                <div class="form-group full-width">
                    <label><i class="fas fa-home"></i> Address</label>
                    <textarea name="address" id="address" placeholder="Full Address">{{ $staff->address }}</textarea>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-city"></i> City</label>
                    <input type="text" name="city" id="city" value="{{ $staff->city }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-map"></i> Block</label>
                    <input type="text" name="block" id="block" value="{{ $staff->block }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-map-marked"></i> District</label>
                    <input type="text" name="district" id="district" value="{{ $staff->district }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-map-pin"></i> State</label>
                    <input type="text" name="state" id="state" value="{{ $staff->state }}">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-mail-bulk"></i> Pincode</label>
                    <input type="text" name="pincode" id="pincode" value="{{ $staff->pincode }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-graduation-cap"></i> Highest Qualification</label>
                    <input type="text" name="highest_qualification" id="highest_qualification" value="{{ $staff->highest_qualification }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-image"></i> Photo</label>
                    <input type="file" name="image" id="image" accept="image/*">
                    <small style="color:#94a3b8; font-size:0.55rem;">Max: 2MB (JPG, PNG)</small>
                </div>
                <div class="form-group">
                    <label>Current Photo</label>
                    <div id="imagePreview">
                        @if($staff->image)
                            <img src="{{ asset('storage/' . $staff->image) }}" class="image-preview">
                        @else
                            <span style="color:#94a3b8; font-size:0.7rem;">No photo</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Bank Details -->
            <div style="border-top:2px dashed #e2e8f0; padding-top:12px; margin-top:5px;">
                <h5 style="font-size:0.8rem; color:#1e3c72; margin-bottom:10px;">
                    <i class="fas fa-university"></i> Bank Details
                </h5>
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-university"></i> Bank Name</label>
                        <input type="text" name="bank_name" id="bank_name" value="{{ $staff->bank_name }}">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-credit-card"></i> Account No.</label>
                        <input type="text" name="account_no" id="account_no" value="{{ $staff->account_no }}">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-code"></i> IFSC Code</label>
                        <input type="text" name="ifsc_code" id="ifsc_code" value="{{ $staff->ifsc_code }}">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-money-bill"></i> Salary Type</label>
                        <select name="salary_type" id="salary_type">
                            @foreach($salaryTypes as $type)
                            <option value="{{ $type }}" {{ $staff->salary_type == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-money-bill-wave"></i> Salary (₹)</label>
                        <input type="number" name="salary" id="salary" step="0.01" value="{{ $staff->salary }}">
                    </div>
                </div>
            </div>
            
            <div style="text-align:right; margin-top:15px; border-top:1px solid #e2e8f0; padding-top:15px;">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Update Staff
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Image Preview
document.getElementById('image').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(event) {
            preview.innerHTML = `<img src="${event.target.result}" class="image-preview">`;
        }
        reader.readAsDataURL(this.files[0]);
    }
});

// Form Submit
document.getElementById('staffForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    Swal.fire({
        title: 'Updating...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });
    
    fetch('{{ route("management.update", $staff->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-HTTP-Method-Override': 'PUT'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            });
            setTimeout(() => {
                window.location.href = '{{ route("management.index") }}';
            }, 1500);
        } else {
            Swal.fire('Error!', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error!', 'Something went wrong!', 'error');
        console.error('Error:', error);
    });
});
</script>
@endsection