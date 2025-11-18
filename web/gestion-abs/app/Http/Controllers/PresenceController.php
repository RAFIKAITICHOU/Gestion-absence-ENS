<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Filiere;
use App\Models\Groupe;
use App\Models\Etudiant;
use App\Models\CoursSession;
use App\Models\Presence;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Notifications\EtudiantAbsentNotification;


class PresenceController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $filieres = Filiere::all();
        $groupes = [];
        $etudiants = collect();

        if ($request->filled('filiere_id')) {
            $groupes = Groupe::where('id_filiere', $request->filiere_id)->get();
        }

        if ($request->filled('groupe_id')) {
            $etudiants = Etudiant::with('user')
                ->where('groupe_id', $request->groupe_id)
                ->get();

            $etudiants = $etudiants->map(function ($etudiant) use ($request) {
                $absences = Presence::with(['session.cours', 'session.salle'])
                    ->where('id_etudiant', $etudiant->id)
                    ->where('etat', 0)
                    ->get();

                if ($request->filled('date')) {
                    $absences = $absences->filter(function ($p) use ($request) {
                        return optional($p->session)->date === $request->date;
                    });
                }

                // Si on filtre par absences non justifiées
                if ($request->filled('non_justifiees')) {
                    $absences = $absences->filter(fn($p) => is_null($p->justification));
                }

                $etudiant->filteredPresences = $absences;

                return $etudiant;
            });

            if ($request->filled('just_absents')) {
                $etudiants = $etudiants->filter(fn($e) => $e->filteredPresences->isNotEmpty())->values();
            }
        }

        return view('presences.presences', [
            'filieres' => $filieres,
            'groupes' => $groupes,
            'etudiants' => $etudiants,
            'title' => 'Dashboard Admin',
            'menu' => 'adminMenu'
        ]);
    }


    // public function justifier($id)
    // {

    //     /** @var \App\Models\User $user */

    //     $user = Auth::user();

    //     if (!$user->hasRole('administrateur')) {
    //         abort(403, 'Accès interdit.');
    //     }
    //     $presence = Presence::findOrFail($id);

    //     if ($presence->etat == 0) {
    //         $presence->justification = 'Justifiée par l\'administrateur';
    //         $presence->save();
    //     }

    //     return back()->with('success', 'Absence justifiée avec succès.');
    // }

    // public function nonJustifier($id)
    // {

    //     /** @var \App\Models\User $user */

    //     $user = Auth::user();

    //     if (!$user->hasRole('administrateur')) {
    //         abort(403, 'Accès interdit.');
    //     }
    //     $presence = Presence::findOrFail($id);

    //     if ($presence->etat == 0) {
    //         $presence->justification = null;
    //         $presence->save();
    //     }

    //     return back()->with('success', 'Justification supprimée avec succès.');
    // }

    public function justifier($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $presence = Presence::findOrFail($id);

        if ($presence->etat == 0) {
            $presence->justification = 'Justifiée par l\'administrateur';
            $presence->save();
        }

        return back()->with('success', 'Absence justifiée avec succès.');
    }

    public function justifierAvecFichier(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $request->validate([
            'justification_text' => 'required|string|max:500',
            'justification_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120' // 5MB max
        ]);

        $presence = Presence::findOrFail($id);

        if ($presence->etat == 0) {
            $presence->justification = $request->justification_text;

            // Gérer l'upload du fichier
            if ($request->hasFile('justification_file')) {
                // Supprimer l'ancien fichier s'il existe
                if ($presence->justificatif_fichier && Storage::disk('public')->exists($presence->justificatif_fichier)) {
                    Storage::disk('public')->delete($presence->justificatif_fichier);
                }

                // Générer un nom unique pour le fichier
                $fileName = 'justifications/' . \Illuminate\Support\Str::uuid() . '.' . $request->file('justification_file')->getClientOriginalExtension();

                // Stocker le fichier
                $filePath = $request->file('justification_file')->storeAs('', $fileName, 'public');
                $presence->justificatif_fichier = $filePath;
            }

            $presence->save();
        }

        return back()->with('success', 'Absence justifiée avec fichier joint avec succès.');
    }

    public function nonJustifier($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $presence = Presence::findOrFail($id);

        if ($presence->etat == 0) {
            // Supprimer le fichier s'il existe
            if ($presence->justificatif_fichier && Storage::disk('public')->exists($presence->justificatif_fichier)) {
                Storage::disk('public')->delete($presence->justificatif_fichier);
            }

            $presence->justification = null;
            $presence->justificatif_fichier = null;
            $presence->save();
        }

        return back()->with('success', 'Justification supprimée avec succès.');
    }

    public function voirFichierJustification($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $presence = Presence::findOrFail($id);

        if (!$presence->justificatif_fichier || !Storage::disk('public')->exists($presence->justificatif_fichier)) {
            abort(404, 'Fichier de justification non trouvé.');
        }

        return Storage::disk('public')->response($presence->justificatif_fichier);
    }

    public function telechargerFichierJustification($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $presence = Presence::findOrFail($id);

        if (!$presence->justificatif_fichier || !Storage::disk('public')->exists($presence->justificatif_fichier)) {
            abort(404, 'Fichier de justification non trouvé.');
        }

        $originalName = 'justification_' . $presence->id . '.' . pathinfo($presence->justificatif_fichier, PATHINFO_EXTENSION);

        return Storage::disk('public')->download($presence->justificatif_fichier, $originalName);
    }
    public function voirFichierJustificationProf($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('professeur') || !$user->professeur) {
            abort(403, 'Accès interdit.');
        }

        $presence = Presence::with(['session.professeur'])->findOrFail($id);

        // Vérifier que le professeur a accès à cette séance
        if ($presence->session->id_professeur !== $user->professeur->id) {
            abort(403, 'Vous n\'êtes pas autorisé à voir ce fichier.');
        }

        if (!$presence->justificatif_fichier || !Storage::disk('public')->exists($presence->justificatif_fichier)) {
            abort(404, 'Fichier de justification non trouvé.');
        }

        return Storage::disk('public')->response($presence->justificatif_fichier);
    }

    public function telechargerFichierJustificationProf($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('professeur') || !$user->professeur) {
            abort(403, 'Accès interdit.');
        }

        $presence = Presence::with(['session.professeur', 'etudiant.user'])->findOrFail($id);

        // Vérifier que le professeur a accès à cette séance
        if ($presence->session->id_professeur !== $user->professeur->id) {
            abort(403, 'Vous n\'êtes pas autorisé à télécharger ce fichier.');
        }

        if (!$presence->justificatif_fichier || !Storage::disk('public')->exists($presence->justificatif_fichier)) {
            abort(404, 'Fichier de justification non trouvé.');
        }

        $etudiantNom = $presence->etudiant->user->prenom . '_' . $presence->etudiant->user->name;
        $originalName = 'justification_' . $etudiantNom . '_' . $presence->id . '.' . pathinfo($presence->justificatif_fichier, PATHINFO_EXTENSION);

        return Storage::disk('public')->download($presence->justificatif_fichier, $originalName);
    }

    /////////////////////////////////////////////////////
    // public function enregistrer(Request $request)
    // {
    //     /** @var \App\Models\User $user */
    //     $user = Auth::user();

    //     if (!$user->hasRole('professeur')) {
    //         abort(403, 'Accès interdit.');
    //     }

    //     date_default_timezone_set('Africa/Casablanca');

    //     $seance_id = $request->input('seance_id');
    //     $seance = CoursSession::findOrFail($seance_id);

    //     $heure_debut = Carbon::createFromFormat('Y-m-d H:i', $seance->date . ' ' . substr($seance->heure_debut, 0, 5));
    //     $now = Carbon::now();

    //     if ($now->lt($heure_debut)) {
    //         return redirect()->back()->with('error', "Vous ne pouvez pas saisir les présences avant le début de la séance.");
    //     }

    //     foreach ($request->input('absences', []) as $etudiant_id => $data) {
    //         $etat = intval($data['etat'] ?? 1); // 0 ou 1
    //         $remarque = trim($data['remarque'] ?? '');
    //         $bonus = $data['bonus'] ?? 0;
    //         $justification = trim($data['justification'] ?? '');

    //         $presence = Presence::where('id_etudiant', $etudiant_id)
    //             ->where('id_session', $seance_id)
    //             ->first();

    //         if ($presence) {
    //             //   Mettre à jour même si état == 1 => 0 autorisé
    //             $presence->etat = $etat;
    //             $presence->remarque = $remarque;
    //             $presence->bonus = $bonus;
    //             $presence->justification = $etat === 0 ? $justification : null;
    //             $presence->save();
    //         } else {
    //             //   Créer une nouvelle ligne
    //             Presence::create([
    //                 'id_etudiant' => $etudiant_id,
    //                 'id_session' => $seance_id,
    //                 'etat' => $etat,
    //                 'remarque' => $remarque,
    //                 'bonus' => $bonus,
    //                 'justification' => $etat === 0 ? $justification : null,
    //             ]);
    //         }
    //     }

    //     return redirect()->back()->with('success', 'Présences enregistrées avec succès.');
    // }






    ////////////////// 6 juil 2025
    // public function enregistrer(Request $request)
    // {
    //     /** @var \App\Models\User $user */
    //     $user = Auth::user();

    //     if (!$user->hasRole('professeur')) {
    //         abort(403, 'Accès interdit.');
    //     }

    //     date_default_timezone_set('Africa/Casablanca');

    //     $seance_id = $request->input('seance_id');
    //     $seance = CoursSession::findOrFail($seance_id);

    //     $heure_debut = Carbon::createFromFormat('Y-m-d H:i', $seance->date . ' ' . substr($seance->heure_debut, 0, 5));
    //     $now = Carbon::now();

    //     if ($now->lt($heure_debut)) {
    //         return redirect()->back()->with('error', "Vous ne pouvez pas saisir les présences avant le début de la séance.");
    //     }

    //     foreach ($request->input('absences', []) as $etudiant_id => $data) {
    //         $etat = intval($data['etat'] ?? 1); // 0 ou 1
    //         $remarque = trim($data['remarque'] ?? '');
    //         $bonus = $data['bonus'] ?? 0;
    //         $justification = trim($data['justification'] ?? '');
    //         $retard = isset($data['retard']) ? intval($data['retard']) : 0;

    //         $presence = Presence::where('id_etudiant', $etudiant_id)
    //             ->where('id_session', $seance_id)
    //             ->first();

    //         if ($presence) {
    //             $presence->etat = $etat;
    //             $presence->remarque = $remarque;
    //             $presence->bonus = $bonus;
    //             $presence->justification = $etat === 0 ? $justification : null;
    //             $presence->retard = $retard;
    //             $presence->save();
    //         } else {
    //             Presence::create([
    //                 'id_etudiant' => $etudiant_id,
    //                 'id_session' => $seance_id,
    //                 'etat' => $etat,
    //                 'remarque' => $remarque,
    //                 'bonus' => $bonus,
    //                 'justification' => $etat === 0 ? $justification : null,
    //                 'retard' => $retard
    //             ]);
    //         }
    //     }

    //     return redirect()->back()->with('success', 'Présences enregistrées avec succès.');
    // }










    // rafik le 27 7 2025 sat3ml hadi li lta7t 7it khadmaaaa 

    // public function enregistrer(Request $request)
    // {
    //     /** @var \App\Models\User $user */
    //     $user = Auth::user();

    //     if (!$user->hasRole('professeur')) {
    //         abort(403, 'Accès interdit.');
    //     }

    //     date_default_timezone_set('Africa/Casablanca');

    //     $seance_id = $request->input('seance_id');
    //     $seance = CoursSession::findOrFail($seance_id);

    //     $heure_debut = Carbon::createFromFormat('Y-m-d H:i', $seance->date . ' ' . substr($seance->heure_debut, 0, 5));
    //     $now = Carbon::now();

    //     if ($now->lt($heure_debut)) {
    //         return redirect()->back()->with('error', "Vous ne pouvez pas saisir les présences avant le début de la séance.");
    //     }

    //     foreach ($request->input('absences', []) as $etudiant_id => $data) {
    //         $etat = intval($data['etat'] ?? 1); // 0 ou 1
    //         $remarque = trim($data['remarque'] ?? '');
    //         $bonus = $data['bonus'] ?? 0;
    //         $retard = isset($data['retard']) ? intval($data['retard']) : 0;

    //         $presence = Presence::where('id_etudiant', $etudiant_id)
    //             ->where('id_session', $seance_id)
    //             ->first();

    //         if ($presence) {
    //             // Ne modifier la justification que si l'étudiant devient "présent"
    //             if ($presence->etat !== $etat && $etat === 1) {
    //                 $presence->justification = null;
    //             }

    //             $presence->etat = $etat;
    //             $presence->remarque = $remarque;
    //             $presence->bonus = $bonus;
    //             $presence->retard = $retard;
    //             $presence->save();
    //         } else {
    //             Presence::create([
    //                 'id_etudiant' => $etudiant_id,
    //                 'id_session' => $seance_id,
    //                 'etat' => $etat,
    //                 'remarque' => $remarque,
    //                 'bonus' => $bonus,
    //                 'justification' => $etat === 0 ? null : null, // vide au départ, l'admin le remplit
    //                 'retard' => $retard
    //             ]);
    //         }
    //     }

    //     return redirect()->back()->with('success', 'Présences enregistrées avec succès.');
    // }


    // hadi d notification:

    public function enregistrer(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('professeur')) {
            abort(403, 'Accès interdit.');
        }

        date_default_timezone_set('Africa/Casablanca');

        $seance_id = $request->input('seance_id');
        $seance = CoursSession::with('cours')->findOrFail($seance_id);
        $now = Carbon::now();
        $heure_debut = Carbon::createFromFormat('Y-m-d H:i', $seance->date . ' ' . substr($seance->heure_debut, 0, 5));

        if ($now->lt($heure_debut)) {
            return redirect()->back()->with('error', "Vous ne pouvez pas saisir les présences avant le début de la séance.");
        }

        foreach ($request->input('absences', []) as $etudiant_id => $data) {
            $etat = intval($data['etat'] ?? 1);
            $remarque = trim($data['remarque'] ?? '');
            $bonus = $data['bonus'] ?? 0;
            $retard = isset($data['retard']) ? intval($data['retard']) : 0;

            $presence = Presence::where('id_etudiant', $etudiant_id)
                ->where('id_session', $seance_id)
                ->first();

            if ($presence) {
                if ($presence->etat !== $etat && $etat === 1) {
                    $presence->justification = null;
                }

                $presence->etat = $etat;
                $presence->remarque = $remarque;
                $presence->bonus = $bonus;
                $presence->retard = $retard;
                $presence->save();
            } else {
                $presence = Presence::create([
                    'id_etudiant' => $etudiant_id,
                    'id_session' => $seance_id,
                    'etat' => $etat,
                    'remarque' => $remarque,
                    'bonus' => $bonus,
                    'justification' => $etat === 0 ? null : null,
                    'retard' => $retard
                ]);
            }

            // 🔔 Envoi de notification uniquement si absent
            if ($etat === 0) {
                $etudiant = \App\Models\Etudiant::with('user')->find($etudiant_id);
                if ($etudiant && $etudiant->user) {
                    $etudiant->user->notify(new EtudiantAbsentNotification($seance));
                }
            }
        }

        return redirect()->back()->with('success', 'Présences enregistrées avec succès.');
    }








    ////////////////////////////////////////////////////////////////////////////
    // public function enregistrer(Request $request)
    // {
    //     $seance_id = $request->input('seance_id');

    //     foreach ($request->input('absences') as $etudiant_id => $data) {
    //         $etat = intval($data['etat'] ?? 1);
    //         $remarque = trim($data['remarque'] ?? '');
    //         $bonus = $data['bonus'] ?? null;
    //         $justification = trim($data['justification'] ?? '');

    //         if ($etat === 0 || $remarque || $bonus || $justification) {
    //             Presence::updateOrCreate(
    //                 ['id_etudiant' => $etudiant_id, 'id_session' => $seance_id],
    //                 [
    //                     'etat' => $etat,
    //                     'remarque' => $remarque,
    //                     'bonus' => $bonus ?? 0,
    //                     'justification' => $etat === 0 ? $justification : null,
    //                 ]
    //             );
    //         } else {
    //             Presence::where('id_etudiant', $etudiant_id)
    //                 ->where('id_session', $seance_id)
    //                 ->delete();
    //         }
    //     }

    //     return redirect()->back()->with('success', 'Présences enregistrées avec succès.');
    // }






    public function gerer($id)
    {
        date_default_timezone_set('Africa/Casablanca');
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('professeur') || !$user->professeur) {
            abort(403, 'Accès réservé aux professeurs.');
        }

        $seance = CoursSession::with([
            'groupe.etudiants.user',
            'cours',
            'salle',
            'professeur.user',
            'presences'
        ])->findOrFail($id);

        if ($seance->id_professeur !== $user->professeur->id) {
            abort(403, 'Vous n’êtes pas autorisé à gérer cette séance.');
        }

        $heure_debut = Carbon::createFromFormat('Y-m-d H:i', $seance->date . ' ' . substr($seance->heure_debut, 0, 5));
        $now = Carbon::now();

        $seanceFuture = $now->lt($heure_debut);

        return view('presences.gerer', compact('seance', 'seanceFuture'));
    }



    public function exportPdf(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $filieres = Filiere::all();
        $groupes = Groupe::where('id_filiere', $request->filiere_id)->get();

        $etudiants = Etudiant::with([
            'user',
            'presences' => function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('etat', 0);

                    if ($request->filled('non_justifiees')) {
                        $query->whereNull('justification');
                    }

                    if ($request->filled('date')) {
                        $query->whereHas('session', function ($q2) use ($request) {
                            $q2->where('date', $request->date);
                        });
                    }
                })
                    ->orWhere(function ($query) use ($request) {
                        $query->where('etat', 1)
                            ->whereNotNull('retard');

                        if ($request->filled('date')) {
                            $query->whereHas('session', function ($q2) use ($request) {
                                $q2->where('date', $request->date);
                            });
                        }
                    });
            },
            'presences.session.cours',
            'presences.session.salle'
        ])
            ->where('groupe_id', $request->groupe_id)
            ->get();


        // Appliquer les filtres sur les étudiants
        if ($request->filled('non_justifiees') || $request->filled('just_absents')) {
            $etudiants = $etudiants->filter(fn($etudiant) => $etudiant->presences->count() > 0)->values();
        }

        // Infos pour l'en-tête
        $filiere = $filieres->where('id', $request->filiere_id)->first()?->nom_filiere ?? 'Filiere';
        $groupe = $groupes->where('id', $request->groupe_id)->first()?->nom_groupe ?? 'Groupe';
        $dateExport = now()->format('Y-m-d');

        $filename = 'liste_absences_' . strtoupper(str_replace(' ', '_', $filiere)) . '_' . strtoupper(str_replace(' ', '_', $groupe)) . '_' . $dateExport . '.pdf';

        // Génération du QR Code
        $qrData = "Export des absences\n";
        $qrData .= "Filière : $filiere\n";
        $qrData .= "Groupe : $groupe\n";
        $qrData .= "Exporté par : $user->name  $user->prenom\n";
        if ($request->filled('date')) {
            $qrData .= "Date filtrée : " . $request->date . "\n";
        }
        if ($request->filled('non_justifiees')) {
            $filtrageText = 'Absences non justifiées uniquement';
        } elseif ($request->filled('just_absents')) {
            $filtrageText = 'Étudiants ayant au moins une absence';
        } else {
            $filtrageText = 'Toutes absences';
        }
        $qrData .= "Exporté le : " . now()->format('Y-m-d H:i');

        $qrCode = base64_encode(QrCode::format('png')->size(120)->generate($qrData));

        // Chargement de la vue PDF
        $pdf = PDF::loadView('presences.pdf', compact('filieres', 'groupes', 'etudiants', 'qrCode', 'filtrageText'));

        return $pdf->stream($filename);
    }


    public function exportSeancePDF($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $seance = CoursSession::with([
            'cours',
            'groupe.etudiants.user',
            'professeur.user',
            'presences'
        ])->findOrFail($id);

        if (!$user->hasRole('professeur') || $user->professeur->id !== $seance->id_professeur) {
            abort(403, 'Accès interdit.');
        }

        $totalEtudiants = $seance->groupe->etudiants->count();
        $presences = $seance->presences->keyBy('id_etudiant');

        $totalPresents = $seance->groupe->etudiants->filter(function ($etudiant) use ($presences) {
            return $presences[$etudiant->id]->etat ?? 1;
        })->count();

        $totalAbsents = $totalEtudiants - $totalPresents;

        $qrData = "Feuille de présence\n";
        $qrData .= "Séance ID : $seance->id\n";
        $qrData .= "Cours : " . $seance->cours->nom . "\n";
        $qrData .= "Groupe : " . $seance->groupe->nom_groupe . "\n";
        $qrData .= "Exporté par : $user->name  $user->prenom\n";
        $qrData .= "Date : " . now()->format('Y-m-d H:i');

        $qrCode = base64_encode(QrCode::format('png')->size(100)->generate($qrData));

        $pdf = PDF::loadView('presences.export', [
            'seance' => $seance,
            'totalEtudiants' => $totalEtudiants,
            'totalPresents' => $totalPresents,
            'totalAbsents' => $totalAbsents,
            'qrCode' => $qrCode
        ]);

        $filename = 'Presence_' . $seance->cours->nom . '_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($filename);
    }




    public function exportAbsencesEtudiant()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('etudiant') || !$user->etudiant) {
            abort(403, 'Accès réservé aux étudiants.');
        }

        $etudiant = $user->etudiant;

        $presences = Presence::with(['session.cours', 'session.salle'])
            ->where('id_etudiant', $etudiant->id)
            ->get();

        $qrData = "Liste des absences\n";
        $qrData .= "Nom : {$user->prenom} {$user->name}\n";
        $qrData .= "Groupe : " . ($etudiant->groupe->nom_groupe ?? '-') . "\n";
        $qrData .= "Exporté par : $user->name  $user->prenom\n";
        $qrData .= "Date : " . now()->format('Y-m-d H:i');

        $qrCode = base64_encode(QrCode::format('png')->size(100)->generate($qrData));

        $pdf = Pdf::loadView('etudiant.absences_pdf', [
            'etudiant' => $etudiant,
            'presences' => $presences,
            'qrCode' => $qrCode
        ]);

        $filename = 'Absences_' . $user->prenom . '_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }
    public function coursDuJourProf()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('professeur') || !$user->professeur) {
            abort(403, 'Accès interdit.');
        }

        $profId = $user->professeur->id;
        $aujourdHui = Carbon::now('Africa/Casablanca')->toDateString();

        $seance = CoursSession::with(['cours', 'groupe.filiere', 'groupe.etudiants', 'presences'])
            ->where('id_professeur', $profId)
            ->where('date', $aujourdHui)
            ->orderBy('heure_debut')
            // ->first();
            ->get();

        return view('prof.dashboard_cours_jour', [
            'seance' => $seance
        ]);
    }


    // public function presenceViaQRCode(Request $request)
    // {
    //     date_default_timezone_set('Africa/Casablanca');

    //     $cne = $request->input('cne');

    //     $etudiant = Etudiant::with(['groupe.filiere', 'user'])
    //         ->where('cne', $cne)
    //         ->first();

    //     if (!$etudiant) {
    //         return response()->json(['etat' => 'erreur', 'message' => "Étudiant n'existe pas"], 404);
    //     }

    //     $etudiant_id = $etudiant->id;
    //     $groupe_id = $etudiant->groupe_id;

    //     $currentDate = Carbon::now()->toDateString();
    //     $currentTime = Carbon::now()->toTimeString();

    //     $seances = CoursSession::where("groupe_id", $groupe_id)
    //         ->whereDate('date', $currentDate)
    //         ->get();

    //     if ($seances->isEmpty()) {
    //         return response()->json(['etat' => 'erreur', 'message' => 'Pas de cours aujourd’hui'], 404);
    //     }

    //     foreach ($seances as $seance) {
    //         $heure_debut = Carbon::parse($seance->heure_debut)->toTimeString();
    //         $heure_fin = Carbon::parse($seance->heure_fin)->toTimeString();

    //         if ($currentTime >= $heure_debut && $currentTime <= $heure_fin) {
    //             $seance_id = $seance->id;

    //             //   1. Marquer l'étudiant qui scanne comme présent (update si existe, sinon create)
    //             Presence::updateOrCreate(
    //                 ['id_etudiant' => $etudiant_id, 'id_session' => $seance_id],
    //                 ['etat' => 1]
    //             );

    //             //   2. Marquer les autres comme absents uniquement si AUCUNE présence n’existe déjà
    //             $etudiantsDuGroupe = Etudiant::where('groupe_id', $groupe_id)->get();

    //             foreach ($etudiantsDuGroupe as $autre) {
    //                 if ($autre->id !== $etudiant_id) {
    //                     $presenceExiste = Presence::where('id_etudiant', $autre->id)
    //                         ->where('id_session', $seance_id)
    //                         ->exists();

    //                     if (!$presenceExiste) {
    //                         Presence::create([
    //                             'id_etudiant' => $autre->id,
    //                             'id_session' => $seance_id,
    //                             'etat' => 0
    //                         ]);
    //                     }
    //                 }
    //             }

    //             return response()->json(['etat' => 'ok', 'message' => 'Présence enregistrée']);
    //         }
    //     }

    //     return response()->json(['etat' => 'erreur', 'message' => "Il n'y a pas de cours en ce moment"], 404);
    // }
    //version sans retarde
    // public function presenceViaQRCode(Request $request)
    // {
    //     date_default_timezone_set('Africa/Casablanca');

    //     $cne = $request->input('cne');

    //     $etudiant = Etudiant::with(['groupe.filiere', 'user'])
    //         ->where('cne', $cne)
    //         ->first();

    //     if (!$etudiant) {
    //         return response()->json(['etat' => 'erreur', 'message' => "Étudiant n'existe pas"], 404);
    //     }

    //     $etudiant_id = $etudiant->id;
    //     $groupe_id = $etudiant->groupe_id;

    //     $currentDate = Carbon::now()->toDateString();
    //     $now = Carbon::now();

    //     $seances = CoursSession::where("groupe_id", $groupe_id)
    //         ->whereDate('date', $currentDate)
    //         ->get();

    //     if ($seances->isEmpty()) {
    //         return response()->json(['etat' => 'erreur', 'message' => 'Pas de cours aujourd’hui'], 404);
    //     }

    //     foreach ($seances as $seance) {
    //         $heure_debut = Carbon::createFromFormat('H:i:s', $seance->heure_debut);
    //         $heure_debut_complet = Carbon::createFromFormat('Y-m-d H:i:s', $seance->date . ' ' . $heure_debut->format('H:i:s'));

    //         $interval_debut = $heure_debut_complet->copy()->subMinutes(15);
    //         $interval_fin = $heure_debut_complet; // jusqu'à l'heure exacte du début

    //         if ($now->between($interval_debut, $interval_fin)) {
    //             $seance_id = $seance->id;

    //             Presence::updateOrCreate(
    //                 ['id_etudiant' => $etudiant_id, 'id_session' => $seance_id],
    //                 ['etat' => 1]
    //             );

    //             $etudiantsDuGroupe = Etudiant::where('groupe_id', $groupe_id)->get();

    //             foreach ($etudiantsDuGroupe as $autre) {
    //                 if ($autre->id !== $etudiant_id) {
    //                     $presenceExiste = Presence::where('id_etudiant', $autre->id)
    //                         ->where('id_session', $seance_id)
    //                         ->exists();

    //                     if (!$presenceExiste) {
    //                         Presence::create([
    //                             'id_etudiant' => $autre->id,
    //                             'id_session' => $seance_id,
    //                             'etat' => 0
    //                         ]);
    //                     }
    //                 }
    //             }

    //             return response()->json(['etat' => 'ok', 'message' => 'Présence enregistrée']);
    //         }
    //     }

    //     return response()->json(['etat' => 'erreur', 'message' => "Vous pouvez scanner votre QR code uniquement 15 minutes avant le début du cours."], 403);
    // }
    //version avec retarde
    /////////////////////////////////
    // public function presenceViaQRCode(Request $request)
    // {
    //     date_default_timezone_set('Africa/Casablanca');

    //     $cne = $request->input('cne');

    //     $etudiant = Etudiant::with(['groupe.filiere', 'user'])
    //         ->where('cne', $cne)
    //         ->first();

    //     if (!$etudiant) {
    //         return response()->json(['etat' => 'erreur', 'message' => "Étudiant n'existe pas"], 404);
    //     }

    //     $etudiant_id = $etudiant->id;
    //     $groupe_id = $etudiant->groupe_id;

    //     $now = Carbon::now();
    //     $currentDate = $now->toDateString();

    //     $seances = CoursSession::where("groupe_id", $groupe_id)
    //         ->whereDate('date', $currentDate)
    //         ->get();

    //     if ($seances->isEmpty()) {
    //         return response()->json(['etat' => 'erreur', 'message' => 'Pas de cours aujourd’hui'], 404);
    //     }

    //     foreach ($seances as $seance) {
    //         $heure_debut = Carbon::parse($seance->heure_debut);
    //         $heure_fin = Carbon::parse($seance->heure_fin);

    //         $debut_complet = Carbon::createFromFormat('Y-m-d H:i:s', $seance->date . ' ' . $heure_debut->format('H:i:s'));
    //         $fin_complet = Carbon::createFromFormat('Y-m-d H:i:s', $seance->date . ' ' . $heure_fin->format('H:i:s'));

    //         $interval_debut = $debut_complet->copy()->subMinutes(15);

    //         if ($now->between($interval_debut, $fin_complet)) {
    //             $seance_id = $seance->id;

    //             //   Calcul du retard exact
    //             $retard = $now->gt($debut_complet) ? $debut_complet->diffInMinutes($now) : 0;

    //             //   Marquer l'étudiant comme présent et stocker le retard même s’il est 0
    //             Presence::updateOrCreate(
    //                 ['id_etudiant' => $etudiant_id, 'id_session' => $seance_id],
    //                 [
    //                     'etat' => 1,
    //                     'retard' => $retard > 0 ? $retard : 0
    //                 ]
    //             );

    //             //   Marquer les autres étudiants comme absents
    //             $etudiantsDuGroupe = Etudiant::where('groupe_id', $groupe_id)->get();

    //             foreach ($etudiantsDuGroupe as $autre) {
    //                 if ($autre->id !== $etudiant_id) {
    //                     $presenceExiste = Presence::where('id_etudiant', $autre->id)
    //                         ->where('id_session', $seance_id)
    //                         ->exists();

    //                     if (!$presenceExiste) {
    //                         Presence::create([
    //                             'id_etudiant' => $autre->id,
    //                             'id_session' => $seance_id,
    //                             'etat' => 0,
    //                             'retard' => null
    //                         ]);
    //                     }
    //                 }
    //             }

    //             //   Message personnalisé
    //             return response()->json([
    //                 'etat' => 'ok',
    //                 'message' => $retard > 5
    //                     ? "Présence enregistrée avec $retard minutes de retard."
    //                     : "Présence enregistrée à l’heure."
    //             ]);
    //         }
    //     }

    //     return response()->json([
    //         'etat' => 'erreur',
    //         'message' => "Il n'y a pas de cours en ce moment"
    //     ], 404);
    // }






    ////////////////////
    public function presenceViaQRCode(Request $request)
    {
        date_default_timezone_set('Africa/Casablanca');

        $cne = $request->input('cne');

        $etudiant = Etudiant::with(['groupe.filiere', 'user'])
            ->where('cne', $cne)
            ->first();

        if (!$etudiant) {
            return response()->json(['etat' => 'erreur', 'message' => "Étudiant n'existe pas"], 404);
        }

        $etudiant_id = $etudiant->id;
        $groupe_id = $etudiant->groupe_id;

        $now = Carbon::now();
        $currentDate = $now->toDateString();

        $seances = CoursSession::where("groupe_id", $groupe_id)
            ->whereDate('date', $currentDate)
            ->get();

        if ($seances->isEmpty()) {
            return response()->json(['etat' => 'erreur', 'message' => 'Pas de cours aujourd’hui'], 404);
        }

        foreach ($seances as $seance) {
            $heure_debut = Carbon::parse($seance->heure_debut);
            $heure_fin = Carbon::parse($seance->heure_fin);

            $debut_complet = Carbon::createFromFormat('Y-m-d H:i:s', $seance->date . ' ' . $heure_debut->format('H:i:s'));
            $fin_complet = Carbon::createFromFormat('Y-m-d H:i:s', $seance->date . ' ' . $heure_fin->format('H:i:s'));

            $interval_debut = $debut_complet->copy()->subMinutes(15); // Scan possible 15 min avant

            if ($now->between($interval_debut, $fin_complet)) {
                $seance_id = $seance->id;

                //   Calcul du retard
                $retard = $now->gt($debut_complet) ? $debut_complet->diffInMinutes($now) : 0;

                //   Si le retard est < 5 min → on ne l'enregistre pas (null)ou 0
                $retardFinal = ($retard >= 5) ? $retard : null;

                //   Enregistrer présence
                Presence::updateOrCreate(
                    ['id_etudiant' => $etudiant_id, 'id_session' => $seance_id],
                    [
                        'etat' => 1,
                        'retard' => $retardFinal
                    ]
                );

                //   Marquer les autres absents
                $etudiantsDuGroupe = Etudiant::where('groupe_id', $groupe_id)->get();

                foreach ($etudiantsDuGroupe as $autre) {
                    if ($autre->id !== $etudiant_id) {
                        $presenceExiste = Presence::where('id_etudiant', $autre->id)
                            ->where('id_session', $seance_id)
                            ->exists();

                        if (!$presenceExiste) {
                            Presence::create([
                                'id_etudiant' => $autre->id,
                                'id_session' => $seance_id,
                                'etat' => 0,
                                'retard' => null
                            ]);
                        }
                    }
                }

                //   Message personnalisé
                return response()->json([
                    'etat' => 'ok',
                    'message' => $retard >= 5
                        ? "Présence enregistrée avec $retard minutes de retard."
                        : "Présence enregistrée à l’heure."
                ]);
            }
        }

        return response()->json([
            'etat' => 'erreur',
            'message' => "Il n'y a pas de cours en ce moment"
        ], 404);
    }



    public function getCod(Request $request)
    {

        date_default_timezone_set('Africa/Casablanca');


        $etudiant = Etudiant::with(['groupe.filiere', 'user'])
            ->where('cne', "G1322110592045245")
            ->first();
        $etudiant_id = $etudiant->id;
        if (!$etudiant) {
            return response()->json(['message' => "etudiant n'existe pas"], 404);
        }

        $group_id = $etudiant->groupe_id;

        $currentDate = Carbon::now();
        $currentTime = Carbon::now()->toTimeString(); // Format: 'HH:mm:ss'


        $seances = CoursSession::with(['groupe.filiere'])
            ->where("groupe_id", $group_id)
            ->whereDate('date', $currentDate->toDateString())
            ->get();

        // dd($seance);

        if ($seances->isEmpty()) {
            return response()->json(['message' => 'Pas de cours'], 404);
        }

        foreach ($seances as $seance) {
            $heure_debut = Carbon::parse($seance->heure_debut)->toTimeString();
            $heure_fin = Carbon::parse($seance->heure_fin)->toTimeString();

            if ($currentTime >= $heure_debut && $currentTime <= $heure_fin) {

                $seance_id =  $seance->id;
                Presence::updateOrCreate(
                    ['id_etudiant' => $etudiant_id, 'id_session' => $seance_id],
                    [
                        'etat' => 1,
                        'remarque' => "",
                        'bonus' => 0,
                        'justification' =>  null,
                    ]
                );

                return response()->json(['message' => 'ok']);
            }
        }
        // $heure_debut = Carbon::createFromFormat('Y-m-d H:i', $seance->date . ' ' . substr($seance->heure_debut, 0, 5), 'Africa/Casablanca');
        // $heure_fin = Carbon::createFromFormat('Y-m-d H:i', $seance->date . ' ' . substr($seance->heure_fin, 0, 5), 'Africa/Casablanca');
        // $seance_id = $request->input('seance_id');

        return response()->json(['message' => "Il n'y a pas de cours en ce moment"], 404);

        // return response()->json([
        //     'message' => "ok",
        //     'group_id' => $etudiant->groupe_id,
        //     'nom' => $etudiant->user->name,
        //     'heure_debut'=>$heure_debut ,
        //     'heure_fin'=>$heure_fin



        // ]);

    }



    public function exportPdfAbsenceEtud($coursId, $filiereId, $groupeId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('professeur') || !$user->professeur) {
            abort(403, 'Accès réservé aux professeurs.');
        }

        $profId = $user->professeur->id;
        $groupe = Groupe::findOrFail($groupeId);

        // Vérifier que le professeur enseigne bien dans ce groupe
        $moduleIds = CoursSession::where('id_professeur', $profId)
            ->where('groupe_id', $groupeId)
            ->pluck('id_cours')
            ->unique();

        // Sécurité : ce cours appartient-il bien à ce prof ?
        if (!$moduleIds->contains($coursId)) {
            abort(403, 'Vous n\'êtes pas autorisé à exporter les absences de ce cours.');
        }

        $modules = \App\Models\Cours::whereIn('id', $moduleIds)->get();

        $etudiants = Etudiant::with([
            'user',
            'presences.session' => function ($query) use ($moduleIds) {
                $query->whereIn('id_cours', $moduleIds);
            }
        ])->where('groupe_id', $groupeId)->get();

        $texteQr = "Document généré pour vérification interne\n"
            . "Professeur : " . ($user->professeur->user->name ?? '-') . " " . ($user->professeur->user->prenom ?? '-') . "\n"
            . "Groupe : " . ($groupe->nom_groupe ?? '-') . "\n"
            . "Filière : " . (\App\Models\Filiere::find($filiereId)->nom_filiere ?? '-') . "\n"
            . "Téléchargé par : " . ($user->name ?? '-') . " " . ($user->prenom ?? '-') . "\n"
            . "Date : " . now()->format('d/m/Y à H:i');

        $qrCodeBase64 = base64_encode(
            QrCode::format('png')->size(120)->generate($texteQr)
        );

        $pdf = Pdf::loadView('professeur.pdf.absences_etudiants_modules', [
            'etudiants' => $etudiants,
            'modules' => $modules,
            'groupe' => $groupe,
            'groupeData' => $groupe,
            'professeur' => $user->professeur,
            'coursData' => \App\Models\Cours::find($coursId),
            'filiereData' => \App\Models\Filiere::find($filiereId),
            'qrCodeBase64' => $qrCodeBase64,
        ])
            ->setPaper('A4', 'landscape');

        return $pdf->download("Absences_Modules_Groupe_{$groupe->nom_groupe}.pdf");
    }
}
