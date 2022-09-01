@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('home') }}">Página Principal</a></li>
					<li class="breadcrumb-item active text-bold">Reporte</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
                <x-form method="get" action="{{ route('proveedores.reporte.print_repprovobjetivo') }}">
                    <x-card>
                        <x-slot name="header">
                            <h3 class="card-title text-bold">Proveedores por Objetivo de la Empresa</h3>
                        </x-slot>

                        <x-slot name="body">
                            <div class="col-12">
                                <x-field class="form-group col-5 offset-3" >
                                    <x-label for="nom_prov">Nombre</x-label>
                                    <x-input name="nom_prov" class=" form-control-sm {{ $errors->has('nom_prov') ? 'is-invalid' : '' }}" type="text" placeholder="Ingrese Nombre" value=""   />
                                    <div class="invalid-feedback">
                                        @error('nom_prov') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="form-group col-5 offset-3" >
                                    <x-label for="objetivo">Actividad Comercial</x-label>
                                    <x-input name="objetivo" class=" form-control-sm {{ $errors->has('objetivo') ? 'is-invalid' : '' }}" type="text" placeholder="Ingrese Activida Comercial" value=""   />
                                    <div class="invalid-feedback">
                                        @error('objetivo') {{ $message }} @enderror
                                    </div>
                                </x-field>
                            </div>
                        </x-slot>
                        <x-slot:footer>
                                <button type="submit" class="btn btn-sm btn-primary text-bold float-right" title="Generar PDF">
                                    <i class="fas fa-download"> Generar PDF</i>
                                </button>
                        </x-slot:footer>
                    </x-card>
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
