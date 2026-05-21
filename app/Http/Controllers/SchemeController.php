<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipScheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SchemeController extends Controller
{
    public function index()
    {
        $institution = Auth::user()->institution;

        if (!$institution) {
            return redirect()->route('institution.profile')->with('error', 'Please complete your institution profile first.');
        }

        $schemes = ScholarshipScheme::where('institution_id', $institution->id)
            ->with(['tiers' => fn ($query) => $query->orderBy('deadline')->orderBy('amount')])
            ->latest()
            ->get();

        return view('institution.schemes.index', compact('institution', 'schemes'));
    }

    public function create()
    {
        $institution = Auth::user()->institution;

        if (!$institution) {
            return redirect()->route('institution.profile')->with('error', 'Please complete your institution profile first.');
        }

        if ($institution->status !== 'approved') {
            return redirect()->route('institution.schemes.index')->with('error', 'Your institution must be approved before creating schemes.');
        }

        return view('institution.schemes.create', compact('institution'));
    }

    public function store(Request $request)
    {
        $institution = Auth::user()->institution;

        if (!$institution) {
            return redirect()->route('institution.profile')->with('error', 'Please complete your institution profile first.');
        }

        if ($institution->status !== 'approved') {
            return redirect()->route('institution.schemes.index')->with('error', 'Your institution must be approved before creating schemes.');
        }

        $validated = $this->validateScheme($request);

        DB::transaction(function () use ($validated, $institution, $request) {
            $scheme = ScholarshipScheme::create([
                'institution_id' => $institution->id,
                'scheme_name' => $validated['scheme_name'],
                'description' => $validated['description'] ?? null,
                'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
            ]);

            $this->saveTiers($scheme, $validated['tiers']);
        });

        return redirect()->route('institution.schemes.index')->with('success', 'Scholarship scheme created successfully.');
    }

    public function edit($id)
    {
        $institution = Auth::user()->institution;

        if (!$institution) {
            return redirect()->route('institution.profile')->with('error', 'Please complete your institution profile first.');
        }

        $scheme = ScholarshipScheme::where('institution_id', $institution->id)
            ->with(['tiers' => fn ($query) => $query->orderBy('deadline')->orderBy('amount')])
            ->findOrFail($id);

        return view('institution.schemes.edit', compact('institution', 'scheme'));
    }

    public function update(Request $request, $id)
    {
        $institution = Auth::user()->institution;

        if (!$institution) {
            return redirect()->route('institution.profile')->with('error', 'Please complete your institution profile first.');
        }

        $scheme = ScholarshipScheme::where('institution_id', $institution->id)->findOrFail($id);
        $validated = $this->validateScheme($request);

        DB::transaction(function () use ($scheme, $validated, $request) {
            $scheme->update([
                'scheme_name' => $validated['scheme_name'],
                'description' => $validated['description'] ?? null,
                'is_active' => $request->boolean('is_active'),
            ]);

            $scheme->tiers()->delete();
            $this->saveTiers($scheme, $validated['tiers']);
        });

        return redirect()->route('institution.schemes.index')->with('success', 'Scholarship scheme updated successfully.');
    }

    public function destroy($id)
    {
        $institution = Auth::user()->institution;

        if (!$institution) {
            return redirect()->route('institution.profile')->with('error', 'Please complete your institution profile first.');
        }

        ScholarshipScheme::where('institution_id', $institution->id)
            ->findOrFail($id)
            ->update(['is_active' => false]);

        return redirect()->route('institution.schemes.index')->with('success', 'Scholarship scheme deactivated successfully.');
    }

    public function getTiers($schemeId)
    {
        $scheme = ScholarshipScheme::where('is_active', true)
            ->with(['tiers' => fn ($query) => $query->whereDate('deadline', '>=', today())->orderBy('amount')])
            ->findOrFail($schemeId);

        $user = Auth::user();

        if ($user?->isStudent() && $user->student?->institution_id !== $scheme->institution_id) {
            abort(403);
        }

        if ($user?->isInstitution() && $user->institution?->id !== $scheme->institution_id) {
            abort(403);
        }

        return response()->json($scheme->tiers->map(fn ($tier) => [
            'id' => $tier->id,
            'tier_name' => $tier->tier_name,
            'criteria' => $tier->criteria,
            'amount' => (float) $tier->amount,
            'total_seats' => $tier->total_seats,
            'filled_seats' => $tier->filled_seats,
            'available_seats' => max(0, $tier->total_seats - $tier->filled_seats),
            'deadline' => $tier->deadline?->format('Y-m-d'),
            'has_seats_available' => $tier->hasSeatsAvailable(),
        ])->values());
    }

    private function validateScheme(Request $request): array
    {
        return $request->validate([
            'scheme_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
            'tiers' => ['required', 'array', 'min:1'],
            'tiers.*.tier_name' => ['required', 'string', 'max:255'],
            'tiers.*.criteria' => ['required', 'string', 'max:2000'],
            'tiers.*.amount' => ['required', 'numeric', 'min:0'],
            'tiers.*.total_seats' => ['required', 'integer', 'min:1'],
            'tiers.*.deadline' => ['required', 'date'],
        ]);
    }

    private function saveTiers(ScholarshipScheme $scheme, array $tiers): void
    {
        foreach ($tiers as $tier) {
            $scheme->tiers()->create([
                'tier_name' => $tier['tier_name'],
                'criteria' => $tier['criteria'],
                'amount' => $tier['amount'],
                'total_seats' => $tier['total_seats'],
                'filled_seats' => 0,
                'deadline' => $tier['deadline'],
            ]);
        }
    }
}
