<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use App\Models\Student;
use App\Models\Scholarship;
use App\Models\Institution;
use App\Models\Verification;
use App\Models\ScholarshipScheme;
use App\Models\SchemeTier;

class StudentController extends Controller {
    public function dashboard() {
        $user = Auth::user();
        $student = $user->student;
        $scholarships = $student
            ? Scholarship::where('student_id', $student->id)
                ->with('verification.institution', 'tier.scheme')
                ->latest()
                ->get()
            : collect();

        $pending = $scholarships->filter(fn ($s) => $s->verification?->status === 'pending')->count();
        $verified = $scholarships->filter(fn ($s) => $s->verification?->status === 'verified')->count();
        $rejected = $scholarships->filter(fn ($s) => $s->verification?->status === 'rejected')->count();

        $stats = [
            'total' => $scholarships->count(),
            'pending' => $pending,
            'verified' => $verified,
            'rejected' => $rejected,
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

        $schemes = ScholarshipScheme::where('institution_id', $student->institution_id)
            ->where('is_active', true)
            ->with(['tiers' => fn ($query) => $query->whereDate('deadline', '>=', today())->orderBy('amount')])
            ->orderBy('scheme_name')
            ->get();

        return view('student.apply', compact('student', 'schemes'));
    }

    public function applySubmit(Request $request) {
        $student = Auth::user()->student;
        if (!$student) return redirect()->route('student.profile')->with('error', 'Please complete your profile first.');

        $validated = $request->validate([
            'scheme_id' => [
                'required',
                Rule::exists('scholarship_schemes', 'id')
                    ->where('institution_id', $student->institution_id)
                    ->where('is_active', true),
            ],
            'tier_id' => [
                'required',
                Rule::exists('scheme_tiers', 'id')->where('scheme_id', $request->scheme_id),
            ],
            'supporting_document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $documentPath = $request->file('supporting_document')->store('documents', 'public');

        try {
            DB::transaction(function () use ($validated, $student, $documentPath) {
                $scheme = ScholarshipScheme::where('institution_id', $student->institution_id)
                    ->where('is_active', true)
                    ->findOrFail($validated['scheme_id']);

                $tier = SchemeTier::where('scheme_id', $scheme->id)
                    ->lockForUpdate()
                    ->findOrFail($validated['tier_id']);

                if ($tier->deadline->lt(today())) {
                    throw ValidationException::withMessages([
                        'tier_id' => 'The selected tier deadline has passed.',
                    ]);
                }

                if (!$tier->hasSeatsAvailable()) {
                    throw ValidationException::withMessages([
                        'tier_id' => 'No seats are available for the selected tier.',
                    ]);
                }

                $scholarship = Scholarship::create([
                    'student_id' => $student->id,
                    'tier_id' => $tier->id,
                    'scholarship_name' => $scheme->scheme_name,
                    'amount' => $tier->amount,
                    'status' => 'pending',
                    'document_path' => $documentPath,
                ]);

                $tier->increment('filled_seats');

                Verification::create([
                    'scholarship_id' => $scholarship->id,
                    'institution_id' => $student->institution_id,
                    'status' => 'pending',
                ]);
            });
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($documentPath);
            throw $exception;
        }

        return redirect()->route('student.dashboard')->with('success', 'Scholarship application submitted!');
    }
}
