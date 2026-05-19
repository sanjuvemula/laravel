@extends('layouts.app')

@section('content')
@php
    $statusClasses = [
        'pending' => 'warning text-dark',
        'approved' => 'success',
        'rejected' => 'danger',
    ];
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="mb-1">Institutions Management</h3>
        <p class="text-muted mb-0">Approve, reject, search, and filter registered institutions.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
</div>

<div class="card p-4 mb-4">
    <form method="GET" action="{{ route('admin.institutions') }}" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Search by Name</label>
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Institution name">
        </div>
        <div class="col-md-3">
            <label class="form-label">State</label>
            <select name="state" class="form-select">
                <option value="">All states</option>
                @foreach($states as $state)
                    <option value="{{ $state }}" {{ request('state') === $state ? 'selected' : '' }}>{{ $state }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All statuses</option>
                @foreach(['pending', 'approved', 'rejected'] as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>
</div>

<div class="card p-4">
    @if($institutions->isEmpty())
        <p class="text-muted mb-0">No institutions found.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Institution Name</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Registration No</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Registered Date</th>
                        <th style="min-width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($institutions as $institution)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $institution->institution_name }}</div>
                                <small class="text-muted">{{ $institution->contact_email ?? $institution->user?->email }}</small>
                            </td>
                            <td>{{ $institution->state ?: '-' }}</td>
                            <td>{{ $institution->city ?: '-' }}</td>
                            <td>{{ $institution->registration_number }}</td>
                            <td>{{ $institution->institution_type ?? '-' }}</td>
                            <td><span class="badge bg-{{ $statusClasses[$institution->status] ?? 'secondary' }}">{{ ucfirst($institution->status) }}</span></td>
                            <td>{{ $institution->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <form method="POST" action="{{ route('admin.institutions.approve', $institution->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this institution?')" {{ $institution->status === 'approved' ? 'disabled' : '' }}>Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.institutions.reject', $institution->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject this institution?')" {{ $institution->status === 'rejected' ? 'disabled' : '' }}>Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $institutions->links() }}
    @endif
</div>
@endsection
