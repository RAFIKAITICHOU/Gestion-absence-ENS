<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Presence;


class EtudiantController extends Controller
{
    /**
     * Tableau de bord de l'étudiant.
     */
    public function index()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('etudiant')) {
            return redirect()->back()->with('error', 'Accès réservé aux étudiants.');
        }

        $title = 'Dashboard Étudiant';
        $menu = 'etudiantMenu';

        return view('dashboardEtudiant', compact('title', 'menu'));
    }

    /**
     * Afficher le QR Code du CNE de l'étudiant connecté.
     */
    public function monQrCode()
    {
         /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('etudiant')) {
            abort(403);
        }

        return view('etudiant.qr-code');
    }
    public function downloadPng()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('etudiant')) {
            abort(403);
        }
        $cne = Auth::user()->etudiant->cne;

        $image = QrCode::format('png')->size(300)->generate($cne);

        return Response::make($image, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="qr-code-' . $cne . '.png"',
        ]);
    }

    public function downloadPdf()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('etudiant')) {
            abort(403);
        }
        $user = Auth::user();
        $etudiant = $user->etudiant;

        $nom = $user->name;
        $prenom = $user->prenom;
        $cne = $etudiant->cne;
        $filiere = $etudiant->groupe->filiere->nom_filiere ?? '-';
        $groupe = $etudiant->groupe->nom_groupe ?? '-';

        $qrCode = base64_encode(QrCode::format('png')->size(200)->generate($cne));

        $photoPath = public_path('storage/' . $user->photo);
        $photoBase64 = file_exists($photoPath) ? base64_encode(file_get_contents($photoPath)) : null;

        return Pdf::loadView('etudiant.qr-pdf', compact(
            'nom',
            'prenom',
            'cne',
            'filiere',
            'groupe',
            'qrCode',
            'photoBase64'
        ))->download('qr-code-' . $cne . '.pdf');
    }
    public function mesAbsences()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('etudiant')) {
            abort(403);
        }
        $etudiant = Auth::user()->etudiant;

        $presences = Presence::with('session.cours')
            ->where('id_etudiant', $etudiant->id)
            ->orderByDesc('created_at')
            ->get();

        return view('etudiant.absences', compact('presences'));
    }
}
