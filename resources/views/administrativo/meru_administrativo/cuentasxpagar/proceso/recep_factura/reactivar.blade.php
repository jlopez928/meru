@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-bold"><a href="{{ route('cuentasxpagar.proceso.recepfactura.index') }}">Página Principal</a></li>
                    <li class="breadcrumb-item active text-bold">Reactivar Factura</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
                <x-form method="post" action="{{ route('cuentasxpagar.proceso.recepfactura.reactivar',$recepfactura->id) }}">
                    <x-card>
                        <x-slot name="header">
                            <h3 class="card-title text-bold">Reactivar Factura</h3>
                        </x-slot>
                        <x-slot name="body">
                            <ul class="nav nav-tabs" id="TabRecepFactura" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="recepcion-tab" data-toggle="tab" data-target="#recepcion" type="button" role="tab" aria-controls="recepcion" aria-selected="true">Recepción Factura</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button  class="nav-link" id="devolucion-tab" data-toggle="tab" data-target="#devolucion" type="button" role="tab" aria-controls="devolucion" aria-selected="false">Devolución Factura</button>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane  active" id="recepcion" role="tabpanel" aria-labelledby="recepcion-tab">
                                    @include('administrativo/meru_administrativo/cuentasxpagar/proceso/recep_factura/partials_show/_recepcion')
                                </div>
                                <div  class="tab-pane fade" id="devolucion" role="tabpanel" aria-labelledby="devolucion-tab">
                                    @include('administrativo/meru_administrativo/cuentasxpagar/proceso/recep_factura/partials_show/_devolucion')
                                </div>
                            </div>
                        </x-slot>
                        <x-slot name="footer">
                            <x-input id="guardar" name="guardar" type="submit" class="btn btn-sm btn-primary text-bold float-right"  value="Reativar" />
                        </x-slot>
                    </x-card>
                </x-form>
		</div>
	</div>
</section>


@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection

