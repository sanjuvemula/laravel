<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Student;
use App\Models\Scholarship;
use App\Models\Institution;
use App\Models\Verification;

class StudentController extends Controller {
    private array $scholarshipAmounts = [
        'Merit' => 25000,
        'Sports' => 18000,
        'Minority' => 22000,
        'Disability' => 30000,
        'OBC' => 20000,
        'SC/ST' => 28000,
    ];

    public function dashboard() {
        $user = Auth::user();
        $student = $user->student;
        $scholarships = $student
            ? Scholarship::where('student_id', $student->id)
                ->with('verification.institution')
                ->latest()
                ->get()
            : collect();

        $stats = [
            'total' => $scholarships->count(),
            'pending' => $scholarships->where('status', 'pending')->count() + $scholarships->where('status', 'verified')->count(),
            'approved' => $scholarships->where('status', 'approved')->count(),
            'rejected' => $scholarships->where('status', 'rejected')->count(),
        ];

        return view('student.dashboard', compact('user', 'student', 'scholarships', 'stats'));
    }

    public function profileForm() {
        $institutions = Institution::where('status', 'approved')->orderBy('institution_name')->get();
        $student = Auth::user()->student;
        return view('student.profile', compact('institutions', 'student'));
    }

    public function saveProfile(Request $request) {
        $request->validate([
            'enrollment_number' => 'required|string|max:255|unique:students,enrollment_number,' . (Auth::user()->student?->id),
            'home_state' => 'required|string|max:255',
            'studying_state' => 'required|string|max:255',
            'institution_id' => [
                'required',
                Rule::exists('institutions', 'id')->where('status', 'approved'),
            ],
            'course' => 'required|string|max:255',
            'year' => 'required|string|max:50',
            'phone' => 'required|digits:10',
        ]);

        Student::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only([
                'enrollment_number',
                'home_state',
                'studying_state',
                'institution_id',
                'course',
                'year',
                'phone',
            ]) + ['user_id' => Auth::id()]
        );

        return redirect()->route('student.dashboard')->with('success', 'Profile saved successfully!');
    }

    public function applyForm() {
        $student = Auth::user()->student;
        if (!$student) return redirect()->route('student.profile')->with('error', 'Please complete your profile first.');
        return view('student.apply', [
            'student' => $student,
            'scholarshipAmounts' => $this->scholarshipAmounts,
        ]);
    }

    public function applySubmit(Request $request) {
        $request->validate([
            'scholarship_name' => ['required', Rule::in(array_keys($this->scholarshipAmounts))],
            'supporting_document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $student = Auth::user()->student;
        if (!$student) return redirect()->route('student.profile')->with('error', 'Please complete your profile first.');

        $documentPath = $request->file('supporting_document')->store('documents', 'public');

        $scholarship = Scholarship::create([
            'student_id' => $student->id,
            'scholarship_name' => $request->scholarship_name,
            'amount' => $this->scholarshipAmounts[$request->scholarship_name],
            'status' => 'pending',
            'document_path' => $documentPath,
        ]);

        Verification::create([
            'scholarship_id' => $scholarship->id,
            'institution_id' => $student->institution_id,
            'status' => 'pending',
        ]);

        return redirect()->route('student.dashboard')->with('success', 'Scholarship application submitted!');
    }
}
