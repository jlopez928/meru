@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-bold"><a href="{{ route('home') }}">Página Principal</a></li>
                    <li class="breadcrumb-item text-bold"><a href="{{ route('formulacion.configuracion.maestro_ley.index') }}">Maestro de Ley</a></li>
                    <li class="breadcrumb-item active text-bold">Importar Excel</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <x-form method="post" action="{{ route('formulacion.configuracion.maestro_ley.importar.store') }}" enctype="multipart/form-data">
                    <x-card>
                        <x-slot name="header">
                            <h3 class="card-title text-bold">Importar Maestro de Ley</h3>
                        </x-slot>

                        <x-slot name="body">

                            <div class="row col-12">
                                <div class="form-group col-4 offset-4">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="import_file" name="import_file">
                                            <label class="custom-file-label" for="import_file">Elegir archivo...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <button class="btn btn-success btn-bold" type="submit"><i class="fas fa-file-import"></i>&nbsp;Importar</button>
                                        </div>
                                    </div>

                                    @error('import_file')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            @if (!empty($failures))
                                <div class="row col-12">
                                    <div class="card card-outline col-6 offset-3 card-danger">
                                        <div class="card-header text-bold text-danger">
                                            Errores encontrados
                                        </div>

                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" width="10%">Fila</th>
                                                            <th class="text-center" width="25%">Columna</th>
                                                            <th class="text-center" width="65%">Error</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($failures as $error)
                                                            <tr>
                                                                <td class="text-center" style="vertical-align: middle;"> {{ $error['fila'] }}</td>
                                                                <td class="text-center" style="vertical-align: middle;"> {{ $error['columna'] }}</td>
                                                                <td style="vertical-align: middle;"> {{ $error['error'] }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </x-slot>

                        <x-slot name="footer">
                        </x-slot>
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

@push('scripts')
    <script type="text/javascript"> 
        $(function () {
            bsCustomFileInput.init();
        });
    </script>
@endpush