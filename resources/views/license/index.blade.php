@extends('layouts.blank')

@section('title', 'License Management')

@section('styles')
<style>
    .license-container {
        max-width: 480px;
        width: 100%;
        margin: 0 auto;
        animation: fadeIn 0.5s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px) scale(0.96); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    
    .license-card {
        background: white;
        border-radius: 16px;
        padding: 32px 28px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        position: relative;
        overflow: hidden;
    }
    
    .license-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #1e3c72, #fbbf24, #1e3c72);
        background-size: 200% 100%;
        animation: gradientMove 3s ease infinite;
    }
    
    @keyframes gradientMove {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    .license-header {
        text-align: center;
        margin-bottom: 24px;
    }
    
    .license-header .icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px;
        font-size: 28px;
        color: white;
        box-shadow: 0 4px 14px rgba(30, 60, 114, 0.3);
    }
    
    .license-header h2 {
        font-size: 1.25rem;
        color: #1e3c72;
        font-weight: 700;
        margin: 0;
    }
    
    .license-header p {
        color: #6b7280;
        font-size: 0.8rem;
        margin-top: 4px;
    }
    
    .status-box {
        background: #f8fafc;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        border: 1px solid #e2e8f0;
    }
    
    .status-label {
        font-size: 0.7rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
    }
    
    .status-badge {
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .status-badge.active {
        background: #dcfce7;
        color: #15803d;
    }
    .status-badge.inactive {
        background: #fef3c7;
        color: #b45309;
    }
    .status-badge.expired {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .days-left {
        font-size: 0.8rem;
        font-weight: 700;
        padding: 2px 12px;
        border-radius: 12px;
        display: inline-block;
    }
    .days-left.good { background: #dcfce7; color: #15803d; }
    .days-left.warning { background: #fef3c7; color: #b45309; }
    .days-left.danger { background: #fee2e2; color: #dc2626; }
    
    .alert {
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 0.8rem;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid;
    }
    .alert-danger {
        background: #fee2e2;
        border-color: #fecaca;
        color: #dc2626;
    }
    .alert-success {
        background: #dcfce7;
        border-color: #bbf7d0;
        color: #15803d;
    }
    .alert-warning {
        background: #fef3c7;
        border-color: #fde68a;
        color: #b45309;
    }
    
    .info-box {
        background: #f8fafc;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 18px;
        border: 1px solid #e2e8f0;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        border-bottom: 1px dashed #f1f5f9;
        font-size: 0.75rem;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        color: #64748b;
        font-weight: 600;
    }
    .info-value {
        color: #1e293b;
        font-weight: 500;
        font-family: monospace;
        font-size: 0.7rem;
        word-break: break-all;
        max-width: 60%;
        text-align: right;
    }
    
    .form-group {
        margin-bottom: 14px;
    }
    .form-group label {
        display: block;
        font-size: 0.6rem;
        font-weight: 700;
        color: #1e3c72;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .form-group label .required {
        color: #dc2626;
    }
    .form-group label i {
        color: #3b82f6;
        margin-right: 4px;
        width: 16px;
    }
    .form-group input {
        width: 100%;
        padding: 8px 12px;
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.85rem;
        transition: all 0.25s;
        font-family: inherit;
        background: #fafbfc;
    }
    .form-group input:focus {
        outline: none;
        border-color: #1e3c72;
        box-shadow: 0 0 0 3px rgba(30,60,114,0.1);
        background: white;
    }
    .form-group .hint {
        display: block;
        color: #94a3b8;
        font-size: 0.6rem;
        margin-top: 4px;
    }
    a{
         display: block;
            text-align: center;
            margin-top: 14px;
            font-size: 13px;
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s;
    }
    .btn-license {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 4px;
    }
    .btn-license.activate {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 2px 10px rgba(16,185,129,0.2);
    }
    .btn-license.activate:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16,185,129,0.35);
    }
    .btn-license.renew {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: 0 2px 10px rgba(245,158,11,0.2);
    }
    .btn-license.renew:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245,158,11,0.35);
    }
    .btn-license:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
    }
    
    .license-footer {
        text-align: center;
        margin-top: 16px;
        padding-top: 14px;
        border-top: 1px solid #e2e8f0;
        font-size: 0.65rem;
        color: #94a3b8;
    }
    
    .btn-sm {
        padding: 5px 12px;
        font-size: 0.65rem;
        background: none;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        color: #64748b;
    }
    .btn-sm:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }
    
    @media (max-width: 480px) {
        .license-card { padding: 20px 16px; }
        .license-header h2 { font-size: 1.05rem; }
        .info-row { font-size: 0.65rem; flex-direction: column; gap: 2px; }
        .info-value { max-width: 100%; text-align: left; }
        .status-box { flex-direction: column; text-align: center; }
        .form-group input { font-size: 0.75rem; padding: 6px 10px; }
        .btn-license { font-size: 0.75rem; padding: 8px; }
    }
</style>
@endsection

@section('content')
<div class="license-container">
    <div class="license-card">
        
        <!-- ===== HEADER ===== -->
      
        <!-- ===== ALERTS ===== -->
        @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        @if($license->status == 'expired')
        <div class="alert alert-danger">
            <i class="fas fa-times-circle"></i> 
            <strong>License Expired!</strong> Your license expired on 
            {{ $expiryDate }}. Please renew.
        </div>
        @endif

        @if($license->status == 'inactive')
        <div class="alert alert-warning">
            <i class="fas fa-info-circle"></i> 
            <strong>Not Activated!</strong> Please activate your license.
        </div>
        @endif

        @if($license->status == 'active')
            @php $daysLeft = \App\Helpers\LicenseHelper::getDaysLeft(); @endphp
            @if($daysLeft !== null && $daysLeft <= 30)
            <div class="alert alert-warning">
                <i class="fas fa-clock"></i> 
                <strong>⚠️ License expiring soon!</strong> Only <strong>{{ $daysLeft }}</strong> days left.
            </div>
            @else
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> 
                <strong>License Active</strong> Valid until {{ $expiryDate }}
            </div>
            @endif
        @endif

        <!-- ===== STATUS ===== -->
        <div class="status-box">
            <span class="status-label"><i class="fas fa-circle"></i> Status</span>
            <span>
                <span class="status-badge {{ $license->status }}">
                    {{ ucfirst($license->status) }}
                </span>
                @if($license->status == 'active' && $daysLeft !== null)
                    <span class="days-left 
                        {{ $daysLeft > 30 ? 'good' : ($daysLeft > 7 ? 'warning' : 'danger') }}">
                        {{ $daysLeft }} days left
                    </span>
                @endif
            </span>
        </div>

        <!-- ===== LICENSE INFO ===== -->
        

        <!-- ===== FORM ===== -->
        <form id="licenseForm">
            @csrf

            <div class="form-group">
                <label><i class="fas fa-key"></i> Secret Key <span class="required">*</span></label>
                <input type="password" id="secret_key" placeholder="Enter your custom secret key" required>
                <span class="hint"><i class="fas fa-info-circle"></i> Enter the secret key you set during installation</span>
            </div>

            <div class="form-group">
                <label><i class="fas fa-lock"></i> Master Key <span class="required">*</span></label>
                <input type="password" id="master_key" placeholder="Enter master key" required>
                <span class="hint"><i class="fas fa-info-circle"></i> Enter the master key provided to you</span>
            </div>

            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> Expiry Date <span class="required">*</span></label>
                <input type="date" id="expiry_date" required>
                <span class="hint"><i class="fas fa-info-circle"></i> License will expire on this date</span>
            </div>

            <button type="submit" class="btn-license {{ $license->status == 'active' ? 'renew' : 'activate' }}" id="licenseBtn">
                <i class="fas {{ $license->status == 'active' ? 'fa-sync-alt' : 'fa-unlock' }}"></i>
                {{ $license->status == 'active' ? 'Renew License' : 'Activate License' }}
            </button>

           <a href="{{ route('dashboard')}}"><i class="fas fa-arrow-left"></i> Login</a>
        </form>

        <!-- ===== FOOTER ===== -->
        

    </div>
</div>
@endsection

@section('scripts')
<script>
// ============================================
// LICENSE FORM SUBMIT (Activate / Renew)
// ============================================
document.getElementById('licenseForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const secretKey = document.getElementById('secret_key').value.trim();
    const masterKey = document.getElementById('master_key').value.trim();
    const expiryDate = document.getElementById('expiry_date').value;
    const isRenew = {{ $license->status == 'active' ? 'true' : 'false' }};
    const url = isRenew ? '{{ route("license.renew") }}' : '{{ route("license.activate") }}';
    
    if (!secretKey || !masterKey || !expiryDate) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Please fill all required fields',
            confirmButtonColor: '#1e3c72'
        });
        return;
    }
    
    const btn = document.getElementById('licenseBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    
    Swal.fire({
        title: isRenew ? 'Renewing...' : 'Activating...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            secret_key: secretKey,
            master_key: masterKey,
            expiry_date: expiryDate
        })
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas ' + (isRenew ? 'fa-sync-alt' : 'fa-unlock') + '"></i> ' + (isRenew ? 'Renew License' : 'Activate License');
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: isRenew ? 'Renewed! 🎉' : 'Activated! 🎉',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            });
            setTimeout(() => location.reload(), 1500);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message || 'Invalid secret key or master key',
                confirmButtonColor: '#1e3c72'
            });
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas ' + (isRenew ? 'fa-sync-alt' : 'fa-unlock') + '"></i> ' + (isRenew ? 'Renew License' : 'Activate License');
        
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Something went wrong!',
            confirmButtonColor: '#1e3c72'
        });
    });
});

// ============================================
// UPDATE SECRET KEY
// ============================================
function updateSecretKey() {
    Swal.fire({
        title: 'Update Secret Key',
        input: 'text',
        inputLabel: 'Enter your new secret key',
        inputPlaceholder: 'e.g. MY-NEW-SECRET-KEY-456',
        inputValue: document.getElementById('displaySecretKey').textContent,
        showCancelButton: true,
        confirmButtonText: 'Update',
        confirmButtonColor: '#1e3c72',
        cancelButtonColor: '#64748b',
        preConfirm: (value) => {
            if (!value || value.length < 10) {
                Swal.showValidationMessage('Secret key must be at least 10 characters');
            }
            return value;
        }
    }).then(result => {
        if (result.isConfirmed && result.value) {
            Swal.fire({
                title: 'Updating...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            
            fetch('{{ route("license.update-secret") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    secret_key: result.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Secret key updated successfully',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    document.getElementById('displaySecretKey').textContent = data.secret_key;
                    document.getElementById('secret_key').value = data.secret_key;
                    setTimeout(() => location.reload(), 1500);
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            });
        }
    });
}
</script>
@endsection