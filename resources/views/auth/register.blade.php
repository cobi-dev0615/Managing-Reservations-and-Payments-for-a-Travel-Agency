<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOJO - Criar Conta</title>
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
        .auth-brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .auth-brand-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);
        }
        .auth-brand h2 {
            color: #f1f5f9;
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -0.02em;
            margin: 0;
        }
        .auth-brand p {
            color: #64748b;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
        .auth-card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
            padding: 2rem;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .auth-card h5 {
            font-weight: 700;
            font-size: 1.1rem;
            color: #0f172a;
            margin-bottom: 1.5rem;
            text-align: center;
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
            margin-top: 1.5rem;
            color: #64748b;
            font-size: 0.84rem;
        }
        .auth-footer a {
            color: #60a5fa;
            text-decoration: none;
            font-weight: 600;
        }
        .auth-footer a:hover {
            color: #93bbfd;
        }
        .alert {
            border-radius: 0.5rem;
            font-size: 0.84rem;
            border: none;
            background: #fef2f2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
        .form-check-label { font-size: 0.84rem; color: #475569; }
        .input-group-text {
            background: #f9fafb;
            border-color: #d1d5db;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-brand">
            <img src="{{ asset('images/mojo.png') }}" alt="Mojo Logo" style="width: 80px; height: 80px; object-fit: contain; margin-bottom: 0.75rem;">
            <h2>MOJO</h2>
            <p>Safaris & Tours</p>
        </div>

        <div class="auth-card">
            <h5>Criar nova conta</h5>

            @if($errors->any())
                <div class="alert mb-3 py-2">
                    @foreach($errors->all() as $error)
                        <div><i class="bi bi-exclamation-circle me-1"></i> {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nome</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Seu nome completo" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="seu@email.com" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Minimo 8 caracteres" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Repita a senha" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-person-plus me-1"></i> Criar Conta
                </button>
            </form>
        </div>

        <div class="auth-footer">
            Ja tem conta? <a href="{{ route('login') }}">Entrar</a>
        </div>
    </div>
</body>
</html>
