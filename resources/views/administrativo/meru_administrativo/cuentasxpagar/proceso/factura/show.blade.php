@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-bold"><a href="{{ route('cuentasxpagar.proceso.factura.index') }}">Página Principal</a></li>
                    <li class="breadcrumb-item active text-bold">Mostrar Factura</li>
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
                        <h3 class="card-title text-bold">Factura</h3>
                    </x-slot>
                    <x-slot name="body">
                        <ul class="nav nav-tabs" id="TabRecepFactura" role="tablist">
                            <li class="nav-item" role="presentation">
                                 <button class="nav-link active" id="factura-tab" data-toggle="tab" data-target="#factura" type="button" role="tab" aria-controls="factura" aria-selected="true">Datos de la Factura/Recibo</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button  class="nav-link" id="detalle-tab" data-toggle="tab" data-target="#detalle" type="button" role="tab" aria-controls="detalle" aria-selected="false">Detalle de Factura/Recibo</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button  class="nav-link" id="gastos-tab" data-toggle="tab" data-target="#gastos" type="button" role="tab" aria-controls="gastos" aria-selected="false">Estructura de Gastos</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button  class="nav-link" id="asiento-tab" data-toggle="tab" data-target="#asiento" type="button" role="tab" aria-controls="asiento" aria-selected="false">Asiento Contable</button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane  active" id="factura" role="tabpanel" aria-labelledby="factura-tab">
                                @include('administrativo/meru_administrativo/cuentasxpagar/proceso/factura/partials_show/_factura')
                            </div>
                            <div  class="tab-pane fade" id="detalle" role="tabpanel" aria-labelledby="detalle-tab">
                                 @include('administrativo/meru_administrativo/cuentasxpagar/proceso/factura/partials_show/_detalle')
                            </div>
                            <div  class="tab-pane fade" id="gastos" role="tabpanel" aria-labelledby="gastos-tab">
                                @include('administrativo/meru_administrativo/cuentasxpagar/proceso/factura/partials_show/_gastos')
                           </div>
                           <div  class="tab-pane fade" id="asiento" role="tabpanel" aria-labelledby="asiento-tab">
                            @include('administrativo/meru_administrativo/cuentasxpagar/proceso/factura/partials_show/_asiento')
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

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
			$('.money-mask').keypress(function (e) {
				if (e.which != 8 && e.which != 0 && e.which != 44 && (e.which < 48 || e.which > 57)) {
					return false;
				}
			});
        });
    </script>
@endpush

