@extends('layouts.app')

@section('content')
@php
    $cards = [
        ['label' => 'Total Students', 'value' => $totalStudents, 'icon' => 'fa-users', 'color' => 'primary'],
        ['label' => 'Total Institutions', 'value' => $totalInstitutions, 'icon' => 'fa-building-columns', 'color' => 'info'],
        ['label' => 'Total Scholarships', 'value' => $totalScholarships, 'icon' => 'fa-award', 'color' => 'secondary'],
        ['label' => 'Pending Verifications', 'value' => $pendingVerifications, 'icon' => 'fa-clock', 'color' => 'warning'],
        ['label' => 'Approved Scholarships', 'value' => $approvedScholarships, 'icon' => 'fa-circle-check', 'color' => 'success'],
        ['label' => 'Rejected Scholarships', 'value' => $rejectedScholarships, 'icon' => 'fa-circle-xmark', 'color' => 'danger'],
    ];
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="mb-1">Admin Dashboard</h3>
        <p class="text-muted mb-0">Monitor scholarship activity across students and institutions.</p>
    </div>
    <div>
        <a href="{{ route('admin.institutions') }}" class="btn btn-outline-primary me-2"><i class="fa-solid fa-building me-2"></i>Institutions</a>
        <a href="{{ route('admin.scholarships') }}" class="btn btn-primary"><i class="fa-solid fa-award me-2"></i>Scholarships</a>
    </div>
</div>

<div class="row g-4 mb-4">
    @foreach($cards as $card)
        <div class="col-md-4 col-xl-2">
            <div class="card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">{{ $card['label'] }}</span>
                    <span class="feature-icon bg-{{ $card['color'] }}-subtle text-{{ $card['color'] }}" style="width: 42px; height: 42px; font-size: 1rem;">
                        <i class="fa-solid {{ $card['icon'] }}"></i>
                    </span>
                </div>
                <h2 class="mb-0">{{ $card['value'] }}</h2>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Recent Activity</h5>
            @if($recentApplications->isEmpty())
                <p class="text-muted mb-0">No scholarship applications yet.</p>
            @else
                <div class="list-group list-group-flush">
                    @foreach($recentApplications as $application)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="fw-semibold">{{ $application->student?->user?->name ?? 'Student' }} applied for {{ $application->scholarship_name }}</div>
                                    <small class="text-muted">{{ $application->student?->institution?->institution_name ?? 'Institution not available' }}</small>
                                </div>
                                <small class="text-muted">{{ $application->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Quick Actions</h5>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.institutions', ['status' => 'pending']) }}" class="btn btn-outline-primary text-start">
                    <i class="fa-solid fa-building-circle-exclamation me-2"></i>Review Pending Institutions
                </a>
                <a href="{{ route('admin.scholarships', ['status' => 'verified']) }}" class="btn btn-outline-primary text-start">
                    <i class="fa-solid fa-file-circle-check me-2"></i>Review Verified Scholarships
                </a>
                <a href="{{ route('admin.students') }}" class="btn btn-outline-primary text-start">
                    <i class="fa-solid fa-users me-2"></i>View Registered Students
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
