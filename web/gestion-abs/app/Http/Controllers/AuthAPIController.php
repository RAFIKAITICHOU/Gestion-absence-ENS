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
use Illuminate\Validation\ValidationException;

use App\Models\CoursSession;


class AuthAPIController extends Controller
{
    public function getCsrfToken()
    {
        return response()->json([
            'csrf_token' => csrf_token()
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Delete old tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('mobile-app')->plainTextToken;


        if ($user) {
            $user->load(['roles']);
        }
      // dd($user);
      
      $role=$user->roles[0]->name ?? '';
      $sessions='';
      $id_professeur='';
      
      if($role == "professeur"){
        
          $id_professeur=Professeur::where('user_id', $user['id'])
                      ->get()[0]->id;

          $sessions = CoursSession::where('id_professeur', $id_professeur)
        ->get();
        $sessions->load(['cours']);
      }

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token,
            'user_type' => $role,
            'sessions'=> $sessions,
            'id_professeur'=>$id_professeur,


        ]);
    }

    

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function checkAuth(Request $request)
    {
        /*return response()->json([
            'authenticated' => true,
            'user' => $request->user(),
            'user_type' => $request->user()->roles[0]->name ?? ''
        ]);*/
        
        $user=$request->user();
        $role=$user->roles[0]->name ?? '';
        $sessions='';
        $id_professeur='';
        
        if($role == "professeur"){
          
            $id_professeur=Professeur::where('user_id', $user['id'])
                        ->get()[0]->id;
  
                        $sessions = CoursSession::where('id_professeur', $id_professeur)
                        ->get();
                        $sessions->load(['cours']);
        }
  
          return response()->json([
            'authenticated' => true,
              'success' => true,
              'user' => $user,
              'user_type' => $role,
              'sessions'=> $sessions,
              'id_professeur'=>$id_professeur
  
  
          ]);
    }
}
