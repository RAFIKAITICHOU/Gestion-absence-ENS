<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnonceController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $annonces = Annonce::latest()->get();
        return view('annonces.index', compact('annonces'));
    }

    public function create()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        return view('annonces.create');
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'type' => 'required|in:info,warning,danger',
            'audience' => 'required|in:professeur,etudiant',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,mp4,pdf,doc,docx,xls,xlsx,csv,zip|max:20480',
        ]);

        $path = null;
        if ($request->hasFile('media')) {
            $path = $request->file('media')->store('annonces', 'public');
        }

        Annonce::create([
            'titre' => $request->titre,
            'contenu' => $request->contenu,
            'type' => $request->type,
            'audience' => $request->audience,
            'media' => $path,
        ]);

        return redirect()->route('annonces.index')->with('success', 'Annonce créée avec succès.');
    }

    // public function mesAnnonces()
    // {
    //     /** @var \App\Models\User $user */
    //     $user = Auth::user();
    //     $role = $user->getRoleNames()->first();

    //     $menu = match ($role) {
    //         'etudiant' => 'etudiantMenu',
    //         'professeur' => 'profMenu',
    //         default => abort(403)
    //     };

    //     $annonces = Annonce::where('audience', $role)->latest()->get();

    //     return view('annonces.mes-annonces', compact('annonces', 'menu'));
    // }
    public function mesAnnonces()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $role = $user->getRoleNames()->first();

        $menu = match ($role) {
            'etudiant' => 'etudiantMenu',
            'professeur' => 'profMenu',
            default => abort(403)
        };

        $annonces = Annonce::where('audience', $role)->latest()->get();

        Annonce::where('audience', $role)->where('vue', false)->update(['vue' => true]);

        return view('annonces.mes-annonces', compact('annonces', 'menu'));
    }


    public function edit($id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $annonce = Annonce::findOrFail($id);
        return view('annonces.edit', compact('annonce'));
    }

    public function update(Request $request, $id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'type' => 'required|in:info,warning,danger',
            'audience' => 'required|in:etudiant,professeur',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,mp4,pdf,doc,docx,xls,xlsx,csv,zip|max:20480',
        ]);

        $annonce = Annonce::findOrFail($id);
        $annonce->titre = $request->titre;
        $annonce->contenu = $request->contenu;
        $annonce->type = $request->type;
        $annonce->audience = $request->audience;

        if ($request->hasFile('media')) {
            $annonce->media = $request->file('media')->store('annonces', 'public');
        }

        $annonce->save();

        return redirect()->route('annonces.index')->with('success', 'Annonce modifiée avec succès.');
    }

    public function destroy($id)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        if (!$user->hasRole('administrateur')) {
            abort(403, 'Accès interdit.');
        }

        $annonce = Annonce::findOrFail($id);
        $annonce->delete();

        return redirect()->route('annonces.index')->with('success', 'Annonce supprimée avec succès.');
    }
    public function markAllAsRead(Request $request)
    {
        // ////////////
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $role = $user->getRoleNames()->first(); // 'professeur' ou 'etudiant'

        if (!in_array($role, ['professeur', 'etudiant'])) {
            return response()->json(['success' => false, 'message' => 'Rôle non autorisé.'], 403);
        }

        Annonce::where('audience', $role)
            ->where('vue', false)
            ->update(['vue' => true]);

        return response()->json(['success' => true, 'message' => 'Notifications marquées comme lues pour ' . $role]);
    }
}
