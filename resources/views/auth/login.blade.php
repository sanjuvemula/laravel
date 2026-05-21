@extends('layouts.app')

@section('guest-fullscreen', 'true')
@section('body-class', 'auth-page')

@section('content')
<section class="auth-split">
    <div class="auth-visual">
        <div class="auth-visual-inner">
            <div class="auth-orb">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h1 class="fw-bold mb-2">ScholarPortal</h1>
            <p class="text-muted mb-4">Scholarship applications, institution verification, and admin decisions in one calm workspace.</p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <span class="neo-pill"><i class="fas fa-file-circle-check text-primary"></i>Apply</span>
                <span class="neo-pill"><i class="fas fa-building-columns text-primary"></i>Verify</span>
                <span class="neo-pill"><i class="fas fa-award text-primary"></i>Approve</span>
            </div>
        </div>
    </div>

    <div class="auth-form-side">
        <div class="auth-card">
            <div class="mb-4">
                <h2 class="fw-bold mb-1" style="font-size: 1.6rem;">Welcome Back 👋</h2>
                <p class="text-muted mb-0">Sign in to continue</p>
            </div>

            @if(session('status'))
                <div class="status-banner mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus autocomplete="username">
                    @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
                    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                        <label for="remember_me" class="form-check-label text-muted small fw-semibold">Remember me</label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small fw-semibold">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-right-to-bracket me-2"></i>Sign In
                </button>

                <div class="text-center mt-4">
                    <span class="text-muted small">Don't have an account?</span>
                    <a href="{{ route('register') }}" class="small fw-bold">Register</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
