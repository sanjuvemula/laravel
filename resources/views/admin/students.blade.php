@extends('layouts.app')

@section('page-title', 'Students')

@section('content')
@php
    $initials = function ($name) {
        return collect(explode(' ', trim($name ?: 'NA')))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('') ?: 'NA';
    };
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">Students</h3>
        <p class="text-muted mb-0">Browse registered students by enrollment, state, and institution.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Dashboard
    </a>
</div>

<div class="surface-panel mb-4">
    <form method="GET" action="{{ route('admin.students') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label for="search" class="form-label">Search</label>
            <input id="search" type="text" name="search" class="form-control" value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <label for="institution_id" class="form-label">Institution</label>
            <select id="institution_id" name="institution_id" class="form-select">
                <option value="">All institutions</option>
                @foreach($institutions as $institution)
                    <option value="{{ $institution->id }}" {{ (string) request('institution_id') === (string) $institution->id ? 'selected' : '' }}>
                        {{ $institution->institution_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="state" class="form-label">Home State</label>
            <select id="state" name="state" class="form-select">
                <option value="">All states</option>
                @foreach($states as $state)
                    <option value="{{ $state }}" {{ request('state') === $state ? 'selected' : '' }}>{{ $state }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill">
                <i class="fas fa-filter me-1"></i>Filter
            </button>
            <a href="{{ route('admin.students') }}" class="btn btn-outline-secondary" aria-label="Reset filters">
                <i class="fas fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<div class="surface-panel">
    @if($students->isEmpty())
        <p class="text-muted mb-0">No students found.</p>
    @else
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Enrollment</th>
                        <th>Institution</th>
                        <th>Home State</th>
                        <th>Studying State</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        @php
                            $studentName = $student->user?->name ?? 'Not available';
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar-circle">{{ $initials($studentName) }}</span>
                                    <div>
                                        <div class="fw-bold">{{ $studentName }}</div>
                                        <div class="small text-muted">{{ $student->user?->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $student->enrollment_number }}</td>
                            <td>{{ $student->institution?->institution_name ?? '-' }}</td>
                            <td>{{ $student->home_state }}</td>
                            <td>{{ $student->studying_state }}</td>
                            <td>{{ $student->course }}</td>
                            <td>{{ $student->year }}</td>
                            <td>{{ $student->phone }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $students->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
