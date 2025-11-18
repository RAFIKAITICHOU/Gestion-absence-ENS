<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Etudiant;

class EtudiantDashboardController extends Controller
{
    public function index()
    {// mkhadminch b hadi voir DashboardEtudiantController.php
        /** @var \App\Models\User $user */

        $user = Auth::user();

        $etudiant = Etudiant::with(['groupe.filiere', 'absences', 'notifications'])
            ->where('user_id', $user->id)
            ->first();

        $title = 'Dashboard Admin';
        $role = $user->getRoleNames()[0];

        if ($role == 'etudiant')
            $title = 'Dashboard Etudiant';
        elseif ($role == 'professeur')
            $title = 'Dashboard Professeur';

        return view('etudiant.dashboard', compact('etudiant', 'title'));
    }
}
