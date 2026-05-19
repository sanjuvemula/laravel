@extends('layouts.app')

@section('content')
@php
    $statusClasses = [
        'pending' => 'warning text-dark',
        'verified' => 'success',
        'approved' => 'success',
        'rejected' => 'danger',
    ];
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="mb-1">Scholarships Management</h3>
        <p class="text-muted mb-0">Review institution-verified applications and manage approval decisions.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
</div>

<div class="card p-4 mb-4">
    <form method="GET" action="{{ route('admin.scholarships') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Search Student</label>
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Name or enrollment no">
        </div>
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All statuses</option>
                @foreach(['pending', 'verified', 'approved', 'rejected'] as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Home State</label>
            <select name="state" class="form-select">
                <option value="">All states</option>
                @foreach($states as $state)
                    <option value="{{ $state }}" {{ request('state') === $state ? 'selected' : '' }}>{{ $state }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Type</label>
            <select name="scholarship_type" class="form-select">
                <option value="">All types</option>
                @foreach($scholarshipTypes as $type)
                    <option value="{{ $type }}" {{ request('scholarship_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>
</div>

<div class="card p-4">
    @if($scholarships->isEmpty())
        <p class="text-muted mb-0">No scholarship applications found.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Student Name</th>
                        <th>Enrollment No</th>
                        <th>Home State</th>
                        <th>Studying State</th>
                        <th>Institution</th>
                        <th>Scholarship Type</th>
                        <th>Amount</th>
                        <th>Institution Verification</th>
                        <th style="min-width: 190px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($scholarships as $scholarship)
                        @php
                            $student = $scholarship->student;
                            $verificationStatus = $scholarship->verification?->status ?? 'pending';
                            $canDecide = $verificationStatus === 'verified' && !in_array($scholarship->status, ['approved', 'rejected'], true);
                        @endphp
                        <tr>
                            <td>{{ $student?->user?->name ?? 'Not available' }}</td>
                            <td>{{ $student?->enrollment_number ?? '-' }}</td>
                            <td>{{ $student?->home_state ?? '-' }}</td>
                            <td>{{ $student?->studying_state ?? '-' }}</td>
                            <td>{{ $student?->institution?->institution_name ?? $scholarship->verification?->institution?->institution_name ?? '-' }}</td>
                            <td>{{ $scholarship->scholarship_name }}</td>
                            <td>Rs. {{ number_format((float) $scholarship->amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $statusClasses[$verificationStatus] ?? 'secondary' }}">{{ ucfirst($verificationStatus) }}</span>
                                <div class="small text-muted mt-1">Admin: {{ ucfirst($scholarship->status) }}</div>
                            </td>
                            <td>
                                @if($canDecide)
                                    <div class="d-flex gap-2">
                                        <form method="POST" action="{{ route('admin.scholarships.approve', $scholarship->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this scholarship?')">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.scholarships.reject', $scholarship->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject this scholarship?')">Reject</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="badge bg-{{ $statusClasses[$scholarship->status] ?? 'secondary' }}">{{ ucfirst($scholarship->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $scholarships->links() }}
    @endif
</div>
@endsection
