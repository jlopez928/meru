<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Modificaciones\Movimientos;

use App\Enums\Administrativo\Meru_Administrativo\Modificaciones\EstadoSolicitudTraspaso;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administrativo\Meru_Administrativo\Modificaciones\Movimientos\SolicitudTraspasoRequest;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\CorrModificaciones;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\SolicitudTraspaso;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\SolicitudTraspasoTraspasoDetalle;
use App\Models\User;
use App\Support\Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudTraspasoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administrativo.meru_administrativo.modificaciones.movimientos.solicitud_traspaso.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $solicitudTraspaso = new SolicitudTraspaso;
        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.solicitud_traspaso.create',
            compact('solicitudTraspaso')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SolicitudTraspasoRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->safe()->except('estructuras');
            $corr = CorrModificaciones::find($this->anoPro);
            
            if (is_null($corr)) {
                $corr = CorrModificaciones::create([
                    'ano_pro' => $this->anoPro,
                    'nro_reg' => 1,
                    'num_sol' => 1
                ]);
            }

            $solicitud = SolicitudTraspaso::create($data + [
                'nro_sol' => $corr->num_sol,
                'sta_reg' => EstadoSolicitudTraspaso::Creada,
                'usuario' => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                'user_id' => auth()->user()->id,
            ]);

            $idSolicitud = $solicitud->id;

            $estructuras = json_decode($request->get('estructuras'), true);

            foreach($estructuras as $key => $row) {
                $maestro = MaestroLey::where('ano_pro', $this->anoPro)->where('cod_com', $row['cod_com'])->first();
                SolicitudTraspasoTraspasoDetalle::create($row + [
                    'ano_pro'               => $this->anoPro,
                    'nro_sol'               => $corr->num_sol,
                    'solicitud_traspaso_id' => $idSolicitud,
                    'maestro_ley_id'        => $maestro->id
                ]);
            }

            $corr->increment('num_sol');
            DB::commit();

            alert()->success('¡Éxito!', 'Registro Creado Exitosamente');
            return to_route('modificaciones.movimientos.solicitud_traspaso.index');
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SolicitudTraspaso $solicitudTraspaso)
    {
        $gerencias = Gerencia::all();
        $estructuras = $solicitudTraspaso->obtenerEstructuras();

        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.solicitud_traspaso.show',
            compact('solicitudTraspaso', 'gerencias', 'estructuras')
        ); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SolicitudTraspaso $solicitudTraspaso)
    {
        if (!in_array($solicitudTraspaso->sta_reg->value, [0, 2]) ) {
            alert()->warning('¡Advertencia!', 'Solicitud de traspaso en estado inválido para Editar');
            return redirect()->route('modificaciones.movimientos.solicitud_traspaso.index');
        }

        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.solicitud_traspaso.edit',
            compact('solicitudTraspaso')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SolicitudTraspasoRequest $request, SolicitudTraspaso $solicitudTraspaso)
    {
        DB::beginTransaction();

        try {
            $data = $request->safe()->only([
                'num_sop',
                'nro_ext',
                'concepto',
                'justificacion',
                'total',
            ]);

            $solicitudTraspaso->update($data + [
                'sta_reg' => EstadoSolicitudTraspaso::Modificada,
                'usu_sta' => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                'fec_sta' => $this->fechaGuardar,
            ]);

            SolicitudTraspasoTraspasoDetalle::where('solicitud_traspaso_id', $solicitudTraspaso->id)->delete();

            $estructuras = json_decode($request->get('estructuras'), true);

            foreach($estructuras as $key => $row) {
                $maestro = MaestroLey::where('ano_pro', $this->anoPro)->where('cod_com', $row['cod_com'])->first();
                SolicitudTraspasoTraspasoDetalle::create($row + [
                    'ano_pro'               => $solicitudTraspaso->ano_pro,
                    'nro_sol'               => $solicitudTraspaso->nro_sol,
                    'solicitud_traspaso_id' => $solicitudTraspaso->id,
                    'maestro_ley_id'        => $maestro->id
                ]);
            }

            DB::commit();

            alert()->success('¡Éxito!', 'Registro Modificado Exitosamente');
            return redirect()->route('modificaciones.movimientos.solicitud_traspaso.index');
        } catch(\Exception $e){
            DB::rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function aprobarEdit(SolicitudTraspaso $solicitudTraspaso)
    {
        if (!in_array($solicitudTraspaso->sta_reg->value, [0, 2]) ) {
            alert()->warning('¡Advertencia!', 'Solicitud de traspaso en estado inválido para Aprobar');
            return redirect()->route('modificaciones.movimientos.solicitud_traspaso.index');
        }

        $gerencias = Gerencia::all();
        $estructuras = $solicitudTraspaso->obtenerEstructuras();
        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.solicitud_traspaso.aprobar',
            compact('solicitudTraspaso', 'gerencias', 'estructuras')
        );
    }

    public function aprobarUpdate(Request $request, SolicitudTraspaso $solicitudTraspaso)
    {
        try {
            $usuario = \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email);
            $solicitudTraspaso->update([
                'sta_reg'     => EstadoSolicitudTraspaso::Aprobada,
                'usu_sta'     => $usuario,
                'fec_sta'     => $this->fechaGuardar,
                'usu_apr'     => $usuario,
                'fec_apr'     => $this->fechaGuardar,
                'user_id_apr' => auth()->user()->id
            ]);

            alert()->success('¡Éxito!', 'Registro Aprobado Exitosamente');
            return redirect()->route('modificaciones.movimientos.solicitud_traspaso.index');
        } catch(\Exception $e) {
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function anularEdit(SolicitudTraspaso $solicitudTraspaso)
    {
        if (!in_array($solicitudTraspaso->sta_reg->value, [0, 2]) ) {
            alert()->warning('¡Advertencia!', 'Solicitud de traspaso en estado inválido para anular');
            return redirect()->route('modificaciones.movimientos.solicitud_traspaso.index');
        }

        $gerencias = Gerencia::all();
        $estructuras = $solicitudTraspaso->obtenerEstructuras();
        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.solicitud_traspaso.anular',
            compact('solicitudTraspaso', 'gerencias', 'estructuras')
        );
    }

    public function anularUpdate(Request $request, SolicitudTraspaso $solicitudTraspaso)
    {
        $validar = $request->validate([
            'cau_anu' => 'required|string|between:1,500',
        ]);

        try {
            $solicitudTraspaso->update($validar + [
                'sta_reg' => EstadoSolicitudTraspaso::Anulada,
                'usu_sta' => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                'fec_sta' => $this->fechaGuardar,
            ]);
            
            alert()->success('¡Éxito!', 'Registro Anulado Exitosamente');
            return redirect()->route('modificaciones.movimientos.solicitud_traspaso.index');
        } catch(\Exception $e) {
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function rechazarEdit(SolicitudTraspaso $solicitudTraspaso)
    {
        if ($solicitudTraspaso->sta_reg->value != 1) {
            alert()->warning('¡Advertencia!', 'Solicitud de traspaso en estado inválido para Rechazar');
            return redirect()->route('modificaciones.movimientos.solicitud_traspaso.index');
        }

        $gerencias = Gerencia::all();
        $estructuras = $solicitudTraspaso->obtenerEstructuras();
        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.solicitud_traspaso.rechazar',
            compact('solicitudTraspaso', 'gerencias', 'estructuras')
        );
    }

    public function rechazarUpdate(Request $request, SolicitudTraspaso $solicitudTraspaso)
    {
        try {
            $solicitudTraspaso->update(
                ['sta_reg' => EstadoSolicitudTraspaso::Modificada,
                'usu_sta' => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                'fec_sta' => $this->fechaGuardar,
            ]);

            alert()->success('¡Éxito!', 'Registro Reversado Exitosamente');
            return redirect()->route('modificaciones.movimientos.solicitud_traspaso.index');
        } catch(\Exception $e) {
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function imprimir(SolicitudTraspaso $solicitudTraspaso)
    {
        $pdf = new Fpdf('P','mm','letter');
        $pdf->AddPage('P');

        $pdf->SetY(10);
        $pdf->SetFont('Arial','B',6);
        $pdf->Cell(180,5,utf8_decode('Código: F-PP-003'),0,0,'R');
        $pdf->Ln(3);

        $pdf->SetFont('Arial','B',6);
        $pdf->Cell(180,5,utf8_decode('Vigencia: 24/01/2018'),0,0,'R');
        $pdf->Ln(3);

        $pdf->SetFont('Arial','B',6);
        $pdf->Cell(180,5,utf8_decode('Revisión: 2'),0,0,'R');
        $pdf->Ln(4);

        /////////////////////////////////////////////////////////////////////////////////////////

        $pdf->SetFont('Arial','B',9);
        $pdf->SetY(8);
        $pdf->SetX(20);
        $pdf->Image('img/logo_superior_izquierdo.png',10,15,40,13,'PNG');
        $pdf->Image('img/logo_superior_derecho.png',190,9,12,12,'PNG');
        $pdf->Image('img/logo_superior_centro.png', 80,10,60,8,'PNG');

        $pdf->SetY(30);
        $pdf->SetX(170);    
        $pdf->Cell(55,4,utf8_decode('Nº Solicitud  '). $solicitudTraspaso->ano_pro . '-'. $solicitudTraspaso->nro_sol,0,0,'L',0);
        $pdf->Ln(12);

        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','B',10);  
        $pdf->SetX(10);
        $pdf->Cell(187,5,utf8_decode('SOLICITUD DE TRASPASO PRESUPUESTARIO'),0,0,'C',1);
        $pdf->Ln(10);

        /// Cuerpo de Reporte ///
        $pdf->SetX(10);
        $pdf->SetFillColor(205,205,205);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(150,5,utf8_decode('GERENCIA'),1,0,'C',1);
        $pdf->SetX(160);
        $pdf->Cell(50,5,utf8_decode('FECHA'),1,0,'C',1);
        $pdf->Ln();

        $pdf->SetX(10);
        $pdf->SetFont('Arial','',7);
        $pdf->SetFillColor(255,255,255);
        $pdf->Cell(150,12,utf8_decode($solicitudTraspaso->gerencia->des_ger),1,0,'C',1);
        $pdf->SetX(160);
        $pdf->Cell(50,12,\Carbon\Carbon::parse($solicitudTraspaso->fec_sol)->format('d/m/Y'),1,0,'C',1);
        $pdf->Ln();

        $pdf->SetX(10);
        $pdf->SetFillColor(205,205,205);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(150,5,utf8_decode('CENTRO DE COSTO'),1,0,'C',1);
        $pdf->SetX(160);
        $pdf->Cell(50,5,utf8_decode('EXTENSIÓN'),1,0,'C',1);
        $pdf->Ln();

        $pdf->SetX(10);
        $pdf->SetFont('Arial','',8);
        $pdf->SetFillColor(255,255,255);
        $pdf->Cell(150,12,utf8_decode($solicitudTraspaso->gerencia->centro_costo),1,0,'C',1);
        $pdf->SetX(160);
        $pdf->Cell(50,12,utf8_decode($solicitudTraspaso->nro_ext),1,0,'C',1);
        $pdf->Ln();

        /// Detalle de Solicitud ///
        $pdf->SetX(10);
        $pdf->SetFillColor(205,205,205);
        $pdf->SetFont('Arial','B',10);  
        $pdf->Cell(150,5,utf8_decode('ESTRUCTURAS PRESUPUESTARIAS'),1,0,'C',1);
        $pdf->SetX(160);
        $pdf->Cell(50,5,utf8_decode('MONTO REQUERIDO'),1,0,'C',1);
        $pdf->Ln();

        $estructuras = $solicitudTraspaso->obtenerEstructuras();

        foreach($estructuras as $row)
        {
            $pdf->SetX(10);
            $pdf->SetFont('Arial','',8);
            $pdf->SetFillColor(255,255,255);            
            $pdf->SetAligns(array('C','R'));
            $pdf->SetWidths(array(150,50));
            $pdf->Row(array(utf8_decode($row['cod_com']), number_format($row['mto_tra'],2,',','.')),5);
        }

        $pdf->SetX(10);
        $pdf->SetFillColor(205,205,205);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(200,5,utf8_decode('CONCEPTO'),1,0,'C',1);
        $pdf->Ln();

        $pdf->SetX(10);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(200,35,utf8_decode($solicitudTraspaso->concepto),1,0,'J',1);
        $pdf->Ln();

        $pdf->SetX(10);
        $pdf->SetFillColor(205,205,205);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(200,5,utf8_decode('JUSTIFICACIÓN'),1,0,'C',1);
        $pdf->Ln();

        $pdf->SetX(10);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(200,5,utf8_decode($solicitudTraspaso->justificacion),1,'J',0);
        $pdf->Ln(50);

        /// Firmas ///
        $pdf->SetX(60);
        $pdf->SetFillColor(205,205,205);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(100,5,utf8_decode('APROBADO POR'),1,0,'C',1);
        $pdf->Ln();

        $pdf->SetX(60);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);

        $usuApr = User::find($solicitudTraspaso->user_id_apr);
        $pdf->Cell(100,20,utf8_decode($usuApr->name),1,0,'C',1);

        $pdf->SetY(245);

        $pdf->SetFont('Arial','',6);
        $pdf->SetTextColor(0,0,0);
        $pdf->setx(80);
        $pdf->Cell(80,2,utf8_decode("VISIÓN: SER LA HIDROLÓGICA DE REFERENCIA NACIONAL"));
        $pdf->ln(3);
        $pdf->setxy(35,250);
        $pdf->MultiCell(155,3,utf8_decode('El logotipo de Certificación está relacionado con los Procesos de Captación, Tratamiento y Almacenamiento en los Ac. Industrial, Pto. Ordaz y Macagua- San Felix de la Empresa HIDROBOLIVAR, C.A.'." "),2,'C');

        $pdf->Image('img/logo_inferior_izquierdo.png', 10,243,13,11,'PNG');
        $pdf->Image('img/logo_inferior_centro.png', 100,233,32,9,'PNG');
        $pdf->Image('img/logo_inferior_derecho.png', 190,243,10,10,'PNG');

        $pdf->Output();
        exit();
    }
}