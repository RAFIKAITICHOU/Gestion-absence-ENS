<?php

namespace App\Http\Controllers;

use App\Models\CoursSession;
use App\Models\Cours;
use App\Models\Salle;
use App\Models\Groupe;
use App\Models\Professeur;
use App\Models\JourInactif;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CoursSessionController extends Controller
{
    public function index()
    {

        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        // if (!Auth::user()->hasRole('administrateur')) {
        //     return redirect('/')->with('error', 'Accès refusé.');
        // }

        $groupes = Groupe::all();
        $professeurs = Professeur::with('user')->get();
        $seances = CoursSession::with(['cours', 'salle', 'groupe', 'professeur'])->get();
        $menu = 'adminMenu';

        return view('GestionEDT', compact('groupes', 'professeurs', 'seances', 'menu'));
    }

    public function store(Request $request)
    {

        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $request->validate([
            'nom_cours' => 'required|string',
            'semestre' => 'required|string',
            'nom_salle' => 'required|string',
            'id_professeur' => 'required',
            'groupe_id' => 'required|exists:groupes,id',
            'date' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required|after:heure_debut',
            'repeat_weeks' => 'nullable|integer|min:0|max:20',
        ]);

        $cours = Cours::firstOrCreate([
            'nom' => $request->nom_cours,
            'semestre' => $request->semestre
        ]);

        $salle = Salle::firstOrCreate(
            ['nom' => $request->nom_salle],
            ['projecteurs' => true, 'equipements' => '']
        );

        $repeatWeeks = (int) $request->input('repeat_weeks', 0);
        $baseDate = Carbon::parse($request->date);
        $duplicated = 0;
        $skipped = 0;

        $dates = collect(range(0, $repeatWeeks))->map(fn($i) => $baseDate->copy()->addWeeks($i));

        if ($request->id_professeur === 'all') {
            foreach (Professeur::all() as $prof) {
                foreach ($dates as $date) {
                    if ($this->estDateInvalide($date)) {
                        $skipped++;
                        continue;
                    }

                    $exists = $this->checkDuplication($date, $request, $salle->id);
                    if ($exists) {
                        $skipped++;
                        continue;
                    }

                    CoursSession::create([
                        'id_cours' => $cours->id,
                        'id_salle' => $salle->id,
                        'id_professeur' => $prof->id,
                        'groupe_id' => $request->groupe_id,
                        'date' => $date->format('Y-m-d'),
                        'heure_debut' => $request->heure_debut,
                        'heure_fin' => $request->heure_fin,
                    ]);

                    $duplicated++;
                }
            }

            return redirect()->back()->with('success', "$duplicated séance(s) ajoutée(s) pour tous les professeurs. $skipped ignorée(s) (doublons ou jours inactifs).");
        }

        if (!Professeur::find($request->id_professeur)) {
            return redirect()->back()->withErrors(['id_professeur' => 'Professeur introuvable.']);
        }

        foreach ($dates as $date) {
            if ($this->estDateInvalide($date)) {
                $skipped++;
                continue;
            }

            if ($this->checkDuplication($date, $request, $salle->id)) {
                $skipped++;
                continue;
            }

            CoursSession::create([
                'id_cours' => $cours->id,
                'id_salle' => $salle->id,
                'id_professeur' => $request->id_professeur,
                'groupe_id' => $request->groupe_id,
                'date' => $date->format('Y-m-d'),
                'heure_debut' => $request->heure_debut,
                'heure_fin' => $request->heure_fin,
            ]);

            $duplicated++;
        }

        return redirect()->back()->with('success', "$duplicated séance(s) ajoutée(s). $skipped ignorée(s).");
    }

    private function checkDuplication($date, $request, $salleId)
    {

        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        return CoursSession::where('date', $date->format('Y-m-d'))
            ->where('heure_debut', $request->heure_debut)
            ->where('heure_fin', $request->heure_fin)
            ->where('id_salle', $salleId)
            ->where('groupe_id', $request->groupe_id)
            ->exists();
    }

    public function update(Request $request, $id)
    {

        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $request->validate([
            'nom_cours' => 'required|string|max:255',
            'semestre' => 'required|string|max:10',
            'id_salle' => 'required|exists:salles,id',
            'id_professeur' => 'required|exists:professeurs,id',
            'groupe_id' => 'required|exists:groupes,id',
            'date' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required|after:heure_debut',
            'repeat_weeks' => 'nullable|integer|min:0|max:20',
        ]);

        $cours = Cours::firstOrCreate(
            ['nom' => $request->nom_cours],
            ['semestre' => $request->semestre]
        );

        $seance = CoursSession::findOrFail($id);
        $seance->update([
            'id_cours' => $cours->id,
            'id_salle' => $request->id_salle,
            'id_professeur' => $request->id_professeur,
            'groupe_id' => $request->groupe_id,
            'date' => $request->date,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
        ]);

        $repeatWeeks = (int) $request->repeat_weeks;
        $duplicated = 0;
        $skipped = 0;
        $baseDate = Carbon::parse($request->date);

        for ($i = 1; $i <= $repeatWeeks; $i++) {
            $newDate = $baseDate->copy()->addWeeks($i);

            if ($this->checkDuplication($newDate, $request, $request->id_salle)) {
                $skipped++;
                continue;
            }

            CoursSession::create([
                'id_cours' => $cours->id,
                'id_salle' => $request->id_salle,
                'id_professeur' => $request->id_professeur,
                'groupe_id' => $request->groupe_id,
                'date' => $newDate->format('Y-m-d'),
                'heure_debut' => $request->heure_debut,
                'heure_fin' => $request->heure_fin,
            ]);

            $duplicated++;
        }

        $message = "Séance mise à jour avec succès.";
        if ($repeatWeeks > 0) {
            $message .= " $duplicated ajout(s), $skipped ignoré(s).";
        }

        return redirect()->route('cours-sessions.index')->with('success', $message);
    }

    // public function api()
    // {
    //     $seances = CoursSession::with(['cours', 'salle', 'groupe.filiere', 'professeur.user'])->get();

    //     return response()->json($this->formatEvents($seances));
    // }
    public function api()
    {
        $seances = CoursSession::with(['cours', 'salle', 'groupe.filiere', 'professeur.user'])->get();

        return response()->json($seances->map(function ($s) {
            return [
                'id' => $s->id,
                'title' => $s->cours->nom,
                'start' => $s->date . 'T' . $s->heure_debut,
                'end' => $s->date . 'T' . $s->heure_fin,
                'classNames' => ['event-prof'],
                'extendedProps' => [
                    'professeur' => $s->professeur->user->name . ' ' . ($s->professeur->user->prenom ?? ''),
                    'salle' => $s->salle->nom,
                    'groupe' => $s->groupe->nom_groupe,
                    'filiere' => $s->groupe->filiere->nom_filiere ?? '-',
                    'heure_debut' => $s->heure_debut,
                    'heure_fin' => $s->heure_fin,
                ],

            ];
        }));
    }

    public function byProfesseur($id)
    {

        $sessions = CoursSession::where('id_professeur', $id)
            ->with(['cours', 'salle', 'groupe.filiere', 'professeur.user'])
            ->get();

        return response()->json($this->formatEvents($sessions));
    }

    public function destroy($id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        CoursSession::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Séance supprimée avec succès.'
        ]);
    }

    public function edit($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $seance = CoursSession::findOrFail($id);
        $groupes = Groupe::all();
        $professeurs = Professeur::with('user')->get();
        $salles = Salle::all();

        return view('cours-sessions.edit', compact('seance', 'groupes', 'professeurs', 'salles'));
    }

    private function estDateInvalide($date)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $date = Carbon::parse($date);
        $jour = $date->dayOfWeek;
        $key = $date->format('m-d');

        $joursFeriesFixes = [
            '01-01',
            '01-11',
            '01-14',
            '05-01',
            '07-30',
            '08-14',
            '08-20',
            '08-21',
            '11-06',
            '11-18',
        ];

        if ($jour === 0 || in_array($key, $joursFeriesFixes)) {
            return true;
        }

        foreach (JourInactif::all() as $vacance) {
            $debut = Carbon::parse($vacance->date_debut);
            $fin = $vacance->date_fin ? Carbon::parse($vacance->date_fin) : $debut;
            if ($date->between($debut, $fin)) return true;
        }

        return false;
    }


    public function exportPdf(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = CoursSession::with(['cours', 'salle', 'groupe.filiere', 'professeur.user']);

        // Rôle : Professeur
        if ($user->hasRole('professeur')) {
            $professeur = $user->professeur;

            if (!$professeur) {
                abort(403, 'Professeur non trouvé.');
            }

            $query->where('id_professeur', $professeur->id);
            $groupe = Groupe::find($request->groupe_id); // peut être null si non spécifié
            $professeur_id = $professeur->id;
        }
        // Rôle : Étudiant
        elseif ($user->hasRole('etudiant')) {
            $etudiant = $user->etudiant;

            if (!$etudiant) {
                abort(403, 'Étudiant non trouvé.');
            }

            $query->where('groupe_id', $etudiant->groupe_id);
            $groupe = $etudiant->groupe;
            $professeur_id = null;
        }
        // Rôle : Administrateur
        elseif ($user->hasRole('administrateur')) {
            // Filtrage optionnel par groupe ou professeur
            if ($request->filled('groupe_id')) {
                $query->where('groupe_id', $request->groupe_id);
                $groupe = Groupe::find($request->groupe_id);
            } else {
                $groupe = null;
            }

            if ($request->filled('professeur_id')) {
                $query->where('id_professeur', $request->professeur_id);
            }

            $professeur_id = $request->professeur_id ?? null;
        }
        // Sinon, refus
        else {
            abort(403, 'Accès non autorisé à cette fonctionnalité.');
        }

        $seances = $query->orderBy('date')->get();

        // Jours inactifs + jours fériés
        $annee = now()->year;
        $joursFeries = collect([
            ['titre' => 'Nouvel An', 'date_debut' => "$annee-01-01", 'date_fin' => null],
            ['titre' => 'Manifeste de l’Indépendance', 'date_debut' => "$annee-01-11", 'date_fin' => null],
            ['titre' => 'Nouvel An Amazigh', 'date_debut' => "$annee-01-14", 'date_fin' => null],
            ['titre' => 'Fête du Travail', 'date_debut' => "$annee-05-01", 'date_fin' => null],
            ['titre' => 'Fête du Trône', 'date_debut' => "$annee-07-30", 'date_fin' => null],
            ['titre' => 'Oued Ed-Dahab', 'date_debut' => "$annee-08-14", 'date_fin' => null],
            ['titre' => 'Révolution du Roi et du Peuple', 'date_debut' => "$annee-08-20", 'date_fin' => null],
            ['titre' => 'Fête de la Jeunesse', 'date_debut' => "$annee-08-21", 'date_fin' => null],
            ['titre' => 'Marche Verte', 'date_debut' => "$annee-11-06", 'date_fin' => null],
            ['titre' => 'Fête de l’Indépendance', 'date_debut' => "$annee-11-18", 'date_fin' => null],
        ]);
        $joursInactifs = JourInactif::all();
        $tousLesJours = $joursInactifs->concat($joursFeries)->map(fn($j) => (object) $j);

        $seancesGrouped = $seances->groupBy(function ($s) {
            $start = \Carbon\Carbon::parse($s->date)->startOfWeek();
            $end = \Carbon\Carbon::parse($s->date)->endOfWeek();
            return $start->format('d/m/Y') . ' à ' . $end->format('d/m/Y');
        });

        return Pdf::loadView('cours-sessions.export-pdf', [
            'seancesGrouped' => $seancesGrouped,
            'professeur' => ($user->hasRole('professeur')) ? $user->professeur : null,
            'groupe' => ($user->hasRole('etudiant')) ? $user->etudiant->groupe : $groupe,
            'joursInactifs' => $tousLesJours,
        ])
            ->setPaper('A4', 'landscape')
            ->download('emploi_du_temps.pdf');
    }



    public function sessionsProfesseurConnecte()
    {
        $prof = Auth::user()->professeur;
        if (!$prof) return response()->json([]);

        $sessions = CoursSession::where('id_professeur', $prof->id)
            ->with(['cours', 'salle', 'groupe.filiere', 'professeur.user'])
            ->get();

        return response()->json($this->formatEvents($sessions));
    }

    public function sessionsEtudiantConnecte()
    {
        $etudiant = Auth::user()->etudiant;

        if (!$etudiant || !$etudiant->groupe_id) return response()->json([]);


        $sessions = CoursSession::where('groupe_id', $etudiant->groupe_id)
            ->with(['cours', 'salle', 'groupe.filiere', 'professeur.user'])
            ->get();

        return response()->json($this->formatEvents($sessions));
    }

    private function formatEvents($sessions)
    {
        return $sessions->map(function ($s) {
            return [
                'id' => $s->id,
                'title' => $s->cours->nom,
                'start' => $s->date . 'T' . $s->heure_debut,
                'end' => $s->date . 'T' . $s->heure_fin,
                'classNames' => ['event-cours'],
                'extendedProps' => [
                    'professeur' => optional($s->professeur->user)->prenom . ' ' . optional($s->professeur->user)->name,
                    'salle' => optional($s->salle)->nom,
                    'groupe' => optional($s->groupe)->nom_groupe,
                    'filiere' => optional($s->groupe->filiere)->nom_filiere,
                    'heure_debut' => $s->heure_debut,
                    'heure_fin' => $s->heure_fin,
                ]
            ];
        });
    }


    public function edtProfesseur()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('professeur')) {
            abort(403, 'Accès interdit.');
        }
        return view('emplois.emploi', [
            'menu' => 'profMenu',
            'type' => 'professeur',
            'prof' => Auth::user()->professeur
        ]);
    }

    public function edtEtudiant()
    {

        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('etudiant')) {
            abort(403, 'Accès interdit.');
        }
        return view('emplois.emploi', [
            'menu' => 'etudiantMenu',
            'type' => 'etudiant',
            'etudiant' => Auth::user()->etudiant
        ]);
    }

    public function monEmploi()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('professeur') || !$user->hasRole('etudiant')) {
            abort(403, 'Accès interdit.');
        }
        /** @var \App\Models\User $user */
        $type = auth()->user()->getRoleNames()->first();

        $groupes = Groupe::all();
        $professeurs = Professeur::with('user')->get();
        $seances = CoursSession::with(['cours', 'salle', 'groupe', 'professeur'])->get();

        $menu = match ($type) {
            'professeur' => 'profMenu',
            'etudiant'   => 'etudiantMenu',
            'admin'      => 'adminMenu',
            default      => 'dashboard',
        };

        return view('emplois.emploi', compact('groupes', 'professeurs', 'seances', 'menu', 'type'));
    }

//################ AI counter

public function updateNbStudent(Request $request)
{
    $request->validate([
        'session_id' => 'required|integer|exists:cours_sessions,id',
        'nom_salle' => 'required|string',
        'ai_detect' => 'nullable|numeric',
    ]);

    // Find the session by ID
    $session = CoursSession::find($request->session_id);
    // dd($session);
    // Check if the room name matches the room assigned to the session
    $room = Salle::where('id', $session->id_salle)
                ->where('nom', $request->nom_salle)
                ->first();

    if (!$room) {
        return response()->json(['error' => 'Room name does not match session\'s room.'], 400);
    }

    // Update the ai_detect value
    $session->ai_detect = $request->ai_detect;
    $session->save();

    return response()->json(['message' => 'AI detect value updated successfully']);
}


public function getTodayNextSessions(Request $request)
{
    $request->validate([
        'nom_salle' => 'required|string',
    ]);

    // Find the room
    $room = Salle::where('nom', $request->nom_salle)->first();

    if (!$room) {
        return response()->json(['error' => 'Room not found'], 404);
    }

    // Get today's date
    $today = Carbon::today()->toDateString();
    $now = Carbon::now()->format('H:i:s');
    // Fetch sessions for today in the given room
    // $sessions = CoursSession::where('id_salle', $room->id)
    //             ->whereDate('date', $today)
    //             ->orderBy('heure_debut', 'asc')
    //             ->get();

    $sessions = CoursSession::where('id_salle', $room->id)
    ->where('heure_debut', '<=',  $now )
    ->where('heure_fin', '>=',  $now )
     ->orderBy('heure_debut', 'asc')
    ->get();

       $currentSession = $sessions->last(function ($session) use ($now) {
        return $session->heure_debut <= $now;
    });

    if ($currentSession) {
        return response()->json($currentSession);
    }

    return response()->json(['message' => 'No current session found'], 404);
}


}
