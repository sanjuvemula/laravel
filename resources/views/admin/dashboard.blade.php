@extends('layouts.app')

@section('page-title', 'Admin Dashboard')

@section('content')
@php
    $cards = [
        ['label' => 'Total Students', 'value' => $totalStudents, 'icon' => 'fa-user-graduate', 'color' => '#4f46e5', 'note' => 'Active'],
        ['label' => 'Institutions', 'value' => $totalInstitutions, 'icon' => 'fa-building-columns', 'color' => '#7c3aed', 'note' => 'Registered'],
        ['label' => 'Scholarships', 'value' => $totalScholarships, 'icon' => 'fa-award', 'color' => '#10b981', 'note' => 'Applications'],
        ['label' => 'Verified by Institutions', 'value' => $verifiedVerifications, 'icon' => 'fa-circle-check', 'color' => '#06b6d4', 'note' => 'Verified'],
        ['label' => 'Awaiting Verification', 'value' => $awaitingVerifications, 'icon' => 'fa-clock', 'color' => '#f59e0b', 'note' => 'Pending'],
    ];
    $maxStateTotal = max(1, (int) $stateWiseBreakdown->max('total'));
    $badgeClass = fn ($status) => match ($status) {
        'approved' => 'badge-approved',
        'verified' => 'badge-verified',
        'rejected' => 'badge-rejected',
        'pending' => 'badge-pending status-pending',
        default => 'badge-secondary',
    };
    $initials = function ($name) {
        return collect(explode(' ', trim($name ?: 'NA')))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('');
    };
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">Admin Dashboard</h3>
        <p class="text-muted mb-0">Scholarship activity across students and institutions.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.export.csv') }}" class="btn btn-outline-primary">
            <i class="fas fa-file-csv me-2"></i>Export CSV
        </a>
        <a href="{{ route('admin.scholarships', ['status' => 'verified']) }}" class="btn btn-primary">
            <i class="fas fa-award me-2"></i>Review
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    @foreach($cards as $card)
        <div class="col-md-6 col-xl-4">
            <div class="neo-raised p-4 h-100" style="border-radius:20px">
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <div>
                        <p class="stat-label">{{ $card['label'] }}</p>
                        <h2 class="stat-value">{{ $card['value'] }}</h2>
                        <small style="color:#10b981; font-weight:600">
                            <i class="fas fa-arrow-up"></i> {{ $card['note'] }}
                        </small>
                    </div>
                    <div class="stat-icon" style="background:{{ $card['color'] }}; box-shadow: 5px 5px 12px {{ $card['color'] }}66, -3px -3px 8px #ffffff;">
                        <i class="fas {{ $card['icon'] }}"></i>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-xl-7">
        <div class="neo-raised p-4 h-100" style="border-radius:20px">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Recent Applications</h5>
                <a href="{{ route('admin.scholarships') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-right me-1"></i>View All
                </a>
            </div>

            @if($recentApplications->isEmpty())
                <p class="text-muted mb-0">No scholarship applications yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Institution</th>
                                <th>Scheme</th>
                                <th>Status</th>
                                <th>Applied</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentApplications as $application)
                                @php
                                    $studentName = $application->student?->user?->name ?? 'Student';
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="avatar-circle">{{ $initials($studentName) }}</span>
                                            <div>
                                                <div class="fw-bold">{{ $studentName }}</div>
                                                <div class="small text-muted">{{ $application->student?->enrollment_number ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $application->student?->institution?->institution_name ?? '-' }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $application->tier?->scheme?->scheme_name ?? $application->scholarship_name }}</div>
                                        <div class="small text-muted">{{ $application->tier?->tier_name ?? '-' }}</div>
                                    </td>
                                    <td><span class="badge {{ $badgeClass($application->status) }}">{{ $application->status }}</span></td>
                                    <td>{{ $application->created_at?->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="col-xl-5">
        <div class="neo-raised p-4 h-100" style="border-radius:20px">
            <h5 class="fw-bold mb-3">Applications by State</h5>

            @if($stateWiseBreakdown->isEmpty())
                <p class="text-muted mb-0">No state data available.</p>
            @else
                @foreach($stateWiseBreakdown as $state)
                    @php
                        $percentage = round(($state->total / $maxStateTotal) * 100);
                    @endphp
                    <div class="d-flex align-items-center mb-3">
                        <span style="width:110px; font-size:0.82rem; color:#64748b">{{ $state->home_state }}</span>
                        <div class="flex-grow-1 neo-inset" style="height:10px; border-radius:50px">
                            <div style="width:{{ $percentage }}%; height:10px; background:#4f46e5; border-radius:50px"></div>
                        </div>
                        <span class="ms-3 fw-bold small">{{ $percentage }}%</span>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
