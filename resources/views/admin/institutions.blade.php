@extends('layouts.app')

@section('page-title', 'Institutions')

@section('content')
@php
    $statusClass = fn ($status) => match ($status) {
        'approved' => 'badge-approved',
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
        <h3 class="fw-bold mb-1">Institutions</h3>
        <p class="text-muted mb-0">Search, filter, approve, and reject registered institutions.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Dashboard
    </a>
</div>

<div class="surface-panel mb-4">
    <form method="GET" action="{{ route('admin.institutions') }}" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label for="search" class="form-label">Search</label>
            <input id="search" type="text" name="search" class="form-control" value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <label for="state" class="form-label">State</label>
            <select id="state" name="state" class="form-select">
                <option value="">All states</option>
                @foreach($states as $state)
                    <option value="{{ $state }}" {{ request('state') === $state ? 'selected' : '' }}>{{ $state }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="form-select">
                <option value="">All statuses</option>
                @foreach(['pending', 'approved', 'rejected'] as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill">
                <i class="fas fa-filter me-1"></i>Filter
            </button>
            <a href="{{ route('admin.institutions') }}" class="btn btn-outline-secondary" aria-label="Reset filters">
                <i class="fas fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<div class="surface-panel">
    @if($institutions->isEmpty())
        <p class="text-muted mb-0">No institutions found.</p>
    @else
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Institution</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Registration</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th style="min-width: 190px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($institutions as $institution)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar-circle">{{ $initials($institution->institution_name) }}</span>
                                    <div>
                                        <div class="fw-bold">{{ $institution->institution_name }}</div>
                                        <div class="small text-muted">{{ $institution->contact_email ?? $institution->user?->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $institution->state ?: '-' }}</td>
                            <td>{{ $institution->city ?: '-' }}</td>
                            <td>{{ $institution->registration_number }}</td>
                            <td>{{ $institution->institution_type ?? '-' }}</td>
                            <td><span class="neo-pill {{ $statusClass($institution->status) }}">{{ $institution->status }}</span></td>
                            <td>{{ $institution->created_at?->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <form method="POST" action="{{ route('admin.institutions.approve', $institution->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this institution?')" {{ $institution->status === 'approved' ? 'disabled' : '' }}>
                                            <i class="fas fa-check me-1"></i>Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.institutions.reject', $institution->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject this institution?')" {{ $institution->status === 'rejected' ? 'disabled' : '' }}>
                                            <i class="fas fa-xmark me-1"></i>Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $institutions->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
