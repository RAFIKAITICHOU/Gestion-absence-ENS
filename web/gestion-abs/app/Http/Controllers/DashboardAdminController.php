<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Professeur;
use App\Models\Presence;
use App\Models\Filiere;
use App\Models\Groupe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        if (!auth()->user()->hasRole('administrateur')) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->hasRole('professeur')) {
                return redirect()->route('dashboard.prof');
            } elseif ($user->hasRole('etudiant')) {
                return redirect()->route('dashboard.etudiant');
            }
        }

        $nombreEtudiants = Etudiant::count();
        $nombreProfesseurs = Professeur::count();
        $nombreAbsences = Presence::where('etat', false)->count();
        $nombreTotalPresences = Presence::count();

        $pourcentageAbsences = $nombreTotalPresences > 0
            ? round(($nombreAbsences / $nombreEtudiants) * 100)
            : 0;

        // Absences par filière (toutes incluses même si 0)
        $absencesParFiliere = Filiere::with(['groupes.etudiants.presences' => function ($q) {
            $q->where('etat', 0);
        }])->get()->mapWithKeys(function ($filiere) {
            $count = 0;
            foreach ($filiere->groupes as $groupe) {
                foreach ($groupe->etudiants as $etudiant) {
                    $count += $etudiant->presences->count();
                }
            }
            return [$filiere->nom_filiere => $count];
        });

        // Absences par groupe (tous inclus même si 0)
        $absencesParGroupe = Groupe::with(['etudiants.presences' => function ($q) {
            $q->where('etat', 0);
        }])->get()->mapWithKeys(function ($groupe) {
            $count = 0;
            foreach ($groupe->etudiants as $etudiant) {
                $count += $etudiant->presences->count();
            }
            return [$groupe->nom_groupe => $count];
        });

        return view('dashboardAdmin', compact(
            'nombreEtudiants',
            'nombreProfesseurs',
            'nombreAbsences',
            'pourcentageAbsences',
            'absencesParFiliere',
            'absencesParGroupe'
        ));
    }

    public function GestionEDT()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $menu = 'adminMenu';
        return view('GestionEDT', compact('menu'));
    }

    public function configuration()
    {

        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $filieres = Filiere::all();
        return view('configuration', [
            'filieres' => $filieres,
            'menu' => 'adminMenu',
            'title' => 'Configuration'
        ]);
    }

    public function statistiques()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $menu = 'adminMenu';
        return view('statistiques', compact('menu'));
    }


    public function getMonthlyEvolution()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            if ($user->hasRole('professeur')){
                return redirect()->route('dashboard.prof');
            }
             elseif ($user->hasRole('etudiant')) {
                return redirect()->route('dashboard.etudiant');
            }  else{
                return redirect()->route('logout');
            }
        }

        $result = DB::table('presences')
            ->join('cours_sessions', 'presences.id_session', '=', 'cours_sessions.id')
            ->select(DB::raw('MONTH(cours_sessions.date) as month'), DB::raw('count(*) as total'))
            ->where('presences.etat', 0)
            ->groupBy(DB::raw('MONTH(cours_sessions.date)'))
            ->orderBy(DB::raw('MONTH(cours_sessions.date)'))
            ->pluck('total', 'month');

        // Générer les 12 mois
        $fullData = [];
        for ($i = 1; $i <= 12; $i++) {
            $fullData[] = [
                'month' => $i,
                'total' => $result[$i] ?? 0
            ];
        }

        return response()->json($fullData);
    }
}
