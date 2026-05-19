@extends('layouts.app')

@section('content')
<section class="welcome-hero text-white">
    <div class="container py-5">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-8">
                <span class="badge rounded-pill bg-light text-primary mb-3 px-3 py-2">Scholarship Verification Portal</span>
                <h1 class="display-4 fw-bold mb-3">Cross-State Scholarship Verification Portal</h1>
                <p class="lead mb-4">
                    Ensuring students studying outside their home state never miss scholarship benefits.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('login') }}" class="btn btn-light btn-lg px-4">
                        <i class="fa-solid fa-right-to-bracket me-2"></i>Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="fa-solid fa-user-plus me-2"></i>Register
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">How It Works</h2>
            <p class="text-muted mb-0">A clear verification path for students, institutions, and administrators.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="feature-icon mx-auto mb-3 bg-primary-subtle text-primary">
                        <i class="fa-solid fa-file-circle-plus"></i>
                    </div>
                    <h5>Student Applies</h5>
                    <p class="text-muted mb-0">Students submit scholarship details and supporting documents through their account.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="feature-icon mx-auto mb-3 bg-info-subtle text-info">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <h5>Institution Verifies</h5>
                    <p class="text-muted mb-0">Approved institutions verify enrollment, course details, and scholarship eligibility.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="feature-icon mx-auto mb-3 bg-success-subtle text-success">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <h5>Admin Approves</h5>
                    <p class="text-muted mb-0">Administrators review verified applications and complete the final approval step.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="stat-panel">
                    <h2 class="fw-bold text-primary">10,000+</h2>
                    <p class="text-muted mb-0">Students</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-panel">
                    <h2 class="fw-bold text-primary">500+</h2>
                    <p class="text-muted mb-0">Institutions</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-panel">
                    <h2 class="fw-bold text-primary">25</h2>
                    <p class="text-muted mb-0">States</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
