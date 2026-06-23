<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ===== RESET & BASE ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a365d 0%, #2d4a7a 50%, #1a365d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        /* ===== LOGIN CONTAINER ===== */
        .login-container {
            background: white;
            border-radius: 14px;
            padding: 32px 30px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.35);
            animation: fadeIn 0.5s ease;
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
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

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-15px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* ===== HEADER ===== */
        .login-header {
            text-align: center;
            margin-bottom: 22px;
        }

        .login-header .icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 24px;
            color: white;
            box-shadow: 0 4px 12px rgba(30, 60, 114, 0.3);
        }

        .login-header h2 {
            color: #1e3c72;
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .login-header p {
            color: #6b7280;
            font-size: 0.75rem;
            margin-top: 3px;
        }

        /* ===== FORM ===== */
        .form-group {
            margin-bottom: 14px;
        }

        .form-group label {
            display: block;
            font-size: 0.65rem;
            font-weight: 700;
            color: #1e3c72;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .form-group label .required {
            color: #dc2626;
        }

        .form-group .input-icon {
            position: relative;
        }

        .form-group .input-icon i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.8rem;
            transition: all 0.3s;
        }

        .form-group .input-icon input,
        .form-group .input-icon select {
            width: 100%;
            padding: 8px 12px 8px 36px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.85rem;
            transition: all 0.3s;
            background: #fafbfc;
            color: #1e293b;
            height: 40px;
            font-family: 'Arial', sans-serif;
            appearance: none;
            -webkit-appearance: none;
        }

        .form-group .input-icon select {
            padding-right: 30px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394a3b8' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
        }

        .form-group .input-icon input:focus,
        .form-group .input-icon select:focus {
            outline: none;
            border-color: #1e3c72;
            box-shadow: 0 0 0 3px rgba(30, 60, 114, 0.08);
            background: white;
        }

        .form-group .input-icon input:focus + i,
        .form-group .input-icon select:focus + i {
            color: #1e3c72;
        }

        .form-group .input-icon input::placeholder {
            color: #94a3b8;
            font-size: 0.8rem;
        }

        .form-group .hint-text {
            display: block;
            color: #94a3b8;
            font-size: 0.6rem;
            margin-top: 4px;
        }
        .form-group .hint-text i {
            margin-right: 3px;
            color: #3b82f6;
        }

        /* ===== DATE INPUT ===== */
        .form-group input[type="date"] {
            padding-left: 36px;
            color-scheme: light;
        }

        /* ===== BUTTON ===== */
        .btn-login {
            width: 100%;
            padding: 10px;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 42px;
            margin-top: 4px;
            font-family: 'Arial', sans-serif;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 60, 114, 0.35);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .btn-login i {
            font-size: 0.85rem;
            transition: transform 0.3s;
        }

        .btn-login:hover i {
            transform: translateX(3px);
        }

        /* ===== ALERTS ===== */
        .alert-error,
        .alert-success {
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .alert-error {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }

        .alert-success {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #15803d;
        }

        .alert-error i,
        .alert-success i {
            font-size: 0.8rem;
        }

        /* ===== HINT ===== */
        .login-hint {
            background: #f8fafc;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.65rem;
            color: #64748b;
            margin-top: 14px;
            text-align: center;
            border: 1px dashed #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .login-hint i {
            color: #f59e0b;
            font-size: 0.7rem;
        }

        .login-hint strong {
            color: #1e3c72;
        }

        /* ===== FOOTER ===== */
        .login-footer {
            text-align: center;
            margin-top: 14px;
            font-size: 0.6rem;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 12px;
        }

        /* ============================================
           RESPONSIVE
           ============================================ */

        @media (max-width: 480px) {
            body {
                padding: 12px;
            }
            .login-container {
                padding: 24px 18px;
                border-radius: 12px;
            }
            .login-header .icon {
                width: 48px;
                height: 48px;
                font-size: 20px;
            }
            .login-header h2 {
                font-size: 1.1rem;
            }
            .login-header p {
                font-size: 0.7rem;
            }
            .form-group {
                margin-bottom: 12px;
            }
            .form-group label {
                font-size: 0.6rem;
            }
            .form-group .input-icon input,
            .form-group .input-icon select {
                font-size: 0.8rem;
                padding: 6px 10px 6px 32px;
                height: 38px;
            }
            .form-group .input-icon i {
                font-size: 0.75rem;
                left: 10px;
            }
            .btn-login {
                font-size: 0.85rem;
                height: 38px;
                padding: 8px;
            }
            .login-hint {
                font-size: 0.6rem;
                padding: 6px 10px;
            }
            .alert-error,
            .alert-success {
                font-size: 0.7rem;
                padding: 6px 10px;
            }
        }

        @media (max-width: 380px) {
            .login-container {
                padding: 18px 14px;
            }
            .login-header .icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
                margin-bottom: 8px;
            }
            .login-header h2 {
                font-size: 1rem;
            }
            .form-group .input-icon input,
            .form-group .input-icon select {
                font-size: 0.75rem;
                height: 34px;
                padding: 4px 8px 4px 28px;
            }
            .form-group .input-icon i {
                font-size: 0.7rem;
                left: 8px;
            }
            .btn-login {
                font-size: 0.8rem;
                height: 34px;
            }
        }

        /* ===== REMOVE NUMBER SPINNER ===== */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- ===== HEADER ===== -->
        <div class="login-header">
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h2>Parent Login</h2>
            <p>Access your child's academic details</p>
        </div>

        <!-- ===== ALERTS ===== -->
        @if(session('error'))
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i>
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
        @endif

        <!-- ===== FORM ===== -->
        <form method="POST" action="{{ route('parent.login.post') }}">
            @csrf

            <div class="form-group">
                <label><i class="fas fa-phone"></i> Mobile Number <span class="required">*</span></label>
                <div class="input-icon">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="mobile" id="mobile" 
                           placeholder="Enter 10-digit mobile" 
                           value="{{ old('mobile') }}" 
                           required maxlength="10" 
                           pattern="[0-9]{10}"
                           inputmode="numeric">
                </div>
                <span class="hint-text">
                    <i class="fas fa-info-circle"></i> Student's father/mother/mobile number
                </span>
            </div>

            <div class="form-group">
                <label><i class="fas fa-school"></i> Class <span class="required">*</span></label>
                <div class="input-icon">
                    <i class="fas fa-school"></i>
                    <select name="class_id" id="class_id" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->class_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> Student's DOB <span class="required">*</span></label>
                <div class="input-icon">
                    <i class="fas fa-calendar-alt"></i>
                    <input type="date" name="dob" id="dob" value="{{ old('dob') }}" required>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-arrow-right"></i> Login
            </button>

            <div class="login-hint">
                <i class="fas fa-lightbulb"></i>
                <span><strong>Demo:</strong> Use student's mobile, class &amp; DOB</span>
            </div>
        </form>

        <!-- ===== FOOTER ===== -->
        <div class="login-footer">
            &copy; {{ date('Y') }} School Management System
        </div>
    </div>
</body>
</html>