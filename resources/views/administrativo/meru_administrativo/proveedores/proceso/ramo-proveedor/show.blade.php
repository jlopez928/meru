@extends('layouts.aplicacion')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-bold"><a href="{{ route('home') }}">P&aacute;gina principal</a></li>
                        <li class="breadcrumb-item text-bold"><a href="{{ route('proveedores.proceso.ramo_proveedor.index') }}">Ramos de Proveedores</a></li>
                        <li class="breadcrumb-item active text-bold">Mostrar</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <x-card>
            <x-slot:header>
                <h3 class="card-title text-bold">Proveedor</h3>
            </x-slot>
            <x-slot:body>
                <div class="row col-12">
                    <x-field class="col-2">
                        <x-label for="rif_prov">Rif</x-label>
                        <x-input class="form-control-sm" name="rif_prov" value="{{ $proveedor->rif_prov }}" readonly />
                    </x-field>
                    <x-field class="col-6">
                        <x-label >Nombre</x-label>
                        <x-input class="form-control-sm" name="nom_prov" value="{{ $proveedor->nom_prov }}" readonly />
                    </x-field>
                </div>
                <div class="row col-12 mt-3">
                    <x-card class="card-secondary col-12">
                        <x-slot:header>
                            <h3 class="card-title text-bold" >Ramos</h3>
                        </x-slot>

                        <x-slot:body>
                            <table class="table table-bordered table-sm text-center">
                                <thead>
                                    <tr align="center" scope="col" class="table-success" >
                                        <th>Código</th>
                                        <th>Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($proveedor->ramos as $ramo)
                                        <tr>
                                            <td class="text-center">{{ $ramo->cod_ram }}</td>
                                            <td class="text-center">{{ $ramo->des_ram  }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No se encontraron registros.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </x-slote=>
                    </x-card>
                </div>
            </x-slot>
        </x-card>
    </section>
@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection
