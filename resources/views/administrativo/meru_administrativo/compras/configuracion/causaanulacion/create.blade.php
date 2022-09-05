@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-bold"><a href="{{ route('compras.configuracion.causaanulacion.index') }}">Causas de Anulación </a></li>
                    <li class="breadcrumb-item active text-bold">Registrar Causas de Anulación</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <x-form method="post" action="{{ route('compras.configuracion.causaanulacion.store') }}">
                       @include('administrativo/meru_administrativo/compras/configuracion/causaanulacion/partials/_form', ['submit_text' => 'Guardar'])
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
