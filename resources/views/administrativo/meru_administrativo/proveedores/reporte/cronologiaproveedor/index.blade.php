
@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-12">
            <div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('home') }}">P&aacute;gina principal</a></li>
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
                    <x-form method="get" target="_blank" action="{{ route('proveedores.reporte.print_cronologiaproveedor') }}">
                        <x-card>
                            <x-slot:header>
                                <h3 class="card-title text-bold">Cronologia de Inscripcion de Proveedores</h3>
                            </x-slot>
                            <x-slot:body>
                                    <div class="row col-6 offset-3">
                                            <x-label for="lrif_ben">Beneficiario</x-label>
                                            <x-select   id="rif_prov" name="rif_prov" class="form-control select2bs4 text-center form-control-sm {{ $errors->has('rif_prov') ? 'is-invalid' : '' }}">
                                                <option value="">-- Seleccione Beneficiario --</option>
                                                @foreach($beneficiario as $label)
                                                 <option value="{{ $label->rif_prov }}" isSelected($value)>{{ $label->nom_prov }}</option>
                                                @endforeach
                                            </x-select>
                                            <div class="invalid-feedback">
                                                @error('rif_prov') {{ $message }} @enderror
                                            </div>
                                    </div>
                                <br>
                                <div class="row col-12">
                                    <x-field class="text-center col-2 offset-4">
                                        <x-label for="fec_ini">Fecha Inicio</x-label>
                                        <x-input     id="fec_ini"  name="fec_ini" type="date" class="text-center form-control-sm  {{ $errors->has('fec_ini') ? 'is-invalid' : '' }}"  />
                                        <div class="invalid-feedback">
                                            @error('fec_ini') {{ $message }} @enderror
                                        </div>
                                    </x-field>

                                    <x-field class="text-center col-2">
                                        <x-label for="fec_fin">Fecha Fin</x-label>
                                        <x-input    id="fec_fin"  name="fec_fin" type="date"  class="text-center form-control-sm  {{ $errors->has('fec_fin') ? 'is-invalid' : '' }}"  />
                                        <div class="invalid-feedback">
                                            @error('fec_fin') {{ $message }} @enderror
                                        </div>
                                    </x-field>
                                </div>
                                <div class="info-tab tip-icon" title="Useful Tips"><i></i></div>
                                <br>

                                <!-- Warning Alert -->

                                <div class="shadow p-3 mb-5 bg-body rounded">Regular shadow

                                    <div class="info-tab tip-icon" title="Useful Tips"><i></i></div>
                                </div>
                                <div class="alert alert-light alert-dismissible fade show">
                                    <strong>Light!</strong> This is a simple light alert box.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>

                                <div class="col-12">
                                    @if ($errors->has('mensaje'))
                                        <div class="text-center alert alert-warning">
                                            <p>{{ $errors->first('mensaje') }}</p>
                                        </div>
                                    @endif
                                 </div>
                             </x-slot>
                            <x-slot:footer>
                                <button type="submit" class="btn btn-sm btn-primary text-bold float-right" title="Generar PDF"><i class="fas fa-download"> Generar PDF</i></button>
                            </x-slot>
                        </x-card>
                    </x-form>

            </div>
        </div>
    </div>
</section>

@endsection
@push('scripts')
    <script type="text/javascript">

        $(function () {
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                minimumInputLength: 2,
                language: {
                    noResults: function() {
                        return 'No se encontraron resultados';
                    },
                    inputTooShort: function() {
                        return 'Ingrese al menos dos letras';
                    }
                }
            })
            });
    </script>
@endpush



@section('sidebar')
    @include('layouts.sidebar')
@endsection

