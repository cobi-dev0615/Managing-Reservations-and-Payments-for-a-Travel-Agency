<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOJO - Create Account</title>
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
            max-width: 440px;
        }
        .auth-card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 1rem;
            padding: 2.5rem 2rem 2rem;
            box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.5);
        }
        .auth-logo {
            text-align: center;
            margin-bottom: 1.75rem;
        }
        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #374151;
        }
        .form-control, .form-select {
            font-size: 0.88rem;
            padding: 0.6rem 0.85rem;
            border-color: #d1d5db;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .input-group-text {
            background: #f9fafb;
            border-color: #d1d5db;
            color: #6b7280;
        }
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            padding: 0.6rem;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        .auth-footer {
            text-align: center;
            margin-top: 1.25rem;
            font-size: 0.84rem;
            color: #6b7280;
        }
        .auth-footer a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }
        .auth-footer a:hover {
            text-decoration: underline;
        }
        .role-description {
            font-size: 0.76rem;
            color: #6b7280;
            padding: 0.35rem 0.5rem;
            margin-top: 0.35rem;
            background: #f0f9ff;
            border-radius: 0.375rem;
            border-left: 3px solid var(--primary);
        }
        .alert {
            font-size: 0.82rem;
            border-radius: 0.5rem;
        }
        @media (max-width: 480px) {
            .auth-card {
                padding: 2rem 1.5rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-logo">
                <img src="{{ asset('images/mojo.png') }}" alt="Mojo Logo" style="width: 180px; object-fit: contain;">
            </div>

            @if($errors->any())
                <div class="alert alert-danger mb-3 py-2">
                    @foreach($errors->all() as $error)
                        <div><i class="bi bi-exclamation-circle me-1"></i> {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Your full name" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="your@email.com" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                        <select name="role" id="role" class="form-select" required>
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}" {{ old('role') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="role-description">
                        <i class="bi bi-info-circle me-1"></i>
                        <span id="roleDesc">Select the desired role</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Minimum 8 characters" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Repeat password" required>
                    </div>
                </div>

                <div class="alert alert-warning mb-3 py-2">
                    <i class="bi bi-clock me-1"></i> After registration, your account will need to be approved by the administrator.
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-person-plus me-1"></i> Create Account
                </button>
            </form>

            <div class="auth-footer">
                Already have an account? <a href="{{ route('login') }}">Login</a>
            </div>
        </div>
    </div>

    <script>
        const roleDescriptions = {
            'manager': 'Manager: Can manage tours, clients, bookings, and payments.',
            'viewer': 'Viewer: Read-only access to your own data.'
        };

        const roleSelect = document.getElementById('role');
        const roleDesc = document.getElementById('roleDesc');

        function updateRoleDesc() {
            roleDesc.textContent = roleDescriptions[roleSelect.value] || 'Select the desired role';
        }

        roleSelect.addEventListener('change', updateRoleDesc);
        updateRoleDesc();
    </script>
</body>
</html>
