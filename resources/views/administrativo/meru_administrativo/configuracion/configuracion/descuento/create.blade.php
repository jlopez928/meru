@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-bold"><a href="{{ route('configuracion.configuracion.descuento.index') }}">Descuentos y Retenciones </a></li>
                    <li class="breadcrumb-item active text-bold">Registrar Descuentos y Retenciones</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <x-form method="post" action="{{ route('configuracion.configuracion.descuento.store') }}">
                       @include('administrativo/meru_administrativo/configuracion/configuracion/descuento/partials/_form', ['submit_text' => 'Guardar'])
                </x-form>
            </div>
        </div>
    </div>
</section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection


@section('js')
    <script type="text/javascript">
        window.livewire.on('alert', param => {
			toastr[param['type']](param['message']);
		});
    </script>
@endsection
