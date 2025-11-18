<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create() //: View
    {
        $user = Auth::user();
        // dd($user);
        if ($user != null) {
            return redirect()->route('dashboardadmin');
        } else {
            return view('auth.login');
        }
    }

    /**
     * Handle an incoming authentication request.
     */

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Les informations d’identification sont incorrectes.']);
        }

        $user = Auth::user();

        // Redirection selon le rôle
        if ($user->hasRole('administrateur')) {
            return redirect()->route('dashboardadmin');
        } elseif ($user->hasRole('professeur')) {
            return redirect()->route('dashboard.prof'); // 🔁 à adapter
        } elseif ($user->hasRole('etudiant')) {
            return redirect()->route('dashboard.etudiant'); // 🔁 à adapter
        }

        return redirect()->route('login')->withErrors('Aucun rôle valide trouvé.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
