
@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-12">
            <div class="col-sm-6">
                @if($nombreRuta =='contrato')
				    <x-button class="btn-success" href="{{ route('contratos.proceso.certificacioncontrato.crear', 'contrato') }}" title="Nuevo"><i class="fas fa-plus-circle"></i> Nuevo</x-button>
                    <x-button href="{{ route('contratos.proceso.print_certificacion_contrato', 'contrato')}}" target="_blank" class="btn-primary" title="Generar PDF"><i class="fas fa-download"> Generar PDF</i></i></x-button>
                @else
                    <x-button class="btn-success" href="{{ route('contratos.proceso.certificacioncontrato.crear', 'addendum') }}" title="Nuevo"><i class="fas fa-plus-circle"></i> Nuevo</x-button>
                    <x-button href="{{ route('contratos.proceso.print_certificacion_contrato', 'addendum')}}" target="_blank" class="btn-primary" title="Generar PDF"><i class="fas fa-download"> Generar PDF</i></i></x-button>
                @endif

            </div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('home') }}">Página principal</a></li>
                    @if($nombreRuta =='contrato')
                        <li class="breadcrumb-item active text-bold">Listar Certificación de Obras y Servicos</li>
                    @else
                        <li class="breadcrumb-item active text-bold">Listar Certificación de Obras y Servicos Addendum</li>
                    @endif
				</ol>
			</div>
		</div>
	</div>
</section>
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
                <livewire:administrativo.meru-administrativo.contratos.proceso.certificacion-contrato-index :nombreRuta="$nombreRuta"/>
			</div>
			</div>
		</div>
	</div>
</section>

@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection

