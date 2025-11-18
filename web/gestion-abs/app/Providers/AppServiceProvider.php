<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Annonce;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('fr');
        Paginator::useBootstrap();

        // Injection automatique du nombre de nouvelles annonces
        View::composer('*', function ($view) {
            /** @var \App\Models\User $user */
            if (Auth::check()) {
                $role = Auth::user()->getRoleNames()->first();
                $nouvellesAnnonces = Annonce::where('audience', $role)->where('vue', false)->count();
                $view->with('nouvellesAnnonces', $nouvellesAnnonces);
            }
        });
    }
}
