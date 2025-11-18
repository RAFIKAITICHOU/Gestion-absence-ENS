<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\CoursSession;
use App\Models\Presence;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Carbon\Carbon;

class DashboardProfController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('professeur')) {
            abort(403, 'Accès interdit.');
        }

        $professeur = $user->professeur;

        // Statistiques des absences
        $sessions = CoursSession::with('cours')
            ->where('id_professeur', $professeur->id)
            ->get();

        $absenceNonJustifiee = [];
        $absenceJustifiee = [];

        foreach ($sessions as $session) {
            $nomCours = $session->cours->nom ?? 'Inconnu';
            $absenceNonJustifiee[$nomCours] = 0;
            $absenceJustifiee[$nomCours] = 0;
        }

        $presences = Presence::with('session.cours')
            ->whereIn('id_session', $sessions->pluck('id')->toArray())
            ->where('etat', 0)
            ->get();

        foreach ($presences as $presence) {
            $nomCours = $presence->session->cours->nom ?? 'Inconnu';
            if ($presence->justification && trim($presence->justification) !== '') {
                $absenceJustifiee[$nomCours]++;
            } else {
                $absenceNonJustifiee[$nomCours]++;
            }
        }

        $chart = new Chart;
        $chart->labels(array_keys($absenceNonJustifiee));
        $chart->dataset('Absences non justifiées', 'bar', array_values($absenceNonJustifiee))
            ->backgroundColor('rgba(255, 99, 132, 0.8)');
        $chart->dataset('Absences justifiées', 'bar', array_values($absenceJustifiee))
            ->backgroundColor('rgba(255, 206, 86, 0.8)');

        // Cours du jour
        $seancesDuJour = CoursSession::with(['cours', 'groupe.filiere', 'groupe.etudiants.user', 'presences'])
            ->where('id_professeur', $professeur->id)
            ->whereDate('date', Carbon::now('Africa/Casablanca')->toDateString())
            ->orderBy('heure_debut')
            ->get();

        return view('dashboardProf', [
            'chart' => $chart,
            'seancesDuJour' => $seancesDuJour,
            'menu' => 'profMenu',
            'title' => 'Tableau de bord Professeur',
        ]);
    }
}
