<?php

use App\Models\Institution;
use App\Models\Scholarship;
use App\Models\Student;
use Illuminate\Support\Facades\Route;

Route::get('/students/{enrollment}', function (string $enrollment) {
    $student = Student::with('user', 'institution', 'scholarships.verification')
        ->where('enrollment_number', $enrollment)
        ->firstOrFail();

    return response()->json([
        'student' => [
            'name' => $student->user?->name,
            'email' => $student->user?->email,
            'enrollment_number' => $student->enrollment_number,
            'home_state' => $student->home_state,
            'studying_state' => $student->studying_state,
            'course' => $student->course,
            'year' => $student->year,
            'institution' => $student->institution?->institution_name,
        ],
        'scholarships' => $student->scholarships->map(fn ($scholarship) => [
            'scholarship_name' => $scholarship->scholarship_name,
            'amount' => (float) $scholarship->amount,
            'admin_status' => $scholarship->status,
            'verification_status' => $scholarship->verification?->status ?? 'pending',
            'remarks' => $scholarship->verification?->remarks ?? $scholarship->remarks,
            'applied_at' => $scholarship->created_at?->toDateTimeString(),
        ]),
    ]);
});

Route::get('/institutions', function () {
    return Institution::where('status', 'approved')
        ->orderBy('institution_name')
        ->get([
            'id',
            'institution_name',
            'state',
            'city',
            'address',
            'registration_number',
            'institution_type',
            'contact_email',
            'contact_phone',
        ]);
});

Route::get('/scholarships/stats', function () {
    return response()->json([
        'pending' => Scholarship::where('status', 'pending')->count(),
        'verified' => Scholarship::where('status', 'verified')->count(),
        'approved' => Scholarship::where('status', 'approved')->count(),
        'rejected' => Scholarship::where('status', 'rejected')->count(),
        'total' => Scholarship::count(),
    ]);
});
