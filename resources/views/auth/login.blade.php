<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOJO - Login</title>
    <link rel="icon" href="{{ asset('images/mojo.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
        }
        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .auth-container {
            width: 100%;
            max-width: 420px;
        }
        .auth-card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
            padding: 2rem;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .auth-logo {
            text-align: center;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
        }
        .form-control {
            border-radius: 0.5rem;
            padding: 0.6rem 0.75rem;
            font-size: 0.88rem;
            border-color: #d1d5db;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        }
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-color: #2563eb;
            font-weight: 600;
            padding: 0.6rem;
            border-radius: 0.5rem;
            font-size: 0.88rem;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-color: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        .auth-footer {
            text-align: center;
            margin-top: 1.25rem;
            padding-top: 1.25rem;
            border-top: 1px solid #f1f5f9;
            color: #6b7280;
            font-size: 0.84rem;
        }
        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
        .auth-footer a:hover {
            color: var(--primary-dark);
        }
        .alert-danger {
            border-radius: 0.5rem;
            font-size: 0.84rem;
            border: none;
            background: #fef2f2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
        .alert-warning {
            border-radius: 0.5rem;
            font-size: 0.84rem;
            border: none;
            background: #fefce8;
            color: #854d0e;
            border-left: 4px solid #eab308;
        }
        .alert-info {
            border-radius: 0.5rem;
            font-size: 0.84rem;
            border: none;
            background: #eff6ff;
            color: #1e40af;
            border-left: 4px solid #3b82f6;
        }
        .form-check-label { font-size: 0.84rem; color: #475569; }
        .input-group-text {
            background: #f9fafb;
            border-color: #d1d5db;
            color: #9ca3af;
        }

        /* Toast notification for login page */
        .login-toast-container {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .login-toast {
            min-width: 320px;
            max-width: 420px;
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            animation: toastSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        .login-toast.toast-warning {
            background: #fefce8;
            border-left: 4px solid #eab308;
            color: #854d0e;
        }
        .login-toast.toast-error {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            color: #991b1b;
        }
        .login-toast.toast-info {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            color: #1e40af;
        }
        .login-toast.toast-success {
            background: #f0fdf4;
            border-left: 4px solid #22c55e;
            color: #166534;
        }
        .login-toast .toast-icon {
            font-size: 1.25rem;
            flex-shrink: 0;
            margin-top: 0.1rem;
        }
        .login-toast .toast-body {
            flex: 1;
        }
        .login-toast .toast-title {
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 0.15rem;
        }
        .login-toast .toast-message {
            font-size: 0.82rem;
            line-height: 1.4;
        }
        .login-toast .toast-close {
            background: none;
            border: none;
            font-size: 1.1rem;
            cursor: pointer;
            opacity: 0.5;
            color: inherit;
            padding: 0;
            line-height: 1;
        }
        .login-toast .toast-close:hover {
            opacity: 1;
        }
        @keyframes toastSlideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes toastSlideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    </style>
</head>
<body>
    <!-- Toast Container -->
    <div class="login-toast-container" id="toastContainer"></div>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-logo">
                <img src="{{ asset('images/mojo.png') }}" alt="Mojo Logo" style="width: 180px; object-fit: contain;">
            </div>

            @if($errors->has('email'))
                <div class="alert alert-danger mb-3 py-2">
                    <div><i class="bi bi-exclamation-circle me-1"></i> {{ $errors->first('email') }}</div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="your@email.com" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Your password" required>
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Login
                </button>
            </form>

            <div class="auth-footer">
                Don't have an account? <a href="{{ route('register') }}">Create account</a>
            </div>
        </div>
    </div>

    <script>
        function showLoginToast(type, title, message, duration = 8000) {
            const container = document.getElementById('toastContainer');
            const icons = {
                'warning': 'bi-exclamation-triangle-fill',
                'error': 'bi-x-circle-fill',
                'info': 'bi-info-circle-fill',
                'success': 'bi-check-circle-fill'
            };

            const toast = document.createElement('div');
            toast.className = `login-toast toast-${type}`;
            toast.innerHTML = `
                <i class="bi ${icons[type] || icons.info} toast-icon"></i>
                <div class="toast-body">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="this.parentElement.style.animation='toastSlideOut 0.3s ease forwards';setTimeout(()=>this.parentElement.remove(),300)">&times;</button>
            `;
            container.appendChild(toast);

            setTimeout(() => {
                if (toast.parentElement) {
                    toast.style.animation = 'toastSlideOut 0.3s ease forwards';
                    setTimeout(() => toast.remove(), 300);
                }
            }, duration);
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if($errors->has('status'))
                showLoginToast(
                    '{{ $errors->first("status") === __("messages.pending_approval_msg") ? "warning" : "error" }}',
                    '{{ $errors->first("status") === __("messages.pending_approval_msg") ? "Account Pending" : "Account Suspended" }}',
                    '{{ $errors->first("status") }}'
                );
            @endif

            @if(session('info'))
                showLoginToast('info', 'Account Registered', '{{ session("info") }}');
            @endif

            @if(session('success'))
                showLoginToast('success', 'Success', '{{ session("success") }}');
            @endif

            @if(session('warning'))
                showLoginToast('warning', 'Session Expired', '{{ session("warning") }}');
            @endif
        });
    </script>
</body>
</html>
