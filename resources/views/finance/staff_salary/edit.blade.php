@extends('layouts.app')

@section('title', 'Edit Staff Salary')

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
        animation: slideDown 0.3s ease;
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
            <i class="fas fa-edit"></i>
            Edit Staff Salary
            <span class="header-badge">
                <i class="fas fa-id-card"></i>
                #{{ $salary->id }}
            </span>
        </h2>
        <a href="{{ route('finance.staff-salary.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- ===== FORM ===== -->
    <div class="form-card">
        <!-- ===== INFO BOX ===== -->
        <div class="info-box">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-user"></i> Staff</div>
                    <div class="info-value blue">{{ $salary->staff->full_name ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-id-card"></i> Emp ID</div>
                    <div class="info-value purple">{{ $salary->staff->emp_id ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-money-bill-wave"></i> Monthly Salary</div>
                    <div class="info-value green">₹{{ number_format($salary->salary, 2) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-building"></i> Department</div>
                    <div class="info-value orange">{{ ucfirst($salary->staff->department ?? 'N/A') }}</div>
                </div>
            </div>
        </div>

        <form id="salaryForm" onsubmit="updateSalary(event)">
            @csrf
            @method('PUT')
            
            <!-- ===== Row 1: Payment Date, Salary Type, Amount ===== -->
            <div class="form-grid" style="margin-bottom: 8px;">
                <div class="form-group">
                    <label><i class="fas fa-calendar-day"></i> Payment Date <span class="required">*</span></label>
                    <input type="date" name="payment_date" id="payment_date" 
                           value="{{ $salary->payment_date->format('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-money-bill"></i> Salary Type <span class="required">*</span></label>
                    <select name="salary_type" id="salary_type" required>
                        <option value="bank" {{ $salary->salary_type == 'bank' ? 'selected' : '' }}>🏦 Bank</option>
                        <option value="cash" {{ $salary->salary_type == 'cash' ? 'selected' : '' }}>💵 Cash</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-calculator"></i> Amount <span class="required">*</span></label>
                    <input type="number" name="amount" id="amount" step="0.01" 
                           value="{{ $salary->amount }}" min="0" oninput="calculateNetSalary()">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-percent"></i> PF</label>
                    <input type="number" name="pf" id="pf" step="0.01" 
                           value="{{ $salary->pf }}" min="0" oninput="calculateNetSalary()">
                </div>
            </div>
            
            <!-- ===== Row 2: ESIC, Other, Net Salary, Status ===== -->
            <div class="form-grid">
                <div class="form-group">
                    <label><i class="fas fa-shield-alt"></i> ESIC</label>
                    <input type="number" name="esic" id="esic" step="0.01" 
                           value="{{ $salary->esic }}" min="0" oninput="calculateNetSalary()">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-plus-circle"></i> Other</label>
                    <input type="number" name="other" id="other" step="0.01" 
                           value="{{ $salary->other }}" min="0" oninput="calculateNetSalary()">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-money-bill-wave"></i> Net Salary <span class="required">*</span></label>
                    <input type="number" name="net_salary" id="net_salary" step="0.01" 
                           value="{{ $salary->net_salary }}" readonly>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-circle"></i> Status <span class="required">*</span></label>
                    <select name="status" id="status" required>
                        <option value="paid" {{ $salary->status == 'paid' ? 'selected' : '' }} style="color:#10b981;">✅ Paid</option>
                        <option value="hold" {{ $salary->status == 'hold' ? 'selected' : '' }} style="color:#f59e0b;">⏸ Hold</option>
                    </select>
                </div>
            </div>
            
            <!-- ===== Remarks ===== -->
            <div class="form-grid" style="margin-top: 6px;">
                <div class="form-group full-width">
                    <label><i class="fas fa-comment"></i> Remarks</label>
                    <input type="text" name="remarks" id="remarks" 
                           value="{{ $salary->remarks }}" placeholder="Enter remarks..." 
                           style="background:#fafbfc;">
                </div>
            </div>
            
            <!-- ===== FORM ACTIONS ===== -->
            <div class="form-actions">
                <button type="button" class="btn-reset" onclick="resetForm()">
                    <i class="fas fa-undo-alt"></i> Reset
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Update Salary
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function calculateNetSalary() {
    const amount = parseFloat(document.getElementById('amount').value) || 0;
    const pf = parseFloat(document.getElementById('pf').value) || 0;
    const esic = parseFloat(document.getElementById('esic').value) || 0;
    const other = parseFloat(document.getElementById('other').value) || 0;
    
    const net = amount - pf - esic - other;
    document.getElementById('net_salary').value = net.toFixed(2);
}

function resetForm() {
    // Reset to original values
    document.getElementById('payment_date').value = '{{ $salary->payment_date->format('Y-m-d') }}';
    document.getElementById('salary_type').value = '{{ $salary->salary_type }}';
    document.getElementById('amount').value = '{{ $salary->amount }}';
    document.getElementById('pf').value = '{{ $salary->pf }}';
    document.getElementById('esic').value = '{{ $salary->esic }}';
    document.getElementById('other').value = '{{ $salary->other }}';
    document.getElementById('net_salary').value = '{{ $salary->net_salary }}';
    document.getElementById('status').value = '{{ $salary->status }}';
    document.getElementById('remarks').value = '{{ $salary->remarks }}';
    
    Swal.fire({
        icon: 'info',
        title: 'Reset',
        text: 'Form has been reset to original values',
        timer: 1200,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
}

function updateSalary(event) {
    event.preventDefault();
    
    const formData = new FormData(document.getElementById('salaryForm'));
    const data = Object.fromEntries(formData);
    
    if (!data.payment_date) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Please select payment date', confirmButtonColor: '#1e3c72' });
        return;
    }
    
    if (!data.net_salary || parseFloat(data.net_salary) <= 0) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Net salary must be greater than 0', confirmButtonColor: '#1e3c72' });
        return;
    }
    
    Swal.fire({
        title: 'Updating...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });
    
    fetch('{{ route("finance.staff-salary.update", $salary->id) }}', {
        method: 'PUT',
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
                title: 'Updated! 🎉',
                text: response.message,
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
            setTimeout(() => {
                window.location.href = '{{ route("finance.staff-salary.index") }}';
            }, 1500);
        } else {
            Swal.fire({ 
                icon: 'error', 
                title: 'Error!', 
                text: response.message || 'Something went wrong', 
                confirmButtonColor: '#1e3c72' 
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({ 
            icon: 'error', 
            title: 'Error!', 
            text: 'Something went wrong. Please try again.', 
            confirmButtonColor: '#1e3c72' 
        });
    });
}

// Auto-calculate on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateNetSalary();
});
</script>
@endsection