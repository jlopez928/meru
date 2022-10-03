<?php

namespace App\Exports\Administrativo\Meru_Administrativo\Formulacion;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;

class ResumenPresupuestarioExport implements FromView, ShouldAutoSize
{
    use Exportable;

    protected $anoPro;
    protected $mes;
    protected $data;

    public function __construct($anoPro, $mes, $data)
    {
        $this->anoPro = $anoPro;
        $this->mes    = $mes;
        $this->data   = $data;
    }

    public function view(): View
    {
        return view('administrativo.meru_administrativo.formulacion.reportes.maestro_ley.ejecucionexcel', [
            'anoPro' => $this->anoPro,
            'mes'    => $this->mes,
            'data'   => $this->data
        ]);
    }
}