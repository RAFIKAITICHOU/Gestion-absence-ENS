<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    // Durée max d'inactivité (en secondes)
    protected $timeout = 600; // 10 minutes

    /**
     * Gère la vérification d'inactivité et la déconnexion automatique.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $lastActivity = session('lastActivityTime');

            // Si la session a expiré
            if ($lastActivity && (time() - $lastActivity > $this->timeout)) {

                // Déconnexion douce sans flush brutal
                Auth::logout();

                return redirect()->route('login')->withErrors([
                    'message' => '⏳ Votre session a expiré après 10 minutes d’inactivité.',
                ]);
            }

            // Sinon, on met à jour l'heure d'activité
            session(['lastActivityTime' => time()]);
        }

        return $next($request);
    }
}
