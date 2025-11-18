<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Cours;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Groupe;
use App\Models\CoursSession;

class EtudiantsCoursController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->professeur) {
            abort(403, 'Accès réservé aux professeurs.');
        }

        $professeur = $user->professeur;

        $coursIds = CoursSession::where('id_professeur', $professeur->id)
            ->pluck('id_cours')
            ->unique();

        $cours = Cours::whereIn('id', $coursIds)->get();

        $selectedCours = null;
        $selectedFiliere = null;
        $selectedGroupe = null;
        $etudiants = collect();
        $filieres = collect();
        $groupes = collect();

        return view('professeur.etudiants', compact(
            'cours',
            'selectedCours',
            'selectedFiliere',
            'selectedGroupe',
            'etudiants',
            'filieres',
            'groupes'
        ));
    }


    public function getFilieres($idCours)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('professeur')) {
            abort(403, 'Accès interdit.');
        }
        $sessions = CoursSession::where('id_cours', $idCours)
            ->with('groupe.filiere')
            ->get();

        $filieres = collect();

        foreach ($sessions as $session) {
            if ($session->groupe && $session->groupe->filiere) {
                $filieres->push($session->groupe->filiere);
            }
        }

        $filieres = $filieres->unique('id')->values();

        return response()->json($filieres);
    }

    public function getGroupes($idFiliere, $idCours)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('professeur')) {
            abort(403, 'Accès interdit.');
        }
        $groupeIds = CoursSession::where('id_cours', $idCours)
            ->whereHas('groupe', function ($query) use ($idFiliere) {
                $query->where('id_filiere', $idFiliere);
            })
            ->pluck('groupe_id')
            ->unique();

        $groupes = Groupe::whereIn('id', $groupeIds)->get();

        return response()->json($groupes);
    }


    public function getEtudiants(Request $request)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('professeur')) {
            abort(403, 'Accès interdit.');
        }
        $professeur = Auth::user()->professeur;

        $coursIds = CoursSession::where('id_professeur', $professeur->id)
            ->pluck('id_cours')->unique();

        $cours = Cours::whereIn('id', $coursIds)->get();

        $selectedCours = $request->cours_id;
        $selectedFiliere = $request->filiere_id;
        $selectedGroupe = $request->groupe_id;

        $filieres = collect();
        $groupes = collect();
        $etudiants = collect();

        if ($selectedCours) {
            $filieres = CoursSession::where('id_cours', $selectedCours)
                ->with('groupe.filiere')
                ->get()
                ->pluck('groupe.filiere')
                ->unique('id')
                ->filter();

            if ($selectedFiliere) {
                $groupesIds = CoursSession::where('id_cours', $selectedCours)
                    ->whereHas('groupe', function ($q) use ($selectedFiliere) {
                        $q->where('id_filiere', $selectedFiliere);
                    })
                    ->pluck('groupe_id')->unique();

                $groupes = Groupe::whereIn('id', $groupesIds)->get();

                if ($selectedGroupe) {
                    $etudiants = Etudiant::with(['user', 'presences.session'])
                        ->where('groupe_id', $selectedGroupe)
                        ->get()
                        ->each(function ($etudiant) use ($selectedCours) {
                            // On garde uniquement les présences du cours sélectionné
                            $etudiant->presences = $etudiant->presences->filter(function ($presence) use ($selectedCours) {
                                return optional($presence->session)->id_cours == $selectedCours;
                            });
                        });
                }
            }
        }

        return view('professeur.etudiants', compact(
            'cours',
            'etudiants',
            'selectedCours',
            'selectedFiliere',
            'selectedGroupe',
            'filieres',
            'groupes'
        ));
    }


    public function exportPDF($cours, $filiere, $groupe)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('professeur') || !$user->professeur) {
            abort(403, 'Accès interdit.');
        }

        $profId = $user->professeur->id;

        // Vérification de l’autorisation : le prof doit enseigner ce cours dans ce groupe
        $profEnseigne = CoursSession::where('id_professeur', $profId)
            ->where('id_cours', $cours)
            ->where('groupe_id', $groupe)
            ->exists();

        if (!$profEnseigne) {
            abort(403, 'Vous n\'êtes pas autorisé à exporter cette liste.');
        }

        // Récupération des données
        $etudiants = Etudiant::with('user', 'groupe')
            ->where('groupe_id', $groupe)
            ->get();

        $coursData = Cours::findOrFail($cours);
        $filiereData = Filiere::findOrFail($filiere);
        $groupeData = Groupe::findOrFail($groupe);
        $professeur = $user->professeur()->with('user')->first();

        // Génération du contenu du QR code
        $qrContent = "Cours : {$coursData->nom}\n"
            . "Professeur : " . ($professeur->user->name ?? '') . " " . ($professeur->user->prenom ?? '') . "\n"
            . "Filière : " . ($filiereData->nom_filiere ?? '') . "\n"
            . "Groupe : " . ($groupeData->nom_groupe ?? '') . "\n"
            . "Exporté par : " . ($user->name . ' ' . $user->prenom ?? '') . "\n"
            . "Date : " . now()->format('d/m/Y à H:i');

        // QR code en base64
        $qrCode = base64_encode(QrCode::format('png')->size(120)->generate($qrContent));

        return PDF::loadView('professeur.pdf.etudiants', compact(
            'etudiants',
            'coursData',
            'filiereData',
            'groupeData',
            'professeur',
            'qrCode'
        ))->setPaper('A4', 'portrait')->download('liste_etudiants_' . $coursData->nom . '.pdf');
    }
}
