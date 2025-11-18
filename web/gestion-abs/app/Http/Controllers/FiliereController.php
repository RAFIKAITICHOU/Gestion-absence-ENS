<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Filiere;

class FiliereController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();
        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        // Redirection avec tab actif (optionnel si tu ne l’utilises pas)
        return redirect()
            ->route('dashboardadmin.configuration')
            ->with('active_tab', 'tab-filiere');
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();
        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $request->validate([
            'nom_filiere' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Filiere::create([
            'nom_filiere' => $request->nom_filiere,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('dashboardadmin.configuration')
            ->with('success', 'Filière ajoutée avec succès.')
            ->with('active_tab', 'tab-filiere');
    }

    public function edit($id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();
        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $filiere = Filiere::findOrFail($id);
        return view('filieres.edit', compact('filiere'));
    }

    public function update(Request $request, $id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();
        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $request->validate([
            'nom_filiere' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $filiere = Filiere::findOrFail($id);
        $filiere->update($request->only('nom_filiere', 'description'));

        return redirect()
            ->route('dashboardadmin.configuration')
            ->with('success', 'Filière mise à jour avec succès.')
            ->with('active_tab', 'tab-filiere'); 
    }

    public function destroy($id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();
        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        Filiere::destroy($id);

        return redirect()
            ->route('dashboardadmin.configuration')
            ->with('success', 'Filière supprimée avec succès.')
            ->with('active_tab', 'tab-filiere');
    }
}
