<?php

namespace App\Providers;

use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
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
		// View::composer (app, Menu)
		View::composer(['home'] , function ($view) {
			$view->with([
				'app'      => 'Merú',
				'menu'     => Menu::menus('meru'),
				'periodos' => RegistroControl::periodosAbiertos(),
			]);
		});

		View::composer(['administrativo.meru_administrativo.*'] , function ($view) {
			$view->with([
				'app'      => 'Merú',
				'menu'     => Menu::menus('meru'),
				'periodos' => RegistroControl::periodosAbiertos(),
			]);
		});
	}
}