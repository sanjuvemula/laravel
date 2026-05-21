@extends('layouts.app')

@section('page-title', 'Institution Profile')

@section('content')
@php
    $status = $institution->status ?? 'pending';
    $statusBadgeClass = match ($status) {
        'approved' => 'badge-approved',
        'rejected' => 'badge-rejected',
        default => 'badge-pending status-pending',
    };
    $bannerClass = match ($status) {
        'rejected' => 'rejected',
        'approved' => '',
        default => 'pending',
    };
@endphp

<div class="form-shell">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Institution Profile</h3>
            <p class="text-muted mb-0">Profile details are reviewed before verification is enabled.</p>
        </div>
        <div class="d-flex gap-2">
            @if($institution?->status === 'approved')
                <a href="{{ route('institution.schemes.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-layer-group me-2"></i>Schemes
                </a>
            @endif
            <a href="{{ route('institution.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Dashboard
            </a>
        </div>
    </div>

    @if($institution)
        <div class="status-banner {{ $bannerClass }}">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <div class="fw-bold mb-1">Current review status</div>
                    <div class="text-muted">Admin approval controls scheme publishing and student verification.</div>
                </div>
                <span class="neo-pill {{ $statusBadgeClass }}">{{ ucfirst($status) }}</span>
            </div>
        </div>
    @endif

    <div class="neo-form-card">
        <div class="neo-form-header">
            <i class="fas fa-building-columns"></i>
            <div>
                <div class="fw-bold">Institution Profile</div>
                <div class="small opacity-75">Registration and contact information</div>
            </div>
        </div>

        <div class="neo-form-body">
            <form method="POST" action="{{ route('institution.profile.save') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="institution_name" class="form-label">Institution Name</label>
                        <input id="institution_name" type="text" name="institution_name" class="form-control @error('institution_name') is-invalid @enderror" value="{{ old('institution_name', $institution->institution_name ?? '') }}" required>
                        @error('institution_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="institution_type" class="form-label">Institution Type</label>
                        <select id="institution_type" name="institution_type" class="form-select @error('institution_type') is-invalid @enderror" required>
                            <option value="">Select type</option>
                            @foreach(['University', 'College', 'Institute'] as $type)
                                <option value="{{ $type }}" {{ old('institution_type', $institution->institution_type ?? '') === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('institution_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="registration_number" class="form-label">Registration Number</label>
                        <input id="registration_number" type="text" name="registration_number" class="form-control @error('registration_number') is-invalid @enderror" value="{{ old('registration_number', $institution->registration_number ?? '') }}" required>
                        @error('registration_number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="state" class="form-label">State</label>
                        <input id="state" type="text" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state', $institution->state ?? '') }}" required>
                        @error('state')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="city" class="form-label">City</label>
                        <input id="city" type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $institution->city ?? '') }}" required>
                        @error('city')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="affiliated_university" class="form-label">Affiliated University</label>
                        <input id="affiliated_university" type="text" name="affiliated_university" class="form-control @error('affiliated_university') is-invalid @enderror" value="{{ old('affiliated_university', $institution->affiliated_university ?? '') }}">
                        @error('affiliated_university')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="contact_email" class="form-label">Contact Email</label>
                        <input id="contact_email" type="email" name="contact_email" class="form-control @error('contact_email') is-invalid @enderror" value="{{ old('contact_email', $institution->contact_email ?? Auth::user()->email) }}" required>
                        @error('contact_email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="contact_phone" class="form-label">Contact Phone</label>
                        <input id="contact_phone" type="text" name="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $institution->contact_phone ?? '') }}" required>
                        @error('contact_phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Login Email</label>
                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address', $institution->address ?? '') }}</textarea>
                        @error('address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
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
