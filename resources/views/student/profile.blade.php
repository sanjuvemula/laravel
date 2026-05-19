@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Student Profile</h4>
                    <p class="text-muted mb-0">Keep your enrollment and institution details up to date.</p>
                </div>
                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
            </div>

            <form method="POST" action="{{ route('student.profile.save') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Enrollment Number</label>
                        <input type="text" name="enrollment_number" class="form-control @error('enrollment_number') is-invalid @enderror" value="{{ old('enrollment_number', $student->enrollment_number ?? '') }}" required>
                        @error('enrollment_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $student->phone ?? '') }}" required>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Home State</label>
                        <input type="text" name="home_state" class="form-control @error('home_state') is-invalid @enderror" value="{{ old('home_state', $student->home_state ?? '') }}" required>
                        @error('home_state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Studying State</label>
                        <input type="text" name="studying_state" class="form-control @error('studying_state') is-invalid @enderror" value="{{ old('studying_state', $student->studying_state ?? '') }}" required>
                        @error('studying_state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Institution</label>
                    <select name="institution_id" class="form-select @error('institution_id') is-invalid @enderror" required>
                        <option value="">Select approved institution</option>
                        @foreach($institutions as $institution)
                            <option value="{{ $institution->id }}" {{ old('institution_id', $student->institution_id ?? '') == $institution->id ? 'selected' : '' }}>
                                {{ $institution->institution_name }} - {{ $institution->city }}, {{ $institution->state }}
                            </option>
                        @endforeach
                    </select>
                    @error('institution_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Course</label>
                        <input type="text" name="course" class="form-control @error('course') is-invalid @enderror" value="{{ old('course', $student->course ?? '') }}" required>
                        @error('course')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label">Year</label>
                        <select name="year" class="form-select @error('year') is-invalid @enderror" required>
                            <option value="">Select year</option>
                            @foreach(['1st', '2nd', '3rd', '4th'] as $year)
                                <option value="{{ $year }}" {{ old('year', $student->year ?? '') === $year ? 'selected' : '' }}>{{ $year }} Year</option>
                            @endforeach
                        </select>
                        @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Save Profile</button>
            </form>
        </div>
    </div>
</div>
@endsection
