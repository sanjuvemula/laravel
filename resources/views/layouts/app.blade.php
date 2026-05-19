<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Scholarship Portal') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --portal-sidebar-width: 280px;
            --portal-blue: #0d6efd;
            --portal-indigo: #4f46e5;
            --portal-ink: #1f2937;
            --portal-muted: #6b7280;
            --portal-bg: #f4f7fb;
        }

        body {
            min-height: 100vh;
            background: var(--portal-bg);
            color: var(--portal-ink);
        }

        a {
            text-decoration: none;
        }

        .card {
            border: 0;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
        }

        .portal-shell {
            min-height: 100vh;
            display: flex;
        }

        .portal-sidebar {
            width: var(--portal-sidebar-width);
            background: #10233f;
            color: #dbeafe;
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1040;
            overflow-y: auto;
            transition: transform .2s ease;
        }

        .sidebar-brand {
            color: #fff;
            font-size: 1.05rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: 1.25rem 1.25rem .75rem;
        }

        .sidebar-role {
            color: #93c5fd;
            font-size: .8rem;
            padding: 0 1.25rem 1rem;
        }

        .sidebar-link {
            color: #c7d2fe;
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .8rem 1.25rem;
            margin: .15rem .75rem;
            border-radius: 8px;
            font-weight: 500;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            color: #fff;
            background: rgba(255, 255, 255, .12);
        }

        .portal-main {
            margin-left: var(--portal-sidebar-width);
            min-height: 100vh;
            width: calc(100% - var(--portal-sidebar-width));
            display: flex;
            flex-direction: column;
        }

        .portal-topbar {
            height: 72px;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .portal-content {
            flex: 1;
            padding: 1.5rem;
        }

        .portal-footer {
            color: var(--portal-muted);
            font-size: .9rem;
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            background: #fff;
        }

        .avatar-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--portal-blue), var(--portal-indigo));
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .status-badge {
            text-transform: capitalize;
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .stat-panel {
            background: #fff;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
        }

        .welcome-hero {
            background: linear-gradient(135deg, #0d6efd 0%, #4f46e5 55%, #312e81 100%);
        }

        .min-vh-75 {
            min-height: 75vh;
        }

        .timeline-steps {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .timeline-step {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1rem;
            background: #fff;
        }

        .timeline-step.complete {
            border-color: #198754;
            background: #f0fdf4;
        }

        .guest-topbar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
        }

        @media (max-width: 991.98px) {
            .portal-sidebar {
                transform: translateX(-100%);
            }

            .portal-sidebar.show {
                transform: translateX(0);
            }

            .portal-main {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
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
    $statusClass = fn ($status) => match ($status) {
        'approved', 'verified' => 'success',
        'rejected' => 'danger',
        'pending' => 'warning text-dark',
        default => 'secondary',
    };
@endphp

@auth
    <div class="portal-shell">
        <aside id="portalSidebar" class="portal-sidebar">
            <div class="sidebar-brand">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>Scholarship Portal</span>
            </div>
            <div class="sidebar-role">{{ ucfirst($role) }} Workspace</div>

            @if($role === 'student')
                <a href="{{ route('student.dashboard') }}" class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('student.profile') }}" class="sidebar-link {{ request()->routeIs('student.profile') ? 'active' : '' }}">
                    <i class="fa-solid fa-user"></i><span>My Profile</span>
                </a>
                <a href="{{ route('student.apply') }}" class="sidebar-link {{ request()->routeIs('student.apply') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-circle-plus"></i><span>Apply for Scholarship</span>
                </a>
                <a href="{{ route('student.dashboard') }}#applications" class="sidebar-link">
                    <i class="fa-solid fa-folder-open"></i><span>My Applications</span>
                </a>
            @elseif($role === 'institution')
                <a href="{{ route('institution.dashboard') }}" class="sidebar-link {{ request()->routeIs('institution.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-table-columns"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('institution.profile') }}" class="sidebar-link {{ request()->routeIs('institution.profile') ? 'active' : '' }}">
                    <i class="fa-solid fa-building-columns"></i><span>Institution Profile</span>
                </a>
                <a href="{{ route('institution.dashboard') }}#verification-requests" class="sidebar-link">
                    <i class="fa-solid fa-user-check"></i><span>Verify Students</span>
                </a>
            @elseif($role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('admin.institutions') }}" class="sidebar-link {{ request()->routeIs('admin.institutions') ? 'active' : '' }}">
                    <i class="fa-solid fa-building"></i><span>Institutions</span>
                </a>
                <a href="{{ route('admin.scholarships') }}" class="sidebar-link {{ request()->routeIs('admin.scholarships') ? 'active' : '' }}">
                    <i class="fa-solid fa-award"></i><span>Scholarships</span>
                </a>
                <a href="{{ route('admin.students') }}" class="sidebar-link {{ request()->routeIs('admin.students') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i><span>Students</span>
                </a>
            @endif
        </aside>

        <main class="portal-main">
            <header class="portal-topbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-primary d-lg-none" type="button" data-sidebar-toggle>
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div>
                        <div class="fw-semibold">{{ config('app.name', 'Scholarship Portal') }}</div>
                        <div class="text-muted small">Cross-State Scholarship Verification</div>
                    </div>
                </div>

                <div class="dropdown">
                    <button class="btn btn-light d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="avatar-circle">{{ $initials }}</span>
                        <span class="d-none d-md-inline">{{ $currentUser->name }}</span>
                        <span class="badge bg-primary-subtle text-primary">{{ ucfirst($role) }}</span>
                        <i class="fa-solid fa-chevron-down small"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ $profileRoute }}"><i class="fa-solid fa-user me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </header>

            <section class="portal-content">
                @yield('content')
            </section>

            <footer class="portal-footer text-center">
                Cross-State Scholarship Verification Portal | Developed for INT221
            </footer>
        </main>
    </div>
@else
    <nav class="navbar navbar-expand-lg guest-topbar sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ route('welcome') }}">
                <i class="fa-solid fa-graduation-cap me-2"></i>Scholarship Portal
            </a>
            <div class="ms-auto d-flex gap-2">
                <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="portal-footer text-center">
        Cross-State Scholarship Verification Portal | Developed for INT221
    </footer>
@endauth

<div class="toast-container position-fixed top-0 end-0 p-3">
    @foreach(['success' => 'text-bg-success', 'error' => 'text-bg-danger', 'warning' => 'text-bg-warning'] as $messageType => $toastClass)
        @if(session($messageType))
            <div class="toast align-items-center {{ $toastClass }} border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4500">
                <div class="d-flex">
                    <div class="toast-body">{{ session($messageType) }}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    @endforeach
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.toast').forEach((toastElement) => {
        new bootstrap.Toast(toastElement).show();
    });

    document.querySelectorAll('[data-sidebar-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            document.getElementById('portalSidebar')?.classList.toggle('show');
        });
    });
</script>
@stack('scripts')
</body>
</html>
