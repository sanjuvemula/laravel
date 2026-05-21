@extends('layouts.app')

@section('guest-fullscreen', 'true')
@section('body-class', 'auth-page')

@section('content')
@php
    $selectedRole = old('role', 'student');
@endphp

<section class="auth-split">
    <div class="auth-visual">
        <div class="auth-visual-inner">
            <div class="auth-orb">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1 class="fw-bold mb-2">ScholarPortal</h1>
            <p class="text-muted mb-4">Create a verified workspace for applications, institution reviews, and scholarship decisions.</p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <span class="neo-pill"><i class="fas fa-user-graduate text-primary"></i>Students</span>
                <span class="neo-pill"><i class="fas fa-building-columns text-primary"></i>Institutions</span>
                <span class="neo-pill"><i class="fas fa-shield-halved text-primary"></i>Secure</span>
            </div>
        </div>
    </div>

    <div class="auth-form-side">
        <div class="auth-card" style="width: min(100%, 640px);">
            <div class="mb-4">
                <h2 class="fw-bold mb-1" style="font-size: 1.6rem;">Create Account</h2>
                <p class="text-muted mb-0">Choose your role and complete your details</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <input id="role" type="hidden" name="role" value="{{ $selectedRole }}">
                @error('role')<div class="text-danger small fw-semibold mb-3">{{ $message }}</div>@enderror

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="role-card neo-raised p-3 text-center {{ $selectedRole === 'student' ? 'selected' : '' }}" data-role="student" tabindex="0">
                            <div class="fs-3 mb-2"><i class="fas fa-user-graduate"></i></div>
                            <div class="fw-semibold">Student</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="role-card neo-raised p-3 text-center {{ $selectedRole === 'institution' ? 'selected' : '' }}" data-role="institution" tabindex="0">
                            <div class="fs-3 mb-2"><i class="fas fa-building-columns"></i></div>
                            <div class="fw-semibold">Institution</div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name</label>
                        <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus autocomplete="name">
                        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="username">
                        @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
                        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                    </div>
                </div>

                <div id="studentFields" class="mt-4 {{ $selectedRole === 'student' ? '' : 'd-none' }}">
                    <div class="neo-inset p-3 mb-3">
                        <div class="fw-bold mb-3"><i class="fas fa-user-graduate me-2 text-primary"></i>Student Details</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="enrollment_number" class="form-label">Enrollment</label>
                                <input id="enrollment_number" type="text" name="enrollment_number" class="form-control @error('enrollment_number') is-invalid @enderror" value="{{ old('enrollment_number') }}">
                                @error('enrollment_number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input id="phone" type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                                @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="home_state" class="form-label">Home State</label>
                                <input id="home_state" type="text" name="home_state" class="form-control @error('home_state') is-invalid @enderror" value="{{ old('home_state') }}">
                                @error('home_state')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="studying_state" class="form-label">Studying State</label>
                                <input id="studying_state" type="text" name="studying_state" class="form-control @error('studying_state') is-invalid @enderror" value="{{ old('studying_state') }}">
                                @error('studying_state')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="course" class="form-label">Course</label>
                                <input id="course" type="text" name="course" class="form-control @error('course') is-invalid @enderror" value="{{ old('course') }}">
                                @error('course')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="year" class="form-label">Year</label>
                                <select id="year" name="year" class="form-select @error('year') is-invalid @enderror">
                                    <option value="">Select year</option>
                                    @foreach(['1st', '2nd', '3rd', '4th'] as $year)
                                        <option value="{{ $year }}" {{ old('year') === $year ? 'selected' : '' }}>{{ $year }} Year</option>
                                    @endforeach
                                </select>
                                @error('year')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="institution_id" class="form-label">Institution</label>
                                <select id="institution_id" name="institution_id" class="form-select @error('institution_id') is-invalid @enderror">
                                    <option value="">Select approved institution</option>
                                    @foreach($institutions as $institution)
                                        <option value="{{ $institution->id }}" {{ old('institution_id') == $institution->id ? 'selected' : '' }}>
                                            {{ $institution->institution_name }} - {{ $institution->city }}, {{ $institution->state }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('institution_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div id="institutionFields" class="mt-4 {{ $selectedRole === 'institution' ? '' : 'd-none' }}">
                    <div class="neo-inset p-3 mb-3">
                        <div class="fw-bold mb-3"><i class="fas fa-building-columns me-2 text-primary"></i>Institution Details</div>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="institution_name" class="form-label">Institution Name</label>
                                <input id="institution_name" type="text" name="institution_name" class="form-control @error('institution_name') is-invalid @enderror" value="{{ old('institution_name') }}">
                                @error('institution_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="institution_type" class="form-label">Type</label>
                                <select id="institution_type" name="institution_type" class="form-select @error('institution_type') is-invalid @enderror">
                                    <option value="">Select type</option>
                                    @foreach(['University', 'College', 'Institute'] as $type)
                                        <option value="{{ $type }}" {{ old('institution_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('institution_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="registration_number" class="form-label">Registration</label>
                                <input id="registration_number" type="text" name="registration_number" class="form-control @error('registration_number') is-invalid @enderror" value="{{ old('registration_number') }}">
                                @error('registration_number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label for="institution_state" class="form-label">State</label>
                                <input id="institution_state" type="text" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state') }}">
                                @error('state')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label for="city" class="form-label">City</label>
                                <input id="city" type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}">
                                @error('city')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="affiliated_university" class="form-label">Affiliated University</label>
                                <input id="affiliated_university" type="text" name="affiliated_university" class="form-control @error('affiliated_university') is-invalid @enderror" value="{{ old('affiliated_university') }}">
                                @error('affiliated_university')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="contact_phone" class="form-label">Contact Phone</label>
                                <input id="contact_phone" type="text" name="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone') }}">
                                @error('contact_phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="address" class="form-label">Address</label>
                                <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address') }}</textarea>
                                @error('address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-2">
                    <i class="fas fa-circle-check me-2"></i>Create Account
                </button>

                <div class="text-center mt-4">
                    <span class="text-muted small">Already registered?</span>
                    <a href="{{ route('login') }}" class="small fw-bold">Sign in</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    const roleInput = document.getElementById('role');
    const roleCards = document.querySelectorAll('.role-card');
    const studentFields = document.getElementById('studentFields');
    const institutionFields = document.getElementById('institutionFields');

    function setRole(role) {
        roleInput.value = role;
        roleCards.forEach((card) => {
            card.classList.toggle('selected', card.dataset.role === role);
        });
        studentFields.classList.toggle('d-none', role !== 'student');
        institutionFields.classList.toggle('d-none', role !== 'institution');
    }

    roleCards.forEach((card) => {
        card.addEventListener('click', () => setRole(card.dataset.role));
        card.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                setRole(card.dataset.role);
            }
        });
    });

    setRole(roleInput.value || 'student');
</script>
@endpush
