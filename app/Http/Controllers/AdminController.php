<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Institution;
use App\Models\Student;
use App\Models\Scholarship;
use App\Models\Verification;

class AdminController extends Controller {
    public function dashboard() {
        $totalStudents = Student::count();
        $totalInstitutions = Institution::count();
        $totalScholarships = Scholarship::count();
        $pendingVerifications = Verification::where('status', 'pending')->count();
        $approvedScholarships = Scholarship::where('status', 'approved')->count();
        $rejectedScholarships = Scholarship::where('status', 'rejected')->count();
        $recentApplications = Scholarship::with('student.user', 'student.institution')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalInstitutions',
            'totalScholarships',
            'pendingVerifications',
            'approvedScholarships',
            'rejectedScholarships',
            'recentApplications'
        ));
    }

    public function institutions(Request $request) {
        $institutions = Institution::with('user')
            ->when($request->filled('state'), fn ($query) => $query->where('state', $request->state))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('institution_name', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $states = Institution::whereNotNull('state')
            ->where('state', '!=', '')
            ->distinct()
            ->orderBy('state')
            ->pluck('state');

        return view('admin.institutions', compact('institutions', 'states'));
    }

    public function approveInstitution($id) {
        Institution::findOrFail($id)->update(['status' => 'approved']);
        return redirect()->route('admin.institutions')->with('success', 'Institution approved!');
    }

    public function rejectInstitution($id) {
        Institution::findOrFail($id)->update(['status' => 'rejected']);
        return redirect()->route('admin.institutions')->with('success', 'Institution rejected!');
    }

    public function scholarships(Request $request) {
        $scholarships = Scholarship::with('student.user', 'student.institution', 'verification.institution')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('state'), function ($query) use ($request) {
                $query->whereHas('student', fn ($studentQuery) => $studentQuery->where('home_state', $request->state));
            })
            ->when($request->filled('scholarship_type'), fn ($query) => $query->where('scholarship_name', $request->scholarship_type))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->whereHas('student', function ($studentQuery) use ($search) {
                    $studentQuery->where('enrollment_number', 'like', '%' . $search . '%')
                        ->orWhereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', '%' . $search . '%'));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $states = Student::whereNotNull('home_state')
            ->distinct()
            ->orderBy('home_state')
            ->pluck('home_state');

        $scholarshipTypes = ['Merit', 'Sports', 'Minority', 'Disability', 'OBC', 'SC/ST'];

        return view('admin.scholarships', compact('scholarships', 'states', 'scholarshipTypes'));
    }

    public function approve($id) {
        $scholarship = Scholarship::with('verification')->findOrFail($id);

        if ($scholarship->verification?->status !== 'verified') {
            return redirect()->route('admin.scholarships')->with('error', 'Institution verification is required before approval.');
        }

        $scholarship->update(['status' => 'approved']);
        return redirect()->route('admin.scholarships')->with('success', 'Scholarship approved!');
    }

    public function reject($id) {
        $scholarship = Scholarship::with('verification')->findOrFail($id);

        if ($scholarship->verification?->status !== 'verified') {
            return redirect()->route('admin.scholarships')->with('error', 'Institution verification is required before rejection.');
        }

        $scholarship->update(['status' => 'rejected']);
        return redirect()->route('admin.scholarships')->with('success', 'Scholarship rejected!');
    }

    public function students() {
        $students = Student::with('user', 'institution')
            ->latest()
            ->paginate(10);

        return view('admin.students', compact('students'));
    }
}
