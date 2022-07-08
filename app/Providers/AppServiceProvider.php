<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        // View::composer (app, Menu) - Siasm
        View::composer(['home'] , function ($view) {
            $view->with([
                    'app'       => 'Merú',
                    'menu'   => Menu::menus('meru')
            ]);
        });
    }
}
