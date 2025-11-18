<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Presence;
use App\Models\CoursSession;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class DashboardEtudiantController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('etudiant')) {
            abort(403, 'Accès interdit.');
        }

        $etudiant = $user->etudiant;

        $coursSessions = CoursSession::with('cours')
            ->where('groupe_id', $etudiant->groupe_id)
            ->get();

        $absencesJustifiees = [];
        $absencesNonJustifiees = [];

        foreach ($coursSessions as $session) {
            $nomCours = $session->cours->nom ?? 'Inconnu';
            $absencesJustifiees[$nomCours] = 0;
            $absencesNonJustifiees[$nomCours] = 0;
        }

        // Récupérer toutes les absences (etat == 0) de l’étudiant
        $presences = Presence::with('session.cours')
            ->where('id_etudiant', $etudiant->id)
            ->where('etat', 0)
            ->get();

        foreach ($presences as $presence) {
            $cours = $presence->session->cours;
            $nomCours = $cours->nom ?? 'Inconnu';

            if ($presence->justification) {
                $absencesJustifiees[$nomCours] = ($absencesJustifiees[$nomCours] ?? 0) + 1;
            } else {
                $absencesNonJustifiees[$nomCours] = ($absencesNonJustifiees[$nomCours] ?? 0) + 1;
            }
        }


        $chart = new Chart;
        $chart->labels(array_keys($absencesJustifiees));
        $chart->dataset('Absences justifiées', 'bar', array_values($absencesJustifiees))
            ->backgroundColor('#36a2eb'); // Bleu
        $chart->dataset('Absences non justifiées', 'bar', array_values($absencesNonJustifiees))
            ->backgroundColor('#ff6384'); // Rouge

        return view('dashboardEtudiant', compact('chart'));
    }
    
}
