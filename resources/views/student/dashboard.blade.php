@extends('layouts.app')

@section('content')
@php
    $badgeClass = fn ($status) => match ($status) {
        'approved', 'verified' => 'success',
        'rejected' => 'danger',
        'pending' => 'warning text-dark',
        default => 'secondary',
    };
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="mb-1">Student Dashboard</h3>
        <p class="text-muted mb-0">Track your scholarship applications and verification status.</p>
    </div>
    <div>
        <a href="{{ route('student.profile') }}" class="btn btn-outline-primary me-2">My Profile</a>
        <a href="{{ route('student.apply') }}" class="btn btn-primary">Apply for Scholarship</a>
    </div>
</div>

@if(!$student)
    <div class="alert alert-warning">
        Please <a href="{{ route('student.profile') }}">complete your profile</a> before applying for a scholarship.
    </div>
@else
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card p-4 h-100">
                <div class="text-muted">Total Applications</div>
                <h2 class="mb-0">{{ $stats['total'] }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 h-100">
                <div class="text-muted">Pending</div>
                <h2 class="mb-0">{{ $stats['pending'] }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 h-100">
                <div class="text-muted">Approved</div>
                <h2 class="mb-0">{{ $stats['approved'] }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 h-100">
                <div class="text-muted">Rejected</div>
                <h2 class="mb-0">{{ $stats['rejected'] }}</h2>
            </div>
        </div>
    </div>

    <div id="applications" class="card p-4">
        <h5 class="mb-3">My Applications</h5>

        @if($scholarships->isEmpty())
            <p class="text-muted mb-0">No applications submitted yet.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>Scholarship</th>
                            <th>Amount</th>
                            <th>Applied Date</th>
                            <th>Institution Verification</th>
                            <th>Admin Approval</th>
                            <th>Remarks</th>
                            <th>Timeline</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($scholarships as $scholarship)
                            @php
                                $verificationStatus = $scholarship->verification?->status ?? 'pending';
                                $adminStatus = in_array($scholarship->status, ['approved', 'rejected'], true) ? $scholarship->status : 'pending';
                            @endphp
                            <tr>
                                <td>{{ $scholarship->scholarship_name }}</td>
                                <td>Rs. {{ number_format((float) $scholarship->amount, 2) }}</td>
                                <td>{{ $scholarship->created_at->format('d M Y') }}</td>
                                <td><span class="badge status-badge bg-{{ $badgeClass($verificationStatus) }}">{{ $verificationStatus }}</span></td>
                                <td><span class="badge status-badge bg-{{ $badgeClass($adminStatus) }}">{{ $adminStatus }}</span></td>
                                <td>{{ $scholarship->verification?->remarks ?? $scholarship->remarks ?? '-' }}</td>
                                <td style="min-width: 420px;">
                                    <div class="timeline-steps">
                                        <div class="timeline-step complete">
                                            <div class="fw-semibold"><i class="fa-solid fa-circle-check me-1"></i>Applied</div>
                                            <small class="text-muted">{{ $scholarship->created_at->format('d M Y') }}</small>
                                        </div>
                                        <div class="timeline-step {{ $verificationStatus === 'verified' || $scholarship->status === 'approved' ? 'complete' : '' }}">
                                            <div class="fw-semibold"><i class="fa-solid fa-building-columns me-1"></i>Institution Verified</div>
                                            <small class="text-muted">{{ ucfirst($verificationStatus) }}</small>
                                        </div>
                                        <div class="timeline-step {{ $scholarship->status === 'approved' ? 'complete' : '' }}">
                                            <div class="fw-semibold"><i class="fa-solid fa-award me-1"></i>Admin Approved</div>
                                            <small class="text-muted">{{ ucfirst($adminStatus) }}</small>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endif
@endsection
