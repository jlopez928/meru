@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-bold"><a href="{{ route('cuentasxpagar.proceso.recepfactura.index') }}">Página Principal</a></li>
                    <li class="breadcrumb-item active text-bold">Mostrar Recepción de Factura</li>
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
                        <h3 class="card-title text-bold">Recepción Factura</h3>
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

