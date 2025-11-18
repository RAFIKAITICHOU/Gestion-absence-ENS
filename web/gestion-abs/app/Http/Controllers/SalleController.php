<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salle;
use Illuminate\Support\Facades\Auth;

class SalleController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $request->validate([
            'nom' => 'required|string|max:255',
            'equipements' => 'nullable|string',
            'projecteurs' => 'required|boolean',
        ]);

        Salle::create([
            'nom' => $request->nom,
            'equipements' => $request->equipements,
            'projecteurs' => $request->projecteurs,
        ]);

        return redirect()
            ->route('dashboardadmin.configuration')
            ->with('success', 'Salle ajoutée avec succès.')
            ->with('active_tab', 'tab-salle');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $salle = Salle::findOrFail($id);
        return view('salle.edit_salle', compact('salle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $request->validate([
            'nom' => 'required|string|max:255',
            'equipements' => 'nullable|string',
            'projecteurs' => 'required|boolean',
        ]);

        $salle = Salle::findOrFail($id);
        $salle->update([
            'nom' => $request->nom,
            'equipements' => $request->equipements,
            'projecteurs' => $request->projecteurs,
        ]);

        return redirect()
            ->route('dashboardadmin.configuration')
            ->with('success', 'Salle mise à jour avec succès.')
            ->with('active_tab', 'tab-salle');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        Salle::destroy($id);

        return redirect()
            ->route('dashboardadmin.configuration')
            ->with('success', 'Salle supprimée avec succès.')
            ->with('active_tab', 'tab-salle');
    }
}
