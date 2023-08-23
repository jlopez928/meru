@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-bold"><a href="{{ route('otrospagos.proceso.certificacionservicio.index') }}">Página Principal</a></li>
                    <li class="breadcrumb-item active text-bold">Aprobar Certificación de Servicios</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <x-form method="get" action="{{ route('otrospagos.proceso.certificacionservicio.aprobar_certificacion', $opsolservicio->id) }}">
                    <div class="col-12">
                        <x-card>
                            <x-slot name="header">
                                <h3 class="card-title text-bold">Certificación de Servicio</h3>
                            </x-slot>
                            <x-slot name="body">
                                <ul class="nav nav-tabs" id="TabCertificacion" role="tablist">
                                    <li class="nav-item" role="presentation">
                                         <button class="nav-link active" id="identificacion-tab" data-toggle="tab" data-target="#identificacion" type="button" role="tab" aria-controls="identificacion" aria-selected="true">Identificación</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button  class="nav-link" id="detalle-tab" data-toggle="tab" data-target="#detalle" type="button" role="tab" aria-controls="detalle" aria-selected="false">Detalle Del Servicio</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                         <button class="nav-link" id="condiciones-tab" data-toggle="tab" data-target="#condiciones" type="button" role="tab" aria-controls="condiciones" aria-selected="false">Condiciones de Negociación</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="comprobante-tab" data-toggle="tab" data-target="#comprobante" type="button" role="tab" aria-controls="comprobante" aria-selected="false">Comprobante Contable</button>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane container active" id="identificacion" role="tabpanel" aria-labelledby="identificacion-tab">
                                        @include('administrativo/meru_administrativo/otrospagos/proceso/certificacion_servicio/partials _show/_identificacion')
                                    </div>
                                    <div  class="tab-pane container fade" id="detalle" role="tabpanel" aria-labelledby="detalle-tab">
                                        @include('administrativo/meru_administrativo/otrospagos/proceso/certificacion_servicio/partials _show/_detalle')
                                    </div>
                                    <div   class="tab-pane container fade" id="condiciones" role="tabpanel" aria-labelledby="condiciones-tab">
                                        @include('administrativo/meru_administrativo/otrospagos/proceso/certificacion_servicio/partials _show/_condiciones')
                                    </div>
                                    <div   class="tab-pane container fade" id="comprobante" role="tabpanel" aria-labelledby="comprobante-tab">
                                        @include('administrativo/meru_administrativo/otrospagos/proceso/certificacion_servicio/partials _show/_comprobante')
                                    </div>
                                </div>
                            </x-slot>
                            <x-slot name="footer">
                                <x-input type="submit" class="btn btn-sm btn-primary text-bold float-right"  value="Aprobar" />
                            </x-slot>
                        </x-card>
                    </div>
                </x-form>
            </div>
        </div>
    </div>
</section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection





