@extends('layouts.app')

@section('title', 'Teacher Registration')

@section('content')
<style>
    .reg-container { animation: fadeIn 0.3s ease; }
    .reg-header {
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
    .reg-header h2 { font-size: 1rem; margin: 0; display: flex; align-items: center; gap: 6px; }
    .session-badge { background: rgba(255,255,255,0.2); padding: 3px 10px; border-radius: 20px; font-size: 0.65rem; }
    .reg-form { background: white; border-radius: 10px; padding: 15px; border: 1px solid #e2e8f0; }
    .form-section { background: #fefefe; border-radius: 8px; margin-bottom: 12px; border: 1px solid #e2edf7; overflow: hidden; }
    .section-title { background: #f8fbff; padding: 6px 12px; border-bottom: 1px solid #cbdde9; }
    .section-title h3 { font-size: 0.8rem; font-weight: 600; color: #1e4663; margin: 0; display: flex; align-items: center; gap: 5px; }
    .section-content { padding: 10px 12px; }
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 8px 12px; }
    .field { display: flex; flex-direction: column; gap: 3px; }
    .field label { font-weight: 600; color: #1f3b4c; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.3px; }
    .field label .req { color: #dc2626; }
    .field label .opt { color: #94a3b8; font-weight: normal; font-size: 0.6rem; }
    input, select, textarea { padding: 5px 8px; border: 1px solid #d4dee8; border-radius: 6px; font-size: 0.75rem; font-family: inherit; transition: 0.2s; background: white; }
    input:focus, select:focus, textarea:focus { outline: none; border-color: #1e3c72; box-shadow: 0 0 0 2px rgba(30,60,114,0.1); }
    textarea { resize: vertical; min-height: 45px; }
    .radio-group, .checkbox-group { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; margin-top: 2px; }
    .radio-group label, .checkbox-group label { display: flex; align-items: center; gap: 4px; font-weight: 500; text-transform: none; font-size: 0.7rem; }
    .misc-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .misc-item { display: flex; gap: 8px; align-items: center; }
    .misc-item .misc-title { flex: 1; }
    .misc-item .misc-doc { flex: 1; }
    .misc-item label { font-weight: 600; color: #1f4970; font-size: 0.65rem; display: block; margin-bottom: 2px; }
    hr { margin: 8px 0; border: none; height: 1px; background: #e2edf7; }
    .btn-area { display: flex; justify-content: flex-end; gap: 8px; margin-top: 12px; }
    .btn-submit, .btn-reset { padding: 6px 16px; border: none; border-radius: 6px; font-weight: 600; font-size: 0.75rem; cursor: pointer; transition: 0.2s; }
    .btn-submit { background: linear-gradient(135deg, #1e3c72, #0f2b4d); color: white; }
    .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 2px 6px rgba(0,0,0,0.15); }
    .btn-reset { background: #eef2f7; color: #2c3e50; border: 1px solid #cbdde9; }
    .full-width { grid-column: 1 / -1; }
    @media (max-width: 768px) {
        .reg-form { padding: 10px; }
        .form-grid { grid-template-columns: 1fr; }
        .misc-grid { grid-template-columns: 1fr; }
        .misc-item { flex-direction: column; }
        .reg-header { flex-direction: column; text-align: center; }
    }
</style>

<div class="reg-container">
    <div class="reg-header">
        <h2><i class="fas fa-chalkboard-user"></i> Teacher Registration</h2>
        <div class="session-badge">
            <i class="fas fa-calendar-alt"></i> Session: <strong>{{ $currentSession->session_name ?? 'No Active Session' }}</strong>
        </div>
    </div>

    <div class="reg-form">
        <form id="teacherForm" enctype="multipart/form-data">
            @csrf
            
            <!-- PERSONAL INFORMATION -->
            <div class="form-section">
                <div class="section-title">
                    <h3><i class="fas fa-user"></i> Personal Information</h3>
                </div>
                <div class="section-content">
                    <div class="form-grid">
                        <div class="field">
                            <label>Full Name <span class="req">*</span></label>
                            <input type="text" id="full_name" name="full_name" placeholder="Full name" required>
                        </div>
                        <div class="field">
                            <label>Father's Name</label>
                            <input type="text" id="father_name" name="father_name" placeholder="Father's name">
                        </div>
                        <div class="field">
                            <label>Mother's Name</label>
                            <input type="text" id="mother_name" name="mother_name" placeholder="Mother's name">
                        </div>
                        <div class="field">
                            <label>Date of Birth</label>
                            <input type="date" id="dob" name="dob">
                        </div>
                        <div class="field">
                            <label>Gender</label>
                            <div class="radio-group">
                                <label><input type="radio" name="gender" value="Male"> Male</label>
                                <label><input type="radio" name="gender" value="Female"> Female</label>
                                <label><input type="radio" name="gender" value="Other"> Other</label>
                            </div>
                        </div>
                        <div class="field">
                            <label>Marital Status</label>
                            <select id="marital_status" name="marital_status">
                                <option value="">Select</option>
                                <option value="Married">Married</option>
                                <option value="Unmarried">Unmarried</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Mobile <span class="req">*</span></label>
                            <input type="tel" id="mobile" name="mobile" placeholder="10-digit" pattern="[0-9]{10}" required>
                        </div>
                        <div class="field">
                            <label>Email</label>
                            <input type="email" id="email" name="email" placeholder="Email address">
                        </div>
                        <div class="field">
                            <label>Experience (Years)</label>
                            <input type="number" id="experience" name="experience" placeholder="Years" min="0" step="1" value="0">
                        </div>
                        <div class="field">
                            <label>Teacher Photo</label>
                            <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                            <div id="imagePreview" style="margin-top: 8px; display: none;">
                                <img id="previewImg" src="#" alt="Preview" style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px;">
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
                            <textarea id="address" name="address" rows="2" placeholder="House/Street/Locality"></textarea>
                        </div>
                        <div class="field"><label>City</label><input type="text" id="city" name="city"></div>
                        <div class="field"><label>Block</label><input type="text" id="block" name="block"></div>
                        <div class="field"><label>District</label><input type="text" id="district" name="district"></div>
                        <div class="field"><label>State</label><input type="text" id="state" name="state"></div>
                        <div class="field"><label>Pincode</label><input type="text" id="pincode" name="pincode" pattern="[0-9]{6}"></div>
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
                        <div class="field"><label>Highest Qualification</label><input type="text" id="highest_qualification" name="highest_qualification"></div>
                        <div class="field"><label>Institute Name</label><input type="text" id="institute_name" name="institute_name"></div>
                        <div class="field"><label>Passing Year</label><input type="number" id="passing_year" name="passing_year" min="1950" max="2030"></div>
                        <div class="field"><label>Obtained Marks</label><input type="text" id="obtained_marks" name="obtained_marks" placeholder="e.g., 75% or 8.5 CGPA"></div>
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
                        <div class="field"><label>Bank Name</label><input type="text" id="bank_name" name="bank_name"></div>
                        <div class="field"><label>Account Number</label><input type="text" id="account_no" name="account_no"></div>
                        <div class="field"><label>IFSC Code</label><input type="text" id="ifsc_code" name="ifsc_code" placeholder="e.g., SBIN0001234"></div>
                        <div class="field"><label>Salary Type</label><select id="salary_type" name="salary_type"><option value="bank">Bank</option><option value="cash">Cash</option></select></div>
                        <div class="field"><label>Salary (₹)</label><input type="number" id="salary" name="salary" step="0.01" min="0"></div>
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
                        <div class="field"><label>Registration Date</label><input type="date" id="registration_date" name="registration_date" value="{{ date('Y-m-d') }}"></div>
                        <div class="field"><label>Is Hosteler?</label><div class="checkbox-group"><input type="hidden" name="is_hosteler" value="0"><label><input type="checkbox" name="is_hosteler" id="is_hosteler" value="1"> 
                            Yes, Hosteler</label></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="btn-area">
                <button type="button" class="btn-reset" id="resetBtn">Reset</button>
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Register Teacher</button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }

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
        formData.append('full_name', document.getElementById('full_name').value);
        formData.append('father_name', document.getElementById('father_name').value);
        formData.append('mother_name', document.getElementById('mother_name').value);
        formData.append('dob', document.getElementById('dob').value);
        
        let gender = '';
        document.querySelectorAll('input[name="gender"]').forEach(r => { if(r.checked) gender = r.value; });
        formData.append('gender', gender);
        
        // Get checkbox value
         let isHostelerInput = document.querySelector('input[name="is_hosteler"]:checked');
        let isHostelerValue = 0;
    
        if (isHostelerInput && isHostelerInput.value == '1') {
        isHostelerValue = 1;
        }
        
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
        formData.append('is_hosteler', isHostelerValue);
        formData.append('_token', '{{ csrf_token() }}');
        
        const imageFile = document.getElementById('image').files[0];
        if (imageFile) formData.append('image', imageFile);
        
        Swal.fire({
            title: 'Registering...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        fetch('{{ route("teacher.store") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Registered!',
                    html: `<strong>Teacher ID:</strong> ${data.teacher_id}<br>${data.message}`,
                    confirmButtonColor: '#1e3c72'
                }).then(() => {
                    document.getElementById('teacherForm').reset();
                    document.getElementById('imagePreview').style.display = 'none';
                });
            } else if(data.errors) {
                let errorMsg = '';
                for(let key in data.errors) {
                    errorMsg += data.errors[key][0] + '\n';
                }
                Swal.fire({ icon: 'error', title: 'Validation Error', text: errorMsg });
            } else {
                Swal.fire({ icon: 'error', title: 'Error!', text: data.error });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({ icon: 'error', title: 'Error!', text: 'Something went wrong!' });
        });
    });
    
    document.getElementById('resetBtn').addEventListener('click', function() {
        document.getElementById('teacherForm').reset();
        document.getElementById('imagePreview').style.display = 'none';
    });
</script>
@endsection