<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\JourInactif;

class JourInactifController extends Controller
{
    /**
     * Enregistrer un nouveau jour inactif (vacance).
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $request->validate([
            'titre' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
        ]);

        JourInactif::create([
            'titre' => $request->titre,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
        ]);

        return redirect()
            ->route('dashboardadmin.configuration')
            ->with('success', 'Jour inactif ajouté avec succès.')
            ->with('active_tab', 'tab-vacance');
    }


    /**
     * Supprimer un jour inactif.
     */
    public function destroy($id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $jour = JourInactif::findOrFail($id);
        $jour->delete();

        return redirect()
            ->route('dashboardadmin.configuration')
            ->with('success', 'Vacance supprimée avec succès.')
            ->with('active_tab', 'tab-vacance');
    }

    /**
     * Afficher la vue de modification d’un jour inactif.
     */
    public function edit($id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $jour = JourInactif::findOrFail($id);
        return view('jours_inactifs.edit', compact('jour'));
    }

    /**
     * Mettre à jour un jour inactif.
     */
    public function update(Request $request, $id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $request->validate([
            'titre' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
        ]);

        $jour = JourInactif::findOrFail($id);
        $jour->update([
            'titre' => $request->titre,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
        ]);

        return redirect()
            ->route('dashboardadmin.configuration')
            ->with('success', 'Jour inactif modifié avec succès.')
            ->with('active_tab', 'tab-vacance');
    }

    /**
     * API pour FullCalendar – retourne les jours inactifs formatés.
     */
    public function api()
    {
        $joursInactifs = JourInactif::all()->map(function ($j) {
            return [
                'id' => $j->id,
                'title' => $j->titre,
                'start' => $j->date_debut,
                'end' => $j->date_fin
                    ? date('Y-m-d', strtotime($j->date_fin . ' +1 day'))
                    : $j->date_debut,
                'type' => 'vacance',
                'classNames' => ['vacance'],
            ];
        });

        $annee = date('Y');
        $joursFeriesFixes = collect([
            ['date' => "$annee-01-01", 'titre' => 'Nouvel An'],
            ['date' => "$annee-01-11", 'titre' => 'Manifeste de l’Indépendance'],
            ['date' => "$annee-01-14", 'titre' => 'Nouvel An Amazigh'],
            ['date' => "$annee-05-01", 'titre' => 'Fête du Travail'],
            ['date' => "$annee-07-30", 'titre' => 'Fête du Trône'],
            ['date' => "$annee-08-14", 'titre' => 'Oued Ed-Dahab'],
            ['date' => "$annee-08-20", 'titre' => 'Révolution du Roi et du Peuple'],
            ['date' => "$annee-08-21", 'titre' => 'Fête de la Jeunesse'],
            ['date' => "$annee-11-06", 'titre' => 'Marche Verte'],
            ['date' => "$annee-11-18", 'titre' => 'Fête de l’Indépendance'],
        ])->map(function ($ferie, $i) {
            return [
                'id' => 'fixe-' . $i,
                'title' => $ferie['titre'],
                'start' => $ferie['date'],
                'end' => $ferie['date'],
                'type' => 'vacance',
                'classNames' => ['vacance'],
            ];
        });
        if ($joursInactifs->isEmpty()) {
            return $joursFeriesFixes;
        }
        return $joursInactifs->merge($joursFeriesFixes)->values();
    }
}
