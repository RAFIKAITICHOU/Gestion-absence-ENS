<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\CoursSession;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\EtudiantAbsenceController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\CoursSessionController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\GroupeController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\StatistiquesController;
use App\Http\Controllers\JourInactifController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\DashboardProfController;
use App\Http\Controllers\EtudiantAdminController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\DashboardEtudiantController;
use App\Http\Controllers\EtudiantsCoursController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\AuthAPIController;
use App\Http\Controllers\Auth\ForgotPasswordController;

// ========== API ==========
Route::group(['prefix' => 'api', 'middleware' => ['api']], function () {

    // CSRF token endpoint
    Route::get('/csrf-token', [AuthAPIController::class, 'getCsrfToken']);

    //#### Application mobile

    // Authentication routes
    Route::post('/login', [AuthAPIController::class, 'login']);
    Route::post('/logout', [AuthAPIController::class, 'logout'])->middleware('auth:sanctum');

    // User profile routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [UserManagementController::class, 'getUser']);
        Route::get('/check-auth', [AuthAPIController::class, 'checkAuth']);
        Route::get('/user/role', [UserManagementController::class, 'getUserRole']);
    });


    //######### system de presence

    Route::post('/presenceViaQRCode', [PresenceController::class, 'presenceViaQRCode'])->name('presenceViaQRCode');


    //######### AI Counter
    Route::post('/update-nb-student', [CoursSessionController::class, 'updateNbStudent']);
    Route::get('/get-today-next-sessions', [CoursSessionController::class, 'getTodayNextSessions']);
});


//==========================================

// ========== Page de connexion ==========
Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');
// routes/auth.php

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ========== Routes Authentifiées ==========
Route::middleware(['auth'])->group(function () {


    Route::get('/getCod', [PresenceController::class, 'getCod'])->name('getCod');


    // Dashboard
    //Route::get('/dashboardadmin', [DashboardAdminController::class, 'index'])->name('dashboardadmin');
    Route::middleware(['web', 'auth'])->group(function () {
        Route::get('/dashboardadmin', [DashboardAdminController::class, 'index'])->name('dashboardadmin');
    });
    Route::get('/dashboardprof', [DashboardProfController::class, 'index'])->name('dashboard.prof');
    Route::get('/dashboardetudiant', [DashboardEtudiantController::class, 'index'])->name('dashboard.etudiant');

    Route::get('/configuration', [DashboardAdminController::class, 'configuration'])->name('dashboard.configuration');

    Route::get('/etudiant/absences', [EtudiantAbsenceController::class, 'index'])->name('etudiant.absences');

    // Pages annexes
    Route::match(['get', 'post'], '/GestionEDT', [DashboardAdminController::class, 'GestionEDT'])->name('dashboardadmin.GestionEDT');
    Route::get('/statistiques', [DashboardAdminController::class, 'statistiques'])->name('dashboardadmin.statistiques');
    Route::get('/configuration', [DashboardAdminController::class, 'configuration'])->name('dashboardadmin.configuration');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Gestion des utilisateurs
    Route::get('/gestion-utilisateurs', [UserManagementController::class, 'index'])->name('gestion.utilisateurs');

    // Routes Étudiants
    Route::prefix('etudiants')->group(function () {
        Route::get('create', [UserManagementController::class, 'createEtud'])->name('etudiants.create');
        Route::post('store', [UserManagementController::class, 'storeEtud'])->name('etudiants.store');
        Route::get('{id}/edit', [UserManagementController::class, 'editEtud'])->name('etudiants.edit');
        Route::put('{id}', [UserManagementController::class, 'updateEtud'])->name('etudiants.update');
        Route::delete('{id}', [UserManagementController::class, 'destroyEtud'])->name('etudiants.destroy');
        Route::post('import', [UserManagementController::class, 'importEtudiantsFromCSV'])->name('etudiants.import');
        Route::get('export', [UserManagementController::class, 'exportEtudiantsToCSV'])->name('etudiants.export');
    });

    // Routes Admins
    Route::prefix('admins')->group(function () {
        Route::get('create', [UserManagementController::class, 'createAdmin'])->name('admins.create');
        Route::post('store', [UserManagementController::class, 'storeAdmin'])->name('admins.store');
        Route::get('{id}/edit', [UserManagementController::class, 'editAdmin'])->name('admins.edit');
        Route::put('{id}', [UserManagementController::class, 'updateAdmin'])->name('admins.update');
        Route::delete('{id}', [UserManagementController::class, 'destroyAdmin'])->name('admins.destroy');
    });

    // Routes Professeurs
    Route::prefix('professeurs')->group(function () {
        Route::get('create', [UserManagementController::class, 'createProf'])->name('professeurs.create');
        Route::post('store', [UserManagementController::class, 'storeProf'])->name('professeurs.store');
        Route::get('{id}/edit', [UserManagementController::class, 'editProf'])->name('professeurs.edit');
        Route::put('{id}', [UserManagementController::class, 'updateProf'])->name('professeurs.update');
        Route::delete('{id}', [UserManagementController::class, 'destroyProf'])->name('professeurs.destroy');
    });

    // Emploi du temps (CoursSessions)
    Route::get('/edt', [CoursSessionController::class, 'calendar'])->name('edt');
    Route::get('/cours-sessions-api', [CoursSessionController::class, 'api']);
    Route::get('/api/seances', [CoursSessionController::class, 'api'])->name('edt.api');
    Route::get('/cours-sessions-professeur/{id}', [CoursSessionController::class, 'byProfesseur'])->name('cours-sessions.byProfesseur');
    Route::resource('cours-sessions', CoursSessionController::class)->except(['index']);
    Route::get('/cours-sessions', [CoursSessionController::class, 'index'])->name('cours-sessions.index');
    Route::get('/cours-sessions/{id}/edit', [CoursSessionController::class, 'edit'])->name('cours-sessions.edit');
    Route::delete('/cours-sessions/{id}', [CoursSessionController::class, 'destroy'])->name('cours-sessions.destroy');

    // Vue personnalisée avec filtres
    Route::get('/cours-sessions-list', function (Request $request) {
        $sessions = CoursSession::with(['professeur.user', 'groupe', 'cours', 'salle'])
            ->when($request->professeur, function ($q) use ($request) {
                $q->whereHas('professeur.user', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->professeur . '%')
                        ->orWhere('prenom', 'like', '%' . $request->professeur . '%');
                });
            })
            ->when($request->date, fn($q) => $q->where('date', $request->date))
            ->orderByDesc('date')
            ->get();

        return view('cours_sessions.index', compact('sessions'));
    })->name('cours-sessions.list');

    // Gestion des salles
    Route::resource('salles', SalleController::class);

    // Filières
    Route::post('/filieres', [FiliereController::class, 'store'])->name('filieres.store');
    Route::get('/filieres/{id}/edit', [FiliereController::class, 'edit'])->name('filieres.edit');
    Route::put('/filieres/{id}', [FiliereController::class, 'update'])->name('filieres.update');
    Route::delete('/filieres/{id}', [FiliereController::class, 'destroy'])->name('filieres.destroy');

    // Groupes
    Route::post('/groupes', [GroupeController::class, 'store'])->name('groupes.store');
    Route::get('/groupes/{id}/edit', [GroupeController::class, 'edit'])->name('groupes.edit');
    Route::delete('/groupes/{id}', [GroupeController::class, 'destroy'])->name('groupes.destroy');
});

Route::get('/statistiques', [StatistiquesController::class, 'index'])->name('statistiques');
//Route::get('/statistiques', [StatistiquesController::class, 'index'])->name('admin.statistiques');
Route::get('/absences/export', [StatistiquesController::class, 'export'])->name('absences.export');

Route::put('/groupes/{groupe}', [GroupeController::class, 'update'])->name('groupes.update');


// =================== ROUTES EXPORT / IMPORT CSV ===================

// Professeurs
Route::get('/professeurs/export', [UserManagementController::class, 'exportProfesseursToCSV'])->name('professeurs.export');
Route::post('/professeurs/import', [UserManagementController::class, 'importProfesseursFromCSV'])->name('professeurs.import');

// Administrateurs
Route::get('/admins/export', [UserManagementController::class, 'exportAdminsToCSV'])->name('admins.export');
Route::post('/admins/import', [UserManagementController::class, 'importAdminsFromCSV'])->name('admins.import');


Route::get('/groupes-par-filiere/{filiere_id}', [UserManagementController::class, 'getGroupesParFiliere']);



Route::resource('jours_inactifs', JourInactifController::class)->only(['index', 'store', 'destroy']);

Route::get('/jours-inactifs/{id}/edit', [JourInactifController::class, 'edit'])->name('jours_inactifs.edit');
Route::put('/jours-inactifs/{id}', [JourInactifController::class, 'update'])->name('jours_inactifs.update');
Route::get('/jours_inactifs/{id}/edit', [JourInactifController::class, 'edit'])->name('jours_inactifs.edit');
Route::put('/jours_inactifs/{id}', [JourInactifController::class, 'update'])->name('jours_inactifs.update');
Route::delete('/jours_inactifs/{id}', [JourInactifController::class, 'destroy'])->name('jours_inactifs.destroy');
Route::resource('jours_inactifs', JourInactifController::class);
Route::get('/jours-inactifs-api', [JourInactifController::class, 'api']);

Route::post('/utilisateurs/reset-password/{id}', [UserManagementController::class, 'resetPassword'])->name('utilisateur.resetPassword');

Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

Route::get('/etudiants/example-csv', [UserManagementController::class, 'generateImportExample'])->name('etudiants.example_csv');

Route::get('/etudiants/example-csv', [UserManagementController::class, 'generateImportExample'])->name('etudiants.example');

// Exemple CSV pour les professeurs
Route::get('/professeurs/example-csv', [UserManagementController::class, 'generateProfExample'])->name('professeurs.example');

// Exemple CSV pour les admins
Route::get('/admins/example-csv', [UserManagementController::class, 'generateAdminExample'])->name('admins.example');


Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.updatePhoto');
Route::post('/profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.updatePhoto');
Route::post('/profile/delete-photo', [ProfileController::class, 'deletePhoto'])->name('profile.deletePhoto');

Route::post('/profile/photo/delete', [ProfileController::class, 'deletePhoto'])->name('profile.deletePhoto');
Route::get('/filieres', [FiliereController::class, 'index'])->name('filieres.index');


Route::get('/liste-presences', [App\Http\Controllers\PresenceController::class, 'index'])->name('liste.presences');
Route::put('/presences/{id}/justifier', [PresenceController::class, 'justifier'])->name('presences.justifier');
Route::put('/presences/{id}/nonjustifier', [App\Http\Controllers\PresenceController::class, 'nonJustifier'])->name('presences.nonjustifier');
Route::get('/presences/export-pdf', [App\Http\Controllers\PresenceController::class, 'exportPdf'])->name('presences.export.pdf');


Route::get('/export-planning', [\App\Http\Controllers\CoursSessionController::class, 'exportPdf'])->name('export.planning');

Route::get('/mon-emploi', [CoursSessionController::class, 'monEmploi'])->middleware(['auth'])->name('mon.emploi');
//Route::get('/dashboard-prof', [DashboardProfController::class, 'index'])->name('dashboard.prof');
Route::view('/dashboard-prof', 'dashboardProf')->name('dashboard.prof');
// Route::get('/cours-sessions-professeur-connecte', [CoursSessionController::class, 'sessionsProfesseurConnecte']);
Route::get('/cours-sessions-etudiant-connecte', [CoursSessionController::class, 'sessionsEtudiantConnecte']);
Route::get('/cours-sessions-professeur-connecte', [CoursSessionController::class, 'sessionsProfesseurConnecte']);
Route::get('/cours-sessions-etudiant-connecte', [CoursSessionController::class, 'sessionsEtudiantConnecte']);
Route::get('/dashboard-admin', [DashboardAdminController::class, 'index'])->name('dashboard.admin');
Route::get('/dashboard-prof', [DashboardProfController::class, 'index'])->name('dashboard.prof');
Route::get('/dashboard-etudiant', [DashboardEtudiantController::class, 'index'])->name('dashboard.etudiant');
Route::get('/emploi-du-temps/professeur', [CoursSessionController::class, 'edtProfesseur'])->name('emploi');
Route::get('/emploi-du-temps/etudiant', [CoursSessionController::class, 'edtEtudiant'])->name('emploi');
Route::get('/api/professeur/sessions', [CoursSessionController::class, 'sessionsProfesseurConnecte']);
Route::get('/api/etudiant/sessions', [CoursSessionController::class, 'sessionsEtudiantConnecte']);

Route::get('/api/professeur/sessions', [CoursSessionController::class, 'sessionsProfesseurConnecte']);
Route::get('/api/etudiant/sessions', [CoursSessionController::class, 'sessionsEtudiantConnecte']);
Route::get('/admin/configuration', [DashboardAdminController::class, 'configuration'])->name('adminMenu.configuration');
Route::get('/api/jours-inactifs', [\App\Http\Controllers\JourInactifController::class, 'api']);
Route::get('/professeur/emploi-du-temps/pdf', [CoursSessionController::class, 'exportPdf'])
    ->name('professeur.export.emploi');
Route::get('/etudiant/emploi-du-temps/pdf', [CoursSessionController::class, 'exportPdf'])
    ->name('etudiant.export.emploi');
// Route::get('/emploi-du-temps/etudiant', [CoursSessionController::class, 'edtEtudiant'])->name('emploi.etudiant');
Route::get('/emploi-du-temps/etudiant', [CoursSessionController::class, 'edtEtudiant'])
    ->name('emploi.etudiant');

Route::get('/etudiant/qr-code', [EtudiantController::class, 'monQrCode'])->name('etudiant.qr-code');
Route::get('/etudiant/qr-code/download-png', [EtudiantController::class, 'downloadPng'])->name('etudiant.qr.png');
Route::get('/etudiant/qr-code/download-pdf', [EtudiantController::class, 'downloadPdf'])->name('etudiant.qr.pdf');


Route::get('/dashboard-etudiant', [App\Http\Controllers\DashboardEtudiantController::class, 'index'])->name('dashboard.etudiant');
Route::get('/etudiant/absences', [App\Http\Controllers\EtudiantController::class, 'mesAbsences'])->name('etudiant.absences');

// Pour afficher la page de gestion de présence
Route::get('/presences/gerer/{id}', [PresenceController::class, 'gerer'])->name('presence.gerer');

// Pour soumettre les absences
Route::post('/presences/enregistrer', [PresenceController::class, 'enregistrer'])->name('presence.enregistrer');
Route::get('/emploi-du-temps', [CoursSessionController::class, 'monEmploi'])->name('mon.emploi');
Route::put('/presences/{id}/nonjustifier', [PresenceController::class, 'nonJustifier'])->name('presences.nonjustifier');
Route::get('/seance/{id}/export-pdf', [PresenceController::class, 'exportSeancePDF'])->name('presence.export');
Route::get('/etudiants/absences/pdf', [PresenceController::class, 'exportAbsencesEtudiant'])->name('etudiant.absences.pdf');
// Liste des étudiants d’un module (professeur)
Route::get('/professeur/etudiants', [EtudiantsCoursController::class, 'index'])->name('prof.etudiants');
Route::post('/professeur/etudiants', [EtudiantsCoursController::class, 'getEtudiants'])->name('prof.etudiants.filtrer');
Route::get('/filieres/{idCours}', [EtudiantsCoursController::class, 'getFilieres']);
Route::get('/groupes/{idFiliere}', [EtudiantsCoursController::class, 'getGroupes']);
Route::get('/professeur/etudiants/pdf/{cours}/{filiere}/{groupe}', [EtudiantsCoursController::class, 'exportPDF'])->name('prof.etudiants.pdf');
Route::get('/groupes/{idFiliere}/{idCours}', [EtudiantsCoursController::class, 'getGroupes']);
Route::get('/admins/export-etudiants-pdf', [UserManagementController::class, 'exportEtudiantsToPDF'])
    ->name('admins.exportEtudiantsToPDF');
Route::get('/admins/export-professeurs-pdf', [UserManagementController::class, 'exportProfesseursToPDF'])
    ->name('admins.exportProfesseursToPDF');

Route::resource('annonces', AnnonceController::class)->except(['show']);
Route::middleware('auth')->get('/mes-annonces', [AnnonceController::class, 'mesAnnonces'])->name('annonces.miennes');
Route::middleware(['auth', 'role:professeur'])->group(function () {
    Route::get('/dashboardprof', [DashboardProfController::class, 'index'])->name('dashboardprof');
});


Route::post('/etudiants/bulk-action', [UserManagementController::class, 'bulkAction'])->name('etudiants.bulkAction');
Route::post('/professeurs/bulk-action', [UserManagementController::class, 'bulkActionProfesseurs'])
    ->name('professeurs.bulkAction');
Route::post('/admins/bulk-action', [UserManagementController::class, 'bulkActionAdmins'])->name('admins.bulkAction');

Route::middleware(['auth'])->post('/mark-notifications-read', [AnnonceController::class, 'markAllAsRead']);
Route::middleware(['auth'])->post('/prof/mark-notifications-read', [AnnonceController::class, 'markAllAsRead']);






// Routes pour administrateurs
Route::post('/presences/{id}/justifier-fichier', [PresenceController::class, 'justifierAvecFichier'])
    ->name('presences.justifier.fichier');

Route::get('/presences/{id}/fichier', [PresenceController::class, 'voirFichierJustification'])
    ->name('presences.fichier.voir');

Route::get('/presences/{id}/fichier/telecharger', [PresenceController::class, 'telechargerFichierJustification'])
    ->name('presences.fichier.telecharger');

// Routes pour professeurs
Route::get('/presences/{id}/fichier/prof', [PresenceController::class, 'voirFichierJustificationProf'])
    ->name('presences.fichier.voir.prof');

Route::get('/presences/{id}/fichier/telecharger/prof', [PresenceController::class, 'telechargerFichierJustificationProf'])
    ->name('presences.fichier.telecharger.prof');


Route::get('/mot-de-passe-oublie', [ForgotPasswordController::class, 'showRequestForm'])->name('password.request.custom');
Route::post('/mot-de-passe-oublie', [ForgotPasswordController::class, 'handleResetRequest'])->name('password.reset.custom');

Route::get('/evolution-mensuelle', [DashboardAdminController::class, 'getMonthlyEvolution']);



Route::get('/prof/absences/pdf/{cours}/{filiere}/{groupe}', [PresenceController::class, 'exportPdfAbsenceEtud'])
    ->name('prof.etudiants.absences.pdf');


// ========== Auth Routes (Laravel Breeze/Fortify/etc.) ==========
require __DIR__ . '/auth.php';
