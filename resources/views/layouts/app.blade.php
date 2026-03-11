<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MOJO - @yield('title', 'Safaris & Tours')</title>
    <link rel="icon" href="{{ asset('images/mojo.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" crossorigin="anonymous">
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #3b82f6;
            --sidebar-border: #1e293b;
            --header-height: 60px;
            --page-bg: #f8fafc;
            --card-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.06);
            --card-shadow-hover: 0 10px 15px -3px rgba(0,0,0,0.08), 0 4px 6px -2px rgba(0,0,0,0.04);
            --radius: 0.625rem;
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--page-bg);
            min-height: 100vh;
            font-size: 0.875rem;
            color: #1e293b;
            line-height: 1.6;
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
            color: #94a3b8;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1050;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-right: 1px solid rgba(255,255,255,0.04);
            display: flex;
            flex-direction: column;
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }

        .sidebar-brand {
            padding: 1.25rem 1.25rem;
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
            padding: 0.75rem 0;
            flex: 1;
        }

        .sidebar-nav .nav-label {
            padding: 1rem 1.25rem 0.4rem;
            font-size: 0.625rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #475569;
            font-weight: 700;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 1.25rem;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 450;
            transition: var(--transition);
            border-left: 3px solid transparent;
            margin: 1px 0;
            position: relative;
        }

        .sidebar-nav .nav-link:hover {
            background: var(--sidebar-hover);
            color: #e2e8f0;
        }

        .sidebar-nav .nav-link.active {
            background: rgba(59, 130, 246, 0.08);
            color: #60a5fa;
            border-left-color: var(--sidebar-active);
            font-weight: 550;
        }

        .sidebar-nav .nav-link.active i {
            color: #60a5fa;
        }

        .sidebar-nav .nav-link i {
            font-size: 1.05rem;
            width: 1.25rem;
            text-align: center;
            flex-shrink: 0;
            opacity: 0.75;
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
        }

        .top-header {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 1.75rem;
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            background: rgba(255,255,255,0.95);
        }

        .top-header h6 {
            font-size: 0.95rem;
            font-weight: 650;
            letter-spacing: -0.01em;
            color: #0f172a;
        }

        .header-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            transition: var(--transition);
        }

        .page-content {
            padding: 1.5rem 1.75rem 2.5rem;
        }

        /* ===== BREADCRUMBS ===== */
        .rpms-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.78rem;
            color: #94a3b8;
            margin-bottom: 1.25rem;
        }
        .rpms-breadcrumb a {
            color: #64748b;
            text-decoration: none;
            transition: var(--transition);
        }
        .rpms-breadcrumb a:hover { color: var(--sidebar-active); }
        .rpms-breadcrumb .separator { color: #cbd5e1; }
        .rpms-breadcrumb .current { color: #334155; font-weight: 600; }

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
            color: #0f172a;
            margin: 0;
        }

        .page-header-subtitle {
            font-size: 0.82rem;
            color: #64748b;
            margin-top: 0.15rem;
        }

        /* ===== CARDS ===== */
        .card {
            border: 1px solid #e2e8f0;
            box-shadow: var(--card-shadow);
            border-radius: var(--radius);
            background: #fff;
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.875rem 1.25rem;
            color: #334155;
        }

        .card-body { padding: 1.25rem; }

        /* Stat cards */
        .stat-card {
            transition: var(--transition);
            cursor: pointer;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            position: relative;
        }
        .stat-card:hover {
            transform: translateY(-3px);
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
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .stat-card-value {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.2;
        }

        .stat-card-label {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        /* ===== STATUS BADGES ===== */
        .status-badge {
            font-size: 0.7rem;
            padding: 0.22rem 0.6rem;
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
            padding: 0.15rem 0.5rem;
            border-radius: 5px;
            font-weight: 700;
            letter-spacing: 0.03em;
        }
        .currency-BRL { background: #dcfce7; color: #166534; }
        .currency-USD { background: #dbeafe; color: #1e40af; }
        .currency-EUR { background: #fef3c7; color: #92400e; }

        /* ===== TABLES ===== */
        .table { --bs-table-hover-bg: #f8fafc; }

        .table th {
            font-size: 0.72rem;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 700;
            letter-spacing: 0.05em;
            white-space: nowrap;
            border-bottom: 2px solid #e2e8f0 !important;
            padding: 0.75rem 1rem;
            background: #fafbfc;
        }

        .table td {
            vertical-align: middle;
            font-size: 0.84rem;
            padding: 0.7rem 1rem;
            border-bottom-color: #f1f5f9;
            color: #334155;
        }

        .table-hover tbody tr { transition: var(--transition); }

        .table-hover tbody tr:hover {
            background-color: #f8fafc !important;
        }

        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
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
            color: #94a3b8;
        }
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
            opacity: 0.35;
            color: #cbd5e1;
        }
        .empty-state p {
            margin: 0;
            font-size: 0.88rem;
            font-weight: 500;
        }

        /* ===== FORMS ===== */
        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.35rem;
        }

        .form-text {
            font-size: 0.74rem;
            color: #9ca3af;
        }

        .form-control, .form-select {
            border-radius: 0.5rem;
            border-color: #d1d5db;
            font-size: 0.85rem;
            padding: 0.5rem 0.75rem;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--sidebar-active);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
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
            border-radius: 0.5rem;
            transition: var(--transition);
            letter-spacing: 0.01em;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-color: #2563eb;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-color: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .btn-outline-primary:hover { transform: translateY(-1px); }
        .btn-outline-secondary:hover { transform: translateY(-1px); }
        .btn-outline-danger:hover { transform: translateY(-1px); }
        .btn-outline-success:hover { transform: translateY(-1px); }

        /* ===== SECTION HEADING ===== */
        .section-heading {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
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
            background: #e2e8f0;
        }

        /* ===== FILTER BAR ===== */
        .filter-bar {
            background: #fafbfc;
            border: 1px solid #e2e8f0;
            border-radius: var(--radius);
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
        }

        .filter-bar .form-control,
        .filter-bar .form-select {
            font-size: 0.82rem;
            background: #fff;
        }

        .filter-bar .form-label {
            font-size: 0.72rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 700;
        }

        /* ===== TIMESTAMPS ===== */
        .timestamp-muted {
            font-size: 0.72rem;
            color: #94a3b8;
            font-weight: 500;
        }

        /* ===== PROGRESS BAR (capacity) ===== */
        .capacity-bar {
            height: 6px;
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
            color: #64748b;
        }
        .rpms-pagination-info strong {
            color: #334155;
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
            min-width: 36px;
            height: 36px;
            padding: 0 0.5rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
        }
        .rpms-page-item .rpms-page-link:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #1e293b;
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
            border-color: #f1f5f9;
            cursor: not-allowed;
            pointer-events: none;
        }
        .rpms-page-dots {
            border: none !important;
            background: transparent !important;
            color: #94a3b8 !important;
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

        /* ===== MODAL ===== */
        .modal-content {
            border-radius: 0.75rem;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        }
        .modal-header {
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
        }
        .modal-footer {
            border-top: 1px solid #f1f5f9;
            padding: 1rem 1.5rem;
        }

        /* ===== ACCORDION ===== */
        .accordion-item {
            border: 1px solid #e2e8f0;
            border-radius: var(--radius) !important;
            margin-bottom: 0.5rem;
            overflow: hidden;
        }
        .accordion-button {
            font-weight: 600;
            font-size: 0.88rem;
            color: #334155;
        }
        .accordion-button:not(.collapsed) {
            background: #f8fafc;
            color: #1e40af;
        }
        .accordion-button:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        }

        /* ===== NAV TABS (LOGS) ===== */
        .nav-tabs {
            border-bottom: 2px solid #e2e8f0;
        }
        .nav-tabs .nav-link {
            font-weight: 600;
            font-size: 0.84rem;
            color: #64748b;
            border: none;
            padding: 0.65rem 1.25rem;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: var(--transition);
        }
        .nav-tabs .nav-link:hover {
            color: #334155;
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
            padding: 0.6rem 0;
            border-bottom: 1px solid #f8fafc;
            align-items: center;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label {
            width: 140px;
            font-size: 0.78rem;
            color: #6b7280;
            font-weight: 600;
            flex-shrink: 0;
        }
        .detail-value {
            font-size: 0.85rem;
            color: #1e293b;
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
            color: #475569;
        }

        /* ===== FADE-IN ANIMATION ===== */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .page-content { animation: fadeIn 0.3s ease-out; }

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

        /* ===== PRINT ===== */
        @media print {
            .sidebar, .sidebar-overlay, .top-header, .btn, .filter-bar, .rpms-pagination { display: none !important; }
            .main-content { margin-left: 0 !important; }
            .page-content { padding: 0 !important; animation: none !important; }
            .card { box-shadow: none !important; border: 1px solid #ddd !important; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <a href="#main-content" class="skip-link visually-hidden-focusable">Pular para o conteudo</a>

    {{-- Mobile overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- Sidebar --}}
    <nav class="sidebar" id="sidebar" aria-label="Navegacao principal">
        <div class="sidebar-brand" style="justify-content: center; padding: 1.25rem;">
            <img src="{{ asset('images/mojo.png') }}" alt="Mojo Logo" style="width: 100%; max-width: 180px; object-fit: contain;">
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
                <i class="bi bi-credit-card-2-front-fill"></i> Cockpit de Pagamentos
            </a>

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

            @if(auth()->user()?->isAdmin())
                <div class="nav-label">Administracao</div>
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-lock-fill"></i> Usuarios
                </a>
            @endif
        </div>

        {{-- User info & logout --}}
        <div style="border-top: 1px solid var(--sidebar-border); padding: 1rem 1.25rem; margin-top: auto;">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #3b82f6, #6366f1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.75rem; font-weight: 700; flex-shrink: 0;">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div style="overflow: hidden;">
                    <div style="color: #e2e8f0; font-size: 0.8rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ auth()->user()->name ?? 'Usuario' }}</div>
                    <div style="font-size: 0.65rem; color: #64748b;">
                        <span class="count-badge" style="font-size: 0.6rem; padding: 1px 5px; background: rgba(59,130,246,0.15); color: #60a5fa;">{{ auth()->user()->roleName() ?? 'Viewer' }}</span>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm w-100" style="background: rgba(255,255,255,0.05); color: #94a3b8; border: 1px solid rgba(255,255,255,0.08); font-size: 0.78rem; font-weight: 500;">
                    <i class="bi bi-box-arrow-left me-1"></i> Sair
                </button>
            </form>
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
                    <h6 class="mb-0">@yield('page-title', 'Dashboard')</h6>
                    @hasSection('page-subtitle')
                        <div style="font-size: 0.72rem; color: #64748b; margin-top: 0.1rem;">@yield('page-subtitle')</div>
                    @endif
                </div>
            </div>
            <div class="dropdown">
                <button class="btn p-0 d-flex align-items-center gap-2 border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div style="text-align: right;">
                        <div style="font-size: 0.82rem; font-weight: 600; color: #0f172a; line-height: 1.2;">{{ auth()->user()->name ?? 'Usuario' }}</div>
                        <div style="font-size: 0.68rem; color: #64748b;">{{ auth()->user()->roleName() ?? 'Viewer' }}</div>
                    </div>
                    <div class="header-avatar" style="width: 36px; height: 36px; background: linear-gradient(135deg, #3b82f6, #6366f1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.85rem; font-weight: 700; flex-shrink: 0;">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="min-width: 200px; border: 1px solid #e2e8f0; border-radius: 0.625rem; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); padding: 0.5rem;">
                    <li class="px-3 py-2" style="border-bottom: 1px solid #f1f5f9;">
                        <div style="font-size: 0.82rem; font-weight: 600; color: #0f172a;">{{ auth()->user()->name ?? 'Usuario' }}</div>
                        <div style="font-size: 0.72rem; color: #64748b;">{{ auth()->user()->email ?? '' }}</div>
                    </li>
                    <li><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('profile.edit') }}" style="font-size: 0.84rem; border-radius: 0.375rem;"><i class="bi bi-person text-muted"></i> Meu Perfil</a></li>
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
        </header>

        <div class="page-content">
            @hasSection('breadcrumb')
                <div class="rpms-breadcrumb">
                    <a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill"></i></a>
                    <span class="separator">/</span>
                    @yield('breadcrumb')
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show py-2 px-3" role="alert">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show py-2 px-3" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show py-2 px-3" role="alert">
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                </div>
            @endif

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

        // Auto-dismiss alerts after 5 seconds
        document.querySelectorAll('.alert-dismissible').forEach(function(alert) {
            setTimeout(function() {
                var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                if (bsAlert) bsAlert.close();
            }, 5000);
        });

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

        // Shared delete modal
        function confirmDelete(url, message) {
            document.getElementById('deleteModalForm').action = url;
            if (message) document.getElementById('deleteModalMessage').textContent = message;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
    @stack('scripts')
</body>
</html>
