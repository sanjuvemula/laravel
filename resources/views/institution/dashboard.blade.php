@extends('layouts.app')

@section('content')
@php
    $status = $institution->status ?? 'pending';
    $badgeClass = match ($status) {
        'approved' => 'success',
        'rejected' => 'danger',
        default => 'warning text-dark',
    };
    $verificationBadge = fn ($value) => match ($value) {
        'verified' => 'success',
        'rejected' => 'danger',
        default => 'warning text-dark',
    };
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="mb-1">Institution Dashboard</h3>
        <p class="text-muted mb-0">Verify scholarship requests for students enrolled at your institution.</p>
    </div>
    <a href="{{ route('institution.profile') }}" class="btn btn-outline-primary">Institution Profile</a>
</div>

@if(!$institution)
    <div class="alert alert-warning">
        Please <a href="{{ route('institution.profile') }}">complete your institution profile</a> before receiving verification requests.
    </div>
@else
    <div class="alert alert-{{ str_contains($badgeClass, 'danger') ? 'danger' : (str_contains($badgeClass, 'success') ? 'success' : 'warning') }}">
        Approval status:
        <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($status) }}</span>
        @if($status === 'pending')
            Your institution is waiting for admin approval.
        @elseif($status === 'rejected')
            Your institution profile was rejected. Update and resubmit your profile for review.
        @else
            You can verify student scholarship requests.
        @endif
    </div>

    @if($status === 'approved')
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card p-4 h-100">
                    <div class="text-muted">Total Requests</div>
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
                    <div class="text-muted">Verified</div>
                    <h2 class="mb-0">{{ $stats['verified'] }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4 h-100">
                    <div class="text-muted">Rejected</div>
                    <h2 class="mb-0">{{ $stats['rejected'] }}</h2>
                </div>
            </div>
        </div>

        <div id="verification-requests" class="card p-4">
            <h5 class="mb-3">Verification Requests</h5>

            @if($verifications->isEmpty())
                <p class="text-muted mb-0">No verification requests found.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th>Student</th>
                                <th>Enrollment No</th>
                                <th>Home State</th>
                                <th>Course</th>
                                <th>Year</th>
                                <th>Scholarship</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th style="min-width: 320px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($verifications as $verification)
                                @php
                                    $student = $verification->scholarship?->student;
                                    $collapseId = 'studentDetails' . $verification->id;
                                @endphp
                                <tr>
                                    <td>
                                        <button class="btn btn-link p-0 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}">
                                            {{ $student?->user?->name ?? 'Not available' }}
                                        </button>
                                    </td>
                                    <td>{{ $student?->enrollment_number ?? 'Not available' }}</td>
                                    <td>{{ $student?->home_state ?? 'Not available' }}</td>
                                    <td>{{ $student?->course ?? 'Not available' }}</td>
                                    <td>{{ $student?->year ?? 'Not available' }}</td>
                                    <td>{{ $verification->scholarship?->scholarship_name ?? 'Not available' }}</td>
                                    <td>Rs. {{ number_format((float) ($verification->scholarship?->amount ?? 0), 2) }}</td>
                                    <td><span class="badge bg-{{ $verificationBadge($verification->status) }}">{{ ucfirst($verification->status) }}</span></td>
                                    <td>
                                        @if($verification->status === 'pending')
                                            <form method="POST" action="{{ route('institution.verify', $verification->id) }}">
                                                @csrf
                                                <textarea name="remarks" class="form-control form-control-sm mb-2 @error('remarks') is-invalid @enderror" rows="2" placeholder="Remarks">{{ old('remarks') }}</textarea>
                                                @error('remarks')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                <div class="d-flex gap-2">
                                                    <button type="submit" name="status" value="verified" class="btn btn-success btn-sm">Verify</button>
                                                    <button type="submit" name="status" value="rejected" class="btn btn-danger btn-sm" onclick="return confirm('Reject this verification request?')">Reject</button>
                                                </div>
                                            </form>
                                        @else
                                            <span class="text-muted">{{ $verification->remarks ?? 'No remarks' }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="collapse" id="{{ $collapseId }}">
                                    <td colspan="9" class="bg-light">
                                        <div class="row g-3">
                                            <div class="col-md-3"><strong>Email:</strong> {{ $student?->user?->email ?? '-' }}</div>
                                            <div class="col-md-3"><strong>Phone:</strong> {{ $student?->phone ?? '-' }}</div>
                                            <div class="col-md-3"><strong>Studying State:</strong> {{ $student?->studying_state ?? '-' }}</div>
                                            <div class="col-md-3"><strong>Applied:</strong> {{ $verification->scholarship?->created_at?->format('d M Y') ?? '-' }}</div>
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
@endif
@endsection
