@extends('layouts.app')

@section('page-title', 'Student Dashboard')

@section('content')
@php
    $badgeClass = fn ($status) => match ($status) {
        'approved' => 'badge-approved',
        'verified' => 'badge-verified',
        'rejected' => 'badge-rejected',
        'pending' => 'badge-pending status-pending',
        default => 'badge-secondary',
    };
    $cards = [
        ['label' => 'Applications', 'value' => $stats['total'] ?? 0, 'icon' => 'fa-folder-open', 'color' => '#4f46e5'],
        ['label' => 'In Review', 'value' => $stats['pending'] ?? 0, 'icon' => 'fa-clock', 'color' => '#f59e0b'],
        ['label' => 'Approved', 'value' => $stats['approved'] ?? 0, 'icon' => 'fa-circle-check', 'color' => '#06b6d4'],
        ['label' => 'Rejected', 'value' => $stats['rejected'] ?? 0, 'icon' => 'fa-circle-xmark', 'color' => '#ef4444'],
    ];
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">Student Dashboard</h3>
        <p class="text-muted mb-0">Track applications from submission to final approval.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('student.profile') }}" class="btn btn-outline-primary">
            <i class="fas fa-user me-2"></i>Profile
        </a>
        <a href="{{ route('student.apply') }}" class="btn btn-primary">
            <i class="fas fa-file-circle-plus me-2"></i>Apply
        </a>
    </div>
</div>

@if(!$student)
    <div class="status-banner pending">
        <div class="fw-bold mb-1">Profile incomplete</div>
        <div class="text-muted">Complete your profile before applying for scholarships.</div>
        <a href="{{ route('student.profile') }}" class="btn btn-primary mt-3">Complete Profile</a>
    </div>
@else
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

    <div id="applications" class="neo-raised p-4" style="border-radius:20px">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Applications</h5>
                <div class="text-muted small">{{ $student->institution?->institution_name ?? 'Institution not assigned' }}</div>
            </div>
            <a href="{{ route('student.apply') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-plus me-1"></i>New Application
            </a>
        </div>

        @if($scholarships->isEmpty())
            <div class="text-center py-5">
                <div class="stat-icon mx-auto mb-3" style="background:#4f46e5">
                    <i class="fas fa-file-circle-plus"></i>
                </div>
                <h5 class="fw-bold">No applications yet</h5>
                <p class="text-muted mb-3">Submitted scholarship applications will appear here.</p>
                <a href="{{ route('student.apply') }}" class="btn btn-primary">Apply Now</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Scheme</th>
                            <th>Amount</th>
                            <th>Document</th>
                            <th>Institution</th>
                            <th>Admin</th>
                            <th style="min-width: 440px;">Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($scholarships as $scholarship)
                            @php
                                $verificationStatus = $scholarship->verification?->status ?? 'pending';
                                $adminStatus = in_array($scholarship->status, ['approved', 'rejected'], true) ? $scholarship->status : 'pending';
                                $institutionStep = match ($verificationStatus) {
                                    'verified' => 'completed',
                                    'rejected' => 'rejected',
                                    default => 'active',
                                };
                                $adminStep = match ($scholarship->status) {
                                    'approved' => 'completed',
                                    'rejected' => 'rejected',
                                    default => $verificationStatus === 'verified' ? 'active' : '',
                                };
                            @endphp
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $scholarship->tier?->scheme?->scheme_name ?? $scholarship->scholarship_name }}</div>
                                    <div class="small text-muted">{{ $scholarship->tier?->tier_name ?? '-' }} | {{ $scholarship->created_at?->format('d M Y') }}</div>
                                </td>
                                <td>Rs. {{ number_format((float) $scholarship->amount, 2) }}</td>
                                <td>
                                    @if($scholarship->document_path)
                                        <a href="{{ asset('storage/' . $scholarship->document_path) }}" target="_blank" class="btn btn-outline-secondary btn-sm" aria-label="View document">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">Missing</span>
                                    @endif
                                </td>
                                <td><span class="badge {{ $badgeClass($verificationStatus) }}">{{ $verificationStatus }}</span></td>
                                <td><span class="badge {{ $badgeClass($adminStatus) }}">{{ $adminStatus }}</span></td>
                                <td>
                                    <div class="stepper">
                                        <div class="step completed">
                                            <div class="step-circle"><i class="fas fa-check"></i></div>
                                            <span>Applied</span>
                                        </div>
                                        <div class="step-line"></div>
                                        <div class="step {{ $institutionStep }}">
                                            <div class="step-circle">2</div>
                                            <span>Institution</span>
                                        </div>
                                        <div class="step-line"></div>
                                        <div class="step {{ $adminStep }}">
                                            <div class="step-circle">3</div>
                                            <span>Admin</span>
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
