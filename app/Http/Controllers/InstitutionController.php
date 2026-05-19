<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Institution;
use App\Models\Verification;

class InstitutionController extends Controller {
    public function dashboard() {
        $institution = Auth::user()->institution;
        $verifications = $institution
            ? Verification::where('institution_id', $institution->id)
                ->with('scholarship.student.user', 'scholarship.student.institution')
                ->latest()
                ->get()
            : collect();

        $stats = [
            'total' => $verifications->count(),
            'pending' => $verifications->where('status', 'pending')->count(),
            'verified' => $verifications->where('status', 'verified')->count(),
            'rejected' => $verifications->where('status', 'rejected')->count(),
        ];

        return view('institution.dashboard', compact('institution', 'verifications', 'stats'));
    }

    public function profileForm() {
        $institution = Auth::user()->institution;
        return view('institution.profile', compact('institution'));
    }

    public function saveProfile(Request $request) {
        $request->validate([
            'institution_name' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:1000',
            'registration_number' => 'required|string|max:255|unique:institutions,registration_number,' . (Auth::user()->institution?->id),
            'institution_type' => ['required', Rule::in(['University', 'College', 'Institute'])],
            'affiliated_university' => 'nullable|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|digits:10',
        ]);

        $currentInstitution = Auth::user()->institution;
        $status = $currentInstitution?->status === 'approved' ? 'approved' : 'pending';

        Institution::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only([
                'institution_name',
                'state',
                'city',
                'address',
                'registration_number',
                'institution_type',
                'affiliated_university',
                'contact_email',
                'contact_phone',
            ]) + ['user_id' => Auth::id(), 'status' => $status]
        );

        return redirect()->route('institution.dashboard')->with('success', 'Profile saved successfully!');
    }

    public function verify(Request $request, $id) {
        $request->validate([
            'status' => 'required|in:verified,rejected',
            'remarks' => 'nullable|string',
        ]);

        $institution = Auth::user()->institution;

        if (!$institution || $institution->status !== 'approved') {
            return redirect()->route('institution.dashboard')->with('error', 'Your institution must be approved before verifying applications.');
        }

        $verification = Verification::where('institution_id', $institution->id)->findOrFail($id);
        $verification->update([
            'status' => $request->status,
            'remarks' => $request->remarks,
            'verified_at' => $request->status === 'verified' ? now() : null,
        ]);

        $scholarship = $verification->scholarship;
        $scholarship->update(['status' => $request->status]);

        return redirect()->route('institution.dashboard')->with('success', 'Verification updated!');
    }
}
