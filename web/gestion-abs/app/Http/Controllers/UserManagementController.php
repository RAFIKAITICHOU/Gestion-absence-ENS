<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Professeur;
use App\Models\Etudiant;
use App\Models\Administrateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Groupe;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Pagination\LengthAwarePaginator;




class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $role = $user->getRoleNames()[0];

        if ($role != 'administrateur')
            return redirect()->route('dashboardadmin');

        $searchProf = $request->input('search_prof');
        $searchEtud = $request->input('search_etud');
        $searchAdmin = $request->input('search_admin');

        $filiereId = $request->input('filiere_id');
        $groupeId = $request->input('groupe_id');

        $professeurs = Professeur::with('user')
            ->when($searchProf, function ($query, $searchProf) {
                return $query->whereHas('user', function ($q) use ($searchProf) {
                    $q->where('name', 'like', "%{$searchProf}%")
                        ->orWhere('prenom', 'like', "%{$searchProf}%")
                        ->orWhere('email', 'like', "%{$searchProf}%");
                });
            })
            ->paginate(12, ['*'], 'professeurs_page');

        $filieres = \App\Models\Filiere::all();
        $groupes = \App\Models\Groupe::all();

        // Si une recherche est faite, on filtre les étudiants indépendamment des groupes
        if (!empty($searchEtud)) {
            $groupesAvecEtudiants = collect(); // vide, pas besoin
            $tousEtudiants = Etudiant::with(['user', 'groupe.filiere'])
                ->when($searchEtud, function ($query, $searchEtud) {
                    return $query->whereHas('user', function ($q) use ($searchEtud) {
                        $q->where('name', 'like', "%{$searchEtud}%")
                            ->orWhere('prenom', 'like', "%{$searchEtud}%")
                            ->orWhere('email', 'like', "%{$searchEtud}%")
                            ->orWhere('cne', 'like', "%{$searchEtud}%");
                    });
                })
                ->when($groupeId, fn($q) => $q->where('groupe_id', $groupeId))
                ->when($filiereId, fn($q) => $q->whereHas('groupe', fn($g) => $g->where('id_filiere', $filiereId)))
                ->get();

            // 📌
            $etudiantsSansGroupe = Etudiant::whereNull('groupe_id')
                ->with('user')
                ->when($searchEtud, function ($query, $searchEtud) {
                    return $query->whereHas('user', function ($q) use ($searchEtud) {
                        $q->where('name', 'like', "%{$searchEtud}%")
                            ->orWhere('prenom', 'like', "%{$searchEtud}%")
                            ->orWhere('email', 'like', "%{$searchEtud}%")
                            ->orWhere('cne', 'like', "%{$searchEtud}%");
                    });
                })
                ->get();
            // Regrouper les étudiants par groupe pour réutiliser la même vue
            $groupesParEtudiant = $tousEtudiants->groupBy(function ($etudiant) {
                return $etudiant->groupe->id ?? 0;
            });

            $etudiantsParGroupe = collect();
            foreach ($groupesParEtudiant as $idGroupe => $etudiants) {
                $groupe = $etudiants->first()->groupe ?? null;
                if ($groupe) {
                    $groupe->setRelation('etudiants', $etudiants);
                    $etudiantsParGroupe->push($groupe);
                }
            }

            $etudiantsParGroupe = new \Illuminate\Pagination\LengthAwarePaginator(
                $etudiantsParGroupe->forPage(LengthAwarePaginator::resolveCurrentPage('etudiants_page'), 1),
                $etudiantsParGroupe->count(),
                1,
                LengthAwarePaginator::resolveCurrentPage('etudiants_page'),
                ['pageName' => 'etudiants_page', 'path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            // Cas sans recherche, affichage par groupes
            $groupesAvecEtudiants = \App\Models\Groupe::with(['filiere', 'etudiants.user'])
                ->when($filiereId, fn($q) => $q->where('id_filiere', $filiereId))
                ->when($groupeId, fn($q) => $q->where('id', $groupeId))
                ->get()
                ->sortBy([
                    fn($a, $b) => strcmp($a->filiere->nom_filiere ?? '', $b->filiere->nom_filiere ?? ''),
                    fn($a, $b) => strcmp($a->nom_groupe, $b->nom_groupe)
                ])
                ->values();

            // Créer un faux "groupe" pour les étudiants sans groupe
            $etudiantsSansGroupe = Etudiant::whereNull('groupe_id')->with('user')->get();

            if ($etudiantsSansGroupe->isNotEmpty()) {
                $groupeVirtuel = new \App\Models\Groupe([
                    'nom_groupe' => 'Aucun',
                    'id_filiere' => null,
                ]);
                $groupeVirtuel->setRelation('filiere', null);
                $groupeVirtuel->setRelation('etudiants', $etudiantsSansGroupe);

                $groupesAvecEtudiants->push($groupeVirtuel);
            }

            $perPage = 1;
            $currentPage = LengthAwarePaginator::resolveCurrentPage('etudiants_page');
            $groupesPage = $groupesAvecEtudiants->slice(($currentPage - 1) * $perPage, $perPage)->values();

            $etudiantsParGroupe = new LengthAwarePaginator(
                $groupesPage,
                $groupesAvecEtudiants->count(),
                $perPage,
                $currentPage,
                ['pageName' => 'etudiants_page', 'path' => $request->url(), 'query' => $request->query()]
            );
        }

        // Génération des index
        $indexParEtudiant = [];
        $compteurs = [];
        foreach ($etudiantsParGroupe as $groupe) {
            foreach ($groupe->etudiants as $etudiant) {
                $nomGroupe = $groupe->nom_groupe ?? 'Autre';
                $compteurs[$nomGroupe] = ($compteurs[$nomGroupe] ?? 0) + 1;
                $indexParEtudiant[$etudiant->id] = $compteurs[$nomGroupe];
            }
        }

        $administrateurs = Administrateur::with('user')
            ->when($searchAdmin, function ($query, $searchAdmin) {
                return $query->whereHas('user', function ($q) use ($searchAdmin) {
                    $q->where('name', 'like', "%{$searchAdmin}%")
                        ->orWhere('prenom', 'like', "%{$searchAdmin}%")
                        ->orWhere('email', 'like', "%{$searchAdmin}%");
                });
            })
            ->paginate(7, ['*'], 'admins_page');

        $activeTab = $request->get('tab', 'professeurs');

        // Vue et titre
        $title = 'Dashboard Admin';
        $view = 'dashboardAdmin';
        $menu = 'adminMenu';

        if ($role == 'etudiant') {
            $title = 'Dashboard Etudiant';
            $view = 'dashboardEtudiant';
            $menu = 'etudiantMenu';
        } elseif ($role == 'professeur') {
            $title = 'Dashboard Professeur';
            $view = 'dashboardProf';
            $menu = 'profMenu';
        }

        return view('gestion_utilisateurs.index', compact(
            'professeurs',
            'etudiantsParGroupe',
            'etudiantsSansGroupe',
            'administrateurs',
            'activeTab',
            'filieres',
            'groupes',
            'request',
            'title',
            'menu',
            'indexParEtudiant'
        ));
    }





    // ============================================================================================
    // ============================================================================================ // ============================================================================================
    // ============================================================================================
    // ============================================================================================
    public function importEtudiantsFromCSV(Request $request)
    {
        ini_set('max_execution_time', 300); // Étendre le temps d'exécution à 5 min si gros fichier

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $handle = fopen($path, 'r');

        if (!$handle) {
            return redirect()->back()->withErrors(['csv_file' => 'Impossible de lire le fichier CSV.']);
        }

        $importes = 0; // compteur d'étudiants importés

        // Lire la première ligne comme en-tête
        $header = fgetcsv($handle, 0, ';');
        if (!$header) {
            return redirect()->back()->withErrors(['csv_file' => 'Le fichier CSV est vide ou mal formaté.']);
        }

        $header = array_map('strtolower', array_map('trim', $header));

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (count($row) !== count($header)) {
                continue; // ignorer ligne incorrecte
            }

            $row = array_combine($header, $row);
            $row = array_map('trim', $row);

            // Recherche du groupe par ID ou nom
            $groupe_id = null;
            if (!empty($row['groupe_id']) && is_numeric($row['groupe_id'])) {
                $groupe_id = $row['groupe_id'];
            } elseif (!empty($row['nom_groupe'])) {
                $groupe = Groupe::where('nom_groupe', $row['nom_groupe'])->first();
                if ($groupe) {
                    $groupe_id = $groupe->id;
                }
            }

            // Validation des champs
            $validator = Validator::make($row, [
                'nom'    => 'required|string',
                'prenom' => 'required|string',
                'email'  => 'required|email|unique:users,email',
                'cne'    => 'required|string|unique:etudiants,cne',
            ]);

            if ($validator->fails()) {
                continue; // ignorer ligne invalide
            }

            // Mot de passe sécurisé (cne@2025)
            $password = $row['cne'] . '@2025';

            // Création de l'utilisateur
            $user = User::create([
                'name'     => $row['nom'],
                'prenom'   => $row['prenom'],
                'email'    => $row['email'],
                'password' => Hash::make($password),
            ]);

            // Création de l'étudiant
            Etudiant::create([
                'user_id'   => $user->id,
                'cne'       => $row['cne'],
                'groupe_id' => $groupe_id,
            ]);

            $user->assignRole('etudiant');
            $importes++;
        }

        fclose($handle);

        return redirect()->route('gestion.utilisateurs', ['tab' => 'etudiants'])
            ->with('success', "$importes étudiant(s) importé(s) avec succès.");
    }


    public function generateImportExample()
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    if (!$user->hasRole('administrateur')) {
        abort(403, 'Accès interdit.');
    }

    $headers = [
        "Content-Type"        => "text/csv; charset=UTF-8",
        "Content-Disposition" => "attachment; filename=liste_etudiants_" . now()->format('Y-m-d_H-i-s') . ".csv",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0",
    ];

    $groupes = Groupe::pluck('nom_groupe')->toArray();

    $callback = function () use ($groupes) {
        $handle = fopen('php://output', 'w');

        // Encodage UTF-8 avec BOM (important pour Excel et accents)
        fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // En-tête (format d'import attendu)
        fputcsv($handle, ['nom', 'prenom', 'email', 'cne', 'nom_groupe'], ';');

        // Exemple de ligne
        fputcsv($handle, ['DUPONT', 'Jean', 'jean.dupont@example.com', 'CNE123456', 'info_1a_G1'], ';');

        // Séparation
        fputcsv($handle, [], ';');
        fputcsv($handle, ['Groupes disponibles :'], ';');

        // Liste des groupes
        foreach ($groupes as $nom) {
            fputcsv($handle, [$nom], ';');
        }

        fclose($handle);
    };

    return response()->stream($callback, 200, $headers);
}



    public function exportEtudiantsToCSV()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $etudiants = Etudiant::with(['user', 'groupe.filiere'])->get();

        $date = Carbon::now()->format('Y-m-d_H-i');
        $fileName = "liste_etudiants_{$date}.csv";

        $headers = [
            "Content-Type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        ];

        $callback = function () use ($etudiants) {
            $handle = fopen('php://output', 'w');

            // Encodage UTF-8 avec BOM pour Excel
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // En-têtes CSV
            fputcsv($handle, [
                'Nom',
                'Prénom',
                'Email',
                'CNE',
                'Mot de passe par défaut',
                'Groupe',
                'Filière'
            ], ';');

            // Lignes de données
            foreach ($etudiants as $etudiant) {
                fputcsv($handle, [
                    $etudiant->user->name,
                    $etudiant->user->prenom,
                    $etudiant->user->email,
                    $etudiant->cne,
                    $etudiant->cne . '@2025',
                    optional($etudiant->groupe)->nom_groupe ?? 'Non défini',
                    optional(optional($etudiant->groupe)->filiere)->nom_filiere ?? 'Non défini',
                ], ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }


    public function exportEtudiantsToPDF()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $etudiants = Etudiant::with(['user', 'groupe.filiere'])->get();

        $grouped = $etudiants
            ->sortBy(function ($e) {
                $filiere = optional(optional($e->groupe)->filiere)->nom_filiere ?? 'ZZZ_Sans filière';
                $groupe = optional($e->groupe)->nom_groupe ?? 'ZZZ_Sans groupe';
                return $filiere . '___' . $groupe . '___' . $e->user->name;
            })
            ->groupBy(function ($etudiant) {
                return optional(optional($etudiant->groupe)->filiere)->nom_filiere ?? 'Sans filière';
            })
            ->map(function ($group) {
                return $group->groupBy(function ($e) {
                    return optional($e->groupe)->nom_groupe ?? 'Sans groupe';
                });
            });

        $date = Carbon::now();

        $pdf = Pdf::loadView('gestion_utilisateurs.etudiants.listeEtudiants', [
            'etudiantsParFiliereEtGroupe' => $grouped,
            'date' => $date
        ])->setPaper('a4', 'portrait');

        return $pdf->download('liste_etudiants_par_filiere_' . $date->format('Y-m-d_H-i') . '.pdf');
    }



    // ============================================================================================
    // ============================================================================================
    // ============================================================================================
    // ============================================================================================


    public function exportProfesseursToCSV()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $professeurs = Professeur::with('user')->get();

        $date = Carbon::now()->format('Y-m-d_H-i-s');
        $fileName = "liste_professeurs_{$date}.csv";

        $headers = [
            "Content-Type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        ];

        $callback = function () use ($professeurs) {
            $handle = fopen('php://output', 'w');

            // Encodage UTF-8 avec BOM pour Excel
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // En-tête CSV
            fputcsv($handle, ['Nom', 'Prénom', 'Email'], ';');

            // Lignes de données
            foreach ($professeurs as $prof) {
                fputcsv($handle, [
                    $prof->user->name,
                    $prof->user->prenom,
                    $prof->user->email,
                ], ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }


    public function exportProfesseursToPDF()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $professeurs = Professeur::with(['user', 'coursSessions.cours'])->get();
        $date = Carbon::now();

        $qrText = utf8_encode('Liste des professeurs - Généré le ' . $date->format('d/m/Y à H:i'));

        $qr = base64_encode(
            QrCode::format('png')
                ->size(100)
                ->generate($qrText)
        );

        return Pdf::loadView('gestion_utilisateurs.professeurs.listeProfesseurs', [
            'professeurs' => $professeurs,
            'date' => $date,
            'qr' => $qr,
        ])
            ->setPaper('a4', 'portrait')
            ->download('liste_professeurs_' . $date->format('Ymd_His') . '.pdf');
    }

    public function generateProfExample()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=professeurs_example_" . now()->format('Y-m-d_H-i-s') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            //  En-tête
            fputcsv($handle, ['nom', 'prenom', 'email']);

            //  Exemple de ligne
            fputcsv($handle, ['NOM', 'PRENOM', 'email-de-prof@example.com']);

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }


    public function importProfesseursFromCSV(Request $request)
    {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt']);

        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $header = array_map(fn($h) => strtolower(str_replace(['é', 'è', 'ê', 'à'], ['e', 'e', 'e', 'a'], trim($h))), $data[0]);

        unset($data[0]); // remove header

        $importes = 0;

        foreach ($data as $index => $row) {
            if (count($row) !== count($header)) continue;

            $row = array_combine($header, $row);

            $validator = Validator::make($row, [
                'nom' => 'required|string',
                'prenom' => 'required|string',
                'email' => 'required|email|unique:users,email',
            ]);

            if ($validator->fails()) {
                Log::warning("Ligne ignorée (ligne {$index}) : données invalides", ['row' => $row]);
                continue;
            }

            $password = $row['prenom'] . $row['nom'] . '@2025';

            $user = User::create([
                'name' => $row['nom'],
                'prenom' => $row['prenom'],
                'email' => $row['email'],
                'password' => Hash::make($password),
            ]);

            Professeur::create(['user_id' => $user->id]);
            $user->assignRole('professeur');

            $importes++;
        }

        return redirect()->route('gestion.utilisateurs')->with('success', "$importes professeur(s) importé(s) avec succès.");
    }



    // ============================================================================================
    // ============================================================================================
    // ============================================================================================
    // ============================================================================================

    public function exportAdminsToCSV()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $admins = Administrateur::with('user')->get();

        $date = Carbon::now()->format('Y-m-d_H-i-s');
        $fileName = "liste_admins_{$date}.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        ];

        $callback = function () use ($admins) {
            $handle = fopen('php://output', 'w');

            // En-tête CSV
            fputcsv($handle, ['Nom', 'Prenom', 'Email']);

            foreach ($admins as $admin) {
                fputcsv($handle, [
                    $admin->user->name,
                    $admin->user->prenom,
                    $admin->user->email,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function generateAdminExample()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=admins_example_" . now()->format('Y-m-d_H-i-s') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            //  En-tête
            fputcsv($handle, ['name', 'prenom', 'email']);

            //  Exemple de ligne
            fputcsv($handle, ['NOM', 'PRENOM', 'email-de-l\'admin@example.com']);

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }


    public function importAdminsFromCSV(Request $request)
    {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt']);

        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $header = array_map('strtolower', array_map('trim', $data[0]));
        unset($data[0]);

        foreach ($data as $row) {
            if (count($row) !== count($header)) continue; // sécurité

            $row = array_combine($header, $row);

            $validator = Validator::make($row, [
                'name' => 'required|string',
                'prenom' => 'required|string',
                'email' => 'required|email|unique:users,email',
            ]);

            if ($validator->fails()) continue;

            $password = $row['prenom'] . $row['name'] . '@2025';
            //$password = strtolower($row['prenom'] . $row['name']) . '@2025';


            $user = User::create([
                'name' => $row['name'],
                'prenom' => $row['prenom'],
                'email' => $row['email'],
                'password' => Hash::make($password),
            ]);

            Administrateur::create(['user_id' => $user->id]);
            $user->assignRole('administrateur');
        }

        return redirect()->route('gestion.utilisateurs')->with('success', 'Importation des admins réussie.');
    }



    // ============================================================================================
    // ============================================================================================
    // ============================================================================================
    // ============================================================================================
    // ============================================================================================
    // ============================================================================================
    // ============================================================================================
    // ============================================================================================

    public function createProf()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // the defautl role and view is admin
        $title = 'Dashboard Admin';
        $view = 'dashboardAdmin';
        $menu = 'adminMenu';

        //check the role and update the title and view variables
        $role = $user->getRoleNames()[0];

        if ($role == 'etudiant') {
            $title = 'Dashboard Etudiant';
            $view = 'dashboardEtudiant';
            $menu = 'etudiantMenu';
        } elseif ($role == 'professeur') {
            $title = 'Dashboard Professeur';
            $view = 'dashboardProf';
            $menu = 'profMenu';
        }
        return view('gestion_utilisateurs.professeurs.create', compact(
            'title',
            'menu'
        ));
    }

    public function storeProf(Request $request)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $request->validate([
            'name' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|email|unique:users,email',
        ]);

        $password = $request->prenom . $request->name . '@2025';
        //$password = strtolower($request->prenom . $request->name) . '@2025';

        $user = User::create([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);

        Professeur::create([
            'user_id' => $user->id,
        ]);
        $user->assignRole('professeur');
        return redirect()->route('gestion.utilisateurs', ['tab' => 'professeurs'])->with('success', "Professeur {$user->prenom} {$user->name} ajouté avec succès.");
    }


    public function editProf($id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // the defautl role and view is admin
        $title = 'Dashboard Admin';
        $view = 'dashboardAdmin';
        $menu = 'adminMenu';

        //check the role and update the title and view variables
        $role = $user->getRoleNames()[0];

        if ($role == 'etudiant') {
            $title = 'Dashboard Etudiant';
            $view = 'dashboardEtudiant';
            $menu = 'etudiantMenu';
        } elseif ($role == 'professeur') {
            $title = 'Dashboard Professeur';
            $view = 'dashboardProf';
            $menu = 'profMenu';
        }
        $professeur = Professeur::with('user')->findOrFail($id);
        return view('gestion_utilisateurs.professeurs.edit', compact('professeur', 'title', 'menu'));
    }

    public function updateProf(Request $request, $id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $professeur = Professeur::findOrFail($id);
        $user = $professeur->user;

        $user->update([
            'name' => $request->name,
            'prenom' => $request->prenom, //==========Ajouter prenom===============
            'email' => $request->email,
        ]);

        // $professeur->update([
        //     'specialite' => $request->specialite,
        // ]);

        return redirect()->route('gestion.utilisateurs', ['tab' => 'professeurs'])->with('success', "Professeur {$user->prenom} {$user->name} modifié avec succès");
    }

    public function destroyProf($id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $professeur = Professeur::findOrFail($id);
        $user = $professeur->user;

        $userPrenom = $user->prenom;
        $userNom = $user->name;

        $user->delete();
        $professeur->delete();

        return redirect()->route('gestion.utilisateurs', ['tab' => 'professeurs'])->with('success', "Professeur {$user->prenom} {$user->name} supprimé.");
    }



    // ============================================================================================
    // ============================================================================================
    // ============================================================================================

    public function createAdmin()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // the defautl role and view is admin
        $title = 'Dashboard Admin';
        $view = 'dashboardAdmin';
        $menu = 'adminMenu';

        //check the role and update the title and view variables
        $role = $user->getRoleNames()[0];

        if ($role == 'etudiant') {
            $title = 'Dashboard Etudiant';
            $view = 'dashboardEtudiant';
            $menu = 'etudiantMenu';
        } elseif ($role == 'professeur') {
            $title = 'Dashboard Professeur';
            $view = 'dashboardProf';
            $menu = 'profMenu';
        }
        return view('gestion_utilisateurs.admins.create', compact(
            'title',
            'menu'
        ));
    }

    // INSERTION
    public function storeAdmin(Request $request)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $request->validate([
            'name' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|email|unique:users,email',
        ]);

        $password = $request->prenom . $request->name . '@2025';
        //$password = strtolower($request->prenom . $request->name) . '@2025';


        $user = User::create([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);

        Administrateur::create(['user_id' => $user->id]);
        $user->assignRole('administrateur');
        return redirect()->route('gestion.utilisateurs', ['tab' => 'admins'])->with('success', "L'admin {$user->prenom} {$user->name}ajouté.");
    }


    // FORMULAIRE DE MODIFICATION
    public function editAdmin($id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // the defautl role and view is admin
        $title = 'Dashboard Admin';
        $view = 'dashboardAdmin';
        $menu = 'adminMenu';

        //check the role and update the title and view variables
        $role = $user->getRoleNames()[0];

        if ($role == 'etudiant') {
            $title = 'Dashboard Etudiant';
            $view = 'dashboardEtudiant';
            $menu = 'etudiantMenu';
        } elseif ($role == 'professeur') {
            $title = 'Dashboard Professeur';
            $view = 'dashboardProf';
            $menu = 'profMenu';
        }
        $admin = Administrateur::findOrFail($id);
        return view('gestion_utilisateurs.admins.edit', compact('admin', 'title', 'menu'));
    }

    // MISE À JOUR
    public function updateAdmin(Request $request, $id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $admin = Administrateur::findOrFail($id);
        $user = $admin->user;

        $request->validate([
            'name' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
        ]);

        return redirect()->route('gestion.utilisateurs', ['tab' => 'admins'])->with('success', "L'admin {$user->prenom} {$user->name} modifié");
    }

    // SUPPRESSION
    public function destroyAdmin($id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $admin = Administrateur::findOrFail($id);
        $user = $admin->user;

        $userPrenom = $user->prenom;
        $userNom = $user->name;

        $user->delete();
        $admin->delete();

        return redirect()->route('gestion.utilisateurs', ['tab' => 'admins'])->with('success', "L'admin {$userPrenom} {$userNom} supprimé.");
    }


    // ============================================================================================
    // ============================================================================================
    // ============================================================================================

    public function createEtud()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // the defautl role and view is admin
        $title = 'Dashboard Admin';
        $view = 'dashboardAdmin';
        $menu = 'adminMenu';

        //check the role and update the title and view variables
        $role = $user->getRoleNames()[0];

        if ($role == 'etudiant') {
            $title = 'Dashboard Etudiant';
            $view = 'dashboardEtudiant';
            $menu = 'etudiantMenu';
        } elseif ($role == 'professeur') {
            $title = 'Dashboard Professeur';
            $view = 'dashboardProf';
            $menu = 'profMenu';
        }
        return view('gestion_utilisateurs.etudiants.create', compact(
            'title',
            'menu'
        ));
    }

    public function storeEtud(Request $request)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $request->validate([
            'name' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'cne' => 'required|string|unique:etudiants,cne',
            'groupe_id' => 'nullable|exists:groupes,id',
        ]);

        $password = $request->cne . '@2025';

        $user = User::create([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);

        $etudiant = Etudiant::create([
            'user_id' => $user->id,
            'cne' => $request->cne,
            'groupe_id' => $request->groupe_id,
        ]);
        $user->assignRole('etudiant');
        return redirect()->route('gestion.utilisateurs', ['tab' => 'etudiants'])
            ->with('success', "L'étudiant {$etudiant->user->prenom} {$etudiant->user->name} ajouté avec succès.");
    }




    public function editEtud($id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // the defautl role and view is admin
        $title = 'Dashboard Admin';
        $view = 'dashboardAdmin';
        $menu = 'adminMenu';

        //check the role and update the title and view variables
        $role = $user->getRoleNames()[0];

        if ($role == 'etudiant') {
            $title = 'Dashboard Etudiant';
            $view = 'dashboardEtudiant';
            $menu = 'etudiantMenu';
        } elseif ($role == 'professeur') {
            $title = 'Dashboard Professeur';
            $view = 'dashboardProf';
            $menu = 'profMenu';
        }
        $etudiant = Etudiant::findOrFail($id);
        return view('gestion_utilisateurs.etudiants.edit', compact(
            'etudiant',
            'title',
            'menu'
        ));
    }

    public function updateEtud(Request $request, $id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $etudiant = Etudiant::findOrFail($id);
        $user = $etudiant->user;

        $request->validate([
            'name' => 'required|string',
            'prenom' => 'required|string',
            'cne' => 'required|string|unique:etudiants,cne,' . $etudiant->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'groupe_id' => 'nullable|exists:groupes,id',
        ]);

        //  Mettre à jour le user
        $user->update([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
        ]);

        //  Mettre à jour l'étudiant
        $etudiant->update([
            'cne' => $request->cne,
            'groupe_id' => $request->groupe_id,
        ]);

        return redirect()->route('gestion.utilisateurs', ['tab' => 'etudiants'])->with('success', "L'étudiant {$etudiant->user->prenom} {$etudiant->user->name} modifié.");
    }
    public function getGroupesParFiliere($filiere_id)
    {
        $groupes = \App\Models\Groupe::where('id_filiere', $filiere_id)->get();
        return response()->json($groupes);
    }


    public function destroyEtud($id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }
        $etudiant = Etudiant::with('user')->findOrFail($id);
        $prenom = $etudiant->user->prenom;
        $nom = $etudiant->user->name;

        $etudiant->user->delete();
        $etudiant->delete();

        return redirect()->route('gestion.utilisateurs', ['tab' => 'etudiants'])->with('success', "L'étudiant {$prenom} {$nom} supprimé.");
    }

    // ============================================================================================
    // ============================================================================================
    // ============================================================================================
    // ============================================================================================
    public function bulkAction(Request $request)
    {
        $request->validate([
            'selected' => 'required|array',
            'action' => 'required|string|in:delete,reset',
        ]);

        $ids = $request->input('selected');
        $action = $request->input('action');

        if ($action === 'delete') {
            foreach ($ids as $userId) {
                $etudiant = Etudiant::where('user_id', $userId)->first();
                if ($etudiant) {
                    $etudiant->delete();
                    $etudiant->user->delete();
                }
            }
            return back()->with('success', 'Étudiants supprimés avec succès.');
        }

        if ($action === 'reset') {
            foreach ($ids as $userId) {
                $user = User::find($userId);
                if ($user) {
                    $user->password = Hash::make($user->etudiant->cne . '@2025');
                    $user->save();
                }
            }
            return back()->with('success', 'Mots de passe réinitialisés avec succès.');
        }

        return back()->with('error', 'Action non reconnue.');
    }
    public function bulkActionProfesseurs(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('selected', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Aucun professeur sélectionné.');
        }

        if ($action === 'delete') {
            User::whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', 'Professeurs supprimés avec succès.');
        }

        if ($action === 'reset') {
            foreach ($ids as $id) {
                $user = User::find($id);
                if ($user) {
                    $newPassword = $user->prenom . $user->name . '@2025';
                    // dd($newPassword);
                    $user->password = bcrypt($newPassword);
                    $user->save();
                }
            }
            return redirect()->back()->with('success', 'Mots de passe réinitialisés avec succès.');
        }

        return redirect()->back()->with('error', 'Action non reconnue.');
    }
    public function bulkActionAdmins(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('selected', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Aucun administrateur sélectionné.');
        }

        if ($action === 'delete') {
            // Suppression des utilisateurs liés aux admins sélectionnés
            User::whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', 'Administrateurs supprimés avec succès.');
        }

        if ($action === 'reset') {
            foreach ($ids as $id) {
                $user = User::find($id);
                if ($user) {
                    $newPassword = $user->prenom . $user->name . '@2025';
                    $user->password = bcrypt($newPassword);
                    $user->save();
                }
            }
            return redirect()->back()->with('success', 'Mots de passe réinitialisés avec succès.');
        }

        return redirect()->back()->with('error', 'Action non reconnue.');
    }
    // ============================================================================================
    // ============================================================================================
    // ============================================================================================
    // ============================================================================================
    public function resetPassword($id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $user = User::findOrFail($id);

        // Vérification pour l'étudiant
        if ($user->etudiant) {
            $password = $user->etudiant->cne . '@2025';
            //$password = $user->etudiant->prenom . $user->etudiant->nom . '@2025';
            //dd($password);
        }
        // Vérification pour le professeur
        elseif ($user->professeur) {
            $password = $user->prenom . $user->name . '@2025';
        }
        // Vérification pour l'administrateur
        elseif ($user->administrateur) {
            $password = $user->prenom . $user->name . '@2025';
        } else {
            return back()->with('error', 'Type d’utilisateur inconnu.');
        }

        // Mise à jour du mot de passe
        $user->password = Hash::make($password);
        $user->save();

        return back()->with('success', "Mot de passe réinitialisé pour {$user->prenom} {$user->name}.");
    }


    ///////////////////////////////////////////////////////API
    public function getUser(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
            'user_type' => $user->roles[0]->name ?? ''
        ]);
    }

    public function getUserRole(Request $request)
    {
        return response()->json([
            'role' => $user->roles[0]->name ?? ''
        ]);
    }




    /////////////////////////////////////////////////////////////////
}
