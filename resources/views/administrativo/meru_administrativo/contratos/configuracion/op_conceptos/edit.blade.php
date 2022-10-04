@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-bold"><a href="{{ route('contratos.configuracion.conceptoscontratos.index') }}">Página Principal</a></li>
                    <li class="breadcrumb-item active text-bold">Modificar Conceptos de Contratos</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                    <x-form method="put" action="{{ route('contratos.configuracion.conceptoscontratos.update', $conceptoscontrato->id) }}">
                            <livewire:administrativo.meru-administrativo.contratos.configuracion.conceptos-contratos :conceptoscontratos="$conceptoscontrato" />
                    </x-form>
            </div>
        </div>
    </div>
</section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection
