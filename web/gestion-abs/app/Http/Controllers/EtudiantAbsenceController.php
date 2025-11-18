<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Etudiant;

class EtudiantAbsenceController extends Controller
{
    public function index()
    {
        $etudiant = Etudiant::with('presences')->where('user_id', Auth::id())->first();

        return view('etudiant.absences', compact('etudiant'));
    }
}
