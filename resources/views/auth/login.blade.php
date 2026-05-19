@extends('layouts.app')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card p-4">
                    <div class="text-center mb-4">
                        <div class="feature-icon bg-primary-subtle text-primary mb-3 mx-auto">
                            <i class="fa-solid fa-right-to-bracket"></i>
                        </div>
                        <h3 class="fw-bold mb-1">Login</h3>
                        <p class="text-muted mb-0">Access your scholarship portal workspace.</p>
                    </div>

                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus autocomplete="username">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                <label for="remember_me" class="form-check-label">Remember me</label>
                            </div>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="small">Forgot password?</a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Login</button>
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary w-100 mt-2">Create Account</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
