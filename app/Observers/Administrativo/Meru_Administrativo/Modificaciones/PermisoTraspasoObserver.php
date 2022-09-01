<?php

namespace App\Observers\Administrativo\Meru_Administrativo\Modificaciones;

use App\Models\Administrativo\Meru_Administrativo\Modificaciones\HistoricoPermisoTraspaso;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\PermisoTraspaso;
use Exception;

class PermisoTraspasoObserver
{
    /**
     * Handle the PermisoTraspaso "created" event.
     *
     * @param  \App\Models\Administrativo\Meru_Administrativo\Modificaciones\PermisoTraspaso  $permisoTraspaso
     * @return void
     */
    public function created(PermisoTraspaso $permisoTraspaso)
    {
        HistoricoPermisoTraspaso::create(
            $permisoTraspaso->only([
                'usuario',
                'maxut',
                'multicentro',
                'usuario_id',
                'user_id'
            ]) + 
            ['usu_mod' => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email)]);
    }

    /**
     * Handle the PermisoTraspaso "updated" event.
     *
     * @param  \App\Models\Administrativo\Meru_Administrativo\Modificaciones\PermisoTraspaso  $permisoTraspaso
     * @return void
     */
    public function updated(PermisoTraspaso $permisoTraspaso)
    {
        HistoricoPermisoTraspaso::create(
            $permisoTraspaso->only([
                'usuario',
                'maxut',
                'multicentro',
                'usuario_id',
                'user_id'
            ]) +
            ['usu_mod' => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email)]);
    }

    /**
     * Handle the PermisoTraspaso "deleted" event.
     *
     * @param  \App\Models\Administrativo\Meru_Administrativo\Modificaciones\PermisoTraspaso  $permisoTraspaso
     * @return void
     */
    public function deleted(PermisoTraspaso $permisoTraspaso)
    {
        HistoricoPermisoTraspaso::create(
            $permisoTraspaso->only([
                'usuario',
                'maxut',
                'multicentro',
                'usuario_id',
                'user_id'
            ]) + [
                'usu_mod' => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                'activo'  => false
            ]);
    }

    /**
     * Handle the PermisoTraspaso "restored" event.
     *
     * @param  \App\Models\Administrativo\Meru_Administrativo\Modificaciones\PermisoTraspaso  $permisoTraspaso
     * @return void
     */
    public function restored(PermisoTraspaso $permisoTraspaso)
    {
        //
    }

    /**
     * Handle the PermisoTraspaso "force deleted" event.
     *
     * @param  \App\Models\Administrativo\Meru_Administrativo\Modificaciones\PermisoTraspaso  $permisoTraspaso
     * @return void
     */
    public function forceDeleted(PermisoTraspaso $permisoTraspaso)
    {
        //
    }
}
