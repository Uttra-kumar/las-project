<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    
    <!-- Sweet Alert CSS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-4px);
        }

        /* ===== HEADER ===== */
        .login-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            padding: 32px 24px 24px 24px;
            text-align: center;
        }

        .login-header .icon {
            width: 64px;
            height: 64px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            font-size: 28px;
            color: #fbbf24;
            border: 2px solid rgba(255, 255, 255, 0.06);
        }

        .login-header h1 {
            font-size: 22px;
            margin-bottom: 4px;
            font-weight: 600;
            letter-spacing: -0.3px;
        }

        .login-header p {
            font-size: 13px;
            opacity: 0.7;
            font-weight: 400;
        }

        /* ===== BODY ===== */
        .login-body {
            padding: 28px 24px 20px 24px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #1e293b;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .form-group label i {
            margin-right: 4px;
            color: #64748b;
        }

        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
            background: #f8fafc;
            color: #1e293b;
            font-weight: 500;
        }

        .form-group input:focus {
            border-color: #0f172a;
            background: white;
            box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.08);
        }

        .form-group input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        /* ===== CHECKBOX ===== */
        .checkbox-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            cursor: pointer;
            gap: 8px;
            color: #475569;
            font-weight: 500;
        }

        .checkbox-label input {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #0f172a;
        }

        .forgot-link {
            color: #475569;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: #0f172a;
            text-decoration: underline;
        }

        /* ===== BUTTON ===== */
        .login-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            letter-spacing: 0.3px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-btn i {
            font-size: 16px;
        }

        /* ===== FOOTER ===== */
        .login-footer {
            text-align: center;
            padding: 14px 24px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            font-size: 11px;
            color: #94a3b8;
        }

        .login-footer a {
            color: #475569;
            text-decoration: none;
        }

        .login-footer a:hover {
            color: #0f172a;
        }

        /* ===== LOADING ===== */
        .login-btn.loading {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* ===== RENEW LICENSE LINK ===== */
        .renew-link {
            display: block;
            text-align: center;
            margin-top: 14px;
            font-size: 13px;
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s;
        }

        .renew-link:hover {
            color: #0f172a;
        }

        .renew-link i {
            margin-right: 4px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 480px) {
            body {
                padding: 12px;
            }
            
            .login-header {
                padding: 24px 20px 18px 20px;
            }
            
            .login-header .icon {
                width: 54px;
                height: 54px;
                font-size: 24px;
            }
            
            .login-header h1 {
                font-size: 20px;
            }
            
            .login-body {
                padding: 22px 18px 16px 18px;
            }
            
            .form-group input {
                padding: 9px 12px;
                font-size: 13px;
            }
            
            .login-btn {
                padding: 10px;
                font-size: 14px;
            }

            .checkbox-group {
                font-size: 12px;
            }
        }

        /* ===== ANIMATION ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- ===== HEADER ===== -->
            <div class="login-header">
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h1>Welcome Back</h1>
                <p>Sign in to your account</p>
            </div>
            
            <!-- ===== BODY ===== -->
            <div class="login-body">
                <form id="loginForm" method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Enter your email" required autocomplete="off">
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Password</label>
                        <input type="password" name="password" id="password" placeholder="Enter your password" required autocomplete="off">
                    </div>
                    
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>Remember me</span>
                        </label>
                        <a href="{{ route('license.index') }}" class="forgot-link">
                            <i class="fas fa-key"></i> License
                        </a>
                    </div>
                    
                    <button type="submit" class="login-btn" id="loginBtn">
                        <i class="fas fa-arrow-right"></i>
                        <span>Sign In</span>
                    </button>

                    <a href="{{ route('home') }}" class="renew-link">
                        <i class="fas fa-arrow-left"></i> Home
                    </a>
                </form>
            </div>
            
            <!-- ===== FOOTER ===== -->
            <div class="login-footer">
                &copy; {{ date('Y') }} <a href="#">School Management System</a>. All rights reserved.
            </div>
        </div>
    </div>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        // ============================================
        // SWEET ALERT CONFIG
        // ============================================
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Please fill in all fields!',
                    confirmButtonColor: '#0f172a',
                    confirmButtonText: 'OK',
                    timer: 2000,
                    showConfirmButton: true
                });
                return false;
            }
            
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Email',
                    text: 'Please enter a valid email address!',
                    confirmButtonColor: '#0f172a',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Signing in...</span>';
        });
        
        // ============================================
        // ERROR MESSAGES
        // ============================================
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Login Failed!',
                html: `
                    @foreach($errors->all() as $error)
                        <div style="padding:4px 0;">{{ $error }}</div>
                    @endforeach
                `,
                confirmButtonColor: '#0f172a',
                confirmButtonText: 'Try Again',
                showConfirmButton: true
            });
        @endif
        
        // ============================================
        // SUCCESS MESSAGE
        // ============================================
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#0f172a',
                confirmButtonText: 'OK',
                timer: 3000,
                showConfirmButton: false
            });
        @endif
        
        // ============================================
        // RESET LOADING STATE
        // ============================================
        window.addEventListener('load', function() {
            const btn = document.getElementById('loginBtn');
            btn.classList.remove('loading');
            btn.innerHTML = '<i class="fas fa-arrow-right"></i><span>Sign In</span>';
        });
    </script>
</body>
</html>