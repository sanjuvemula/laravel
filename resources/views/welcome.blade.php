@extends('layouts.app')

@section('content')
<style>
    .welcome-page {
        background: #e8ecf0;
        color: #2d3748;
    }

    .welcome-hero {
        min-height: 100vh;
        background: #e8ecf0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 72px 16px;
        text-align: center;
    }

    .welcome-hero-inner {
        width: min(100%, 720px);
        margin: 0 auto;
    }

    .welcome-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #e8ecf0;
        color: #4f46e5;
        box-shadow: inset 3px 3px 6px #c5ccd6, inset -3px -3px 6px #ffffff;
        border-radius: 50px;
        padding: 6px 18px;
        font-size: 0.8rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .welcome-title {
        font-size: clamp(2.2rem, 5vw, 3.2rem);
        font-weight: 800;
        color: #2d3748;
        line-height: 1.08;
        margin: 24px 0 18px;
        letter-spacing: 0;
    }

    .welcome-subtitle {
        max-width: 520px;
        margin: 0 auto;
        color: #64748b;
        font-size: 1.05rem;
        line-height: 1.7;
    }

    .welcome-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 16px;
        margin-top: 32px;
    }

    .welcome-btn {
        min-width: 160px;
        border-radius: 12px;
        border: none;
        padding: 13px 24px;
        font-weight: 800;
        background: #e8ecf0;
        color: #4f46e5;
        box-shadow: 6px 6px 12px #c5ccd6, -6px -6px 12px #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .welcome-btn:hover {
        color: #3730a3;
        transform: translateY(-1px);
    }

    .welcome-btn-primary {
        background: #4f46e5;
        color: #ffffff;
        box-shadow: 4px 4px 10px rgba(79, 70, 229, 0.4), -2px -2px 6px #ffffff;
    }

    .welcome-btn-primary:hover {
        background: #3730a3;
        color: #ffffff;
    }

    .feature-pills {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 12px;
        margin-top: 28px;
    }

    .feature-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #e8ecf0;
        border-radius: 50px;
        border: none;
        padding: 10px 16px;
        color: #64748b;
        font-size: 0.86rem;
        font-weight: 700;
        box-shadow: 6px 6px 12px #c5ccd6, -6px -6px 12px #ffffff;
    }

    .feature-pill i {
        color: #10b981;
    }

    .stats-band {
        background: #dde1e7;
        padding: 72px 0;
    }

    .welcome-section {
        background: #e8ecf0;
        padding: 80px 0;
    }

    .welcome-section-title {
        color: #2d3748;
        font-weight: 800;
        margin-bottom: 12px;
    }

    .welcome-section-subtitle {
        color: #64748b;
        max-width: 560px;
        margin: 0 auto 36px;
    }

    .stat-card,
    .step-card {
        background: #e8ecf0;
        border: none;
        border-radius: 16px;
        box-shadow: 6px 6px 12px #c5ccd6, -6px -6px 12px #ffffff;
        padding: 28px 22px;
        height: 100%;
    }

    .stats-band .stat-card {
        background: #dde1e7;
    }

    .stat-number {
        color: #4f46e5;
        font-size: 2.35rem;
        line-height: 1;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .stat-label {
        color: #64748b;
        font-weight: 700;
    }

    .step-number {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #4f46e5;
        color: #ffffff;
        box-shadow: 4px 4px 10px rgba(79, 70, 229, 0.4), -2px -2px 6px #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        margin-bottom: 20px;
    }

    .step-card h3 {
        color: #2d3748;
        font-size: 1.08rem;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .step-card p {
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 0;
    }

    @media (max-width: 575.98px) {
        .welcome-hero {
            padding: 52px 12px;
        }

        .welcome-actions {
            flex-direction: column;
        }

        .welcome-btn {
            width: 100%;
        }

        .feature-pill {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="welcome-page">
    <section class="welcome-hero">
        <div class="welcome-hero-inner">
            <span class="welcome-eyebrow">
                <i class="fas fa-shield-halved"></i>
                Scholarship Verification Portal
            </span>

            <h1 class="welcome-title">Cross-State Scholarship Verification Portal</h1>

            <p class="welcome-subtitle">
                A clear workspace for students, institutions, and administrators to move scholarship applications through document-backed verification.
            </p>

            <div class="welcome-actions">
                <a href="{{ route('register') }}" class="welcome-btn">
                    <i class="fas fa-graduation-cap"></i>
                    Register
                </a>
                <a href="{{ route('login') }}" class="welcome-btn welcome-btn-primary">
                    <i class="fas fa-arrow-right"></i>
                    Login
                </a>
            </div>

            <div class="feature-pills">
                <span class="feature-pill">
                    <i class="fas fa-check"></i>
                    Cross-state verification
                </span>
                <span class="feature-pill">
                    <i class="fas fa-check"></i>
                    Real-time status tracking
                </span>
                <span class="feature-pill">
                    <i class="fas fa-check"></i>
                    Institution-managed schemes
                </span>
            </div>
        </div>
    </section>

    <section class="stats-band">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Students</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Institutions</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-number">3</div>
                        <div class="stat-label">States Covered</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="welcome-section">
        <div class="container">
            <div class="text-center">
                <h2 class="welcome-section-title">How It Works</h2>
                <p class="welcome-section-subtitle">Each application follows a simple path from student profile to institution verification and final admin approval.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h3>Register &amp; Complete Profile</h3>
                        <p>Students create an account, select their institution, and submit the academic details needed for review.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h3>Institution Verifies Documents</h3>
                        <p>Institutions check uploaded documents, scheme eligibility, enrollment details, and tier requirements.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h3>Admin Approves &amp; Notifies</h3>
                        <p>Administrators finalize verified applications and keep scholarship records ready for reporting.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
