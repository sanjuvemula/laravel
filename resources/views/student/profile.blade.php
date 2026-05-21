@extends('layouts.app')

@section('page-title', 'Student Profile')

@section('content')
<div class="form-shell">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">My Profile</h3>
            <p class="text-muted mb-0">Keep enrollment and institution details current for verification.</p>
        </div>
        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Dashboard
        </a>
    </div>

    <div class="neo-form-card">
        <div class="neo-form-header">
            <i class="fas fa-user"></i>
            <div>
                <div class="fw-bold">My Profile</div>
                <div class="small opacity-75">Student identity and academic details</div>
            </div>
        </div>

        <div class="neo-form-body">
            <form method="POST" action="{{ route('student.profile.save') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="enrollment_number" class="form-label">Enrollment Number</label>
                        <input id="enrollment_number" type="text" name="enrollment_number" class="form-control @error('enrollment_number') is-invalid @enderror" value="{{ old('enrollment_number', $student->enrollment_number ?? '') }}" required>
                        @error('enrollment_number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input id="phone" type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $student->phone ?? '') }}" required>
                        @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="home_state" class="form-label">Home State</label>
                        <input id="home_state" type="text" name="home_state" class="form-control @error('home_state') is-invalid @enderror" value="{{ old('home_state', $student->home_state ?? '') }}" required>
                        @error('home_state')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="studying_state" class="form-label">Studying State</label>
                        <input id="studying_state" type="text" name="studying_state" class="form-control @error('studying_state') is-invalid @enderror" value="{{ old('studying_state', $student->studying_state ?? '') }}" required>
                        @error('studying_state')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label for="institution_id" class="form-label">Institution</label>
                        <select id="institution_id" name="institution_id" class="form-select @error('institution_id') is-invalid @enderror" required>
                            <option value="">Select approved institution</option>
                            @foreach($institutions as $institution)
                                <option value="{{ $institution->id }}" {{ old('institution_id', $student->institution_id ?? '') == $institution->id ? 'selected' : '' }}>
                                    {{ $institution->institution_name }} - {{ $institution->city }}, {{ $institution->state }}
                                </option>
                            @endforeach
                        </select>
                        @error('institution_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="course" class="form-label">Course</label>
                        <input id="course" type="text" name="course" class="form-control @error('course') is-invalid @enderror" value="{{ old('course', $student->course ?? '') }}" required>
                        @error('course')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="year" class="form-label">Year</label>
                        <select id="year" name="year" class="form-select @error('year') is-invalid @enderror" required>
                            <option value="">Select year</option>
                            @foreach(['1st', '2nd', '3rd', '4th'] as $year)
                                <option value="{{ $year }}" {{ old('year', $student->year ?? '') === $year ? 'selected' : '' }}>{{ $year }} Year</option>
                            @endforeach
                        </select>
                        @error('year')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-4">
                    <i class="fas fa-floppy-disk me-2"></i>Save Profile
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
