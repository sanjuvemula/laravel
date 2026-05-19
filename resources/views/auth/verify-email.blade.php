@extends('layouts.app')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card p-4">
                    <h3 class="fw-bold mb-2">Verify Email</h3>
                    <p class="text-muted">Before getting started, please verify your email address using the link we sent during registration.</p>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success">A new verification link has been sent to your email address.</div>
                    @endif

                    <div class="d-flex flex-wrap gap-2">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">Resend Verification Email</button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
