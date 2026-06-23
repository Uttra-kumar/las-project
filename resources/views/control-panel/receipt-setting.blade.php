@extends('layouts.app')

@section('title', 'Receipt Settings')

@section('content')
<style>
    .receipt-container {
        animation: fadeIn 0.3s ease;
        max-width: 550px;
        margin: 0 auto;
    }

    /* Compact Header */
    .receipt-header {
        background: linear-gradient(135deg, #1e3c72 0%, #0f2b4d 100%);
        color: white;
        padding: 10px 16px;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    .receipt-header h2 {
        font-size: 0.95rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .receipt-header p {
        font-size: 0.65rem;
        margin: 3px 0 0;
        opacity: 0.85;
    }

    /* Form Card */
    .form-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        border: 1px solid #e2e8f0;
    }

    .form-group {
        margin-bottom: 12px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #1e3c72;
        font-size: 0.65rem;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .form-group label i {
        width: 18px;
        color: #f59e0b;
        font-size: 0.65rem;
    }

    .form-group input {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
        transition: all 0.2s;
    }

    .form-group input:focus {
        outline: none;
        border-color: #f59e0b;
        box-shadow: 0 0 0 2px rgba(245,158,11,0.1);
    }

    .info-text {
        font-size: 0.6rem;
        color: #64748b;
        margin-top: 3px;
    }

    /* Preview Box - Compact */
    .preview-box {
        background: #fef3c7;
        border: 1px solid #fde68a;
        border-radius: 8px;
        padding: 10px;
        margin: 12px 0;
        text-align: center;
    }

    .preview-box h4 {
        font-size: 0.65rem;
        color: #92400e;
        margin-bottom: 5px;
    }

    .preview-receipt {
        font-size: 1rem;
        font-weight: 700;
        color: #d97706;
        font-family: monospace;
        letter-spacing: 0.5px;
    }

    .preview-note {
        font-size: 0.6rem;
        color: #92400e;
        margin-top: 5px;
    }

    /* Example Box - Compact */
    .example-box {
        background: #f8fafc;
        border-radius: 8px;
        padding: 10px;
        margin-top: 12px;
        border: 1px solid #e2e8f0;
    }

    .example-box h4 {
        font-size: 0.65rem;
        color: #1e3c72;
        margin-bottom: 6px;
    }

    .example-list {
        list-style: none;
        padding: 0;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .example-list li {
        font-size: 0.65rem;
        padding: 2px 6px;
        color: #475569;
        font-family: monospace;
        background: white;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
    }

    .example-list li i {
        color: #10b981;
        margin-right: 4px;
        font-size: 0.6rem;
    }

    .btn-area {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 15px;
    }

    .btn-submit, .btn-cancel {
        padding: 6px 18px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-submit {
        background: linear-gradient(135deg, #f59e0b, #ea580c);
        color: white;
    }

    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(245,158,11,0.3);
    }

    .btn-cancel {
        background: #e2e8f0;
        color: #475569;
    }

    .btn-cancel:hover {
        background: #cbd5e1;
    }

    @media (max-width: 550px) {
        .receipt-container {
            margin: 0;
        }
        .example-list {
            flex-direction: column;
            gap: 4px;
        }
    }
</style>

<div class="receipt-container">
    <div class="receipt-header">
        <h2>
            <i class="fas fa-receipt"></i> Receipt Settings
        </h2>
        <p>Configure receipt number format</p>
    </div>

    <div class="form-card">
        <form id="receiptForm">
            @csrf
            <input type="hidden" id="settingId" name="setting_id" value="{{ $setting->id ?? '' }}">
            
            <div class="form-group">
                <label><i class="fas fa-tag"></i> Prefix</label>
                <input type="text" id="prefix" name="prefix" required 
                       value="{{ old('prefix', $setting->prefix ?? 'REC') }}" 
                       placeholder="REC" maxlength="10">
                <div class="info-text">Receipt prefix (REC, INV, FEE)</div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> Year</label>
                <input type="text" id="year" name="year" required 
                       value="{{ old('year', $setting->year ?? date('y') . (date('y')+1)) }}" 
                       placeholder="2627" maxlength="10">
                <div class="info-text">Academic year (e.g., 2627 for 2026-27)</div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-hashtag"></i> Last Receipt No.</label>
                <input type="number" id="last_receipt_no" name="last_receipt_no" required 
                       value="{{ old('last_receipt_no', $setting->last_receipt_no ?? 0) }}" 
                       min="0" step="1">
                <div class="info-text">Last used number (next = this + 1)</div>
            </div>

            <!-- Live Preview -->
            <div class="preview-box">
                <h4><i class="fas fa-eye"></i> Next Receipt</h4>
                <div class="preview-receipt" id="nextReceiptPreview">---</div>
                <div class="preview-note" id="nextReceiptNote">Change values to preview</div>
            </div>

            <!-- Example Box -->
            <div class="example-box">
                <h4><i class="fas fa-info-circle"></i> Examples</h4>
                <ul class="example-list">
                    <li><i class="fas fa-check-circle"></i> REC-2627-01</li>
                    <li><i class="fas fa-check-circle"></i> REC-2627-02</li>
                    <li><i class="fas fa-check-circle"></i> INV-2025-001</li>
                    <li><i class="fas fa-check-circle"></i> FEE-2425-01</li>
                </ul>
            </div>

            <div class="btn-area">
                <button type="button" class="btn-cancel" onclick="resetForm()">Reset</button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updatePreview() {
        const prefix = document.getElementById('prefix').value.trim() || 'REC';
        const year = document.getElementById('year').value.trim() || '0000';
        const lastNo = parseInt(document.getElementById('last_receipt_no').value) || 0;
        
        const nextNo = lastNo + 1;
        const formattedNo = nextNo.toString().padStart(2, '0');
        const nextReceipt = `${prefix.toUpperCase()}-${year}-${formattedNo}`;
        
        document.getElementById('nextReceiptPreview').innerText = nextReceipt;
        document.getElementById('nextReceiptNote').innerHTML = `Next: ${nextReceipt}`;
    }
    
    document.getElementById('prefix').addEventListener('input', updatePreview);
    document.getElementById('year').addEventListener('input', updatePreview);
    document.getElementById('last_receipt_no').addEventListener('input', updatePreview);
    
    updatePreview();
    
    document.getElementById('receiptForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const settingId = document.getElementById('settingId').value;
        const url = settingId ? `/admin/receipt-setting/update/${settingId}` : '/admin/receipt-setting/store';
        
        const formData = {
            prefix: document.getElementById('prefix').value,
            year: document.getElementById('year').value,
            last_receipt_no: document.getElementById('last_receipt_no').value,
            _token: '{{ csrf_token() }}'
        };
        
        Swal.fire({
            title: 'Saving...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        fetch(url, {
            method: settingId ? 'PUT' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
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
        });
    });
    
    function resetForm() {
        Swal.fire({
            title: 'Reset?',
            text: 'Unsaved changes will be lost!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#dc2626',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) location.reload();
        });
    }
</script>
@endsection