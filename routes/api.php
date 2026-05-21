<?php

use App\Models\Institution;
use App\Models\Scholarship;
use App\Models\Student;
use Illuminate\Support\Facades\Route;

Route::get('/students/{enrollment}', function (string $enrollment) {
    $student = Student::with('user', 'institution', 'scholarships.tier.scheme', 'scholarships.verification')
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
            'scheme' => $scholarship->tier?->scheme?->scheme_name ?? $scholarship->scholarship_name,
            'tier' => $scholarship->tier?->tier_name,
            'amount' => (float) $scholarship->amount,
            'document' => $scholarship->document_path ? asset('storage/' . $scholarship->document_path) : null,
            'verification_status' => $scholarship->verification?->status ?? 'pending',
            'admin_status' => $scholarship->status,
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
    $counts = Scholarship::selectRaw('status, count(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status');

    return response()->json([
        'pending' => (int) ($counts['pending'] ?? 0),
        'verified' => (int) ($counts['verified'] ?? 0),
        'approved' => (int) ($counts['approved'] ?? 0),
        'rejected' => (int) ($counts['rejected'] ?? 0),
        'total' => Scholarship::count(),
    ]);
});
