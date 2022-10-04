{{--  <div wire:init="cargar_emit"x-data="" class="row col 12" style="display:contents">  --}}
<div wire:init="cargar_emit" x-data="">

    <!-- Divisor HidroBolívar -->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">HidroBolívar</h5>
        </div>

        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>


    <div class="row col-12 ">
        {{--  <x-input  wire:model="enc_id"  id="enc_id" name="enc_id" />  --}}
            <div class="form-group col-4 ">
                <x-label for="ced_hb">Cédula Resp. HB</x-label>
                <x-select  wire:model="selectedCedHb" name="ced_hb" id="ced_hb" class="form-control-sm {{ $errors->has('ced_hb') ? 'is-invalid' : '' }}">
                    <option value="{{'0'}}"> {{ 'Seleccione' }}</option>
                    @foreach ($trabajador as $tabtem)
                        <option value="{{ $tabtem->rif_ben}}" @selected(old('ced_hb', $encnotaentrega->ced_hb) == $tabtem->rif_ben)> {{ $tabtem->rif_ben.'--'.$tabtem->nom_ben }}</option>
                    @endforeach
                </x-select>
                <div class="invalid-feedback">
                    @error('ced_hb') {{ $message }} @enderror
                </div>
            </div>


            <div class="form-group col-4 ">
                <x-label for="nom_hb">Nombre Resp. HB</x-label>
                <x-input  wire:model="nom_hb" class="form-control-sm" name="nom_hb" value="" readonly/>
            </div>

            <div class="form-group col-4 ">
                <x-label for="cargo_hb">Cargo Resp. HB</x-label>
                <x-input  wire:model="cargo_hb" class="form-control-sm text-center" name="cargo_hb" type="text" rows="4" value="" readonly/>
            </div>

    </div>

    <!-- Divisor Lugar-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">&nbsp</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>
    <div class="row col-12 offset-1">

        <div class="form-group col-6 ">
            <x-label for="lug_reunion">Reunidos En</x-label>
            <x-input class="form-control-sm" wire:model.defer="lug_reunion" name="lug_reunion" value="" readonly/>
        </div>

        <div class="form-group col-2 ">
            <x-label for="fec_act">En Fecha:</x-label>
            <x-input class="form-control-sm text-center" wire:model.defer="fec_act" name="fec_act" type="date"  readonly/>
        </div>

        <div class="form-group col-2 ">
            <x-label for="revision">En Revision</x-label>
            <x-input class="form-control-sm text-center" wire:model.defer="revision" name="revision" type="text" value="" readonly/>
        </div>

        <div class="form-group col-6 ">
            <x-label for="gerencia">Gerencia</x-label>
            <x-select  wire:model="gerencia" name="gerencia" id="gerencia" class="form-control-sm {{ $errors->has('gerencia') ? 'is-invalid' : '' }}" >
                <option value="{{'0'}}"> {{ 'Seleccione' }}</option>
                @foreach ($gerencias as $gertem)
                    <option value="{{ $gertem->cod_ger}}" @selected(old('cod_ger', $encnotaentrega->gerencia) == $gertem->cod_ger)> {{ $gertem->cod_ger.'--'.$gertem->des_ger }}</option>
                @endforeach
            </x-select>

        </div>


    </div>

    <!-- Divisor Contratista -->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Contratista</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 ">
        {{--  <div class="form-group col-4 ">
            <x-label for="ced_con">Cédula Resp. Cont.</x-label>
            <x-input class="form-control-sm text-center" wire:model.defer="ced_con" name="ced_con" value="" />
        </div>  --}}
        <x-field class="form-group col-5 ">
            <x-label for="ced_con">Cédula Resp. Cont.</x-label>
            <x-input name="ced_con"  wire:model.defer="ced_con"  class="form-control-sm {{ $errors->has('ced_con') ? 'is-invalid' : '' }}" type="text" placeholder="Ingrese cédula represenante" value="{{ old('ced_con', $encnotaentrega->ced_con) }}"  />
            <div class="invalid-feedback">
                @error('ced_con') {{ $message }} @enderror
            </div>
        </x-field>
        <x-field class="form-group col-5 ">
            <x-label for="nom_con">Cédula Resp. Cont.</x-label>
            <x-input name="nom_con"  wire:model.defer="nom_con"  class="form-control-sm {{ $errors->has('nom_con') ? 'is-invalid' : '' }}" type="text" placeholder="Ingrese nombre represenante" value="{{ old('nom_con', $encnotaentrega->nom_con) }}"  />
            <div class="invalid-feedback">
                @error('nom_con') {{ $message }} @enderror
            </div>
        </x-field>

        {{--  <div class="form-group col-4 ">
            <x-label for="nom_con">Nombre Resp. Cont.</x-label>
            <x-input class="form-control-sm text-center" wire:model.defer="nom_con" name="nom_con" value="" />
        </div>  --}}
    </div>


    {{--  <!-- Divisor HidroBolívar -->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">HidroBolívar</h5>
        </div>

        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>


    <div class="row col-12 ">

            <div class="form-group col-4 ">
                <x-label for="ced_hb">Cédula Resp. HB</x-label>
                <x-select  wire:model="selectedCedHb" name="ced_hb" id="ced_hb" class="form-control-sm {{ $errors->has('ced_hb') ? 'is-invalid' : '' }}">
                    <option value="{{'0'}}"> {{ 'Seleccione' }}</option>
                    @foreach ($trabajador as $tabtem)
                        <option value="{{ $tabtem->rif_ben}}" @selected(old('ced_hb', $encnotaentrega->ced_hb) == $tabtem->rif_ben)> {{ $tabtem->rif_ben.'--'.$tabtem->nom_ben }}</option>
                    @endforeach
                </x-select>
            </div>

            <div class="form-group col-4 ">
                <x-label for="nom_hb">Nombre Resp. HB</x-label>
                <x-input  wire:model="nom_hb" class="form-control-sm" name="nom_hb" value="" readonly/>
            </div>

            <div class="form-group col-4 ">
                <x-label for="cargo_hb">Cargo Resp. HB</x-label>
                <x-input  wire:model="cargo_hb" class="form-control-sm text-center" name="cargo_hb" type="text" rows="4" value="" readonly/>
            </div>

    </div>

    <!-- Divisor Lugar-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">&nbsp</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>
    <div class="row col-12 offset-1">

        <div class="form-group col-6 ">
            <x-label for="lug_reunion">Reunidos En</x-label>
            <x-input class="form-control-sm" wire:model.defer="lug_reunion" name="lug_reunion" value="" readonly/>
        </div>

        <div class="form-group col-2 ">
            <x-label for="fec_act">En Fecha:</x-label>
            <x-input class="form-control-sm text-center" wire:model.defer="fec_act" name="fec_act" type="date"  readonly/>
        </div>

        <div class="form-group col-2 ">
            <x-label for="revision">En Revision</x-label>
            <x-input class="form-control-sm text-center" wire:model.defer="revision" name="revision" type="text" value="" readonly/>
        </div>

        <div class="form-group col-6 ">
            <x-label for="gerencia">Gerencia</x-label>
            <x-select  wire:model.defer="gerencia" name="gerencia" id="gerencia" class="form-control-sm {{ $errors->has('ced_hb') ? 'is-invalid' : '' }}" >
                <option value="{{'0'}}"> {{ 'Seleccione' }}</option>
                @foreach ($gerencias as $gertem)
                    <option value="{{ $gertem->cod_ger}}" @selected(old('cod_ger', $encnotaentrega->gerencia) == $gertem->cod_ger)> {{ $gertem->cod_ger.'--'.$gertem->des_ger }}</option>
                @endforeach
            </x-select>

        </div>


    </div>

    <!-- Divisor Contratista -->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Contratista</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 ">
        <div class="form-group col-4 ">
            <x-label for="ced_con">Cédula Resp. Cont.</x-label>
            <x-input class="form-control-sm text-center" wire:model.defer="ced_con" name="ced_con" value="" />
        </div>

        <div class="form-group col-4 ">
            <x-label for="nom_con">Nombre Resp. Cont.</x-label>
            <x-input class="form-control-sm text-center" wire:model.defer="nom_con" name="nom_con" value="" />
        </div>
    </div>  --}}

</div>

@push('scripts')
    <script type="text/javascript">
        window.livewire.on('enableModificar', param => {
            $('#cont_fis').attr('readonly', false);
            $('#jus_sol').attr('readonly', false);
            $('#observacion').attr('readonly', false);
            $('#gerencia').attr('readonly', false);
            $('#ced_con').attr('readonly', false);
            $('#nom_con').attr('readonly', false);
        });
        window.livewire.on('enableBoton', param => {
            $("#iniciar").prop('disabled', true );
        });
        window.livewire.on('activateBoton', param => {
            $("#iniciar").prop('disabled', false );
        });
        window.livewire.on('enableTerminar', param => {
            $('#gerencia').attr('readonly', true);
            $('#gerencia').css('pointer-events', 'none');
            $('#cont_fis').attr('readonly', false);
        });

        window.livewire.on('enableInicio', param => {
            $('#cont_fis').attr('readonly', false);
            $('#gerencia').attr('readonly', true);
            $('#gerencia').css('pointer-events', 'none');
        });

        window.livewire.on('enableAceptar', param => {
            $('#gerencia').attr('readonly', false);
            $('#cont_fis').attr('readonly', false);
            $('#recomen').attr('readonly', false);
            $('#lug_reunion').attr('readonly', false);
            $('#revision').attr('readonly', false);
            $('#jus_sol').attr('readonly', false);
            $('#observacion').attr('readonly', false);

        });
        window.livewire.on('enableAnular', param => {
            $('#gerencia').attr('readonly', true);
            $('#gerencia').css('pointer-events', 'none');
            $('#cont_fis').attr('readonly', true);
            $('#recomen').attr('readonly', true);
            $('#lug_reunion').attr('readonly', true);
            $('#revision').attr('readonly', true);
            $('#jus_sol').attr('readonly', true);
            $('#observacion').attr('readonly', true);
            $('#ced_hb').attr('readonly', true);
            $('#ced_hb').css('pointer-events', 'none');
            $('#ced_con').attr('readonly', true);
            $('#nom_con').attr('readonly', true);
            $('#fk_tip_ord').css('pointer-events', 'none');
            $('#tip_ent').css('pointer-events', 'none');
            $('#fondos').css('pointer-events', 'none');
        });
    </script>
@endpush

