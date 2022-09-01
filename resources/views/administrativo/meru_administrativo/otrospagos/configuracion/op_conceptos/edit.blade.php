@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-bold"><a href="{{ route('otrospagos.configuracion.conceptoservicio.index') }}">Página Principal</a></li>
                    <li class="breadcrumb-item active text-bold">Modificar Conceptos de Servicios</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                    <x-form method="put" action="{{ route('otrospagos.configuracion.conceptoservicio.update', $conceptoservicio->id) }}">
                            <livewire:administrativo.meru-administrativo.otros-pagos.configuracion.op-conceptos :conceptoservicio="$conceptoservicio" />
                    </x-form>
            </div>
        </div>
    </div>
</section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection
