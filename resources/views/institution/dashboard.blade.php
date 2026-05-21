@extends('layouts.app')

@section('page-title', 'Institution Dashboard')

@section('content')
@php
    $status = $institution->status ?? 'pending';
    $bannerClass = match ($status) {
        'approved' => '',
        'rejected' => 'rejected',
        default => 'pending',
    };
    $badgeClass = fn ($value) => match ($value) {
        'approved' => 'badge-approved',
        'verified' => 'badge-verified',
        'rejected' => 'badge-rejected',
        'pending' => 'badge-pending status-pending',
        default => 'badge-secondary',
    };
    $initials = function ($name) {
        return collect(explode(' ', trim($name ?: 'NA')))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('');
    };
    $cards = [
        ['label' => 'Total Requests', 'value' => $stats['total'] ?? 0, 'icon' => 'fa-folder-open', 'color' => '#4f46e5'],
        ['label' => 'Pending', 'value' => $stats['pending'] ?? 0, 'icon' => 'fa-clock', 'color' => '#f59e0b'],
        ['label' => 'Verified', 'value' => $stats['verified'] ?? 0, 'icon' => 'fa-circle-check', 'color' => '#06b6d4'],
        ['label' => 'Rejected', 'value' => $stats['rejected'] ?? 0, 'icon' => 'fa-circle-xmark', 'color' => '#ef4444'],
    ];
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">Institution Dashboard</h3>
        <p class="text-muted mb-0">Verify applications submitted by enrolled students.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('institution.schemes.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-layer-group me-2"></i>Schemes
        </a>
        <a href="{{ route('institution.profile') }}" class="btn btn-primary">
            <i class="fas fa-building-columns me-2"></i>Profile
        </a>
    </div>
</div>

@if(!$institution)
    <div class="status-banner pending">
        <div class="fw-bold mb-1">Institution profile incomplete</div>
        <div class="text-muted">Complete your institution profile before receiving verification requests.</div>
        <a href="{{ route('institution.profile') }}" class="btn btn-primary mt-3">Complete Profile</a>
    </div>
@else
    <div class="status-banner {{ $bannerClass }}">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <div class="fw-bold mb-1">{{ $institution->institution_name }}</div>
                <div class="text-muted">
                    @if($status === 'approved')
                        Your institution is approved and can verify scholarship applications.
                    @elseif($status === 'rejected')
                        Your institution profile needs changes before verification can continue.
                    @else
                        Your institution profile is waiting for admin approval.
                    @endif
                </div>
            </div>
            <span class="badge {{ $badgeClass($status) }}">{{ $status }}</span>
        </div>
    </div>

    <div class="row g-4 mb-4">
        @foreach($cards as $card)
            <div class="col-md-6 col-xl-3">
                <div class="neo-raised p-4 h-100" style="border-radius:20px">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <p class="stat-label">{{ $card['label'] }}</p>
                            <h2 class="stat-value">{{ $card['value'] }}</h2>
                            <small style="color:#10b981; font-weight:600"><i class="fas fa-arrow-up"></i> Current</small>
                        </div>
                        <div class="stat-icon" style="background:{{ $card['color'] }}; box-shadow: 5px 5px 12px {{ $card['color'] }}66, -3px -3px 8px #ffffff;">
                            <i class="fas {{ $card['icon'] }}"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div id="verification-requests" class="neo-raised p-4" style="border-radius:20px">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Verification Requests</h5>
                <div class="text-muted small">{{ $institution->city }}, {{ $institution->state }}</div>
            </div>
            <span class="neo-pill"><i class="fas fa-file-shield text-primary"></i>{{ $verifications->count() }} requests</span>
        </div>

        @if($verifications->isEmpty())
            <p class="text-muted mb-0">No verification requests found.</p>
        @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Enrollment</th>
                            <th>Scheme</th>
                            <th>Amount</th>
                            <th>Document</th>
                            <th>Status</th>
                            <th style="min-width: 310px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($verifications as $verification)
                            @php
                                $scholarship = $verification->scholarship;
                                $student = $scholarship?->student;
                                $studentName = $student?->user?->name ?? 'Student';
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar-circle">{{ $initials($studentName) }}</span>
                                        <div>
                                            <div class="fw-bold">{{ $studentName }}</div>
                                            <div class="small text-muted">{{ $student?->course ?? '-' }} | {{ $student?->year ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $student?->enrollment_number ?? '-' }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $scholarship?->tier?->scheme?->scheme_name ?? $scholarship?->scholarship_name ?? '-' }}</div>
                                    <div class="small text-muted">{{ $scholarship?->tier?->tier_name ?? '-' }}</div>
                                </td>
                                <td>Rs. {{ number_format((float) ($scholarship?->amount ?? 0), 2) }}</td>
                                <td>
                                    @if($scholarship?->document_path)
                                        <a href="{{ asset('storage/' . $scholarship->document_path) }}" target="_blank" class="btn btn-outline-secondary btn-sm" aria-label="View document">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">Missing</span>
                                    @endif
                                </td>
                                <td><span class="badge {{ $badgeClass($verification->status) }}">{{ $verification->status }}</span></td>
                                <td>
                                    @if($verification->status === 'pending' && $status === 'approved')
                                        <form method="POST" action="{{ route('institution.verify', $verification->id) }}">
                                            @csrf
                                            <textarea name="remarks" class="form-control form-control-sm mb-2 @error('remarks') is-invalid @enderror" rows="2" placeholder="Remarks">{{ old('remarks') }}</textarea>
                                            @error('remarks')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                            <div class="d-flex gap-2">
                                                <button type="submit" name="status" value="verified" class="btn btn-success btn-sm">
                                                    <i class="fas fa-check me-1"></i>Verify
                                                </button>
                                                <button type="submit" name="status" value="rejected" class="btn btn-danger btn-sm" onclick="return confirm('Reject this verification request?')">
                                                    <i class="fas fa-xmark me-1"></i>Reject
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <div class="text-muted small">{{ $verification->remarks ?? 'No pending action' }}</div>
                                        @if($verification->verified_at)
                                            <div class="small text-muted">{{ \Illuminate\Support\Carbon::parse($verification->verified_at)->format('d M Y') }}</div>
                                        @endif
                                    @endif
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
