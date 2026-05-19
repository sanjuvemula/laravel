@extends('layouts.app')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card p-4">
                    <h3 class="fw-bold mb-2">Confirm Password</h3>
                    <p class="text-muted">Please confirm your password before continuing.</p>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
