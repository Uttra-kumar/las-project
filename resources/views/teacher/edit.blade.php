@extends('layouts.app')

@section('title', 'Edit Teacher')

@section('content')
<style>
    .edit-container {
        animation: fadeIn 0.3s ease;
    }

    .edit-header {
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

    .edit-header h2 {
        font-size: 1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .teacher-id-badge {
        background: rgba(255,255,255,0.2);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
    }

    .edit-form {
        background: white;
        border-radius: 10px;
        padding: 15px;
        border: 1px solid #e2e8f0;
    }

    .form-section {
        background: #fefefe;
        border-radius: 8px;
        margin-bottom: 12px;
        border: 1px solid #e2edf7;
        overflow: hidden;
    }

    .section-title {
        background: #f8fbff;
        padding: 6px 12px;
        border-bottom: 1px solid #cbdde9;
    }

    .section-title h3 {
        font-size: 0.8rem;
        font-weight: 600;
        color: #1e4663;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .section-content {
        padding: 10px 12px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 8px 12px;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .field label {
        font-weight: 600;
        color: #1f3b4c;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .field label .req {
        color: #dc2626;
    }

    input, select, textarea {
        padding: 5px 8px;
        border: 1px solid #d4dee8;
        border-radius: 6px;
        font-size: 0.75rem;
        font-family: inherit;
        transition: 0.2s;
        background: white;
    }

    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: #1e3c72;
        box-shadow: 0 0 0 2px rgba(30,60,114,0.1);
    }

    textarea {
        resize: vertical;
        min-height: 45px;
    }

    .radio-group, .checkbox-group {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 2px;
    }

    .radio-group label, .checkbox-group label {
        display: flex;
        align-items: center;
        gap: 4px;
        font-weight: 500;
        text-transform: none;
        font-size: 0.7rem;
    }

    .full-width {
        grid-column: 1 / -1;
    }

    .btn-area {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 12px;
    }

    .btn-submit, .btn-reset, .btn-back {
        padding: 6px 16px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-submit {
        background: linear-gradient(135deg, #1e3c72, #0f2b4d);
        color: white;
    }

    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    .btn-reset {
        background: #eef2f7;
        color: #2c3e50;
        border: 1px solid #cbdde9;
    }

    .btn-back {
        background: #64748b;
        color: white;
    }

    .current-image {
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .current-image img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }

    @media (max-width: 768px) {
        .edit-form {
            padding: 10px;
        }
        .form-grid {
            grid-template-columns: 1fr;
        }
        .edit-header {
            flex-direction: column;
            text-align: center;
        }
        .btn-area {
            flex-wrap: wrap;
        }
    }
</style>

<div class="edit-container">
    <div class="edit-header">
        <h2>
            <i class="fas fa-edit"></i> Edit Teacher
        </h2>
        <div class="teacher-id-badge">
            <i class="fas fa-id-card"></i> Teacher ID: <strong>{{ $teacher->teacher_id }}</strong>
        </div>
    </div>

    <div class="edit-form">
        <form id="teacherForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- PERSONAL INFORMATION -->
            <div class="form-section">
                <div class="section-title">
                    <h3><i class="fas fa-user"></i> Personal Information</h3>
                </div>
                <div class="section-content">
                    <div class="form-grid">
                        <div class="field">
                            <label>Full Name <span class="req">*</span></label>
                            <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $teacher->full_name) }}" required>
                        </div>
                        <div class="field">
                            <label>Father's Name</label>
                            <input type="text" id="father_name" name="father_name" value="{{ old('father_name', $teacher->father_name) }}">
                        </div>
                        <div class="field">
                            <label>Mother's Name</label>
                            <input type="text" id="mother_name" name="mother_name" value="{{ old('mother_name', $teacher->mother_name) }}">
                        </div>
                        <div class="field">
                            <label>Date of Birth</label>
                            <input type="date" id="dob" name="dob" value="{{ old('dob', $teacher->dob) }}">
                        </div>
                        <div class="field">
                            <label>Gender</label>
                            <div class="radio-group">
                                <label><input type="radio" name="gender" value="Male" {{ $teacher->gender == 'Male' ? 'checked' : '' }}> Male</label>
                                <label><input type="radio"name="gender" value="Female" {{ $teacher->gender == 'Female' ? 'checked' : '' }}> Female</label>
                                <label><input type="radio" name="gender" value="Other" {{ $teacher->gender == 'Other' ? 'checked' : '' }}> Other</label>
                            </div>
                        </div>
                        <div class="field">
                            <label>Marital Status</label>
                            <select id="marital_status" name="marital_status">
                                <option value="">Select</option>
                                <option value="Married" {{ $teacher->marital_status == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Unmarried" {{ $teacher->marital_status == 'Unmarried' ? 'selected' : '' }}>Unmarried</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Mobile <span class="req">*</span></label>
                            <input type="tel" id="mobile" name="mobile" value="{{ old('mobile', $teacher->mobile) }}" pattern="[0-9]{10}" required>
                        </div>
                        <div class="field">
                            <label>Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $teacher->email) }}">
                        </div>
                        <div class="field">
                            <label>Experience (Years)</label>
                            <input type="number" id="experience" name="experience" value="{{ old('experience', $teacher->experience) }}" min="0" step="1">
                        </div>
                        <div class="field">
                            <label>Teacher Photo</label>
                            <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                            @if($teacher->image)
                            <div class="current-image" id="currentImageDiv">
                                <img src="{{ asset('storage/' . $teacher->image) }}" alt="Current Photo">
                                <span>Current Photo</span>
                            </div>
                            @endif
                            <div id="imagePreview" style="margin-top: 8px; display: none;">
                                <img id="previewImg" src="#" alt="Preview" style="width: 50px; height: 50px; object-fit: cover; border-radius: 10px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ADDRESS SECTION -->
            <div class="form-section">
                <div class="section-title">
                    <h3><i class="fas fa-map-marker-alt"></i> Address Details</h3>
                </div>
                <div class="section-content">
                    <div class="form-grid">
                        <div class="field full-width">
                            <label>Address</label>
                            <textarea id="address" name="address" rows="2">{{ old('address', $teacher->address) }}</textarea>
                        </div>
                        <div class="field"><label>City</label><input type="text" id="city" name="city" value="{{ old('city', $teacher->city) }}"></div>
                        <div class="field"><label>Block</label><input type="text" id="block" name="block" value="{{ old('block', $teacher->block) }}"></div>
                        <div class="field"><label>District</label><input type="text" id="district" name="district" value="{{ old('district', $teacher->district) }}"></div>
                        <div class="field"><label>State</label><input type="text" id="state" name="state" value="{{ old('state', $teacher->state) }}"></div>
                        <div class="field"><label>Pincode</label><input type="text" id="pincode" name="pincode" value="{{ old('pincode', $teacher->pincode) }}" pattern="[0-9]{6}"></div>
                    </div>
                </div>
            </div>

            <!-- EDUCATIONAL INFO -->
            <div class="form-section">
                <div class="section-title">
                    <h3><i class="fas fa-graduation-cap"></i> Educational Information</h3>
                </div>
                <div class="section-content">
                    <div class="form-grid">
                        <div class="field"><label>Highest Qualification</label><input type="text" id="highest_qualification" name="highest_qualification" value="{{ old('highest_qualification', $teacher->highest_qualification) }}"></div>
                        <div class="field"><label>Institute Name</label><input type="text" id="institute_name" name="institute_name" value="{{ old('institute_name', $teacher->institute_name) }}"></div>
                        <div class="field"><label>Passing Year</label><input type="number" id="passing_year" name="passing_year" value="{{ old('passing_year', $teacher->passing_year) }}" min="1950" max="2030"></div>
                        <div class="field"><label>Obtained Marks</label><input type="text" id="obtained_marks" name="obtained_marks" value="{{ old('obtained_marks', $teacher->obtained_marks) }}"></div>
                    </div>
                </div>
            </div>

            <!-- BANK INFORMATION -->
            <div class="form-section">
                <div class="section-title">
                    <h3><i class="fas fa-university"></i> Bank Information</h3>
                </div>
                <div class="section-content">
                    <div class="form-grid">
                        <div class="field"><label>Bank Name</label><input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name', $teacher->bank_name) }}"></div>
                        <div class="field"><label>Account Number</label><input type="text" id="account_no" name="account_no" value="{{ old('account_no', $teacher->account_no) }}"></div>
                        <div class="field"><label>IFSC Code</label><input type="text" id="ifsc_code" name="ifsc_code" value="{{ old('ifsc_code', $teacher->ifsc_code) }}"></div>
                        <div class="field"><label>Salary Type</label><select id="salary_type" name="salary_type"><option value="bank" {{ $teacher->salary_type == 'bank' ? 'selected' : '' }}>Bank</option><option value="cash" {{ $teacher->salary_type == 'cash' ? 'selected' : '' }}>Cash</option></select></div>
                        <div class="field"><label>Salary (₹)</label><input type="number" id="salary" name="salary" step="0.01" min="0" value="{{ old('salary', $teacher->salary) }}"></div>
                    </div>
                </div>
            </div>

            <!-- OTHER INFORMATION -->
            <div class="form-section">
                <div class="section-title">
                    <h3><i class="fas fa-info-circle"></i> Other Information</h3>
                </div>
                <div class="section-content">
                    <div class="form-grid">
                        <div class="field"><label>Registration Date</label><input type="date" id="registration_date" name="registration_date" value="{{ old('registration_date', $teacher->registration_date ?? date('Y-m-d')) }}"></div>
                        <div class="field">
                                <label>Is Hosteler?</label>
                                <div class="checkbox-group">
                                    <input type="hidden" name="is_hosteler" value="0">
                                    <label>
                                        <input type="checkbox" name="is_hosteler" id="is_hosteler" value="1" {{ $teacher->is_hosteler ? 'checked' : '' }}> 
                                        Yes, Hosteler
                                    </label>
                                </div>
                                <small style="color: #64748b;">Check if teacher lives in hostel</small>
                            </div>
                            <div class="field">
                            <label>Status <span class="req">*</span></label>
                            <div class="radio-group">
                                <label>
                                    <input type="radio" name="status" value="1" {{ $teacher->status == 1 ? 'checked' : '' }}> 
                                    <span class="status-badge status-active"> Active</span>
                                </label>
                                <label>
                                    <input type="radio" name="status" value="0" {{ $teacher->status == 0 ? 'checked' : '' }}> 
                                    <span class="status-badge status-inactive">Inactive</span>
                                </label>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>

            <div class="btn-area">
                <button type="button" class="btn-back" onclick="window.location.href='{{ route('teacher.list') }}'">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
                <button type="button" class="btn-reset" onclick="resetForm()">Reset</button>
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Update Teacher</button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const currentDiv = document.getElementById('currentImageDiv');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
                if(currentDiv) currentDiv.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
            if(currentDiv) currentDiv.style.display = 'flex';
        }
    }

    function resetForm() {
        location.reload();
    }

    // Replace your form submission with this
document.getElementById('teacherForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validation
    if(!document.getElementById('full_name').value) {
        Swal.fire('Error!', 'Please enter Full Name', 'error');
        return;
    }
    let mobile = document.getElementById('mobile').value;
    if(!mobile || !/^\d{10}$/.test(mobile)) {
        Swal.fire('Error!', 'Please enter valid 10-digit Mobile Number', 'error');
        return;
    }
    
    let formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('full_name', document.getElementById('full_name').value);
    formData.append('father_name', document.getElementById('father_name').value);
    formData.append('mother_name', document.getElementById('mother_name').value);
    formData.append('dob', document.getElementById('dob').value);
        let statusValue = 0;
        document.querySelectorAll('input[name="status"]').forEach(r => { 
                if(r.checked) statusValue = parseInt(r.value); 
                });
formData.append('status', statusValue);
    let gender = '';
    document.querySelectorAll('input[name="gender"]').forEach(r => { if(r.checked) gender = r.value; });
    formData.append('gender', gender);
    
    formData.append('marital_status', document.getElementById('marital_status').value);
    formData.append('mobile', document.getElementById('mobile').value);
    formData.append('email', document.getElementById('email').value);
    formData.append('experience', document.getElementById('experience').value);
    formData.append('address', document.getElementById('address').value);
    formData.append('city', document.getElementById('city').value);
    formData.append('block', document.getElementById('block').value);
    formData.append('district', document.getElementById('district').value);
    formData.append('state', document.getElementById('state').value);
    formData.append('pincode', document.getElementById('pincode').value);
    formData.append('highest_qualification', document.getElementById('highest_qualification').value);
    formData.append('institute_name', document.getElementById('institute_name').value);
    formData.append('passing_year', document.getElementById('passing_year').value);
    formData.append('obtained_marks', document.getElementById('obtained_marks').value);
    formData.append('bank_name', document.getElementById('bank_name').value);
    formData.append('account_no', document.getElementById('account_no').value);
    formData.append('ifsc_code', document.getElementById('ifsc_code').value);
    formData.append('salary_type', document.getElementById('salary_type').value);
    formData.append('salary', document.getElementById('salary').value);
    formData.append('registration_date', document.getElementById('registration_date').value);
    
        // ✅ CRITICAL FIX - Force set is_hosteler value
        const isHostelerCheckbox = document.getElementById('is_hosteler');
        const isHostelerValue = isHostelerCheckbox.checked ? 1 : 0;
        formData.append('is_hosteler', isHostelerValue);
        
        // Debug
        console.log('Checkbox checked:', isHostelerCheckbox.checked);
        console.log('is_hosteler value:', isHostelerValue);
        
        formData.append('_token', '{{ csrf_token() }}');
        
        const imageFile = document.getElementById('image').files[0];
        if (imageFile) formData.append('image', imageFile);
        
        Swal.fire({
            title: 'Updating...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        fetch('{{ route("teacher.update", $teacher->id) }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: data.message,
                    confirmButtonColor: '#1e3c72'
                }).then(() => {
                    window.location.href = '{{ route("teacher.list") }}';
                });
            } else if(data.errors) {
                let errorMsg = '';
                for(let key in data.errors) {
                    errorMsg += data.errors[key][0] + '\n';
                }
                Swal.fire({ icon: 'error', title: 'Validation Error', text: errorMsg });
            } else {
                Swal.fire({ icon: 'error', title: 'Error!', text: data.error || 'Something went wrong!' });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({ icon: 'error', title: 'Error!', text: 'Something went wrong!' });
        });
    });
</script>
@endsection