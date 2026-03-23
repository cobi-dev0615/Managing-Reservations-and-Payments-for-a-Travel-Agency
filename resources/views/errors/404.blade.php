<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link rel="icon" href="{{ asset('images/mojo.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0f172a;
            color: #e2e8f0;
        }
        .error-container {
            text-align: center;
            max-width: 440px;
            padding: 2rem;
        }
        .error-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: rgba(234, 179, 8, 0.1);
            border: 1px solid rgba(234, 179, 8, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: #facc15;
        }
        .error-code {
            font-size: 3.5rem;
            font-weight: 800;
            letter-spacing: -0.04em;
            color: #f1f5f9;
            line-height: 1;
            margin-bottom: 0.5rem;
        }
        .error-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: #cbd5e1;
        }
        .error-message {
            font-size: 0.9rem;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .error-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.5rem;
            background: #3b82f6;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        .error-btn:hover {
            background: #2563eb;
            color: #fff;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="bi bi-search"></i>
        </div>
        <div class="error-code">404</div>
        <div class="error-title">{{ __('messages.page_not_found') }}</div>
        <p class="error-message">
            {{ __('messages.page_not_found_msg') }}
        </p>
        <a href="{{ url('/') }}" class="error-btn">
            <i class="bi bi-arrow-left"></i> {{ __('messages.back_to_dashboard') }}
        </a>
    </div>
</body>
</html>
