@extends('layouts.app')

@section('page-title', 'Scholarships')

@section('content')
@php
    $statusClass = fn ($status) => match ($status) {
        'approved' => 'badge-approved',
        'verified' => 'badge-verified',
        'rejected' => 'badge-rejected',
        'pending' => 'badge-pending status-pending',
        default => 'badge-secondary',
    };
    $initials = function ($name) {
        return collect(explode(' ', trim($name ?: 'NA')))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('') ?: 'NA';
    };
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">Scholarships</h3>
        <p class="text-muted mb-0">Review verified applications and finalize admin decisions.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.export.csv') }}" class="btn btn-outline-primary">
            <i class="fas fa-file-csv me-2"></i>Export CSV
        </a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Dashboard
        </a>
    </div>
</div>

<div class="surface-panel mb-4">
    <form method="GET" action="{{ route('admin.scholarships') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label for="search" class="form-label">Search</label>
            <input id="search" type="text" name="search" class="form-control" value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="form-select">
                <option value="">All statuses</option>
                @foreach(['pending', 'verified', 'approved', 'rejected'] as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label for="state" class="form-label">Home State</label>
            <select id="state" name="state" class="form-select">
                <option value="">All states</option>
                @foreach($states as $state)
                    <option value="{{ $state }}" {{ request('state') === $state ? 'selected' : '' }}>{{ $state }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="scheme_id" class="form-label">Scheme</label>
            <select id="scheme_id" name="scheme_id" class="form-select">
                <option value="">All schemes</option>
                @foreach($schemes as $scheme)
                    <option value="{{ $scheme->id }}" {{ (string) request('scheme_id') === (string) $scheme->id ? 'selected' : '' }}>
                        {{ $scheme->scheme_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill">
                <i class="fas fa-filter me-1"></i>Filter
            </button>
            <a href="{{ route('admin.scholarships') }}" class="btn btn-outline-secondary" aria-label="Reset filters">
                <i class="fas fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<div class="surface-panel">
    @if($scholarships->isEmpty())
        <p class="text-muted mb-0">No scholarship applications found.</p>
    @else
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Institution</th>
                        <th>Scheme</th>
                        <th>Tier</th>
                        <th>Amount</th>
                        <th>Document</th>
                        <th>Verification</th>
                        <th>Admin</th>
                        <th style="min-width: 190px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($scholarships as $scholarship)
                        @php
                            $student = $scholarship->student;
                            $studentName = $student?->user?->name ?? 'Not available';
                            $verificationStatus = $scholarship->verification?->status ?? 'pending';
                            $canDecide = $verificationStatus === 'verified' && !in_array($scholarship->status, ['approved', 'rejected'], true);
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar-circle">{{ $initials($studentName) }}</span>
                                    <div>
                                        <div class="fw-bold">{{ $studentName }}</div>
                                        <div class="small text-muted">{{ $student?->enrollment_number ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $student?->institution?->institution_name ?? $scholarship->verification?->institution?->institution_name ?? '-' }}</td>
                            <td>{{ $scholarship->tier?->scheme?->scheme_name ?? $scholarship->scholarship_name }}</td>
                            <td>{{ $scholarship->tier?->tier_name ?? '-' }}</td>
                            <td>Rs. {{ number_format((float) $scholarship->amount, 2) }}</td>
                            <td>
                                @if($scholarship->document_path)
                                    <a href="{{ asset('storage/' . $scholarship->document_path) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                @else
                                    <span class="text-muted">Missing</span>
                                @endif
                            </td>
                            <td><span class="neo-pill {{ $statusClass($verificationStatus) }}">{{ $verificationStatus }}</span></td>
                            <td><span class="neo-pill {{ $statusClass($scholarship->status) }}">{{ $scholarship->status }}</span></td>
                            <td>
                                @if($canDecide)
                                    <div class="d-flex flex-wrap gap-2">
                                        <form method="POST" action="{{ route('admin.scholarships.approve', $scholarship->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this scholarship?')">
                                                <i class="fas fa-check me-1"></i>Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.scholarships.reject', $scholarship->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject this scholarship?')">
                                                <i class="fas fa-xmark me-1"></i>Reject
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="neo-pill badge-secondary">No action</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $scholarships->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
