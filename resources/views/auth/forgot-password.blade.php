@extends('layouts.app')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card p-4">
                    <h3 class="fw-bold mb-2">Forgot Password</h3>
                    <p class="text-muted">Enter your email address and we will send you a password reset link.</p>

                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Email Password Reset Link</button>
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100 mt-2">Back to Login</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
