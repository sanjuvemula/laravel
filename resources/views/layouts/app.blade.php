<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Scholarship Portal'))</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        :root {
            --bg: #e8ecf0;
            --bg-card: #e8ecf0;
            --shadow-light: #ffffff;
            --shadow-dark: #c5ccd6;
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --primary-light: #ede9fe;
            --secondary: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --purple: #7c3aed;
            --text: #2d3748;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            --radius: 16px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            letter-spacing: 0;
        }

        a {
            color: var(--primary);
            text-decoration: none;
        }

        a:hover {
            color: var(--primary-dark);
        }

        .neo-raised,
        .card,
        .surface-panel,
        .metric-card,
        .info-card {
            background: var(--bg-card);
            border-radius: var(--radius);
            box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light);
            border: none;
            animation: fadeInUp 0.3s ease forwards;
        }

        .neo-inset {
            background: var(--bg-card);
            border-radius: var(--radius);
            box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
            border: none;
        }

        .neo-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 50px;
            box-shadow: 3px 3px 6px var(--shadow-dark), -3px -3px 6px var(--shadow-light);
            background: var(--bg-card);
            padding: 6px 12px;
            font-size: .75rem;
            font-weight: 700;
            color: var(--text-muted);
        }

        .surface-panel,
        .metric-card,
        .info-card {
            padding: 1.5rem;
        }

        .btn {
            border-radius: 12px;
            font-weight: 600;
            padding: 10px 22px;
            border: none;
            box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light);
            transform: scale(0.98);
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            box-shadow: 4px 4px 10px rgba(79, 70, 229, 0.4), -2px -2px 6px var(--shadow-light);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: var(--primary-dark);
            color: #fff;
            box-shadow: 6px 6px 14px rgba(79, 70, 229, 0.5), -2px -2px 6px var(--shadow-light);
        }

        .btn-outline-primary,
        .btn-outline-secondary,
        .btn-outline-danger,
        .btn-light {
            background: var(--bg-card);
            border: none;
            box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
        }

        .btn-outline-primary {
            color: var(--primary);
        }

        .btn-outline-secondary,
        .btn-light {
            color: var(--text-muted);
        }

        .btn-outline-danger {
            color: var(--danger);
        }

        .btn-success {
            background: var(--success);
            color: #fff;
            box-shadow: 4px 4px 10px rgba(16, 185, 129, .35), -2px -2px 6px var(--shadow-light);
        }

        .btn-danger {
            background: var(--danger);
            color: #fff;
            box-shadow: 4px 4px 10px rgba(239, 68, 68, .35), -2px -2px 6px var(--shadow-light);
        }

        .form-control,
        .form-select {
            background: var(--bg-card);
            border: none;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.9rem;
            box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
            color: var(--text);
            transition: box-shadow 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            background: var(--bg-card);
            color: var(--text);
            outline: none;
            box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light), 0 0 0 3px rgba(79, 70, 229, 0.2);
        }

        .form-control[readonly] {
            background: var(--bg-card);
            color: var(--text-muted);
        }

        .input-group-text {
            background: var(--bg-card);
            border: none;
            border-radius: 12px;
            box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
            color: var(--text-muted);
            font-weight: 700;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.82rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
        }

        textarea.form-control {
            resize: vertical;
        }

        .form-check-input {
            background-color: var(--bg-card);
            border: none;
            box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light);
        }

        .form-check-input:checked {
            background-color: var(--primary);
            box-shadow: 3px 3px 8px rgba(79, 70, 229, .35), -2px -2px 6px var(--shadow-light);
        }

        .table {
            border-collapse: separate;
            border-spacing: 0 10px;
            background: transparent;
            margin-bottom: 0;
        }

        .table thead th {
            background: transparent;
            color: var(--text-light);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            border: none;
            padding: 10px 16px;
        }

        .table tbody tr {
            box-shadow: 4px 4px 10px var(--shadow-dark), -4px -4px 10px var(--shadow-light);
            border-radius: 14px;
        }

        .table tbody td {
            background: var(--bg-card);
            padding: 14px 16px;
            border: none;
            vertical-align: middle;
        }

        .table tbody td:first-child {
            border-radius: 14px 0 0 14px;
        }

        .table tbody td:last-child {
            border-radius: 0 14px 14px 0;
        }

        .table-bordered > :not(caption) > * {
            border-width: 0;
        }

        .badge {
            border-radius: 8px;
            padding: 5px 12px;
            font-size: 0.72rem;
            font-weight: 700;
            box-shadow: 2px 2px 5px var(--shadow-dark), -2px -2px 5px var(--shadow-light);
            background: var(--bg-card);
            text-transform: capitalize;
        }

        .badge-pending,
        .bg-warning,
        .text-bg-warning {
            background: var(--bg-card) !important;
            color: var(--warning) !important;
        }

        .badge-approved,
        .badge-success,
        .bg-success,
        .text-bg-success {
            background: var(--bg-card) !important;
            color: var(--success) !important;
        }

        .badge-rejected,
        .badge-danger,
        .bg-danger,
        .text-bg-danger {
            background: var(--bg-card) !important;
            color: var(--danger) !important;
        }

        .badge-verified,
        .badge-primary,
        .bg-primary,
        .text-bg-primary {
            background: var(--bg-card) !important;
            color: var(--primary) !important;
        }

        .badge-secondary,
        .bg-secondary {
            background: var(--bg-card) !important;
            color: var(--text-muted) !important;
        }

        .status-pending::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--warning);
            margin-right: 6px;
            animation: pulse-dot 1.5s infinite;
        }

        .sidebar {
            width: 72px;
            min-height: 100vh;
            background: var(--bg);
            box-shadow: 6px 0 20px var(--shadow-dark), -2px 0 6px var(--shadow-light);
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            padding: 24px 0;
            transition: width 0.3s ease;
            overflow: hidden;
            z-index: 200;
        }

        .sidebar:hover {
            width: 240px;
        }

        .sidebar .logo-area {
            padding: 0 16px 32px;
            display: flex;
            align-items: center;
            gap: 12px;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar .logo-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            flex-shrink: 0;
            background: var(--primary);
            box-shadow: 4px 4px 8px rgba(79, 70, 229, 0.4), -2px -2px 6px var(--shadow-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.1rem;
        }

        .sidebar .logo-text {
            font-weight: 700;
            font-size: 1rem;
            color: var(--text);
            opacity: 0;
            transition: opacity 0.2s 0.1s;
        }

        .sidebar:hover .logo-text {
            opacity: 1;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            margin: 3px 10px;
            border-radius: 14px;
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.88rem;
            white-space: nowrap;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .nav-link .nav-icon {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            border-radius: 12px;
            background: var(--bg-card);
            box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .nav-link:hover .nav-icon {
            box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light);
            color: var(--primary);
        }

        .nav-link.active {
            color: var(--primary);
            font-weight: 600;
        }

        .nav-link.active .nav-icon {
            background: var(--primary);
            color: #fff;
            box-shadow: 4px 4px 10px rgba(79, 70, 229, 0.4), -2px -2px 6px var(--shadow-light);
        }

        .nav-label {
            opacity: 0;
            transition: opacity 0.15s ease;
        }

        .sidebar:hover .nav-label {
            opacity: 1;
        }

        .sidebar-user {
            margin: auto 10px 16px;
            padding: 12px;
            border-radius: 16px;
            background: var(--bg-card);
            box-shadow: 4px 4px 10px var(--shadow-dark), -4px -4px 10px var(--shadow-light);
            display: flex;
            align-items: center;
            gap: 10px;
            overflow: hidden;
        }

        .avatar-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            flex-shrink: 0;
            background: var(--primary);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            box-shadow: 3px 3px 6px rgba(79, 70, 229, 0.35);
        }

        .sidebar-user-info {
            min-width: 0;
            opacity: 0;
            transition: opacity 0.15s ease;
            white-space: nowrap;
        }

        .sidebar:hover .sidebar-user-info {
            opacity: 1;
        }

        .logout-mini {
            width: 34px;
            height: 34px;
            border: none;
            border-radius: 10px;
            background: var(--bg-card);
            color: var(--danger);
            box-shadow: 3px 3px 6px var(--shadow-dark), -3px -3px 6px var(--shadow-light);
        }

        .main-content {
            margin-left: 72px;
            transition: margin-left 0.3s ease;
            padding: 32px;
            min-height: 100vh;
        }

        .sidebar:hover ~ .main-content {
            margin-left: 240px;
        }

        .topbar {
            background: var(--bg);
            border-radius: 20px;
            box-shadow: 6px 6px 14px var(--shadow-dark), -6px -6px 14px var(--shadow-light);
            padding: 0 28px;
            min-height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
            position: sticky;
            top: 16px;
            z-index: 100;
        }

        .topbar-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text);
        }

        .topbar-subtitle {
            font-size: .78rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        .notif-btn {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--bg-card);
            box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
        }

        .notif-btn:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light);
        }

        .stat-label {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--text-light);
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text);
            margin: 0;
            line-height: 1;
        }

        .stat-icon {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 5px 5px 12px rgba(79, 70, 229, 0.28), -3px -3px 8px var(--shadow-light);
            font-size: 1.3rem;
        }

        .status-banner {
            border-radius: 18px;
            padding: 18px 24px;
            box-shadow: 6px 6px 14px var(--shadow-dark), -6px -6px 14px var(--shadow-light);
            border-left: 5px solid var(--success);
            margin-bottom: 28px;
            background: var(--bg-card);
        }

        .status-banner.pending {
            border-left-color: var(--warning);
        }

        .status-banner.rejected {
            border-left-color: var(--danger);
        }

        .stepper {
            display: flex;
            align-items: center;
            min-width: 420px;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--bg-card);
            box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--text-light);
        }

        .step.completed .step-circle {
            background: var(--success);
            color: #fff;
            box-shadow: 4px 4px 10px rgba(16, 185, 129, 0.4), -2px -2px 6px var(--shadow-light);
        }

        .step.active .step-circle {
            background: var(--primary);
            color: #fff;
            box-shadow: 4px 4px 10px rgba(79, 70, 229, 0.4), -2px -2px 6px var(--shadow-light);
        }

        .step.rejected .step-circle {
            background: var(--danger);
            color: #fff;
            box-shadow: 4px 4px 10px rgba(239, 68, 68, 0.4), -2px -2px 6px var(--shadow-light);
        }

        .step span {
            font-size: 0.75rem;
            color: var(--text-light);
            font-weight: 600;
        }

        .step.active span {
            color: var(--primary);
        }

        .step.completed span {
            color: var(--success);
        }

        .step.rejected span {
            color: var(--danger);
        }

        .step-line {
            flex: 1;
            height: 3px;
            background: var(--bg-card);
            box-shadow: inset 1px 1px 3px var(--shadow-dark), inset -1px -1px 3px var(--shadow-light);
            border-radius: 4px;
            margin: 0 10px 24px;
        }

        .form-shell {
            max-width: 680px;
            margin: 0 auto;
        }

        .neo-form-card {
            overflow: hidden;
            border-radius: 20px;
            background: var(--bg-card);
            box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light);
            border: none;
        }

        .neo-form-header {
            background: var(--primary);
            color: #fff;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .neo-form-body {
            padding: 28px;
        }

        .auth-split {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            background: var(--bg);
        }

        .auth-visual {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px;
        }

        .auth-visual-inner {
            max-width: 460px;
            text-align: center;
        }

        .auth-orb {
            width: 170px;
            height: 170px;
            border-radius: 50%;
            margin: 0 auto 28px;
            background: var(--bg-card);
            box-shadow: 12px 12px 24px var(--shadow-dark), -12px -12px 24px var(--shadow-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 4rem;
        }

        .auth-card {
            width: min(100%, 420px);
            padding: 40px;
            border-radius: 22px;
            background: var(--bg-card);
            box-shadow: 8px 8px 18px var(--shadow-dark), -8px -8px 18px var(--shadow-light);
        }

        .auth-form-side {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .role-card {
            cursor: pointer;
            border-radius: 16px;
            transition: all .2s ease;
        }

        .role-card.selected {
            box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
            color: var(--primary);
            font-weight: 700;
        }

        .scheme-card {
            overflow: hidden;
            border-radius: 20px;
        }

        .scheme-strip,
        .top-strip {
            height: 8px;
            background: var(--primary);
            border-radius: 20px 20px 0 0;
        }

        .tier-card {
            border-left: 3px solid var(--primary);
            border-radius: 18px;
            background: var(--bg-card);
            box-shadow: 5px 5px 12px var(--shadow-dark), -5px -5px 12px var(--shadow-light);
            padding: 18px;
        }

        .add-tier-card {
            width: 100%;
            border: none;
            border-radius: 18px;
            background: var(--bg-card);
            box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
            color: var(--primary);
            padding: 22px;
            font-weight: 700;
        }

        .toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            display: grid;
            gap: 12px;
        }

        .toast-neo {
            background: var(--bg-card);
            border-radius: 14px;
            box-shadow: 6px 6px 16px var(--shadow-dark), -6px -6px 16px var(--shadow-light);
            padding: 14px 20px;
            border-left: 4px solid var(--success);
            min-width: 280px;
            animation: slideInRight 0.3s ease, fadeOut 0.5s ease 3.5s forwards;
            color: var(--text);
            font-weight: 600;
        }

        .toast-neo.error {
            border-left-color: var(--danger);
        }

        .toast-neo.warning {
            border-left-color: var(--warning);
        }

        .toast-neo.status {
            border-left-color: var(--primary);
        }

        .pagination {
            gap: 8px;
        }

        .page-link {
            background: var(--bg-card);
            color: var(--primary);
            border: none;
            border-radius: 10px;
            box-shadow: 3px 3px 6px var(--shadow-dark), -3px -3px 6px var(--shadow-light);
        }

        .page-item.active .page-link {
            background: var(--primary);
            color: #fff;
        }

        .page-item.disabled .page-link {
            background: var(--bg-card);
            color: var(--text-light);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse-dot {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.35;
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(40px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateX(20px);
            }
        }

        @media (max-width: 991.98px) {
            .sidebar {
                width: 72px;
                transform: translateX(-100%);
                transition: transform .2s ease, width .3s ease;
            }

            .sidebar.show,
            .sidebar:hover {
                width: 240px;
                transform: translateX(0);
            }

            .sidebar.show .logo-text,
            .sidebar.show .nav-label,
            .sidebar.show .sidebar-user-info {
                opacity: 1;
            }

            .main-content,
            .sidebar:hover ~ .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .topbar {
                top: 10px;
                margin-bottom: 24px;
                padding: 0 18px;
            }

            .auth-split {
                grid-template-columns: 1fr;
            }

            .auth-visual {
                display: none;
            }
        }

        @media (max-width: 575.98px) {
            .main-content {
                padding: 14px;
            }

            .topbar {
                border-radius: 16px;
            }

            .auth-card {
                padding: 26px;
            }

            .stepper {
                min-width: 360px;
            }
        }
    </style>
</head>
<body class="@yield('body-class')">
@php
    $currentUser = Auth::user();
    $role = $currentUser?->role;
    $initials = $currentUser
        ? collect(explode(' ', trim($currentUser->name)))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('')
        : '';
    $profileRoute = match ($role) {
        'student' => route('student.profile'),
        'institution' => route('institution.profile'),
        'admin' => route('admin.dashboard'),
        default => route('login'),
    };
@endphp

@auth
    <aside id="portalSidebar" class="sidebar">
        <a href="{{ route('dashboard') }}" class="logo-area">
            <div class="logo-icon"><i class="fas fa-graduation-cap"></i></div>
            <div class="logo-text">ScholarPortal</div>
        </a>

        <nav class="sidebar-nav">
            @if($role === 'student')
                <a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-chart-line"></i></span>
                    <span class="nav-label">Dashboard</span>
                </a>
                <a href="{{ route('student.profile') }}" class="nav-link {{ request()->routeIs('student.profile') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-user"></i></span>
                    <span class="nav-label">Profile</span>
                </a>
                <a href="{{ route('student.apply') }}" class="nav-link {{ request()->routeIs('student.apply') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-file-circle-plus"></i></span>
                    <span class="nav-label">Apply</span>
                </a>
            @elseif($role === 'institution')
                <a href="{{ route('institution.dashboard') }}" class="nav-link {{ request()->routeIs('institution.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-table-columns"></i></span>
                    <span class="nav-label">Dashboard</span>
                </a>
                <a href="{{ route('institution.profile') }}" class="nav-link {{ request()->routeIs('institution.profile') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-building-columns"></i></span>
                    <span class="nav-label">Profile</span>
                </a>
                <a href="{{ route('institution.schemes.index') }}" class="nav-link {{ request()->routeIs('institution.schemes.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-layer-group"></i></span>
                    <span class="nav-label">Schemes</span>
                </a>
                <a href="{{ route('institution.dashboard') }}#verification-requests" class="nav-link">
                    <span class="nav-icon"><i class="fas fa-user-check"></i></span>
                    <span class="nav-label">Verifications</span>
                </a>
            @elseif($role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-chart-pie"></i></span>
                    <span class="nav-label">Dashboard</span>
                </a>
                <a href="{{ route('admin.institutions') }}" class="nav-link {{ request()->routeIs('admin.institutions') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-building"></i></span>
                    <span class="nav-label">Institutions</span>
                </a>
                <a href="{{ route('admin.scholarships') }}" class="nav-link {{ request()->routeIs('admin.scholarships') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-award"></i></span>
                    <span class="nav-label">Scholarships</span>
                </a>
                <a href="{{ route('admin.students') }}" class="nav-link {{ request()->routeIs('admin.students') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-users"></i></span>
                    <span class="nav-label">Students</span>
                </a>
                <a href="{{ route('admin.export.csv') }}" class="nav-link">
                    <span class="nav-icon"><i class="fas fa-file-csv"></i></span>
                    <span class="nav-label">Export CSV</span>
                </a>
            @endif
        </nav>

        <div class="sidebar-user">
            <span class="avatar-circle">{{ $initials }}</span>
            <div class="sidebar-user-info">
                <div class="fw-bold small text-truncate" style="max-width: 125px;">{{ $currentUser->name }}</div>
                <div class="text-muted small text-capitalize">{{ $role }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="sidebar-user-info ms-auto">
                @csrf
                <button type="submit" class="logout-mini" aria-label="Logout">
                    <i class="fas fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="notif-btn d-lg-none" type="button" data-sidebar-toggle aria-label="Open navigation">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <div class="topbar-title">@yield('page-title', config('app.name', 'Scholarship Portal'))</div>
                    <div class="topbar-subtitle">Cross-State Scholarship Verification</div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ $profileRoute }}" class="notif-btn" aria-label="Profile">
                    <i class="fas fa-user"></i>
                </a>
                <button class="notif-btn" type="button" aria-label="Notifications">
                    <i class="fas fa-bell"></i>
                </button>
            </div>
        </header>

        @yield('content')
    </main>
@else
    @hasSection('guest-fullscreen')
        @yield('content')
    @else
        <nav class="topbar mx-3 mt-3">
            <a class="d-flex align-items-center gap-2 fw-bold" href="{{ route('welcome') }}">
                <span class="logo-icon"><i class="fas fa-graduation-cap"></i></span>
                <span>ScholarPortal</span>
            </a>
            <div class="d-flex gap-2">
                <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
            </div>
        </nav>
        <main class="px-3 pb-4">
            @yield('content')
        </main>
    @endif
@endauth

<div class="toast-container">
    @foreach(['success' => '', 'error' => 'error', 'warning' => 'warning', 'status' => 'status'] as $messageType => $toastClass)
        @if(session($messageType))
            <div class="toast-neo {{ $toastClass }}">
                {{ session($messageType) }}
            </div>
        @endif
    @endforeach
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('[data-sidebar-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            document.getElementById('portalSidebar')?.classList.toggle('show');
        });
    });

    window.setTimeout(() => {
        document.querySelectorAll('.toast-neo').forEach((toast) => toast.remove());
    }, 4300);
</script>
@stack('scripts')
</body>
</html>
