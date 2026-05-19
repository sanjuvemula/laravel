@extends('layouts.app')

@section('content')
@php
    $status = $institution->status ?? 'pending';
    $badgeClass = match ($status) {
        'approved' => 'success',
        'rejected' => 'danger',
        default => 'warning text-dark',
    };
@endphp

<div class="row justify-content-center">
    <div class="col-lg-9">
        @if($institution)
            <div class="alert alert-{{ str_contains($badgeClass, 'danger') ? 'danger' : (str_contains($badgeClass, 'success') ? 'success' : 'warning') }}">
                Current approval status:
                <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($status) }}</span>
                @if($status === 'pending')
                    Your profile is waiting for admin approval.
                @elseif($status === 'rejected')
                    Please update your details and submit again for review.
                @endif
            </div>
        @endif

        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Institution Profile</h4>
                    <p class="text-muted mb-0">Submit accurate institution details for admin approval.</p>
                </div>
                <a href="{{ route('institution.dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
            </div>

            <form method="POST" action="{{ route('institution.profile.save') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Institution Name</label>
                    <input type="text" name="institution_name" class="form-control @error('institution_name') is-invalid @enderror" value="{{ old('institution_name', $institution->institution_name ?? '') }}" required>
                    @error('institution_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Institution Type</label>
                        <select name="institution_type" class="form-select @error('institution_type') is-invalid @enderror" required>
                            <option value="">Select type</option>
                            @foreach(['University', 'College', 'Institute'] as $type)
                                <option value="{{ $type }}" {{ old('institution_type', $institution->institution_type ?? '') === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('institution_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">State</label>
                        <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state', $institution->state ?? '') }}" required>
                        @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $institution->city ?? '') }}" required>
                        @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Registration Number</label>
                        <input type="text" name="registration_number" class="form-control @error('registration_number') is-invalid @enderror" value="{{ old('registration_number', $institution->registration_number ?? '') }}" required>
                        @error('registration_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Affiliated University</label>
                        <input type="text" name="affiliated_university" class="form-control @error('affiliated_university') is-invalid @enderror" value="{{ old('affiliated_university', $institution->affiliated_university ?? '') }}">
                        @error('affiliated_university')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="contact_email" class="form-control @error('contact_email') is-invalid @enderror" value="{{ old('contact_email', $institution->contact_email ?? Auth::user()->email) }}" required>
                        @error('contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $institution->contact_phone ?? '') }}" required>
                        @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address', $institution->address ?? '') }}</textarea>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">Save Profile</button>
            </form>
        </div>
    </div>
</div>
@endsection
