<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Institution;
use App\Models\Student;
use App\Models\Scholarship;
use App\Models\ScholarshipScheme;
use App\Models\Verification;

class AdminController extends Controller {
    public function dashboard() {
        $totalStudents = Student::count();
        $totalInstitutions = Institution::count();
        $totalScholarships = Scholarship::count();
        $pendingVerifications = Verification::where('status', 'pending')->count();
        $approvedScholarships = Scholarship::where('status', 'approved')->count();
        $rejectedScholarships = Scholarship::where('status', 'rejected')->count();
        $recentApplications = Scholarship::with('student.user', 'student.institution', 'tier.scheme', 'verification')
            ->latest()
            ->take(5)
            ->get();
        $stateWiseBreakdown = Student::select('home_state', DB::raw('count(*) as total'))
            ->whereNotNull('home_state')
            ->where('home_state', '!=', '')
            ->groupBy('home_state')
            ->orderByDesc('total')
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalInstitutions',
            'totalScholarships',
            'pendingVerifications',
            'approvedScholarships',
            'rejectedScholarships',
            'recentApplications',
            'stateWiseBreakdown'
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
        $scholarships = Scholarship::with('student.user', 'student.institution', 'tier.scheme', 'verification.institution')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('state'), function ($query) use ($request) {
                $query->whereHas('student', fn ($studentQuery) => $studentQuery->where('home_state', $request->state));
            })
            ->when($request->filled('scheme_id'), function ($query) use ($request) {
                $query->whereHas('tier.scheme', fn ($schemeQuery) => $schemeQuery->where('id', $request->scheme_id));
            })
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

        $schemes = ScholarshipScheme::orderBy('scheme_name')->get();

        return view('admin.scholarships', compact('scholarships', 'states', 'schemes'));
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

    public function students(Request $request) {
        $students = Student::with('user', 'institution')
            ->when($request->filled('institution_id'), fn ($query) => $query->where('institution_id', $request->institution_id))
            ->when($request->filled('state'), fn ($query) => $query->where('home_state', $request->state))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('enrollment_number', 'like', '%' . $search . '%')
                        ->orWhereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', '%' . $search . '%'));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $institutions = Institution::orderBy('institution_name')->get();
        $states = Student::whereNotNull('home_state')
            ->where('home_state', '!=', '')
            ->distinct()
            ->orderBy('home_state')
            ->pluck('home_state');

        return view('admin.students', compact('students', 'institutions', 'states'));
    }

    public function exportCsv() {
        $filename = 'scholarship-applications-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Student Name',
                'Enrollment',
                'Home State',
                'Studying State',
                'Institution',
                'Scheme',
                'Tier',
                'Amount',
                'Document',
                'Verification Status',
                'Admin Status',
            ]);

            Scholarship::with('student.user', 'student.institution', 'tier.scheme', 'verification')
                ->latest()
                ->chunk(200, function ($scholarships) use ($handle) {
                    foreach ($scholarships as $scholarship) {
                        fputcsv($handle, [
                            $scholarship->student?->user?->name,
                            $scholarship->student?->enrollment_number,
                            $scholarship->student?->home_state,
                            $scholarship->student?->studying_state,
                            $scholarship->student?->institution?->institution_name,
                            $scholarship->tier?->scheme?->scheme_name ?? $scholarship->scholarship_name,
                            $scholarship->tier?->tier_name,
                            $scholarship->amount,
                            $scholarship->document_path ? asset('storage/' . $scholarship->document_path) : '',
                            $scholarship->verification?->status ?? 'pending',
                            $scholarship->status,
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
