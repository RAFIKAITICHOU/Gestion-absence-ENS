<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Mail\ResetPasswordMail;

class ForgotPasswordController extends Controller
{
    /**
     * Affiche le formulaire de demande de réinitialisation.
     */
    public function showRequestForm()
    {
        return view('auth.custom-forgot');
    }

    /**
     * Traite la demande de réinitialisation.
     */
    public function handleResetRequest(Request $request)
    {
        // Validation des données
        $request->validate([
            'email' => 'required|email',
            'birth_date' => 'required|date',
        ]);

        // Recherche de l'utilisateur avec l'email et la date de naissance
        $user = User::where('email', $request->email)
            ->where('date_naissance', $request->birth_date)
            ->first();

        // Utilisateur non trouvé
        if (!$user) {
            return back()->withErrors(['email' => 'Aucun utilisateur ne correspond à ces informations.']);
        }

        // Génération du nouveau mot de passe aléatoire
        $newPassword = Str::random(10);

        // Mise à jour du mot de passe (hashé)
        $user->password = Hash::make($newPassword);
        $user->save();

        // Envoi de l’e-mail avec le nouveau mot de passe
        Mail::to($user->email)->send(new ResetPasswordMail($newPassword));

        // Message de succès
        return back()->with('success', 'Un nouveau mot de passe a été envoyé à votre adresse e-mail.');
    }
}
