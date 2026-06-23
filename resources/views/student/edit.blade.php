@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')
<style>
    .reg-container {
        animation: fadeIn 0.3s ease;
    }

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

    .reg-header h2 {
        font-size: 1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .session-badge {
        background: rgba(255,255,255,0.2);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
    }

    .reg-form {
        background: white;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
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

    .field label .opt {
        color: #94a3b8;
        font-weight: normal;
        font-size: 0.6rem;
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
        background: #eef2f7;
        color: #2c3e50;
        border: 1px solid #cbdde9;
    }

    .full-width {
        grid-column: 1 / -1;
    }

    .current-image {
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .current-image img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }

    @media (max-width: 768px) {
        .reg-form {
            padding: 10px;
        }
        .form-grid {
            grid-template-columns: 1fr;
        }
        .reg-header {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<div class="reg-container">
    <div class="reg-header">
        <h2>
            <i class="fas fa-edit"></i> Edit Student
        </h2>
        <div class="session-badge">
            <i class="fas fa-id-card"></i> Student ID: <strong>{{ $student->student_id }}</strong>
        </div>
    </div>

    <div class="reg-form">
        <form id="studentForm" enctype="multipart/form-data">
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
                            <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $student->full_name) }}" required>
                        </div>
                        <div class="field">
                            <label>Father's Name</label>
                            <input type="text" id="father_name" name="father_name" value="{{ old('father_name', $student->father_name) }}">
                        </div>
                        <div class="field">
                            <label>Mother's Name</label>
                            <input type="text" id="mother_name" name="mother_name" value="{{ old('mother_name', $student->mother_name) }}">
                        </div>
                        <div class="field">
                            <label>DOB</label>
                            <input type="date" id="dob" name="dob" value="{{ old('dob', $student->dob) }}">
                        </div>
                        <div class="field">
                            <label>Gender</label>
                            <div class="radio-group">
                                <label><input type="radio" name="gender" value="Male" {{ $student->gender == 'Male' ? 'checked' : '' }}> Male</label>
                                <label><input type="radio" name="gender" value="Female" {{ $student->gender == 'Female' ? 'checked' : '' }}> Female</label>
                                <label><input type="radio" name="gender" value="Other" {{ $student->gender == 'Other' ? 'checked' : '' }}> Other</label>
                            </div>
                        </div>
                        <div class="field">
                            <label>Category</label>
                            <select id="category" name="category">
                                <option value="">Select</option>
                                <option value="General" {{ $student->category == 'General' ? 'selected' : '' }}>General</option>
                                <option value="ST" {{ $student->category == 'ST' ? 'selected' : '' }}>ST</option>
                                <option value="SC" {{ $student->category == 'SC' ? 'selected' : '' }}>SC</option>
                                <option value="OBC" {{ $student->category == 'OBC' ? 'selected' : '' }}>OBC</option>
                                <option value="Others" {{ $student->category == 'Others' ? 'selected' : '' }}>Others</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Mobile <span class="req">*</span></label>
                            <input type="tel" id="mobile" name="mobile" value="{{ old('mobile', $student->mobile) }}" pattern="[0-9]{10}" required>
                        </div>
                        <div class="field">
                            <label>Student Photo</label>
                            <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                            @if($student->image)
                            <div class="current-image" id="currentImageDiv">
                                <img src="{{ asset('storage/' . $student->image) }}" alt="Current Photo">
                                <span>Current Photo</span>
                            </div>
                            @endif
                            <div id="imagePreview" style="margin-top: 8px; display: none;">
                                <img id="previewImg" src="#" alt="Preview" style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ADDRESS -->
            <div class="form-section">
                <div class="section-title">
                    <h3><i class="fas fa-map-marker-alt"></i> Address Details</h3>
                </div>
                <div class="section-content">
                    <div class="form-grid">
                        <div class="field full-width">
                            <label>Address <span class="req">*</span></label>
                            <textarea id="address" name="address" rows="2" required>{{ old('address', $student->address) }}</textarea>
                        </div>
                        <div class="field">
                            <label>City <span class="req">*</span></label>
                            <input type="text" id="city" name="city" value="{{ old('city', $student->city) }}" required>
                        </div>
                        <div class="field">
                            <label>State <span class="req">*</span></label>
                            <input type="text" id="state" name="state" value="{{ old('state', $student->state) }}" required>
                        </div>
                        <div class="field">
                            <label>Pincode <span class="req">*</span></label>
                            <input type="text" id="pincode" name="pincode" value="{{ old('pincode', $student->pincode) }}" pattern="[0-9]{6}" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ACADEMIC DETAILS -->
            <div class="form-section">
                <div class="section-title">
                    <h3><i class="fas fa-graduation-cap"></i> Academic Details</h3>
                </div>
                <div class="section-content">
                    <div class="form-grid">
                        <div class="field">
                            <label>Class <span class="req">*</span></label>
                            <select id="class_id" name="class_id" required>
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ $student->class_id == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Previous Institute</label>
                            <input type="text" id="previous_institute" name="previous_institute" value="{{ old('previous_institute', $student->previous_institute) }}">
                        </div>
                        <div class="field">
                            <label>Previous Class</label>
                            <input type="text" id="previous_class" name="previous_class" value="{{ old('previous_class', $student->previous_class) }}">
                        </div>
                        <div class="field">
                            <label>Result</label>
                            <select id="previous_result" name="previous_result">
                                <option value="">Select</option>
                                <option value="Pass" {{ $student->previous_result == 'Pass' ? 'selected' : '' }}>Pass</option>
                                <option value="Fail" {{ $student->previous_result == 'Fail' ? 'selected' : '' }}>Fail</option>
                                <option value="Supply" {{ $student->previous_result == 'Supply' ? 'selected' : '' }}>Supply</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Marks (%)</label>
                            <input type="text" id="previous_marks" name="previous_marks" value="{{ old('previous_marks', $student->previous_marks) }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- MISCELLANEOUS DETAILS -->
            <div class="form-section">
                <div class="section-title">
                    <h3><i class="fas fa-paperclip"></i> Miscellaneous Details</h3>
                </div>
                <div class="section-content">
                    <div class="form-grid">
                        <div class="field">
                            <label>Is Hosteler?</label>
                            <div class="checkbox-group">
                                <label>
                                    <input type="checkbox" id="is_hosteler" name="is_hosteler" value="1" {{ $student->is_hosteler ? 'checked' : '' }}> 
                                    Yes, Hosteler
                                </label>
                            </div>
                        </div>
                        <div class="field">
                            <label>Is Promoted?</label>
                            <div class="checkbox-group">
                                <label>
                                    <input type="checkbox" id="is_promoted" name="is_promoted" value="1" {{ $student->is_promoted ? 'checked' : '' }}> 
                                    Yes, Promoted
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="btn-area">
                <button type="button" class="btn-back" onclick="window.location.href='{{ route('student.list') }}'">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
                <button type="button" class="btn-reset" id="resetBtn">Reset</button>
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Update Student</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('studentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validations
    if(!document.getElementById('full_name').value) {
        Swal.fire('Error!', 'Please enter Full Name', 'error');
        return;
    }
    let mobile = document.getElementById('mobile').value;
    if(!mobile || !/^\d{10}$/.test(mobile)) {
        Swal.fire('Error!', 'Please enter valid 10-digit Mobile Number', 'error');
        return;
    }
    if(!document.getElementById('address').value) {
        Swal.fire('Error!', 'Please enter Address', 'error');
        return;
    }
    if(!document.getElementById('city').value) {
        Swal.fire('Error!', 'Please enter City', 'error');
        return;
    }
    if(!document.getElementById('state').value) {
        Swal.fire('Error!', 'Please enter State', 'error');
        return;
    }
    let pincode = document.getElementById('pincode').value;
    if(!pincode || !/^\d{6}$/.test(pincode)) {
        Swal.fire('Error!', 'Please enter valid 6-digit Pincode', 'error');
        return;
    }
    if(!document.getElementById('class_id').value) {
        Swal.fire('Error!', 'Please select Class', 'error');
        return;
    }
    
    let formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('full_name', document.getElementById('full_name').value);
    formData.append('father_name', document.getElementById('father_name').value);
    formData.append('mother_name', document.getElementById('mother_name').value);
    formData.append('dob', document.getElementById('dob').value);
    formData.append('category', document.getElementById('category').value);
    formData.append('mobile', document.getElementById('mobile').value);
    formData.append('address', document.getElementById('address').value);
    formData.append('city', document.getElementById('city').value);
    formData.append('state', document.getElementById('state').value);
    formData.append('pincode', document.getElementById('pincode').value);
    formData.append('class_id', document.getElementById('class_id').value);
    formData.append('previous_institute', document.getElementById('previous_institute').value);
    formData.append('previous_class', document.getElementById('previous_class').value);
    formData.append('previous_result', document.getElementById('previous_result').value);
    formData.append('previous_marks', document.getElementById('previous_marks').value);
    formData.append('_token', '{{ csrf_token() }}');
    
    // Gender
    let gender = '';
    document.querySelectorAll('input[name="gender"]').forEach(r => { if(r.checked) gender = r.value; });
    formData.append('gender', gender);
    
    // Checkboxes - Important: Send 0 if unchecked
    let isHosteler = document.getElementById('is_hosteler').checked ? 1 : 0;
    let isPromoted = document.getElementById('is_promoted').checked ? 1 : 0;
    formData.append('is_hosteler', isHosteler);
    formData.append('is_promoted', isPromoted);
    
    // Image
    const imageFile = document.getElementById('image').files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }
    
    Swal.fire({
        title: 'Updating...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });
    
    fetch('{{ route("student.update", $student->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
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
                window.location.href = '{{ route("student.list") }}';
            });
        } else if(data.errors) {
            let errorMsg = '';
            for(let key in data.errors) {
                errorMsg += data.errors[key][0] + '\n';
            }
            Swal.fire({ icon: 'error', title: 'Validation Error', text: errorMsg });
        } else if(data.error) {
            Swal.fire({ icon: 'error', title: 'Error!', text: data.error });
        }
    })
    .catch(error => {
        Swal.fire({ icon: 'error', title: 'Error!', text: 'Something went wrong!' });
    });
});

document.getElementById('resetBtn').addEventListener('click', function() {
    document.getElementById('studentForm').reset();
    location.reload();
});

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
</script>
@endsection