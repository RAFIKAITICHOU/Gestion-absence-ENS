<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presence;
use App\Models\Filiere;
use App\Models\Groupe;
use Illuminate\Support\Facades\Auth;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class StatistiquesController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $filiereFilter = $request->input('filiere');
        $moisFilter = $request->input('mois');

        // Présences filtrées
        $query = Presence::with(['etudiant.user', 'etudiant.groupe.filiere', 'session.cours'])
            ->where('etat', 0);

        if ($filiereFilter) {
            $query->whereHas('etudiant.groupe.filiere', function ($q) use ($filiereFilter) {
                $q->where('id', $filiereFilter);
            });
        }

        if ($moisFilter) {
            $query->whereHas('session', function ($q) use ($moisFilter) {
                $q->whereMonth('date', $moisFilter);
            });
        }

        $absencesDetaillees = $query->paginate(10)->withQueryString();

        // Statistiques par filière
        $absencesParFiliere = Filiere::with(['groupes.etudiants.presences' => function ($q) {
            $q->where('etat', 0);
        }])->get()->mapWithKeys(function ($filiere) {
            $justifiees = 0;
            $nonJustifiees = 0;

            foreach ($filiere->groupes as $groupe) {
                foreach ($groupe->etudiants as $etudiant) {
                    foreach ($etudiant->presences as $presence) {
                        if ($presence->justification && trim($presence->justification) !== '') {
                            $justifiees++;
                        } else {
                            $nonJustifiees++;
                        }
                    }
                }
            }

            return [$filiere->nom_filiere => [
                'justifiees' => $justifiees,
                'non_justifiees' => $nonJustifiees
            ]];
        });

        $absencesParGroupe = Groupe::with(['etudiants.presences' => function ($q) {
            $q->where('etat', 0);
        }])->get()->mapWithKeys(function ($groupe) {
            $justifiees = 0;
            $nonJustifiees = 0;

            foreach ($groupe->etudiants as $etudiant) {
                foreach ($etudiant->presences as $presence) {
                    if ($presence->justification && trim($presence->justification) !== '') {
                        $justifiees++;
                    } else {
                        $nonJustifiees++;
                    }
                }
            }

            return [$groupe->nom_groupe => [
                'justifiees' => $justifiees,
                'non_justifiees' => $nonJustifiees
            ]];
        });

        $chartFiliere = new Chart;
        $chartFiliere->labels(array_keys($absencesParFiliere->toArray()));
        $chartFiliere->dataset(
            'Absences justifiées',
            'bar',
            array_values($absencesParFiliere->map(fn($d) => $d['justifiees'])->toArray())
        )->backgroundColor('rgba(255, 206, 86, 0.7)');
        $chartFiliere->dataset(
            'Absences non justifiées',
            'bar',
            array_values($absencesParFiliere->map(fn($d) => $d['non_justifiees'])->toArray())
        )->backgroundColor('rgba(255, 99, 132, 0.7)');

        $chartGroupe = new Chart;
        $chartGroupe->labels(array_keys($absencesParGroupe->toArray()));
        $chartGroupe->dataset(
            'Absences justifiées',
            'bar',
            array_values($absencesParGroupe->map(fn($d) => $d['justifiees'])->toArray())
        )->backgroundColor('rgba(255, 206, 86, 0.7)');
        $chartGroupe->dataset(
            'Absences non justifiées',
            'bar',
            array_values($absencesParGroupe->map(fn($d) => $d['non_justifiees'])->toArray())
        )->backgroundColor('rgba(255, 99, 132, 0.7)');

        $filieres = Filiere::all();

        return view('statistiques', [
            'absencesDetaillees' => $absencesDetaillees,
            'absencesParFiliere' => $absencesParFiliere,
            'absencesParGroupe' => $absencesParGroupe,
            'chartFiliere' => $chartFiliere,
            'chartGroupe' => $chartGroupe,
            'filieres' => $filieres,
            'filiereFilter' => $filiereFilter,
            'moisFilter' => $moisFilter,
            'title' => 'Dashboard Admin',
            'menu' => 'adminMenu'
        ]);
    }

    public function export(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $absences = Presence::with(['etudiant.user', 'etudiant.groupe.filiere', 'session.cours'])
            ->where('etat', 0)
            ->get();

        $csvData = [];

        foreach ($absences as $absence) {
            $type = ($absence->justification && trim($absence->justification) !== '')
                ? 'Justifiée'
                : 'Non justifiée';

            $csvData[] = [
                'Nom' => $absence->etudiant->user->name ?? '',
                'Prenom' => $absence->etudiant->user->prenom ?? '',
                'Filiere' => $absence->etudiant->groupe->filiere->nom_filiere ?? '',
                'Groupe' => $absence->etudiant->groupe->nom_groupe ?? '',
                'Cours' => $absence->session->cours->nom ?? '',
                'Date' => $absence->session->date ?? '',
                'Heure début' => $absence->session->heure_debut ?? '',
                'Heure fin' => $absence->session->heure_fin ?? '',
                'Justification' => $type,
            ];
        }

        $filename = 'statistiques_absences_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($csvData) {
            $file = fopen('php://output', 'w');

            // Encodage UTF-8 avec BOM pour Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // En-têtes
            fputcsv($file, array_keys($csvData[0]), ';');

            // Lignes de données
            foreach ($csvData as $row) {
                fputcsv($file, $row, ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
