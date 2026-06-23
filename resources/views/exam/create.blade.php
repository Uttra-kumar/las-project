@extends('layouts.app')

@section('title', 'Add Exam')

@section('content')
<style>
    .exam-form-container { animation: fadeIn 0.3s ease; }
    .exam-form-header {
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
    .exam-form-header h2 { font-size: 1rem; margin: 0; display: flex; align-items: center; gap: 6px; }
    .exam-form-header h2 i { color: #f59e0b; }
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
    
    .form-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #e2e8f0;
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        gap: 16px;
        margin-bottom: 14px;
    }
    .form-group { margin-bottom: 0; }
    .form-group label {
        display: block;
        font-size: 0.6rem;
        font-weight: 700;
        color: #1e3c72;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .form-group label .required { color: #ef4444; }
    .form-group input, .form-group select {
        width: 100%;
        padding: 6px 10px;
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
        background: #f8fafc;
        transition: all 0.3s;
    }
    .form-group input:focus, .form-group select:focus {
        outline: none;
        border-color: #1e3c72;
        box-shadow: 0 0 0 3px rgba(30, 60, 114, 0.1);
        background: white;
    }
    
    .subject-table-wrapper {
        margin-top: 15px;
        border-top: 1.5px dashed #e2e8f0;
        padding-top: 15px;
    }
    .subject-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.75rem;
    }
    .subject-table th {
        text-align: left;
        padding: 6px 10px;
        background: #f8fafc;
        font-weight: 700;
        border-bottom: 2px solid #e2e8f0;
        font-size: 0.65rem;
        text-transform: uppercase;
        color: #1e3c72;
    }
    .subject-table td {
        padding: 4px 10px;
        border-bottom: 1px solid #e2e8f0;
    }
    .subject-table select {
        width: 100%;
        padding: 4px 8px;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        font-size: 0.7rem;
    }
    
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
        box-shadow: 0 3px 10px rgba(30, 60, 114, 0.2);
    }
    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 16px rgba(30, 60, 114, 0.3);
    }
    
    @media (max-width: 768px) {
        .form-row { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 480px) {
        .form-row { grid-template-columns: 1fr; }
    }
</style>

<div class="exam-form-container">
    <div class="exam-form-header">
        <h2><i class="fas fa-plus-circle"></i> Add Exam</h2>
        <a href="{{ route('exam.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="form-card">
        <form id="examForm" onsubmit="saveExam(event)">
            @csrf
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-pencil-alt"></i> Exam Name <span class="required">*</span></label>
                    <input type="text" name="exam_name" id="exam_name" placeholder="e.g. Monthly Test" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-calendar-day"></i> Exam Date <span class="required">*</span></label>
                    <input type="date" name="exam_date" id="exam_date" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Exam Type</label>
                    <select name="exam_type" id="exam_type">
                        <option value="">Select Type</option>
                        @foreach($examTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-school"></i> Class <span class="required">*</span></label>
                    <select name="class_id" id="class_id" required onchange="loadSubjects()">
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-code-branch"></i> Stream</label>
                    <select name="stream_id" id="stream_id" onchange="loadSubjects()">
                        <option value="">No Stream</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-comment"></i> Remarks</label>
                    <input type="text" name="remarks" id="remarks" placeholder="Any remarks...">
                </div>
            </div>
            
            <!-- Subjects Section -->
            <div class="subject-table-wrapper" id="subjectSection" style="display:none;">
                <h4 style="font-size:0.8rem; color:#1e3c72; margin-bottom:10px;">
                    <i class="fas fa-book-open"></i> Subject - Teacher Allocation
                </h4>
                <div class="table-wrapper" style="overflow-x:auto;">
                    <table class="subject-table" id="subjectTable">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Subject</th>
                                <th>Teacher</th>
                            </tr>
                        </thead>
                        <tbody id="subjectBody">
                            <tr>
                                <td colspan="3" style="text-align:center; padding:20px; color:#94a3b8;">
                                    <i class="fas fa-spinner fa-spin"></i> Select class to load subjects
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div style="text-align:right; margin-top:20px; border-top:1.5px solid #e2e8f0; padding-top:15px;">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Save Exam
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function loadSubjects() {
    const classId = document.getElementById('class_id').value;
    const streamId = document.getElementById('stream_id').value;
    const section = document.getElementById('subjectSection');
    const body = document.getElementById('subjectBody');
    
    if (!classId) {
        section.style.display = 'none';
        return;
    }
    
    section.style.display = 'block';
    body.innerHTML = '<tr><td colspan="3" style="text-align:center; padding:20px; color:#94a3b8;"><i class="fas fa-spinner fa-spin"></i> Loading subjects...</td></tr>';
    
    let url = `/exam/get-class-data?class_id=${classId}`;
    if (streamId) {
        url += `&stream_id=${streamId}`;
    }
    
    fetch(url)
        .then(response => response.json())
        .then(response => {
            if (response.success && response.data.length > 0) {
                let html = '';
                response.data.forEach((item, index) => {
                    html += `<tr>
                        <td>${index + 1}</td>
                        <td><strong>${item.subject_name}</strong></td>
                        <td>
                            <select name="subjects[${item.subject_id}]" required>
                                <option value="">-- Select Teacher --</option>
                                <option value="${item.teacher_id}">${item.teacher_name}</option>
                            </select>
                        </td>
                    </tr>`;
                });
                body.innerHTML = html;
            } else {
                body.innerHTML = '<tr><td colspan="3" style="text-align:center; padding:20px; color:#94a3b8;"><i class="fas fa-info-circle"></i> No subjects found for this class</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            body.innerHTML = '<tr><td colspan="3" style="text-align:center; padding:20px; color:#ef4444;"><i class="fas fa-exclamation-triangle"></i> Error loading subjects</td></tr>';
        });
}

// Load streams when class changes
document.getElementById('class_id').addEventListener('change', function() {
    const classId = this.value;
    const streamSelect = document.getElementById('stream_id');
    
    streamSelect.innerHTML = '<option value="">No Stream</option>';
    
    if (classId) {
        fetch(`/streams/by-class?class_id=${classId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.streams) {
                    data.streams.forEach(stream => {
                        const option = document.createElement('option');
                        option.value = stream.id;
                        option.textContent = stream.stream_name;
                        streamSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading streams:', error);
            });
    }
});

function saveExam(event) {
    event.preventDefault();
    
    const form = document.getElementById('examForm');
    const formData = new FormData(form);
    
    // ✅ Debug - See all form data
    console.log('=== Form Data ===');
    let hasTeacher = false;
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
        if (pair[0].startsWith('subjects[') && pair[1] && pair[1] !== '') {
            hasTeacher = true;
        }
    }
    console.log('Has Teacher Assigned:', hasTeacher);
    
    // Validation
    const examName = formData.get('exam_name');
    const examDate = formData.get('exam_date');
    const classId = formData.get('class_id');
    
    if (!examName || !examDate || !classId) {
        Swal.fire('Error', 'Please fill all required fields', 'error');
        return;
    }
    
    if (!hasTeacher) {
        Swal.fire({
            icon: 'warning',
            title: 'Error!',
            text: 'Please assign at least one teacher to a subject!',
            confirmButtonColor: '#1e3c72'
        });
        return;
    }
    
    Swal.fire({
        title: 'Saving...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });
    
    // ✅ Send as FormData
    fetch('{{ route("exam.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.message,
                timer: 1500,
                showConfirmButton: false
            });
            setTimeout(() => {
                window.location.href = '{{ route("exam.index") }}';
            }, 1500);
        } else {
            Swal.fire('Error!', response.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error!', 'Something went wrong!', 'error');
        console.error('Error:', error);
    });
}
</script>
@endsection