@extends('layouts.app')

@section('title', 'Add Teacher Salary')

@section('content')
<style>
    /* ===== ANIMATIONS ===== */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .salary-form-container {
        animation: fadeInUp 0.35s ease;
    }
    
    /* ===== HEADER ===== */
    .salary-header {
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
    .salary-header h2 {
        font-size: 0.95rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }
    .salary-header h2 i {
        color: #fbbf24;
        font-size: 1rem;
    }
    .salary-header .header-badge {
        background: rgba(255,255,255,0.12);
        padding: 2px 12px;
        border-radius: 16px;
        font-size: 0.6rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
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
    
    /* ===== FORM CARD ===== */
    .form-card {
        background: white;
        border-radius: 10px;
        padding: 16px 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    
    /* ===== FORM GRID ===== */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }
    .form-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    .form-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }
    .full-width {
        grid-column: 1 / -1;
    }
    
    /* ===== FORM GROUP ===== */
    .form-group {
        margin-bottom: 0;
    }
    .form-group label {
        display: block;
        font-size: 0.6rem;
        font-weight: 600;
        color: #1e3c72;
        margin-bottom: 3px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .form-group label .required {
        color: #dc2626;
        font-weight: 700;
    }
    .form-group label i {
        width: 14px;
        color: #3b82f6;
        font-size: 0.6rem;
    }
    .form-group input,
    .form-group select {
        width: 100%;
        padding: 5px 10px;
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
        background: #fafbfc;
        transition: all 0.25s ease;
        font-family: inherit;
        height: 32px;
    }
    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        background: white;
    }
    .form-group input.readonly-bg,
    .form-group input[readonly] {
        background: #f1f5f9;
        cursor: not-allowed;
        color: #1e293b;
        font-weight: 500;
    }
    .form-group input[readonly]:focus {
        box-shadow: none;
        border-color: #e2e8f0;
    }
    .form-group .input-icon-wrapper {
        position: relative;
    }
    .form-group .input-icon-wrapper .input-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.7rem;
    }
    .form-group .input-icon-wrapper input {
        padding-left: 28px;
        height: 32px;
    }
    
    /* ===== SECTION TITLE ===== */
    .section-title {
        font-size: 0.65rem;
        font-weight: 700;
        color: #1e3c72;
        margin: 12px 0 8px 0;
        padding-bottom: 5px;
        border-bottom: 1.5px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 6px;
        grid-column: 1 / -1;
    }
    .section-title i {
        color: #3b82f6;
        font-size: 0.75rem;
    }
    .section-title .section-count {
        background: #3b82f6;
        color: white;
        font-size: 0.5rem;
        padding: 1px 8px;
        border-radius: 10px;
        font-weight: 600;
    }
    
    /* ===== INFO BOX ===== */
    .info-box {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 1px solid #bae6fd;
        border-radius: 8px;
        padding: 10px 14px;
        margin: 10px 0;
        display: none;
        animation: slideDown 0.3s ease;
    }
    .info-box.active {
        display: block;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
    }
    .info-grid .info-item {
        background: white;
        padding: 6px 10px;
        border-radius: 6px;
        text-align: center;
        border: 1px solid #e2e8f0;
    }
    .info-grid .info-item .info-label {
        color: #64748b;
        font-size: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        font-weight: 600;
    }
    .info-grid .info-item .info-value {
        font-weight: 700;
        font-size: 0.8rem;
        color: #1e3c72;
        margin-top: 2px;
    }
    .info-grid .info-item .info-value.green { color: #10b981; }
    .info-grid .info-item .info-value.blue { color: #3b82f6; }
    .info-grid .info-item .info-value.purple { color: #8b5cf6; }
    .info-grid .info-item .info-value.orange { color: #f59e0b; }
    
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
    .btn-submit:active {
        transform: translateY(0);
    }
    .btn-submit i {
        font-size: 0.8rem;
    }
    
    .btn-reset {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #475569;
        padding: 6px 18px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s ease;
        height: 34px;
    }
    .btn-reset:hover {
        background: #e2e8f0;
        transform: translateY(-1px);
    }
    
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1.5px solid #f1f5f9;
    }
    
    /* ===== NET SALARY SPECIAL ===== */
    #net_salary {
        font-size: 1rem !important;
        font-weight: 700 !important;
        color: #1e3c72 !important;
        padding: 5px 12px !important;
        background: #eff6ff !important;
        border-color: #93c5fd !important;
        height: 34px !important;
    }
    
    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .form-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        .info-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .salary-header {
            padding: 10px 14px;
        }
        .salary-header h2 {
            font-size: 0.85rem;
        }
        .form-card {
            padding: 12px 14px;
        }
        .form-grid {
            grid-template-columns: 1fr 1fr;
        }
        .form-grid-2 {
            grid-template-columns: 1fr;
        }
        .form-grid-3 {
            grid-template-columns: 1fr 1fr;
        }
        .info-grid {
            grid-template-columns: 1fr 1fr;
            gap: 6px;
        }
        .info-grid .info-item {
            padding: 5px 8px;
        }
        .form-actions {
            flex-direction: column;
        }
        .form-actions .btn-submit,
        .form-actions .btn-reset {
            width: 100%;
            justify-content: center;
            height: 36px;
        }
    }
    
    @media (max-width: 480px) {
        .salary-header {
            flex-direction: column;
            text-align: center;
        }
        .salary-header .header-badge {
            font-size: 0.5rem;
        }
        .form-grid {
            grid-template-columns: 1fr;
        }
        .form-grid-3 {
            grid-template-columns: 1fr;
        }
        .info-grid {
            grid-template-columns: 1fr 1fr;
        }
        .section-title {
            font-size: 0.6rem;
        }
        .form-group label {
            font-size: 0.55rem;
        }
        .form-group input,
        .form-group select {
            font-size: 0.7rem;
            padding: 4px 8px;
            height: 30px;
        }
        .form-group .input-icon-wrapper input {
            padding-left: 26px;
            height: 30px;
        }
        .info-grid .info-item .info-value {
            font-size: 0.7rem;
        }
        .btn-submit {
            padding: 5px 18px;
            font-size: 0.7rem;
            height: 32px;
        }
        .btn-reset {
            padding: 5px 14px;
            font-size: 0.7rem;
            height: 32px;
        }
        #net_salary {
            height: 32px !important;
            font-size: 0.9rem !important;
        }
    }
</style>

<div class="salary-form-container">
    <!-- ===== HEADER ===== -->
    <div class="salary-header">
        <h2>
            <i class="fas fa-money-bill-wave"></i>
            Add Teacher Salary
            <span class="header-badge">
                <i class="fas fa-calendar-alt"></i>
                {{ date('F Y') }}
            </span>
        </h2>
        <a href="{{ route('finance.salary.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- ===== FORM ===== -->
    <div class="form-card">
        <form id="salaryForm" onsubmit="saveSalary(event)">
            @csrf
            
            <!-- ===== SECTION 1: Basic Information ===== -->
            <div class="section-title">
                <i class="fas fa-info-circle"></i>
                Basic Information
                <span class="section-count">Required</span>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label><i class="fas fa-chalkboard-user"></i> Teacher <span class="required">*</span></label>
                    <select name="teacher_id" id="teacher_id" required onchange="getTeacherData()">
                        <option value="">-- Select --</option>
                        @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->full_name }} ({{ $teacher->teacher_id }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-calendar-alt"></i> Month <span class="required">*</span></label>
                    <select name="month" id="month" required onchange="getTeacherData()">
                        <option value="">-- Select --</option>
                        @foreach($months as $month)
                        <option value="{{ $month }}" {{ $month == date('F') ? 'selected' : '' }}>{{ $month }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-calendar-day"></i> Payment Date <span class="required">*</span></label>
                    <input type="date" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}" required>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-money-bill"></i> Salary Type <span class="required">*</span></label>
                    <select name="salary_type" id="salary_type" required>
                        <option value="bank">🏦 Bank</option>
                        <option value="cash">💵 Cash</option>
                    </select>
                </div>
            </div>
            
            <!-- ===== INFO BOX ===== -->
            <div class="info-box" id="infoBox">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-user"></i> Teacher</div>
                        <div class="info-value blue" id="teacherName">-</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-money-bill-wave"></i> Monthly Salary</div>
                        <div class="info-value green" id="salaryAmount">₹0</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-university"></i> Type</div>
                        <div class="info-value purple" id="salaryType">-</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-calendar-check"></i> Month</div>
                        <div class="info-value orange" id="displayMonth">-</div>
                    </div>
                </div>
            </div>
            
            <!-- ===== SECTION 2: Salary Details ===== -->
            <div class="section-title" style="margin-top: 14px;">
                <i class="fas fa-calculator"></i>
                Salary Details
                <span class="section-count">Calculate</span>
            </div>
            
            <div class="form-grid-2">
                <div class="form-group">
                    <label><i class="fas fa-money-bill-wave"></i> Monthly Salary (Fixed)</label>
                    <input type="text" name="salary" id="salary" readonly class="readonly-bg" value="0">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-hand-holding-usd"></i> Amount <span class="required">*</span></label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon">₹</span>
                        <input type="number" name="amount" id="amount" step="0.01" value="0" min="0" oninput="calculateNetSalary()">
                    </div>
                </div>
            </div>
            
            <!-- ===== SECTION 3: Deductions ===== -->
            <div class="section-title" style="margin-top: 8px;">
                <i class="fas fa-minus-circle"></i>
                Deductions
                <span class="section-count">Optional</span>
            </div>
            
            <div class="form-grid-3">
                <div class="form-group">
                    <label><i class="fas fa-percent"></i> PF (12%)</label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon">₹</span>
                        <input type="number" name="pf" id="pf" step="0.01" value="0" min="0" oninput="calculateNetSalary()">
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-shield-alt"></i> ESIC (0.75%)</label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon">₹</span>
                        <input type="number" name="esic" id="esic" step="0.01" value="0" min="0" oninput="calculateNetSalary()">
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-plus-circle"></i> Other</label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon">₹</span>
                        <input type="number" name="other" id="other" step="0.01" value="0" min="0" oninput="calculateNetSalary()">
                    </div>
                </div>
            </div>
            
            <!-- ===== NET SALARY ===== -->
            <div class="section-title" style="margin-top: 8px;">
                <i class="fas fa-check-circle"></i>
                Net Salary
                <span class="section-count">Auto</span>
            </div>
            
            <div class="form-grid" style="margin-bottom: 4px;">
                <div class="form-group full-width">
                    <label style="font-size:0.7rem; color:#1e3c72;">
                        <i class="fas fa-money-bill-wave" style="font-size:0.85rem;"></i>
                        Net Payable Amount <span class="required">*</span>
                    </label>
                    <input type="number" name="net_salary" id="net_salary" step="0.01" readonly class="readonly-bg" value="0">
                </div>
            </div>
            
            <!-- ===== SECTION 4: Additional ===== -->
            <div class="section-title" style="margin-top: 8px;">
                <i class="fas fa-cog"></i>
                Additional
                <span class="section-count">Optional</span>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label><i class="fas fa-comment"></i> Remarks</label>
                    <input type="text" name="remarks" id="remarks" placeholder="Enter remarks..." style="background:#fafbfc;">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-circle"></i> Status <span class="required">*</span></label>
                    <select name="status" id="status" required>
                        <option value="paid" style="color:#10b981;">✅ Paid</option>
                        <option value="hold" style="color:#f59e0b;">⏸ Hold</option>
                       
                    </select>
                </div>
            </div>
            
            <!-- ===== FORM ACTIONS ===== -->
            <div class="form-actions">
                <button type="button" class="btn-reset" onclick="resetForm()">
                    <i class="fas fa-undo-alt"></i> Reset
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Save Salary
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function getTeacherData() {
    const teacherId = document.getElementById('teacher_id').value;
    const month = document.getElementById('month').value;
    
    if (!teacherId || !month) {
        document.getElementById('infoBox').classList.remove('active');
        return;
    }
    
    document.getElementById('teacherName').textContent = 'Loading...';
    
    fetch(`{{ route('finance.salary.get-teacher-data') }}?teacher_id=${teacherId}&month=${month}`)
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                const d = response.data;
                document.getElementById('infoBox').classList.add('active');
                document.getElementById('teacherName').textContent = d.teacher_name;
                document.getElementById('salaryAmount').textContent = '₹' + d.salary;
                document.getElementById('salaryType').textContent = d.salary_type.charAt(0).toUpperCase() + d.salary_type.slice(1);
                document.getElementById('displayMonth').textContent = month;
                
                document.getElementById('salary').value = d.salary;
                document.getElementById('amount').value = d.amount || d.salary;
                document.getElementById('pf').value = d.pf || 0;
                document.getElementById('esic').value = d.esic || 0;
                document.getElementById('other').value = d.other || 0;
                document.getElementById('salary_type').value = d.salary_type || 'bank';
                calculateNetSalary();
            } else {
                document.getElementById('infoBox').classList.remove('active');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('infoBox').classList.remove('active');
        });
}

function calculateNetSalary() {
    const amount = parseFloat(document.getElementById('amount').value) || 0;
    const pf = parseFloat(document.getElementById('pf').value) || 0;
    const esic = parseFloat(document.getElementById('esic').value) || 0;
    const other = parseFloat(document.getElementById('other').value) || 0;
    const net = amount - pf - esic - other;
    document.getElementById('net_salary').value = net.toFixed(2);
}

function resetForm() {
    document.getElementById('salaryForm').reset();
    document.getElementById('infoBox').classList.remove('active');
    document.getElementById('salary').value = '0';
    document.getElementById('amount').value = '0';
    document.getElementById('pf').value = '0';
    document.getElementById('esic').value = '0';
    document.getElementById('other').value = '0';
    document.getElementById('net_salary').value = '0';
    document.getElementById('payment_date').value = '{{ date('Y-m-d') }}';
    
    Swal.fire({
        icon: 'info',
        title: 'Reset',
        text: 'Form has been reset',
        timer: 1200,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
}

function saveSalary(event) {
    event.preventDefault();
    
    const form = document.getElementById('salaryForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    
    if (!data.teacher_id || !data.month || !data.payment_date) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Please fill all required fields', confirmButtonColor: '#1e3c72' });
        return;
    }
    
    if (!data.net_salary || parseFloat(data.net_salary) <= 0) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Net salary must be greater than 0', confirmButtonColor: '#1e3c72' });
        return;
    }
    
    Swal.fire({
        title: 'Saving...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });
    
    fetch('{{ route("finance.salary.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            Swal.fire({
                icon: 'success',
                title: 'Saved! 🎉',
                text: response.message,
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
            setTimeout(() => {
                window.location.href = '{{ route("finance.salary.index") }}';
            }, 1500);
        } else {
            Swal.fire({ icon: 'error', title: 'Error!', text: response.message || 'Something went wrong', confirmButtonColor: '#1e3c72' });
        }
    })
    .catch(error => {
        Swal.fire({ icon: 'error', title: 'Error!', text: 'Something went wrong. Please try again.', confirmButtonColor: '#1e3c72' });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    calculateNetSalary();
});
</script>
@endsection