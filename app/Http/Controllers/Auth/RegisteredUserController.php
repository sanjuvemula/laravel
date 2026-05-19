<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $institutions = Institution::where('status', 'approved')
            ->orderBy('institution_name')
            ->get();

        return view('auth.register', compact('institutions'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'in:student,institution'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'enrollment_number' => ['required_if:role,student', 'nullable', 'string', 'max:255', 'unique:students,enrollment_number'],
            'home_state' => ['required_if:role,student', 'nullable', 'string', 'max:255'],
            'studying_state' => ['required_if:role,student', 'nullable', 'string', 'max:255'],
            'institution_id' => ['required_if:role,student', 'nullable', 'exists:institutions,id'],
            'course' => ['required_if:role,student', 'nullable', 'string', 'max:255'],
            'year' => ['required_if:role,student', 'nullable', 'string', 'max:50'],
            'phone' => ['required_if:role,student', 'nullable', 'digits:10'],
            'institution_name' => ['required_if:role,institution', 'nullable', 'string', 'max:255'],
            'institution_type' => ['required_if:role,institution', 'nullable', 'in:University,College,Institute'],
            'registration_number' => ['required_if:role,institution', 'nullable', 'string', 'max:255', 'unique:institutions,registration_number'],
            'state' => ['required_if:role,institution', 'nullable', 'string', 'max:255'],
            'city' => ['required_if:role,institution', 'nullable', 'string', 'max:255'],
            'address' => ['required_if:role,institution', 'nullable', 'string', 'max:1000'],
            'affiliated_university' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['required_if:role,institution', 'nullable', 'digits:10'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        if ($user->role === 'student') {
            Student::create([
                'user_id' => $user->id,
                'enrollment_number' => $request->enrollment_number,
                'home_state' => $request->home_state,
                'studying_state' => $request->studying_state,
                'institution_id' => $request->institution_id,
                'course' => $request->course,
                'year' => $request->year,
                'phone' => $request->phone,
            ]);
        }

        if ($user->role === 'institution') {
            Institution::create([
                'user_id' => $user->id,
                'institution_name' => $request->institution_name,
                'state' => $request->state,
                'city' => $request->city,
                'address' => $request->address,
                'registration_number' => $request->registration_number,
                'institution_type' => $request->institution_type,
                'affiliated_university' => $request->affiliated_university,
                'contact_email' => $request->email,
                'contact_phone' => $request->contact_phone,
                'status' => 'pending',
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
