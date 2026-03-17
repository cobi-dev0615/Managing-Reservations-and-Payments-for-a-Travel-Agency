<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MOJO Safaris & Tours</title>
    <link rel="icon" href="{{ asset('images/mojo.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        :root {
            --sidebar-width: 250px;
            --sidebar-bg: #000000;
            --sidebar-hover: rgba(255,255,255,0.05);
            --sidebar-active: #3b82f6;
            --sidebar-border: rgba(255,255,255,0.06);
            --header-height: 64px;
            --page-bg: #f1f5f9;
            --card-shadow: 0 1px 2px rgba(0,0,0,0.04);
            --card-shadow-hover: 0 8px 25px -5px rgba(0,0,0,0.08), 0 4px 10px -3px rgba(0,0,0,0.04);
            --radius: 0.75rem;
            --radius-sm: 0.5rem;
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --border-light: #f1f5f9;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            background-color: var(--page-bg);
            min-height: 100vh;
            font-size: 0.875rem;
            color: var(--text-primary);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ===== SKIP LINK ===== */
        .skip-link {
            position: absolute;
            top: -100%;
            left: 0;
            z-index: 9999;
            padding: 0.5rem 1rem;
            background: var(--sidebar-active);
            color: #fff;
        }
        .skip-link:focus { top: 0; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            color: #8b9dc3;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1050;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        .sidebar::-webkit-scrollbar { width: 3px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

        .sidebar-brand {
            padding: 1.5rem 1.25rem 1.25rem;
            border-bottom: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .sidebar-brand-text h5 {
            margin: 0;
            color: #f1f5f9;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: -0.01em;
        }

        .sidebar-brand-text small {
            color: #64748b;
            font-size: 0.68rem;
            letter-spacing: 0.02em;
        }

        .sidebar-nav {
            padding: 0.5rem 0;
            flex: 1;
        }

        .sidebar-nav .nav-label {
            padding: 1.25rem 1.25rem 0.5rem;
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #475569;
            font-weight: 600;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.55rem 1rem;
            margin: 2px 0.75rem;
            color: #8b9dc3;
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 450;
            transition: var(--transition);
            border-radius: 0.5rem;
            position: relative;
        }

        .sidebar-nav .nav-link:hover {
            background: var(--sidebar-hover);
            color: #c8d6e5;
        }

        .sidebar-nav .nav-link.active {
            background: rgba(59, 130, 246, 0.12);
            color: #60a5fa;
            font-weight: 550;
        }

        .sidebar-nav .nav-link.active i {
            color: #60a5fa;
        }

        .sidebar-nav .nav-link i {
            font-size: 1rem;
            width: 1.25rem;
            text-align: center;
            flex-shrink: 0;
            opacity: 0.7;
            transition: var(--transition);
        }

        .sidebar-nav .nav-link:hover i { opacity: 1; }
        .sidebar-nav .nav-link.active i { opacity: 1; }

        /* Mobile sidebar overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }
        .sidebar-overlay.show { display: block; }

        /* ===== MAIN ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: var(--page-bg);
        }

        .top-header {
            background: #fff;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 2px 8px rgba(0,0,0,0.06), 0 8px 24px rgba(0,0,0,0.05);
            padding: 0 2rem;
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .top-header h6 {
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text-primary);
            margin: 0;
        }

        .header-icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            background: transparent;
            border: 1px solid transparent;
            transition: var(--transition);
            font-size: 1.1rem;
            cursor: pointer;
        }
        .header-icon-btn:hover {
            background: #f1f5f9;
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        .header-avatar {
            transition: var(--transition);
        }
        .header-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        }

        .page-content {
            padding: 1.75rem 2rem 3rem;
        }

        /* ===== PAGE HEADER ===== */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .page-header h4 {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text-primary);
            margin: 0;
        }

        .page-header-subtitle {
            font-size: 0.82rem;
            color: #64748b;
            margin-top: 0.15rem;
        }

        /* ===== CARDS ===== */
        .card {
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            border-radius: var(--radius);
            background: #fff;
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid var(--border-light);
            font-weight: 600;
            font-size: 0.85rem;
            padding: 1rem 1.25rem;
            color: var(--text-primary);
        }

        .card-body { padding: 1.25rem; }

        /* Stat cards */
        .stat-card {
            transition: var(--transition);
            cursor: pointer;
            border: 1px solid var(--border-color);
            overflow: hidden;
            position: relative;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--card-shadow-hover);
            border-color: #cbd5e1;
        }

        .stat-card-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .stat-card-value {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.2;
        }

        .stat-card-label {
            font-size: 0.72rem;
            color: #64748b;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        /* ===== STATUS BADGES ===== */
        .status-badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.65rem;
            border-radius: 9999px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            white-space: nowrap;
            letter-spacing: 0.01em;
        }

        .status-pendente { background: #fef3c7; color: #92400e; }
        .status-pago { background: #d1fae5; color: #065f46; }
        .status-atrasado { background: #fee2e2; color: #991b1b; }
        .status-falta_link { background: #e0e7ff; color: #3730a3; }
        .status-confirmado { background: #d1fae5; color: #065f46; }
        .status-cancelado { background: #f3f4f6; color: #6b7280; }
        .status-concluido { background: #dbeafe; color: #1e40af; }
        .status-ativo { background: #d1fae5; color: #065f46; }
        .status-inativo { background: #f3f4f6; color: #6b7280; }
        .status-enviado { background: #d1fae5; color: #065f46; }
        .status-falhou { background: #fee2e2; color: #991b1b; }
        .status-manual { background: #f3f4f6; color: #6b7280; }
        .status-automatico { background: #dbeafe; color: #1e40af; }

        /* ===== CURRENCY BADGES ===== */
        .currency-badge {
            font-size: 0.68rem;
            padding: 0.18rem 0.5rem;
            border-radius: 5px;
            font-weight: 700;
            letter-spacing: 0.03em;
        }
        .currency-BRL { background: #dcfce7; color: #166534; }
        .currency-USD { background: #dbeafe; color: #1e40af; }
        .currency-EUR { background: #fef3c7; color: #92400e; }

        /* ===== TABLES ===== */
        .table {
            --bs-table-hover-bg: rgba(248, 250, 252, 0.8);
            margin-bottom: 0;
        }

        .table th {
            font-size: 0.72rem;
            text-transform: uppercase;
            color: var(--text-secondary);
            font-weight: 600;
            letter-spacing: 0.05em;
            white-space: nowrap;
            border-bottom: 1px solid var(--border-color) !important;
            padding: 0.85rem 1.15rem;
            background: #fafbfd;
        }

        .table td {
            vertical-align: middle;
            font-size: 0.84rem;
            padding: 0.85rem 1.15rem;
            border-bottom: 1px solid var(--border-light) !important;
            color: var(--text-primary);
        }

        .table tbody tr:last-child td {
            border-bottom: none !important;
        }

        .table-hover tbody tr {
            transition: background-color 0.15s ease;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(248, 250, 252, 0.8) !important;
        }

        .btn-action {
            padding: 0.3rem 0.55rem;
            font-size: 0.78rem;
            border-radius: 0.4rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-action:hover {
            transform: translateY(-1px);
        }

        /* ===== EMPTY STATES ===== */
        .empty-state {
            text-align: center;
            padding: 3.5rem 1rem;
            color: var(--text-muted);
        }
        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
            display: block;
            opacity: 0.3;
            color: #cbd5e1;
        }
        .empty-state p {
            margin: 0;
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* ===== FORMS ===== */
        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.35rem;
        }

        .form-text {
            font-size: 0.74rem;
            color: #9ca3af;
        }

        .form-control, .form-select {
            border-radius: var(--radius-sm);
            border-color: #d1d5db;
            font-size: 0.85rem;
            padding: 0.55rem 0.85rem;
            transition: var(--transition);
            color: var(--text-primary);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--sidebar-active);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control::placeholder { color: #c3c8d0; }

        /* Submit button loading state */
        .btn-loading {
            pointer-events: none;
            opacity: 0.7;
        }
        .btn-loading .spinner-border {
            width: 1rem;
            height: 1rem;
            border-width: 0.15em;
        }

        /* ===== BUTTONS ===== */
        .btn {
            font-weight: 550;
            font-size: 0.84rem;
            border-radius: var(--radius-sm);
            transition: var(--transition);
            letter-spacing: 0.01em;
            padding: 0.45rem 1rem;
        }

        .btn-primary {
            background: #3b82f6;
            border-color: #3b82f6;
            box-shadow: 0 1px 2px rgba(59, 130, 246, 0.2);
        }
        .btn-primary:hover {
            background: #2563eb;
            border-color: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-outline-primary {
            border-color: #bfdbfe;
            color: #2563eb;
        }
        .btn-outline-primary:hover {
            background: #eff6ff;
            border-color: #93bbfd;
            color: #1d4ed8;
            transform: translateY(-1px);
        }

        .btn-outline-secondary {
            border-color: #d1d5db;
            color: var(--text-secondary);
        }
        .btn-outline-secondary:hover {
            background: #f8fafc;
            border-color: #9ca3af;
            color: var(--text-primary);
            transform: translateY(-1px);
        }

        .btn-outline-danger:hover { transform: translateY(-1px); }
        .btn-outline-success:hover { transform: translateY(-1px); }
        .btn-outline-warning:hover { transform: translateY(-1px); }

        .btn-secondary {
            background: #475569;
            border-color: #475569;
        }
        .btn-secondary:hover {
            background: #334155;
            border-color: #334155;
        }

        /* ===== SECTION HEADING ===== */
        .section-heading {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            font-weight: 700;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-heading::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-color);
        }

        /* ===== FILTER BAR ===== */
        .filter-bar {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
            box-shadow: var(--card-shadow);
        }

        .filter-bar .form-control,
        .filter-bar .form-select {
            font-size: 0.82rem;
            background: #fff;
            border-color: #e2e8f0;
        }

        .filter-bar .form-label {
            font-size: 0.7rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        /* ===== TIMESTAMPS ===== */
        .timestamp-muted {
            font-size: 0.72rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ===== PROGRESS BAR (capacity) ===== */
        .capacity-bar {
            height: 5px;
            border-radius: 3px;
            background: #e2e8f0;
            overflow: hidden;
        }
        .capacity-bar-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ===== PAGINATION ===== */
        .rpms-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0 0.25rem;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .rpms-pagination-info {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }
        .rpms-pagination-info strong {
            color: var(--text-primary);
            font-weight: 700;
        }
        .rpms-pagination-nav {
            display: flex;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 4px;
        }
        .rpms-page-item .rpms-page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 0.5rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-secondary);
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
        }
        .rpms-page-item .rpms-page-link:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: var(--text-primary);
        }
        .rpms-page-item.active .rpms-page-link {
            background: var(--sidebar-active);
            border-color: var(--sidebar-active);
            color: #fff;
            box-shadow: 0 2px 6px rgba(59, 130, 246, 0.3);
        }
        .rpms-page-item.disabled .rpms-page-link {
            color: #cbd5e1;
            background: #f8fafc;
            border-color: var(--border-light);
            cursor: not-allowed;
            pointer-events: none;
        }
        .rpms-page-dots {
            border: none !important;
            background: transparent !important;
            color: var(--text-muted) !important;
            min-width: 24px !important;
            cursor: default !important;
            letter-spacing: 2px;
        }
        .rpms-page-item .rpms-page-link i { font-size: 0.7rem; }

        /* ===== ALERTS ===== */
        .alert {
            border-radius: var(--radius);
            border: none;
            font-size: 0.84rem;
            font-weight: 500;
        }
        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        /* ===== TOAST ===== */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }
        .rpms-toast {
            pointer-events: auto;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            min-width: 320px;
            max-width: 420px;
            padding: 14px 18px;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.06);
            border: 1px solid var(--border-light);
            transform: translateX(120%);
            opacity: 0;
            transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.4s ease;
        }
        .rpms-toast.show {
            transform: translateX(0);
            opacity: 1;
        }
        .rpms-toast.hiding {
            transform: translateX(120%);
            opacity: 0;
        }
        .rpms-toast-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .rpms-toast-icon.success {
            background: #ecfdf5;
            color: #10b981;
        }
        .rpms-toast-icon.error {
            background: #fef2f2;
            color: #ef4444;
        }
        .rpms-toast-icon.warning {
            background: #fffbeb;
            color: #f59e0b;
        }
        .rpms-toast-body {
            flex: 1;
            min-width: 0;
        }
        .rpms-toast-title {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 2px;
        }
        .rpms-toast-message {
            font-size: 0.76rem;
            color: var(--text-muted);
            line-height: 1.4;
        }
        .rpms-toast-close {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1rem;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            flex-shrink: 0;
            opacity: 0.5;
            transition: opacity 0.2s;
        }
        .rpms-toast-close:hover {
            opacity: 1;
        }
        .rpms-toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            border-radius: 0 0 12px 12px;
            transition: width linear;
        }
        .rpms-toast-progress.success { background: #10b981; }
        .rpms-toast-progress.error { background: #ef4444; }
        .rpms-toast-progress.warning { background: #f59e0b; }
        .rpms-toast { position: relative; overflow: hidden; }

        [data-theme="dark"] .rpms-toast {
            background: rgba(10, 18, 45, 0.9);
            border-color: rgba(59, 130, 246, 0.12);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.4), 0 0 1px rgba(59, 130, 246, 0.15);
        }
        [data-theme="dark"] .rpms-toast-icon.success { background: rgba(16, 185, 129, 0.15); }
        [data-theme="dark"] .rpms-toast-icon.error { background: rgba(239, 68, 68, 0.15); }
        [data-theme="dark"] .rpms-toast-icon.warning { background: rgba(245, 158, 11, 0.15); }

        /* ===== CONFIRM DIALOG ===== */
        .confirm-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.45);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        .confirm-overlay.show { opacity: 1; }
        .confirm-dialog {
            background: #fff;
            border-radius: 16px;
            padding: 32px 28px 24px;
            min-width: 360px;
            max-width: 440px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            transform: scale(0.9) translateY(10px);
            transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1);
            text-align: center;
        }
        .confirm-overlay.show .confirm-dialog {
            transform: scale(1) translateY(0);
        }
        .confirm-icon {
            width: 56px; height: 56px;
            border-radius: 50%;
            background: rgba(245, 158, 11, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            font-size: 1.5rem;
            color: #f59e0b;
        }
        .confirm-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
        }
        .confirm-message {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 24px;
            line-height: 1.5;
        }
        .confirm-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .confirm-btn {
            padding: 10px 28px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .confirm-btn-cancel {
            background: #f1f5f9;
            color: #475569;
        }
        .confirm-btn-cancel:hover {
            background: #e2e8f0;
        }
        .confirm-btn-ok {
            background: #f59e0b;
            color: #fff;
        }
        .confirm-btn-ok:hover {
            background: #d97706;
        }
        .confirm-btn-danger {
            background: #ef4444;
            color: #fff;
        }
        .confirm-btn-danger:hover {
            background: #dc2626;
        }
        [data-theme="dark"] .confirm-dialog {
            background: rgba(10, 18, 45, 0.95);
            border: 1px solid rgba(59, 130, 246, 0.12);
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }
        [data-theme="dark"] .confirm-title { color: #f1f5f9; }
        [data-theme="dark"] .confirm-message { color: #94a3b8; }
        [data-theme="dark"] .confirm-btn-cancel { background: rgba(59, 130, 246, 0.1); color: #94a3b8; }
        [data-theme="dark"] .confirm-btn-cancel:hover { background: rgba(59, 130, 246, 0.18); color: #e2e8f0; }

        /* ===== MODAL ===== */
        .modal-content {
            border-radius: var(--radius);
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        }
        .modal-header {
            border-bottom: 1px solid var(--border-light);
            padding: 1.25rem 1.5rem;
        }
        .modal-footer {
            border-top: 1px solid var(--border-light);
            padding: 1rem 1.5rem;
        }

        /* ===== ACCORDION ===== */
        .accordion-item {
            border: 1px solid var(--border-color);
            border-radius: var(--radius) !important;
            margin-bottom: 0.5rem;
            overflow: hidden;
        }
        .accordion-button {
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--text-primary);
        }
        .accordion-button:not(.collapsed) {
            background: #f8fafc;
            color: #1e40af;
        }
        .accordion-button:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* ===== NAV TABS (LOGS) ===== */
        .nav-tabs {
            border-bottom: 1px solid var(--border-color);
        }
        .nav-tabs .nav-link {
            font-weight: 600;
            font-size: 0.84rem;
            color: var(--text-muted);
            border: none;
            padding: 0.7rem 1.25rem;
            border-bottom: 2px solid transparent;
            margin-bottom: -1px;
            transition: var(--transition);
        }
        .nav-tabs .nav-link:hover {
            color: var(--text-primary);
            border-color: #cbd5e1;
        }
        .nav-tabs .nav-link.active {
            color: var(--sidebar-active);
            border-bottom-color: var(--sidebar-active);
            background: transparent;
        }

        /* ===== DETAIL CARD ROWS ===== */
        .detail-row {
            display: flex;
            padding: 0.7rem 0;
            border-bottom: 1px solid var(--border-light);
            align-items: center;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label {
            width: 140px;
            font-size: 0.78rem;
            color: var(--text-muted);
            font-weight: 600;
            flex-shrink: 0;
        }
        .detail-value {
            font-size: 0.85rem;
            color: var(--text-primary);
        }

        /* ===== TOOLTIP BADGES ===== */
        .count-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 22px;
            height: 22px;
            padding: 0 6px;
            font-size: 0.68rem;
            font-weight: 700;
            border-radius: 6px;
            background: #f1f5f9;
            color: var(--text-secondary);
        }

        /* ===== FADE-IN ANIMATION ===== */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .page-content { animation: fadeIn 0.25s ease-out; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 576px) {
            .rpms-pagination { justify-content: center; }
            .rpms-pagination-info { width: 100%; text-align: center; }
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .page-content { padding: 1rem; }
            .top-header { padding: 0 1rem; }
            .page-header { flex-direction: column; align-items: flex-start; }
            .page-header h4 { font-size: 1.1rem; }
        }

        /* ===== DARK MODE — Transparent Dark Blue ===== */
        [data-theme="dark"] {
            --sidebar-bg: rgba(2, 6, 23, 0.95);
            --sidebar-hover: rgba(59, 130, 246, 0.08);
            --sidebar-border: rgba(99, 130, 202, 0.08);
            --page-bg: #060c1f;
            --card-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
            --card-shadow-hover: 0 12px 32px -6px rgba(0, 0, 0, 0.4);
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --border-color: rgba(59, 130, 246, 0.12);
            --border-light: rgba(59, 130, 246, 0.08);
        }

        [data-theme="dark"] body {
            background-color: var(--page-bg);
            color: var(--text-primary);
        }

        [data-theme="dark"] .top-header {
            background: rgba(8, 15, 40, 0.85);
            border-bottom-color: rgba(59, 130, 246, 0.1);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 1px 0 rgba(59, 130, 246, 0.06);
        }

        [data-theme="dark"] .top-header h6 { color: #e2e8f0; }

        [data-theme="dark"] .header-icon-btn {
            color: #94a3b8;
        }
        [data-theme="dark"] .header-icon-btn:hover {
            background: rgba(59, 130, 246, 0.1);
            color: #e2e8f0;
            border-color: rgba(59, 130, 246, 0.2);
        }

        [data-theme="dark"] .main-content { background: var(--page-bg); }

        [data-theme="dark"] .card {
            background: rgba(15, 23, 55, 0.6);
            border: 1px solid rgba(59, 130, 246, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        [data-theme="dark"] .card-header {
            background: rgba(15, 23, 55, 0.4);
            border-bottom-color: rgba(59, 130, 246, 0.08);
            color: #e2e8f0;
        }

        [data-theme="dark"] .stat-card {
            background: rgba(15, 23, 55, 0.5);
            border-color: rgba(59, 130, 246, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        [data-theme="dark"] .stat-card:hover {
            border-color: rgba(59, 130, 246, 0.25);
            background: rgba(15, 23, 55, 0.7);
        }

        [data-theme="dark"] .filter-bar {
            background: rgba(15, 23, 55, 0.5);
            border-color: rgba(59, 130, 246, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        [data-theme="dark"] .filter-bar .form-control,
        [data-theme="dark"] .filter-bar .form-select {
            background: rgba(6, 12, 31, 0.6);
            border-color: rgba(59, 130, 246, 0.12);
            color: #e2e8f0;
        }

        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select {
            background: rgba(15, 23, 55, 0.5);
            border-color: rgba(59, 130, 246, 0.12);
            color: #e2e8f0;
        }
        [data-theme="dark"] .form-control::placeholder { color: #64748b; }
        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] .form-select:focus {
            background: rgba(15, 23, 55, 0.7);
            border-color: rgba(59, 130, 246, 0.4);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        [data-theme="dark"] .table {
            background: transparent;
            color: #cbd5e1;
        }

        [data-theme="dark"] .table th {
            background: rgba(15, 23, 55, 0.5);
            color: #cbd5e1;
            border-bottom-color: rgba(59, 130, 246, 0.1) !important;
        }

        [data-theme="dark"] .table td {
            background: transparent;
            color: #e2e8f0;
            border-bottom-color: rgba(59, 130, 246, 0.06) !important;
        }

        [data-theme="dark"] .table-striped > tbody > tr:nth-of-type(odd) > * {
            background: rgba(15, 23, 55, 0.3);
            color: #cbd5e1;
        }

        [data-theme="dark"] .table {
            --bs-table-hover-bg: rgba(59, 130, 246, 0.06);
            --bs-table-hover-color: #e2e8f0;
        }

        [data-theme="dark"] .table-hover tbody tr:hover,
        [data-theme="dark"] .table-hover tbody tr:hover > * {
            background-color: rgba(59, 130, 246, 0.08) !important;
            color: #e2e8f0 !important;
        }

        [data-theme="dark"] .dropdown-menu {
            background: rgba(10, 18, 45, 0.92);
            border-color: rgba(59, 130, 246, 0.12);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        [data-theme="dark"] .dropdown-item {
            color: #cbd5e1;
        }
        [data-theme="dark"] .dropdown-item:hover {
            background: rgba(59, 130, 246, 0.1);
            color: #e2e8f0;
        }

        [data-theme="dark"] .modal-content {
            background: rgba(10, 18, 45, 0.92);
            color: #e2e8f0;
            border: 1px solid rgba(59, 130, 246, 0.1);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        [data-theme="dark"] .accordion-item {
            background: rgba(15, 23, 55, 0.5);
            border-color: rgba(59, 130, 246, 0.1);
        }
        [data-theme="dark"] .accordion-button {
            background: rgba(15, 23, 55, 0.5);
            color: #e2e8f0;
        }
        [data-theme="dark"] .accordion-button:not(.collapsed) {
            background: rgba(59, 130, 246, 0.08);
            color: #60a5fa;
        }
        [data-theme="dark"] .accordion-button::after {
            filter: invert(1) brightness(2);
        }
        [data-theme="dark"] .accordion-body {
            color: #e2e8f0;
        }
        [data-theme="dark"] .accordion-body .form-label,
        [data-theme="dark"] .accordion-body .form-check-label {
            color: #e2e8f0;
        }
        [data-theme="dark"] .accordion-body .form-text,
        [data-theme="dark"] .accordion-body .text-muted {
            color: #94a3b8 !important;
        }

        [data-theme="dark"] .nav-tabs {
            border-bottom-color: rgba(59, 130, 246, 0.1);
        }
        [data-theme="dark"] .nav-tabs .nav-link {
            color: #94a3b8;
        }
        [data-theme="dark"] .nav-tabs .nav-link:hover {
            color: #e2e8f0;
            border-color: rgba(59, 130, 246, 0.2);
        }
        [data-theme="dark"] .nav-tabs .nav-link.active {
            color: #60a5fa;
            background: rgba(59, 130, 246, 0.06);
            border-color: rgba(59, 130, 246, 0.15) rgba(59, 130, 246, 0.15) transparent;
        }

        [data-theme="dark"] .alert-success {
            background: rgba(16, 185, 129, 0.08);
            color: #6ee7b7;
            border-left-color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.12);
            border-left-width: 4px;
        }
        [data-theme="dark"] .alert-danger {
            background: rgba(239, 68, 68, 0.08);
            color: #fca5a5;
            border-left-color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.12);
            border-left-width: 4px;
        }

        [data-theme="dark"] .text-muted { color: #94a3b8 !important; }
        [data-theme="dark"] .fw-medium { color: #e2e8f0; }
        [data-theme="dark"] .btn-light { background: rgba(59, 130, 246, 0.1); border-color: rgba(59, 130, 246, 0.15); color: #e2e8f0; }
        [data-theme="dark"] .btn-light:hover { background: rgba(59, 130, 246, 0.18); }
        [data-theme="dark"] .btn-outline-secondary { border-color: rgba(59, 130, 246, 0.15); color: #94a3b8; }
        [data-theme="dark"] .btn-outline-secondary:hover { background: rgba(59, 130, 246, 0.1); color: #e2e8f0; border-color: rgba(59, 130, 246, 0.25); }
        [data-theme="dark"] .btn-outline-primary { border-color: rgba(59, 130, 246, 0.3); color: #60a5fa; }
        [data-theme="dark"] .btn-outline-primary:hover { background: rgba(59, 130, 246, 0.1); color: #93bbfd; border-color: rgba(59, 130, 246, 0.4); }
        [data-theme="dark"] .btn-outline-danger { border-color: rgba(239, 68, 68, 0.3); color: #fca5a5; }
        [data-theme="dark"] .btn-outline-danger:hover { background: rgba(239, 68, 68, 0.1); color: #fca5a5; border-color: rgba(239, 68, 68, 0.4); }
        [data-theme="dark"] .btn-outline-warning { border-color: rgba(245, 158, 11, 0.3); color: #fcd34d; }
        [data-theme="dark"] .btn-outline-warning:hover { background: rgba(245, 158, 11, 0.1); }

        [data-theme="dark"] .count-badge { background: rgba(59, 130, 246, 0.1); color: #cbd5e1; }
        [data-theme="dark"] .section-heading { color: #94a3b8; }
        [data-theme="dark"] .section-heading::after { background: rgba(59, 130, 246, 0.15); }
        [data-theme="dark"] .detail-row { border-bottom-color: rgba(59, 130, 246, 0.06); }
        [data-theme="dark"] .input-group-text { background: rgba(15, 23, 55, 0.5); border-color: rgba(59, 130, 246, 0.12); color: #94a3b8; }
        [data-theme="dark"] .rpms-page-item .rpms-page-link { background: rgba(15, 23, 55, 0.5); border-color: rgba(59, 130, 246, 0.1); color: #94a3b8; }
        [data-theme="dark"] .rpms-page-item .rpms-page-link:hover { background: rgba(59, 130, 246, 0.12); color: #e2e8f0; }
        [data-theme="dark"] .rpms-page-item.disabled .rpms-page-link { background: rgba(6, 12, 31, 0.4); border-color: rgba(59, 130, 246, 0.06); color: #475569; }
        [data-theme="dark"] .empty-state i { color: #475569; }
        [data-theme="dark"] .empty-state p { color: #94a3b8; }
        [data-theme="dark"] .stat-card-label { color: #94a3b8; }
        [data-theme="dark"] .stat-card-value { color: #f1f5f9; }
        [data-theme="dark"] .timestamp-muted { color: #94a3b8 !important; }
        [data-theme="dark"] .form-label { color: #cbd5e1; }
        [data-theme="dark"] .card-body { color: #cbd5e1; }
        [data-theme="dark"] .detail-label { color: #94a3b8; }
        [data-theme="dark"] .detail-value { color: #e2e8f0; }
        [data-theme="dark"] h5, [data-theme="dark"] h6 { color: #f1f5f9; }
        [data-theme="dark"] .currency-BRL { background: rgba(22, 163, 74, 0.12); color: #4ade80; }
        [data-theme="dark"] .currency-USD { background: rgba(59, 130, 246, 0.12); color: #60a5fa; }
        [data-theme="dark"] .currency-EUR { background: rgba(245, 158, 11, 0.12); color: #fbbf24; }
        [data-theme="dark"] .status-pendente { background: rgba(245, 158, 11, 0.1); color: #fbbf24; }
        [data-theme="dark"] .status-pago { background: rgba(16, 185, 129, 0.1); color: #34d399; }
        [data-theme="dark"] .status-atrasado { background: rgba(239, 68, 68, 0.1); color: #fca5a5; }
        [data-theme="dark"] .status-falta_link { background: rgba(99, 102, 241, 0.1); color: #a5b4fc; }
        [data-theme="dark"] .status-confirmado { background: rgba(16, 185, 129, 0.1); color: #34d399; }
        [data-theme="dark"] .status-cancelado { background: rgba(107, 114, 128, 0.1); color: #9ca3af; }
        [data-theme="dark"] .status-concluido { background: rgba(59, 130, 246, 0.1); color: #60a5fa; }
        [data-theme="dark"] .status-ativo { background: rgba(16, 185, 129, 0.1); color: #34d399; }
        [data-theme="dark"] .status-inativo { background: rgba(107, 114, 128, 0.1); color: #9ca3af; }
        [data-theme="dark"] .status-enviado { background: rgba(16, 185, 129, 0.1); color: #34d399; }
        [data-theme="dark"] .status-falhou { background: rgba(239, 68, 68, 0.1); color: #fca5a5; }
        [data-theme="dark"] .status-manual { background: rgba(107, 114, 128, 0.1); color: #9ca3af; }
        [data-theme="dark"] .status-automatico { background: rgba(59, 130, 246, 0.1); color: #60a5fa; }
        [data-theme="dark"] .page-header { color: #e2e8f0; }
        [data-theme="dark"] .badge.bg-secondary { background: rgba(59, 130, 246, 0.1) !important; color: #cbd5e1 !important; }

        /* Theme toggle button */
        .theme-toggle {
            position: relative;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            background: transparent;
            border: 1px solid transparent;
            transition: var(--transition);
            font-size: 1.1rem;
            cursor: pointer;
        }
        .theme-toggle:hover {
            background: #f1f5f9;
            color: var(--text-primary);
            border-color: var(--border-color);
        }
        [data-theme="dark"] .theme-toggle:hover {
            background: rgba(59, 130, 246, 0.1);
            color: #e2e8f0;
            border-color: rgba(59, 130, 246, 0.2);
        }
        .theme-toggle .bi-moon-fill { display: inline; }
        .theme-toggle .bi-sun-fill { display: none; }
        [data-theme="dark"] .theme-toggle .bi-moon-fill { display: none; }
        [data-theme="dark"] .theme-toggle .bi-sun-fill { display: inline; }

        /* ===== PRINT ===== */
        @media print {
            .sidebar, .sidebar-overlay, .top-header, .btn, .filter-bar, .rpms-pagination { display: none !important; }
            .main-content { margin-left: 0 !important; }
            .page-content { padding: 0 !important; animation: none !important; }
            .card { box-shadow: none !important; border: 1px solid #ddd !important; }
        }
    </style>
    @stack('styles')
    <script>
        // Apply saved theme immediately to prevent flash
        (function() {
            var theme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>
<body>
    <a href="#main-content" class="skip-link visually-hidden-focusable">Pular para o conteudo</a>

    {{-- Mobile overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- Sidebar --}}
    <nav class="sidebar" id="sidebar" aria-label="Navegacao principal">
        <div class="sidebar-brand" style="justify-content: center; padding: 1.5rem 1.25rem 1.25rem;">
            <img src="{{ asset('images/mojo.png') }}" alt="Mojo Logo" style="width: 100%; max-width: 160px; object-fit: contain;">
        </div>
        <div class="sidebar-nav">
            <div class="nav-label">Principal</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            <div class="nav-label">Operacional</div>
            <a href="{{ route('tours.index') }}" class="nav-link {{ request()->routeIs('tours.*') ? 'active' : '' }}">
                <i class="bi bi-map-fill"></i> Tours
            </a>
            <a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Clientes
            </a>
            <a href="{{ route('bookings.index') }}" class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                <i class="bi bi-journal-bookmark-fill"></i> Reservas
            </a>
            <a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                <i class="bi bi-credit-card-2-front-fill"></i> Pagamentos
            </a>

            @if(auth()->user()?->canManage())
            <div class="nav-label">Sistema</div>
            <a href="{{ route('email-templates.index') }}" class="nav-link {{ request()->routeIs('email-templates.*') ? 'active' : '' }}">
                <i class="bi bi-envelope-paper-fill"></i> Templates de E-mail
            </a>
            <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i> Configuracoes
            </a>
            <a href="{{ route('logs.index') }}" class="nav-link {{ request()->routeIs('logs.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Logs
            </a>
            @endif

            @if(auth()->user()?->isAdmin())
                <div class="nav-label">Administracao</div>
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-lock-fill"></i> Usuarios
                </a>
            @endif
        </div>

        {{-- User info & logout --}}
        <div style="border-top: 1px solid var(--sidebar-border); padding: 0.875rem 1rem; margin-top: auto;">
            <div class="d-flex align-items-center gap-2">
                <div style="width: 34px; height: 34px; background: linear-gradient(135deg, #3b82f6, #8b5cf6); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1rem; flex-shrink: 0;">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div style="overflow: hidden; flex: 1; min-width: 0;">
                    <div style="color: #e2e8f0; font-size: 0.78rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ auth()->user()->name ?? 'Usuario' }}</div>
                    <div style="font-size: 0.65rem; color: #64748b;">{{ auth()->user()->roleName() ?? 'Viewer' }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="ms-auto flex-shrink-0">
                    @csrf
                    <button type="submit" class="header-icon-btn" style="width: 30px; height: 30px; border-radius: 8px; background: rgba(255,255,255,0.04); border: none; color: #64748b; font-size: 0.85rem;" title="Sair">
                        <i class="bi bi-box-arrow-left"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <div class="main-content" id="main-content">
        <header class="top-header">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-link d-md-none text-dark p-0" onclick="toggleSidebar()" aria-label="Menu">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <div>
                    <h6>@yield('page-title', 'Dashboard')</h6>
                    @hasSection('page-subtitle')
                        <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 0.1rem; line-height: 1.3;">@yield('page-subtitle')</div>
                    @endif
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="theme-toggle" onclick="toggleTheme()" title="Alternar tema">
                    <i class="bi bi-moon-fill"></i>
                    <i class="bi bi-sun-fill"></i>
                </button>
                <div class="dropdown">
                    <button class="btn p-0 d-flex align-items-center gap-2 border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div style="text-align: right; margin-right: 0.25rem;" class="d-none d-sm-block">
                            <div style="font-size: 0.82rem; font-weight: 600; color: var(--text-primary); line-height: 1.2;">{{ auth()->user()->name ?? 'Usuario' }}</div>
                            <div style="font-size: 0.68rem; color: var(--text-muted);">{{ auth()->user()->roleName() ?? 'Viewer' }}</div>
                        </div>
                        <div class="header-avatar" style="width: 38px; height: 38px; background: #e5e7eb; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #6b7280; font-size: 1.1rem; flex-shrink: 0;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width: 220px; border: 1px solid var(--border-color); border-radius: var(--radius); box-shadow: 0 10px 40px -5px rgba(0,0,0,0.12); padding: 0.5rem;">
                        <li class="px-3 py-2" style="border-bottom: 1px solid var(--border-light);">
                            <div style="font-size: 0.82rem; font-weight: 600; color: var(--text-primary);">{{ auth()->user()->name ?? 'Usuario' }}</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">{{ auth()->user()->email ?? '' }}</div>
                        </li>
                        <li><a class="dropdown-item d-flex align-items-center gap-2 py-2 mt-1" href="{{ route('profile.edit') }}" style="font-size: 0.84rem; border-radius: 0.375rem;"><i class="bi bi-person text-muted"></i> Meu Perfil</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger" style="font-size: 0.84rem; border-radius: 0.375rem;">
                                    <i class="bi bi-box-arrow-left"></i> Sair
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <div class="page-content">
            @yield('content')
        </div>
    </div>

    {{-- Delete Confirmation Modal (shared) --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <div style="width: 56px; height: 56px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i class="bi bi-exclamation-triangle text-danger" style="font-size: 1.5rem;"></i>
                    </div>
                    <h6 class="mb-1 fw-bold">Confirmar exclusao</h6>
                    <p class="text-muted small mb-3" id="deleteModalMessage">Tem certeza que deseja excluir este item?</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-sm btn-light px-3" data-bs-dismiss="modal">Cancelar</button>
                        <form id="deleteModalForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger px-3">
                                <i class="bi bi-trash3"></i> Excluir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        // Sidebar toggle
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }

        // Toast notification system
        function showToast(type, title, message, duration) {
            duration = duration || 5000;
            var container = document.getElementById('toastContainer');
            var icons = {
                success: 'bi-check-circle-fill',
                error: 'bi-exclamation-circle-fill',
                warning: 'bi-exclamation-triangle-fill'
            };
            var titles = {
                success: title || 'Sucesso',
                error: title || 'Erro',
                warning: title || 'Aviso'
            };

            var toast = document.createElement('div');
            toast.className = 'rpms-toast';
            toast.innerHTML =
                '<div class="rpms-toast-icon ' + type + '"><i class="bi ' + icons[type] + '"></i></div>' +
                '<div class="rpms-toast-body">' +
                    '<div class="rpms-toast-title">' + titles[type] + '</div>' +
                    '<div class="rpms-toast-message">' + message + '</div>' +
                '</div>' +
                '<button class="rpms-toast-close" onclick="dismissToast(this.parentElement)">&times;</button>' +
                '<div class="rpms-toast-progress ' + type + '" style="width: 100%;"></div>';

            container.appendChild(toast);

            // Trigger animation
            requestAnimationFrame(function() {
                requestAnimationFrame(function() {
                    toast.classList.add('show');
                });
            });

            // Progress bar
            var progress = toast.querySelector('.rpms-toast-progress');
            progress.style.transitionDuration = duration + 'ms';
            requestAnimationFrame(function() {
                requestAnimationFrame(function() {
                    progress.style.width = '0%';
                });
            });

            // Auto dismiss
            setTimeout(function() { dismissToast(toast); }, duration);
        }

        function dismissToast(toast) {
            if (toast.classList.contains('hiding')) return;
            toast.classList.add('hiding');
            toast.classList.remove('show');
            setTimeout(function() { toast.remove(); }, 400);
        }

        // Form submit protection (prevent double-click)
        document.querySelectorAll('form[method="POST"]').forEach(function(form) {
            if (form.closest('.table') || form.id === 'deleteModalForm') return;
            form.addEventListener('submit', function(e) {
                var btn = form.querySelector('button[type="submit"]');
                if (btn && !btn.classList.contains('btn-loading')) {
                    btn.classList.add('btn-loading');
                    btn.insertAdjacentHTML('afterbegin', '<span class="spinner-border me-1" role="status"></span>');
                }
            });
        });

        // Theme toggle
        function toggleTheme() {
            var current = document.documentElement.getAttribute('data-theme') || 'light';
            var next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
        }

        // Shared delete modal
        function confirmDelete(url, message) {
            document.getElementById('deleteModalForm').action = url;
            if (message) document.getElementById('deleteModalMessage').textContent = message;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        // Custom confirm dialog
        function rpmsConfirm(message, options) {
            options = options || {};
            var title = options.title || 'Confirmar';
            var okText = options.okText || 'Confirmar';
            var cancelText = options.cancelText || 'Cancelar';
            var type = options.type || 'warning'; // warning, danger
            var icon = type === 'danger' ? 'bi-exclamation-triangle-fill' : 'bi-question-circle-fill';
            var iconColor = type === 'danger' ? '#ef4444' : '#f59e0b';
            var iconBg = type === 'danger' ? 'rgba(239,68,68,0.1)' : 'rgba(245,158,11,0.1)';
            var btnClass = type === 'danger' ? 'confirm-btn-danger' : 'confirm-btn-ok';

            return new Promise(function(resolve) {
                var overlay = document.createElement('div');
                overlay.className = 'confirm-overlay';
                overlay.innerHTML =
                    '<div class="confirm-dialog">' +
                        '<div class="confirm-icon" style="background:' + iconBg + ';color:' + iconColor + '">' +
                            '<i class="bi ' + icon + '"></i>' +
                        '</div>' +
                        '<div class="confirm-title">' + title + '</div>' +
                        '<div class="confirm-message">' + message + '</div>' +
                        '<div class="confirm-actions">' +
                            '<button class="confirm-btn confirm-btn-cancel" id="confirmCancel">' + cancelText + '</button>' +
                            '<button class="confirm-btn ' + btnClass + '" id="confirmOk">' + okText + '</button>' +
                        '</div>' +
                    '</div>';
                document.body.appendChild(overlay);
                requestAnimationFrame(function() { overlay.classList.add('show'); });

                function close(result) {
                    overlay.classList.remove('show');
                    setTimeout(function() { overlay.remove(); }, 200);
                    resolve(result);
                }

                overlay.querySelector('#confirmOk').addEventListener('click', function() { close(true); });
                overlay.querySelector('#confirmCancel').addEventListener('click', function() { close(false); });
                overlay.addEventListener('click', function(e) { if (e.target === overlay) close(false); });
                document.addEventListener('keydown', function handler(e) {
                    if (e.key === 'Escape') { close(false); document.removeEventListener('keydown', handler); }
                    if (e.key === 'Enter') { close(true); document.removeEventListener('keydown', handler); }
                });
            });
        }

        // Auto-bind forms and buttons with data-confirm attribute
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', function(e) {
                var btn = e.target.closest('[data-confirm]');
                if (!btn) return;
                var form = btn.closest('form');
                if (form && (btn.type === 'submit' || btn.tagName === 'BUTTON')) {
                    e.preventDefault();
                    rpmsConfirm(btn.getAttribute('data-confirm'), {
                        title: btn.getAttribute('data-confirm-title') || 'Confirmar',
                        type: btn.getAttribute('data-confirm-type') || 'warning'
                    }).then(function(ok) {
                        if (ok) form.submit();
                    });
                }
            });

            document.addEventListener('submit', function(e) {
                var form = e.target;
                var confirmMsg = form.getAttribute('data-confirm');
                if (!confirmMsg) return;
                if (form._confirmed) { form._confirmed = false; return; }
                e.preventDefault();
                rpmsConfirm(confirmMsg, {
                    title: form.getAttribute('data-confirm-title') || 'Confirmar',
                    type: form.getAttribute('data-confirm-type') || 'warning'
                }).then(function(ok) {
                    if (ok) { form._confirmed = true; form.submit(); }
                });
            });
        });
    </script>
    {{-- Toast Container --}}
    <div class="toast-container" id="toastContainer"></div>

    {{-- Flash message toasts --}}
    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', function() { showToast('success', 'Sucesso', '{{ session('success') }}'); });</script>
    @endif
    @if(session('error'))
        <script>document.addEventListener('DOMContentLoaded', function() { showToast('error', 'Erro', '{{ session('error') }}'); });</script>
    @endif
    @if($errors->any())
        <script>document.addEventListener('DOMContentLoaded', function() { showToast('error', 'Erro de Validacao', '{!! implode("<br>", $errors->all()) !!}', 8000); });</script>
    @endif

    @stack('scripts')
</body>
</html>
