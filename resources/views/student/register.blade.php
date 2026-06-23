@extends('layouts.app')

@section('title', 'Student Registration')

@section('content')
<style>
    .reg-container {
        animation: fadeIn 0.3s ease;
    }

    /* Compact Header */
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

    /* Compact Form */
    .reg-form {
        background: white;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }

    /* Sections */
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

    .section-title h3 i {
        font-size: 0.8rem;
    }

    .section-content {
        padding: 10px 12px;
    }

    /* Compact Grid */
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

    /* Radio & Checkbox */
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

    /* Misc Rows - 2 rows me 4 columns */
    .misc-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .misc-item {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .misc-item .misc-title {
        flex: 1;
    }

    .misc-item .misc-doc {
        flex: 1;
    }
    .hidden{display: none;}
    .misc-item label {
        font-weight: 600;
        color: #1f4970;
        font-size: 0.65rem;
        display: block;
        margin-bottom: 2px;
    }

    .misc-item input {
        width: 100%;
    }

    hr {
        margin: 8px 0;
        border: none;
        height: 1px;
        background: #e2edf7;
    }

    /* Buttons */
    .btn-area {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 12px;
    }

    .btn-submit, .btn-reset {
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

    .btn-reset:hover {
        background: #e2e8f0;
    }

    /* Full width */
    .full-width {
        grid-column: 1 / -1;
    }

    @media (max-width: 768px) {
        .reg-form {
            padding: 10px;
        }
        .form-grid {
            grid-template-columns: 1fr;
        }
        .misc-grid {
            grid-template-columns: 1fr;
        }
        .misc-item {
            flex-direction: column;
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
            <i class="fas fa-user-graduate"></i> Student Registration
        </h2>
        <div class="session-badge">
            <i class="fas fa-calendar-alt"></i> Session: <strong>{{ $currentSession->session_name ?? 'No Active Session' }}</strong>
        </div>
    </div>

    <div class="reg-form">
        <form id="studentForm" enctype="multipart/form-data">
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
                            <label>DOB</label>
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
                            <label>Category</label>
                            <select id="category" name="category">
                                <option value="">Select</option>
                                <option value="General">General</option>
                                <option value="ST">ST</option>
                                <option value="SC">SC</option>
                                <option value="OBC">OBC</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Mobile <span class="req">*</span></label>
                            <input type="tel" id="mobile" name="mobile" placeholder="10-digit" pattern="[0-9]{10}" required>
                        </div>
                        <div class="field">
                            <label>Student Photo</label>
                            <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                            <div id="imagePreview" style="margin-top: 8px; display: none;">
                                <img id="previewImg" src="#" alt="Preview" style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px; border: 1px solid #e2e8f0;">
                            </div>
                            <small style="color: #64748b;">Max 2MB (JPG, PNG, GIF)</small>
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
                            <textarea id="address" name="address" rows="2" placeholder="House/Street/Locality" required></textarea>
                        </div>
                        <div class="field">
                            <label>City <span class="req">*</span></label>
                            <input type="text" id="city" name="city" placeholder="City" required>
                        </div>
                        <div class="field">
                            <label>State <span class="req">*</span></label>
                            <input type="text" id="state" name="state" placeholder="State" required>
                        </div>
                        <div class="field">
                            <label>Pincode <span class="req">*</span></label>
                            <input type="text" id="pincode" name="pincode" placeholder="6 digits" pattern="[0-9]{6}" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ACADEMIC DETAILS - Only Class is Mandatory -->
            <div class="form-section">
                <div class="section-title">
                    <h3><i class="fas fa-graduation-cap"></i> Academic Details</h3>
                </div>
                <div class="section-content">
                    <div class="form-grid">
                        <div class="field">
                            <label>Applying Class <span class="req">*</span></label>
                            <select id="class_id" name="class_id" required>
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Previous Institute <span class="opt">(Optional)</span></label>
                            <input type="text" id="previous_institute" name="previous_institute" placeholder="School/College name">
                        </div>
                        <div class="field">
                            <label>Previous Class <span class="opt">(Optional)</span></label>
                            <input type="text" id="previous_class" name="previous_class" placeholder="e.g., Nursery, LKG">
                        </div>
                        <div class="field">
                            <label>Result <span class="opt">(Optional)</span></label>
                            <select id="previous_result" name="previous_result">
                                <option value="">Select</option>
                                <option value="Pass">Pass</option>
                                <option value="Fail">Fail</option>
                                <option value="Supply">Supply</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Marks (%) <span class="opt">(Optional)</span></label>
                            <input type="text" id="previous_marks" name="previous_marks" placeholder="e.g., 85%">
                        </div>
                    </div>
                </div>
            </div>

            <!-- MISCELLANEOUS DETAILS - 4 items in 2 rows -->
            <div class="form-section">
                <div class="section-title">
                    <h3><i class="fas fa-paperclip"></i> Miscellaneous Details</h3>
                </div>
                <div class="section-content">
                    <div class="misc-grid">
                        @for($i = 1; $i <= 4; $i++)
                        <div class="misc-item">
                            <div class="misc-title">
                                <label>Title {{ $i }}</label>
                                <input type="text" id="misc_title_{{ $i }}" placeholder="e.g., Aadhar Card">
                            </div>
                            <div class="misc-doc">
                                <label>Document No.</label>
                                <input type="text" id="misc_doc_{{ $i }}" placeholder="Number">
                            </div>
                        </div>
                        @endfor
                    </div>
                    
                    <hr>
                    
                    <div class="form-grid">
                        <div class="field hidden">
                            <label>Is Promoted?</label>
                            <div class="checkbox-group">
                                <label><input type="checkbox" id="is_promoted" name="is_promoted" value="0"> Promoted to next class</label>
                            </div>
                        </div>
                        <div class="field">
                                <label>Is Hosteler?</label>
                                <div class="checkbox-group">
                                    <input type="hidden" name="is_hosteler" value="0">
                                    <label>
                                        <input type="checkbox" name="is_hosteler" value="1" id="is_hosteler"> 
                                        Hosteler
                                    </label>
                                </div>
                            </div>
                                                </div>
                </div>
            </div>


            <div class="btn-area">
                <button type="button" class="btn-reset" id="resetBtn">Reset</button>
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Register Student</button>
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
    formData.append('full_name', document.getElementById('full_name').value);
    formData.append('father_name', document.getElementById('father_name').value);
    formData.append('mother_name', document.getElementById('mother_name').value);
    formData.append('dob', document.getElementById('dob').value);
    
    let gender = '';
    document.querySelectorAll('input[name="gender"]').forEach(r => { if(r.checked) gender = r.value; });
    formData.append('gender', gender);

    let isHostelerInput = document.querySelector('input[name="is_hosteler"]:checked');
        let isHostelerValue = 0;
    
        if (isHostelerInput && isHostelerInput.value == '1') {
        isHostelerValue = 1;
        }
    
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
    formData.append('is_promoted', document.getElementById('is_promoted').checked ? 1 : 0);
    formData.append('is_hosteler', isHostelerValue);
    formData.append('_token', '{{ csrf_token() }}');
    
    // Add image file
    const imageFile = document.getElementById('image').files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }
    
    // Misc fields
    for(let i = 1; i <= 4; i++) {
        formData.append(`misc_title_${i}`, document.getElementById(`misc_title_${i}`).value);
        formData.append(`misc_doc_${i}`, document.getElementById(`misc_doc_${i}`).value);
    }
    
    Swal.fire({
        title: 'Registering...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });
    
    fetch('{{ route("student.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData  // Don't set Content-Type header, browser will set it with boundary
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Student Registered!',
                html: `<strong>Student ID:</strong> ${data.student_id}<br>${data.message}`,
                confirmButtonColor: '#1e3c72'
            }).then(() => {
                document.getElementById('studentForm').reset();
                document.getElementById('imagePreview').style.display = 'none';
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
        console.error('Error:', error);
        Swal.fire({ icon: 'error', title: 'Error!', text: 'Something went wrong!' });
    });
});
document.getElementById('resetBtn').addEventListener('click', function() {
    document.getElementById('studentForm').reset();
});

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
</script>
@endsection