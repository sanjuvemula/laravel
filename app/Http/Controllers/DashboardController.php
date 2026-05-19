<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller {
    public function index() {
        $user = Auth::user();
        if ($user->isAdmin()) return redirect()->route('admin.dashboard');
        if ($user->isInstitution()) return redirect()->route('institution.dashboard');
        if ($user->isStudent()) return redirect()->route('student.dashboard');
        return redirect('/');
    }
}