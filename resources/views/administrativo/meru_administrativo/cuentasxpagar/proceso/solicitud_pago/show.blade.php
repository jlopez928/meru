@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">

                        <li class="breadcrumb-item text-bold"><a href="{{ route('cuentasxpagar.proceso.solicititudpago.index') }}">Página Principal</a></li>
                        <li class="breadcrumb-item active text-bold">Mostrar Solicitud de Pago</li>

                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
                <x-card>
                    <x-slot name="header">
                            <h3 class="card-title text-bold">SOLICITUD DE PAGO - REGISTRO DE RETENCIONES Y DEDUCCIONES</h3>
                    </x-slot>
                    <x-slot name="body">
                        <ul class="nav nav-tabs" id="TabCertificacion" role="tablist">
                            <li class="nav-item" role="presentation">
                                 <button class="nav-link active" id="identificacion-tab" data-toggle="tab" data-target="#identificacion" type="button" role="tab" aria-controls="identificacion" aria-selected="true">Datos de la Solicitud</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button  class="nav-link" id="detalle-tab" data-toggle="tab" data-target="#detalle" type="button" role="tab" aria-controls="detalle" aria-selected="false">Descuentos Especiales</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button  class="nav-link" id="condiciones-tab" data-toggle="tab" data-target="#condiciones" type="button" role="tab" aria-controls="condiciones" aria-selected="false">Gastos y Retenciones</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="comprobante-tab" data-toggle="tab" data-target="#comprobante" type="button" role="tab" aria-controls="comprobante" aria-selected="false">C.C Iva y retenciones</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="notacredito-tab" data-toggle="tab" data-target="#notacredito" type="button" role="tab" aria-controls="notacredito" aria-selected="false">C.C  Nota de Crédito/Débito</button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane container active" id="identificacion" role="tabpanel" aria-labelledby="identificacion-tab">
                                @include('administrativo/meru_administrativo/cuentasxpagar/proceso/solicitud_pago/partials _show/_identificacion')
                            </div>
                            <div  class="tab-pane container fade" id="detalle" role="tabpanel" aria-labelledby="detalle-tab">
                                @include('administrativo/meru_administrativo/cuentasxpagar/proceso/solicitud_pago/partials _show/_descuento')
                            </div>
                            <div  class="tab-pane container fade" id="condiciones" role="tabpanel" aria-labelledby="condiciones-tab">
                                @include('administrativo/meru_administrativo/cuentasxpagar/proceso/solicitud_pago/partials _show/_gasto')
                            </div>
                            <div   class="tab-pane container fade" id="comprobante" role="tabpanel" aria-labelledby="comprobante-tab">
                                @include('administrativo/meru_administrativo/cuentasxpagar/proceso/solicitud_pago/partials _show/_comprobante')
                            </div>
                            <div   class="tab-pane container fade" id="notacredito" role="tabpanel" aria-labelledby="notacredito-tab">
                                @include('administrativo/meru_administrativo/cuentasxpagar/proceso/solicitud_pago/partials _show/_notascredito')
                            </div>
                        </div>
                    </x-slot>
                </x-card>
			</div>
		</div>
	</div>
</section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection



