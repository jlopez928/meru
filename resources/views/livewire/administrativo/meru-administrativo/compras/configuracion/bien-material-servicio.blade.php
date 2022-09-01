<x-card class="card-secondary col-12 mt-3">
    <x-slot:header>
        <h3 class="card-title text-bold" >BIENES/MATERIALES/SERVICIOS</h3>
    </x-slot>
    <x-slot:body>
        {{--  <div x-data="{ isEdit:{{ $accion === 'edit' ? true : false }}, isShow:{{ $accion === 'show' ? true : false }} }">  --}}
        <div x-data="">
            {{------------  IDENTIFICACION  --------}}
            <h5 class="text-bold" >Identificación</h5>
            <hr>
            <div class="row">
                <x-field class="col-4">
                    <x-label for="tip_prod">Tipo</x-label>
                    <x-select wire:model.lazy="tip_prod" class="form-control-sm {{ $errors->has('tip_prod') ? 'is-invalid' : 'is-valid' }}"
                        style="{{ $accion !== 'new' ? 'pointer-events: none' : '' }}"
                        x-bind:readonly="{{ $accion !== 'new' }}"
                    >
                        <option value="">Seleccione Tipo de Producto</option>
                        @foreach (\App\Enums\Administrativo\Meru_Administrativo\Compras\TipoProducto::cases() as $tipoProducto)
                            <option
                                value="{{ $tipoProducto->value }}"
                            >
                            ({{ $tipoProducto->value }}) {{ $tipoProducto->name }}
                            </option>
                        @endforeach
                    </x-select>
                    <div class="invalid-feedback">
                        @error('tip_prod') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>
            <div class="row">
                <x-field class="col-6">
                    <x-label for="grupo">Grupo</x-label>
                    <x-select wire:model.lazy="grupo" class="form-control-sm {{ $errors->has('grupo') ? 'is-invalid' : 'is-valid' }}"
                        style="{{ $accion !== 'new' ? 'pointer-events: none' : '' }}"
                        x-bind:readonly="{{ $accion !== 'new' }}"
                    >
                        <option value="">Seleccione Grupo del SNC</option>
                        @foreach ($grupos as $index => $item)
                            <option
                                value="{{ $index }}"
                            >
                            ({{ $index }}) {{ $item }}
                            </option>
                        @endforeach

                    </x-select>
                    <div class="invalid-feedback">
                        @error('grupo') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>
            <div class="row">
                <x-field class="col-6">
                    <x-label for="subgrupo">Subgrupo</x-label>
                    <x-select wire:model.lazy="subgrupo" class="form-control-sm {{ $errors->has('subgrupo') ? 'is-invalid' : 'is-valid' }}"
                        style="{{ $accion !== 'new' ? 'pointer-events: none' : '' }}"
                        x-bind:readonly="{{ $accion !== 'new' }}"
                    >
                        <option value="">Seleccione Subgrupo del SNC</option>
                        @foreach ($subgrupos as $index => $item)
                            <option
                                value="{{ $index }}"
                            >
                            ({{ $index }}) {{ $item }}
                            </option>
                        @endforeach
                    </x-select>
                    <div class="invalid-feedback">
                        @error('subgrupo') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>
            <div class="row">
                <x-field class="col-6">
                    <x-label for="gru_ram">Grupo-Ramo</x-label>
                    <x-select wire:model.defer="gru_ram" class="form-control-sm {{ $errors->has('gru_ram') ? 'is-invalid' : 'is-valid' }}"
                        style="{{ $accion !== 'new' ? 'pointer-events: none' : '' }}"
                        x-bind:readonly="{{ $accion !== 'new' }}"
                    >
                        <option value="">Seleccione Grupo-ramo</option>
                        @foreach ($this->ramo as $index => $ramo)
                            <option
                                value="{{ $index }}"
                            >
                            ({{ $index }}) {{ $ramo }}
                            </option>
                        @endforeach
                    </x-select>
                    <div class="invalid-feedback">
                        @error('gru_ram') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>
            <div class="row">
                <x-field class="col-3">
                    <x-label for="cod_prod">Código</x-label>
                        <x-input
                            wire:model.defer="cod_prod"
                            class="form-control-sm {{ $errors->has('cod_prod') ? 'is-invalid' : 'is-valid' }}"
                            readonly
                        />
                        <div class="invalid-feedback">
                            @error('cod_prod') {{ $message }} @enderror
                        </div>
                </x-field>
            </div>
            <div class="row">
                <x-field class="col-8">
                    <x-label for="des_prod">Descripción</x-label>
                    <textarea
                        wire:model.defer="des_prod"
                        class="form-control {{ $errors->has('des_prod') ? 'is-invalid' : 'is-valid' }}"
                        style="text-transform: uppercase"
                        maxlength="500"
                        cols="40"
                        rows="2"
                        x-bind:readonly="{{ $accion === 'show' || $accion === 'asignar' }}"
                    >{{ $producto->des_prod }}</textarea>
                    <div class="invalid-feedback">
                        @error('des_prod') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>
            <div class="row d-flex justify-content-between">
                <x-field class="col-3">
                    <x-label for="cod_uni">Unidad de Medida</x-label>
                    <x-select wire:model.defer="cod_uni" class="form-control-sm {{ $errors->has('cod_uni') ? 'is-invalid' : 'is-valid' }}"
                        style="{{ $accion === 'show' || $accion === 'asignar' ? 'pointer-events: none' : '' }}"
                        x-bind:readonly="{{ $accion === 'show' || $accion === 'asignar' }}"
                    >
                        <option value="">Seleccione Unidad de Medida</option>
                        @foreach ($this->unidadmedida as $index => $unidad)
                            <option
                                value="{{ $index }}"
                            >
                            ({{ $index }}) {{ $unidad }}
                            </option>
                        @endforeach
                    </x-select>
                    <div class="invalid-feedback">
                        @error('cod_uni') {{ $message }} @enderror
                    </div>
                </x-field>
                <x-field class="col-3">
                    <x-label for="fec_act">Actualización</x-label>
                    <x-input
                        type="date"
                        class="form-control-sm {{ $errors->has('fec_act') ? 'is-invalid' : 'is-valid' }}"
                        wire:model="fec_act"
                        readonly
                    />
                    <div class="invalid-feedback">
                        @error('fec_act') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>

            <div class="row d-flex justify-content-between">
                <x-field class="col-3">
                    <x-label for="ult_pre">Último Precio</x-label>
                        <x-input
                            data-inputmask="'alias':'decimal','integerDigits':15,'digits':2,'numericInput':true,'radixPoint':'.','placeholder':'0.00','defaultValue': '0.00', 'removeMaskOnSubmit': true"
                            wire:model.defer="ult_pre"
                            class="form-control-sm {{ $errors->has('ult_pre') ? 'is-invalid' : 'is-valid' }}"
                            style="{direction: rtl;}"
                            x-bind:readonly="{{ $accion === 'show' || $accion === 'asignar' }}"
                        />
                        <div class="invalid-feedback">
                            @error('ult_pre') {{ $message }} @enderror
                        </div>
                </x-field>
                <x-field class="col-3">
                    <x-label for="sta_reg">Estado</x-label>
                    <x-select wire:model.defer="sta_reg" class="form-control-sm {{ $errors->has('sta_reg') ? 'is-invalid' : 'is-valid' }}"
                        style="pointer-events: none "
                        readonly
                    >
                        @foreach (\App\Enums\Administrativo\Meru_Administrativo\Estado::cases() as $estado)
                            <option value="{{ $estado->value }}">
                                {{ $estado->name }}
                            </option>
                        @endforeach
                    </x-select>
                    <div class="invalid-feedback">
                        @error('sta_reg') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>

            {{------------  PARTIDA DE GASTOS PRINCIPAL  -------------}}
            <hr>
            <h5 class="text-bold" >Partida de Gastos Principal</h5>
            <hr>

            <div class="row">
                <x-field class="col-6">
                    <x-label for="cod_par">Partida</x-label>
                    <x-select wire:model.lazy="cod_par" class="form-control-sm {{ $errors->has('cod_par') ? 'is-invalid' : 'is-valid' }}"
                        style="{{ $accion !== 'asignar' ? 'pointer-events: none' : '' }}"
                        x-bind:readonly="{{ ($accion !== 'asignar') }}"
                    >
                        <option value="">Seleccione Partida</option>
                        @foreach ($partidas as $index => $partida)
                            <option
                                value="{{ $index }}"
                            >
                            ({{ $index }}) {{ $partida }}
                            </option>
                        @endforeach
                    </x-select>
                    <div class="invalid-feedback">
                        @error('cod_par') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>

            <div class="row">
                <x-field class="col-6">
                    <x-label for="cod_gen">Genérica</x-label>
                    <x-select wire:model.lazy="cod_gen" class="form-control-sm {{ $errors->has('cod_gen') ? 'is-invalid' : 'is-valid' }}"
                        style="{{ $accion !== 'asignar' ? 'pointer-events: none' : '' }}"
                        x-bind:readonly="{{ ($accion !== 'asignar') }}"
                    >
                        <option value="">Seleccione Genérica</option>
                        @foreach ($genericas as $index => $generica)
                            <option
                                value="{{ $index }}"
                            >
                            ({{ $index }}) {{ $generica }}
                            </option>
                        @endforeach
                    </x-select>
                    <div class="invalid-feedback">
                        @error('cod_gen') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>

            <div class="row">
                <x-field class="col-6">
                    <x-label for="cod_esp">Específica</x-label>
                    <x-select wire:model.lazy="cod_esp" class="form-control-sm {{ $errors->has('cod_esp') ? 'is-invalid' : 'is-valid' }}"
                        style="{{ $accion !== 'asignar' ? 'pointer-events: none' : '' }}"
                        x-bind:readonly="{{ ($accion !== 'asignar') }}"
                    >
                        <option value="">Seleccione Específica</option>
                        @foreach ($especificas as $index => $especifica)
                            <option
                                value="{{ $index }}"
                            >
                            ({{ $index }}) {{ $especifica }}
                            </option>
                        @endforeach
                    </x-select>
                    <div class="invalid-feedback">
                        @error('cod_esp') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>

            <div class="row">
                <x-field class="col-6">
                    <x-label for="cod_sub">SubEspecífica</x-label>
                    <x-select wire:model.lazy="cod_sub" class="form-control-sm {{ $errors->has('cod_sub') ? 'is-invalid' : 'is-valid' }}"
                        style="{{ $accion !== 'asignar' ? 'pointer-events: none' : '' }}"
                        x-bind:readonly="{{ ($accion !== 'asignar') }}"
                    >
                        <option value="">Seleccione Sub-Específica</option>
                        @foreach ($subespecificas as $index => $subespecifica)
                            <option
                                value="{{ $index }}"
                            >
                            ({{ $index }}) {{ $subespecifica }}
                            </option>
                        @endforeach
                    </x-select>
                    <div class="invalid-feedback">
                        @error('cod_sub') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>

            {{------------  DESCRIPCIÓN DE BIENES NACIONALES  -------------}}
            <hr>
            <h5 class="text-bold" >Descripción de Bienes Nacionales</h5>
            <hr>

            <div class="row">
                <x-field class="col-6">
                    <x-label for="cod_par">Grupo</x-label>
                    <x-select wire:model.lazy="cod_gru" class="form-control-sm {{ $errors->has('cod_gru') ? 'is-invalid' : 'is-valid' }}"
                        style="{{ $accion !== 'bienes' ? 'pointer-events: none' : '' }}"
                        x-bind:readonly="{{ ($accion !== 'bienes') }}"
                    >
                        <option value="">Seleccione Grupo</option>
                        {{--  @foreach ($bienGrupos as $index => $bienGrupo)
                            <option
                                value="{{ $index }}"
                            >
                            ({{ $index }}) {{ $bienGrupo }}
                            </option>
                        @endforeach  --}}
                    </x-select>
                    <div class="invalid-feedback">
                        @error('cod_gru') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>

            <div class="row">
                <x-field class="col-6">
                    <x-label for="cod_sgru">SubGrupo</x-label>
                    <x-select wire:model.lazy="cod_sgru" class="form-control-sm {{ $errors->has('cod_sgru') ? 'is-invalid' : 'is-valid' }}"
                        style="{{ $accion !== 'bienes' ? 'pointer-events: none' : '' }}"
                        x-bind:readonly="{{ ($accion !== 'bienes') }}"
                    >
                        <option value="">Seleccione SubGrupo</option>
                        {{--  @foreach ($bienSubGrupos as $index => $bienSubGrupo)
                            <option
                                value="{{ $index }}"
                            >
                            ({{ $index }}) {{ $bienSubGrupo }}
                            </option>
                        @endforeach  --}}
                    </x-select>
                    <div class="invalid-feedback">
                        @error('cod_sgru') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>

            <div class="row">
                <x-field class="col-6">
                    <x-label for="cod_esp">Sección</x-label>
                    <x-select wire:model.lazy="seccion" class="form-control-sm {{ $errors->has('seccion') ? 'is-invalid' : 'is-valid' }}"
                        style="{{ $accion !== 'bienes' ? 'pointer-events: none' : '' }}"
                        x-bind:readonly="{{ ($accion !== 'bienes') }}"
                    >
                        <option value="">Seleccione Sección</option>
                        {{--  @foreach ($secciones as $index => $seccion)
                            <option
                                value="{{ $index }}"
                            >
                            ({{ $index }}) {{ $seccion }}
                            </option>
                        @endforeach  --}}
                    </x-select>
                    <div class="invalid-feedback">
                        @error('seccion') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>
        </div>

    </x-slot>
    <x-slot:footer>
        @if ($accion === 'new' || $accion === 'edit')
            <a wire:click="{{ $accion === 'new' ? 'confirmStore' : 'confirmUpdate' }}" class="btn btn-sm btn-primary float-right">Guardar</a>
        @endif
        @if ($accion === 'asignar')
            <a wire:click="confirmAsignarPartida" class="btn btn-sm btn-primary float-right">Asignar Partidas</a>
        @endif
    </x-slot>
</x-card>

@push('scripts')
    <script>
        Livewire.on('swal:alert', param => {
            Swal.fire({
                title: param['titulo'],
                text: param['mensaje'],
                icon: param['tipo'],
            })
        })

        Livewire.on('swal:confirm', param => {
            Swal.fire({
                title: param['titulo'],
                text: param['mensaje'],
                icon: param['tipo'],
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: '¡Sí!',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if(result.isConfirmed){
                    Livewire.emitTo('administrativo.meru-administrativo.compras.configuracion.bien-material-servicio', param['funcion'])
                }
            })
        })
    </script>
@endpush
