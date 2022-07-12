<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));

        // Rutas Api de Aplicaciones Administrativas
        // Se agregan para manejar las rutas Api  del modulo Almacen
        Route::prefix('api/almacen')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/administrativo/meru_administrativo/almacen/api_almacen.php'));

        // Se agregan para manejar las rutas Api  del modulo Bienes
        Route::prefix('api/bienes')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/administrativo/meru_administrativo/bienes/api_bienes.php'));
        // Se agregan para manejar las rutas Api  del modulo Compras
        Route::prefix('api/compras')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/administrativo/meru_administrativo/compras/api_compras.php'));
        // Se agregan para manejar las rutas Api  del modulo Configuracion
        Route::prefix('api/configuracion')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/administrativo/meru_administrativo/configuracion/api_configuracion.php'));
        // Se agregan para manejar las rutas Api  del modulo Contabilidad
        Route::prefix('api/contabilidad')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/administrativo/meru_administrativo/contabilidad/api_contabilidad.php'));
        // Se agregan para manejar las rutas Api  del modulo Contratos
         Route::prefix('api/contratos')
         ->middleware('api')
         ->namespace($this->namespace)
         ->group(base_path('routes/administrativo/meru_administrativo/contratos/api_contratos.php'));
        // Se agregan para manejar las rutas Api  del modulo Cuentas por Pagar
        Route::prefix('api/cuentasxpagar')
        ->middleware('api')
        ->namespace($this->namespace)
        ->group(base_path('routes/administrativo/meru_administrativo/cuentasxpagar/api_cuentasxpagar.php'));
        // Se agregan para manejar las rutas Api  del modulo Formulación
        Route::prefix('api/formulacion')
        ->middleware('api')
        ->namespace($this->namespace)
        ->group(base_path('routes/administrativo/meru_administrativo/formulacion/api_formulacion.php'));
        // Se agregan para manejar las rutas Api  del modulo Gastos Reembolsables
        Route::prefix('api/gastosreembolsables')
        ->middleware('api')
        ->namespace($this->namespace)
        ->group(base_path('routes/administrativo/meru_administrativo/gastosreembolsables/api_gastosreembolsables.php'));
        // Se agregan para manejar las rutas Api  del modulo Modificaciones
        Route::prefix('api/modificaciones')
        ->middleware('api')
        ->namespace($this->namespace)
        ->group(base_path('routes/administrativo/meru_administrativo/modificaciones/api_modificaciones.php'));
        // Se agregan para manejar las rutas Api  del modulo Otros Pagos
        Route::prefix('api/otrospagos')
        ->middleware('api')
        ->namespace($this->namespace)
        ->group(base_path('routes/administrativo/meru_administrativo/otrospagos/api_otrospagos.php'));
        // Se agregan para manejar las rutas Api  del modulo Control Presupuestario
        Route::prefix('api/presupuesto')
        ->middleware('api')
        ->namespace($this->namespace)
        ->group(base_path('routes/administrativo/meru_administrativo/presupuesto/api_presupuesto.php'));
        // Se agregan para manejar las rutas Api  del modulo Proveedores
        Route::prefix('api/proveedores')
        ->middleware('api')
        ->namespace($this->namespace)
        ->group(base_path('routes/administrativo/meru_administrativo/proveedores/api_proveedores.php'));
        // Se agregan para manejar las rutas Api  del modulo RRHH
        Route::prefix('api/rrhh')
        ->middleware('api')
        ->namespace($this->namespace)
        ->group(base_path('routes/administrativo/meru_administrativo/rrhh/api_rrhh.php'));
        // Se agregan para manejar las rutas Api  del modulo Tesoreria
        Route::prefix('api/tesoreria')
        ->middleware('api')
        ->namespace($this->namespace)
        ->group(base_path('routes/administrativo/meru_administrativo/tesoreria/api_tesoreria.php'));
        // Se agregan para manejar las rutas Api  del modulo viaticos
        Route::prefix('api/viaticos')
        ->middleware('api')
        ->namespace($this->namespace)
        ->group(base_path('routes/administrativo/meru_administrativo/viaticos/api_viaticos.php'));


    }
    /**
    * Define the "web" routes for the application.
    *
    * These routes all receive session state, CSRF protection, etc.
    *
    * @return void
    */


   protected function mapWebRoutes()
   {
       Route::middleware('web')
           ->namespace($this->namespace)
           ->group(base_path('routes/web.php'));

       // Rutas Web de Aplicaciones Administrativas
       // Se agregan para manejar las rutas Web del modulo Almacen
       Route::middleware('web')
       ->namespace($this->namespace)
       ->group(base_path('routes/administrativo/meru_administrativo/almacen/web_almacen.php'));
       // Se agregan para manejar las rutas Web del modulo Bienes
       Route::middleware('web')
       ->namespace($this->namespace)
       ->group(base_path('routes/administrativo/meru_administrativo/bienes/web_bienes.php'));
      // Se agregan para manejar las rutas Web del modulo Compras
       Route::middleware('web')
       ->namespace($this->namespace)
       ->group(base_path('routes/administrativo/meru_administrativo/compras/web_compras.php'));
      // Se agregan para manejar las rutas Web del modulo Configuración
      Route::middleware('web')
      ->namespace($this->namespace)
      ->group(base_path('routes/administrativo/meru_administrativo/configuracion/web_configuracion.php'));
      // Se agregan para manejar las rutas Web del modulo Contabilidad
      Route::middleware('web')
      ->namespace($this->namespace)
      ->group(base_path('routes/administrativo/meru_administrativo/contabilidad/web_contabilidad.php'));
      // Se agregan para manejar las rutas Web del modulo Contratos
      Route::middleware('web')
      ->namespace($this->namespace)
      ->group(base_path('routes/administrativo/meru_administrativo/contratos/web_contratos.php'));
      // Se agregan para manejar las rutas Web del modulo Cuentas por Pagar
      Route::middleware('web')
      ->namespace($this->namespace)
      ->group(base_path('routes/administrativo/meru_administrativo/cuentasxpagar/web_cuentasxpagar.php'));
      // Se agregan para manejar las rutas Web del modulo Formulación
      Route::middleware('web')
      ->namespace($this->namespace)
      ->group(base_path('routes/administrativo/meru_administrativo/formulacion/web_formulacion.php'));
       // Se agregan para manejar las rutas Web del modulo Gastos Reembonsables
       Route::middleware('web')
       ->namespace($this->namespace)
       ->group(base_path('routes/administrativo/meru_administrativo/gastosreembolsables/web_gastosreembolsables.php'));
      // Se agregan para manejar las rutas Web del modulo Modificaciones
      Route::middleware('web')
      ->namespace($this->namespace)
      ->group(base_path('routes/administrativo/meru_administrativo/modificaciones/web_modificaciones.php'));
      // Se agregan para manejar las rutas Web del modulo Otros Pagos
      Route::middleware('web')
      ->namespace($this->namespace)
      ->group(base_path('routes/administrativo/meru_administrativo/otrospagos/web_otrospagos.php'));
      // Se agregan para manejar las rutas Web del modulo Presupuestos
      Route::middleware('web')
      ->namespace($this->namespace)
      ->group(base_path('routes/administrativo/meru_administrativo/presupuesto/web_presupuesto.php'));
      // Se agregan para manejar las rutas Web del modulo Proveedores
      Route::middleware('web')
      ->namespace($this->namespace)
      ->group(base_path('routes/administrativo/meru_administrativo/proveedores/web_proveedores.php'));
      // Se agregan para manejar las rutas Web del modulo RRHH
      Route::middleware('web')
      ->namespace($this->namespace)
      ->group(base_path('routes/administrativo/meru_administrativo/rrhh/web_rrhh.php'));
      // Se agregan para manejar las rutas Web del modulo Tesoreria
      Route::middleware('web')
      ->namespace($this->namespace)
      ->group(base_path('routes/administrativo/meru_administrativo/tesoreria/web_tesoreria.php'));
      // Se agregan para manejar las rutas Web del modulo Viaticos
      Route::middleware('web')
      ->namespace($this->namespace)
      ->group(base_path('routes/administrativo/meru_administrativo/viaticos/web_viaticos.php'));

    }
    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
