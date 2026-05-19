@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="mb-1">Registered Students</h3>
        <p class="text-muted mb-0">View student enrollment, institution, and course details.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
</div>

<div class="card p-4">
    @if($students->isEmpty())
        <p class="text-muted mb-0">No students found.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Enrollment No</th>
                        <th>Institution</th>
                        <th>Home State</th>
                        <th>Studying State</th>
                        <th>Course</th>
                        <th>Year</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>{{ $student->user?->name ?? 'Not available' }}</td>
                            <td>{{ $student->user?->email ?? '-' }}</td>
                            <td>{{ $student->enrollment_number }}</td>
                            <td>{{ $student->institution?->institution_name ?? '-' }}</td>
                            <td>{{ $student->home_state }}</td>
                            <td>{{ $student->studying_state }}</td>
                            <td>{{ $student->course }}</td>
                            <td>{{ $student->year }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $students->links() }}
    @endif
</div>
@endsection
