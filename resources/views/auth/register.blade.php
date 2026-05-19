@extends('layouts.app')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                <div class="card overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-5 bg-primary text-white p-4 d-flex flex-column justify-content-between">
                            <div>
                                <div class="feature-icon bg-white text-primary mb-3">
                                    <i class="fa-solid fa-user-plus"></i>
                                </div>
                                <h3 class="fw-bold">Create your portal account</h3>
                                <p class="mb-0">Choose your role and complete the basic details needed to start verification.</p>
                            </div>
                            <div class="small opacity-75 mt-4">
                                Already registered?
                                <a href="{{ route('login') }}" class="text-white fw-semibold">Login here</a>
                            </div>
                        </div>

                        <div class="col-md-7 p-4">
                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="role" class="form-label">Register As</label>
                                    <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                                        <option value="student" {{ old('role', 'student') === 'student' ? 'selected' : '' }}>Student</option>
                                        <option value="institution" {{ old('role') === 'institution' ? 'selected' : '' }}>Institution</option>
                                    </select>
                                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus autocomplete="name">
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="username">
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
                                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                                    </div>
                                </div>

                                <div id="studentFields" class="role-panel border rounded p-3 mb-3">
                                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-user-graduate me-2 text-primary"></i>Student Details</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="enrollment_number" class="form-label">Enrollment Number</label>
                                            <input id="enrollment_number" type="text" name="enrollment_number" class="form-control @error('enrollment_number') is-invalid @enderror" value="{{ old('enrollment_number') }}">
                                            @error('enrollment_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input id="phone" type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="home_state" class="form-label">Home State</label>
                                            <input id="home_state" type="text" name="home_state" class="form-control @error('home_state') is-invalid @enderror" value="{{ old('home_state') }}">
                                            @error('home_state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="studying_state" class="form-label">Studying State</label>
                                            <input id="studying_state" type="text" name="studying_state" class="form-control @error('studying_state') is-invalid @enderror" value="{{ old('studying_state') }}">
                                            @error('studying_state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="course" class="form-label">Course</label>
                                            <input id="course" type="text" name="course" class="form-control @error('course') is-invalid @enderror" value="{{ old('course') }}">
                                            @error('course')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="year" class="form-label">Year</label>
                                            <select id="year" name="year" class="form-select @error('year') is-invalid @enderror">
                                                <option value="">Select year</option>
                                                @foreach(['1st', '2nd', '3rd', '4th'] as $year)
                                                    <option value="{{ $year }}" {{ old('year') === $year ? 'selected' : '' }}>{{ $year }} Year</option>
                                                @endforeach
                                            </select>
                                            @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                                            @error('institution_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>

                                <div id="institutionFields" class="role-panel border rounded p-3 mb-3 d-none">
                                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-building-columns me-2 text-primary"></i>Institution Details</h6>
                                    <div class="mb-3">
                                        <label for="institution_name" class="form-label">Institution Name</label>
                                        <input id="institution_name" type="text" name="institution_name" class="form-control @error('institution_name') is-invalid @enderror" value="{{ old('institution_name') }}">
                                        @error('institution_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="institution_type" class="form-label">Institution Type</label>
                                            <select id="institution_type" name="institution_type" class="form-select @error('institution_type') is-invalid @enderror">
                                                <option value="">Select type</option>
                                                @foreach(['University', 'College', 'Institute'] as $type)
                                                    <option value="{{ $type }}" {{ old('institution_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                                                @endforeach
                                            </select>
                                            @error('institution_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="registration_number" class="form-label">Registration Number</label>
                                            <input id="registration_number" type="text" name="registration_number" class="form-control @error('registration_number') is-invalid @enderror" value="{{ old('registration_number') }}">
                                            @error('registration_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="institution_state" class="form-label">State</label>
                                            <input id="institution_state" type="text" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state') }}">
                                            @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="institution_city" class="form-label">City</label>
                                            <input id="institution_city" type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}">
                                            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address') }}</textarea>
                                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="affiliated_university" class="form-label">Affiliated University</label>
                                            <input id="affiliated_university" type="text" name="affiliated_university" class="form-control @error('affiliated_university') is-invalid @enderror" value="{{ old('affiliated_university') }}">
                                            @error('affiliated_university')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="contact_phone" class="form-label">Contact Phone</label>
                                            <input id="contact_phone" type="text" name="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone') }}">
                                            @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Create Account</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    const roleSelect = document.getElementById('role');
    const studentFields = document.getElementById('studentFields');
    const institutionFields = document.getElementById('institutionFields');

    function toggleRoleFields() {
        const isStudent = roleSelect.value === 'student';
        studentFields.classList.toggle('d-none', !isStudent);
        institutionFields.classList.toggle('d-none', isStudent);
    }

    roleSelect.addEventListener('change', toggleRoleFields);
    toggleRoleFields();
</script>
@endpush
