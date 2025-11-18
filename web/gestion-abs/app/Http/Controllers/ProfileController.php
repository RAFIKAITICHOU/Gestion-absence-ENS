<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    // public function edit(Request $request): View
    // {
    //     return view('profile.edit', [
    //         'user' => $request->user(),
    //     ]);
    // }
    public function edit()
    {
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

        return view('profile', compact('user', 'title', 'menu'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'prenom' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'date_naissance' => ['nullable', 'date'],
        ]);
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        if ($request->filled('prenom')) {
            $user->prenom = $request->prenom;
        }

        if ($request->filled('email') && $user->email !== $request->email) {
            $user->email = $request->email;
            $user->email_verified_at = null;
        }

        if ($request->filled('date_naissance')) {
            $user->date_naissance = $request->date_naissance;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('success', 'Profil mis à jour avec succès.');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return back()->withErrors(['auth' => 'Utilisateur non authentifié.']);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Mot de passe mis à jour avec succès.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        /** @var \App\Models\User $user */

        $user = Auth::user();

        // Supprimer l’ancienne photo s’il ne s’agit pas de la photo par défaut
        if ($user->photo && !str_starts_with($user->photo, 'images/') && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        // Stocker la nouvelle photo
        $path = $request->file('photo')->store('photos', 'public');
        $user->photo = $path;
        $user->save();

        // Retourner une réponse JSON pour le script AJAX
        return response()->json([
            'success' => true,
            'photoUrl' => asset('storage/' . $user->photo) . '?v=' . time(),
        ]);
    }


    public function deletePhoto()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->photo = 'images/default.png';
        $user->save();

        return back()->with('success', 'Photo supprimée avec succès. La photo par défaut a été rétablie.');
    }
}
