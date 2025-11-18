<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * L'URL vers laquelle les utilisateurs seront redirigés après connexion.
     *
     * @var string
     */
    public const HOME = '/'; // Modifier ici pour que les utilisateurs soient redirigés vers "/"

    /**
     * Définir les routes du système.
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });
    }

    /**
     * Configurer la limitation de vitesse des requêtes API.
     */
    protected function configureRateLimiting()
    {
        //
    }
}
