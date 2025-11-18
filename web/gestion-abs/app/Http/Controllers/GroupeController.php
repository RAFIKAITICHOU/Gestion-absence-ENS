<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Groupe;
use App\Models\Filiere;

class GroupeController extends Controller
{
    // Ajout d’un groupe
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();
        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'filiere_id' => 'required|exists:filieres,id',
        ]);

        Groupe::create([
            'nom_groupe' => $request->nom,
            'id_filiere' => $request->filiere_id,
        ]);

        return redirect()
            ->route('dashboardadmin.configuration')
            ->with('success', 'Groupe ajouté avec succès.')
            ->with('active_tab', 'tab-groupe');
    }

    // Formulaire de modification
    public function edit($id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();
        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $groupe = Groupe::findOrFail($id);
        $filieres = Filiere::all();
        return view('groupes.edit', compact('groupe', 'filieres'));
    }

    // Mise à jour d’un groupe
    public function update(Request $request, $id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();
        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $request->validate([
            'nom_groupe' => 'required|string|max:255',
            'id_filiere' => 'required|exists:filieres,id',
        ]);

        $groupe = Groupe::findOrFail($id);
        $groupe->update([
            'nom_groupe' => $request->nom_groupe,
            'id_filiere' => $request->id_filiere,
        ]);

        return redirect()
            ->route('dashboardadmin.configuration')
            ->with('success', 'Groupe mis à jour avec succès.')
            ->with('active_tab', 'tab-groupe');
    }

    // Suppression d’un groupe
    public function destroy($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        Groupe::destroy($id);

        return redirect()
            ->route('dashboardadmin.configuration')
            ->with('success', 'Groupe supprimé avec succès.')
            ->with('active_tab', 'tab-groupe');
    }
}
