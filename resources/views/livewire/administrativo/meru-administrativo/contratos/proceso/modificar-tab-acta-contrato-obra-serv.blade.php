<div wire:init="cargar_emit" x-data="">
    <div  class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Tipo Acta</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>
    <div class="form-group col-2 offset-1">
        <x-label for="acta">Acta</x-label>
        <x-select  wire:model="selectedActa" name="acta" id="acta" class="form-control-sm text-center ">
            <option value="">Seleccione...</option>
            @foreach (\App\Enums\Administrativo\Meru_Administrativo\Compras\TipoActa::cases() as $tipoacta)
                    <option value="{{ $tipoacta->value }}"
                    @selected(old('acta', $tipoacta->value) == $encnotaentrega->acta)> {{ $tipoacta->name }}
                </option>
            @endforeach
        </x-select>
    </div>

    <!-- Divisor Entrega HidroBolívar-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Entrega HidroBolívar</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 offset-1">
        {{--  <div class="form-group col-1">
            <x-label for="id" type="hidden">ID</x-label>  --}}
            <x-input name="id"  id="id" type="hidden" class="form-control-sm text-center " value="{{ $encnotaentrega->id }}" maxlength="5" readonly/>
        {{--  </div>  --}}


        <div class="form-group col-1 ">
            <x-label for="fk_ano_pro">Año</x-label>
            <x-input name="fk_ano_pro" class="form-control-sm text-center " value="{{ $encnotaentrega->fk_ano_pro }}" maxlength="4" readonly/>
        </div>

        <div class="form-group col-1">
            <x-label for="grupo">Grupo</x-label>
            <x-select name="grupo" style="pointer-events:none" class="form-control-sm text-center " readonly>
                <option value="">Seleccione...</option>
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\Compras\GrupoEncNotaEntrega::cases() as $grupoencnotaentrega)
                     <option value="{{ $grupoencnotaentrega->value }}"
                        @selected(old('grupo', $grupoencnotaentrega->value) == $encnotaentrega->grupo)> {{ $grupoencnotaentrega->name }}
                    </option>
                @endforeach
            </x-select>
        </div>

        <div class="form-group col-1">
            <x-label for="nro_ent">Número</x-label>
            <x-input name="nro_ent" class="form-control-sm text-center  text-bold" style="font-size: 7mm" value="{{ $encnotaentrega->nro_ent }}" maxlength="2" readonly/>
        </div>

        <div class="form-group col-2">
            <x-label for="fec_pos">Fecha Registro</x-label>
            <x-input name="fec_pos" class="form-control-sm text-center" type="date" value="{{ $encnotaentrega->fec_pos }}" maxlength="2" readonly/>
        </div>

        <div class="form-group col-2">
            <x-label for="fec_ent">Fecha Entrega</x-label>
            <x-input name="fec_ent" class="form-control-sm text-center" type="date" value="{{ $encnotaentrega->fec_pos }}" maxlength="2" readonly/>
        </div>

        <div class="form-group col-2">
            <x-label for="fec_com">Fecha Aprobación Contable</x-label>
            <x-input name="fec_com" class="form-control-sm text-center" type="datetime" value="{{ $encnotaentrega->fec_com }}" maxlength="2" readonly/>
        </div>
    </div>

<!-- Divisor Orden de Servicio-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Orden de Servicio</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12">
        <div class="form-group col-2 offset-1 ">
            <x-label for="fk_tip_ord">Tipo Contrato</x-label>
            <x-select name="fk_tip_ord" style="pointer-events:none" class="form-control-sm text-center " readonly>
                <option value="">Seleccione...</option>
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\Compras\TipoContEncNotaEntrega::cases() as $tipocontencnotaentrega)
                     <option value="{{ $tipocontencnotaentrega->value }}"
                        @selected(old('fk_tip_ord', $tipocontencnotaentrega->value) == $encnotaentrega->fk_tip_ord)> {{ $tipocontencnotaentrega->name }}
                    </option>
                @endforeach
            </x-select>
        </div>

        <div class="form-group col-1 ">
            <x-label for="ano_ord_com">Año Contrato</x-label>
            <x-input class="form-control-sm text-center" name="ano_ord_com" value="{{ $encnotaentrega->ano_ord_com }}" readonly/>
        </div>

        <div class="form-group col-">
            <x-label for="xnro_ord">Contrato Sistema Merú</x-label>
            <x-input class="form-control-sm text-center" name="xnro_ord" value="{{ $encnotaentrega->xnro_ord }}" readonly/>
        </div>

        <div class="form-group col-2 ">
            <x-label for="cont_fis">Contrato Físico Nro.</x-label>
            <x-input wire:model="cont_fis" class="form-control-sm" name="cont_fis" id="cont_fis" x-mask="CO-999" id="cont_fis" value="{{ $encnotaentrega->cont_fis }}" readonly/>
        </div>

        <div class="form-group col-2 ">
            <x-label for="fec_ord">Fecha Contrato</x-label>
            <x-input class="form-control-sm text-center" name="fec_ord" type="date" value="{{ $encnotaentrega->fec_ord }}" readonly/>
        </div>

        <div class="form-group col-2 ">
            <x-label for="mto_ord">Monto Contrato</x-label>
            <x-input class="form-control-sm text-sm-right" name="mto_ord" value="{{ $encnotaentrega->mto_ord }}" readonly/>
        </div>


        <div class="form-group col-2 offset-1">
            <x-label for="tip_ent">Tipo Entrada</x-label>
            <x-select name="tip_ent" style="pointer-events:none" class="form-control-sm text-center " readonly>
                <option value="">Seleccione...</option>
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\Compras\TipoEntEncNotaEntrega::cases() as $tipoentencnotaentrega)
                     <option value="{{ $tipoentencnotaentrega->value }}"
                        @selected(old('tip_ent', $tipoentencnotaentrega->value) == $encnotaentrega->tip_ent)> {{ $tipoentencnotaentrega->name }}
                    </option>
                @endforeach
            </x-select>
        </div>
    </div>

    <!-- Divisor Proveedor-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">&nbsp</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 ">
        <div class="form-group col-1 offset-1">
            <x-label for="fk_rif_con">Proveedor</x-label>
            <x-input class="form-control-sm text-center" name="fk_rif_con" value="{{ $beneficiarios->rif_ben }}" readonly/>
        </div>
        <div class="form-group col-5 ">
            <x-label for="fk_rif_con_desc">&nbsp</x-label>
            <x-input class="form-control-sm" name="fk_rif_con_desc" value="{{ $beneficiarios->nom_ben }}" readonly/>
        </div>
    </div>

    <!-- Divisor Fondo - Cuenta Contable-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">&nbsp</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 offset-1">
        {{--  <div class="form-group col-1 ">
            <x-label for="fondos">Fondos</x-label>
            <x-input class="form-control-sm" name="fondos" value="{{ $encnotaentrega->fondos }}" readonly/>
        </div>  --}}
        <div class="form-group col-2">
            <x-label for="fondos">Fondos</x-label>
            <x-select name="fondos" style="pointer-events:none" class="form-control-sm text-center " readonly>
                <option value="">Seleccione...</option>
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\Compras\FondoEncNotaEntrega::cases() as $fondoencnotaentrega)
                     <option value="{{ $fondoencnotaentrega->value }}"
                        @selected(old('fondos', $fondoencnotaentrega->value) == $encnotaentrega->fondos)> {{ $fondoencnotaentrega->name }}
                    </option>
                @endforeach
            </x-select>
        </div>

        <div class="form-group col-3 ">
            <x-label for="cuenta_contable">Cuenta Contable</x-label>
            <x-input class="form-control-sm text-center" name="cuenta_contable" value="{{ $encnotaentrega->cuenta_contable }}" readonly/>
        </div>
    </div>

    <!-- Divisor Facturas-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Factura</h5>
        </div>

        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12  offset-1">
        <div class="form-group col-4 ">
            <x-label for="num_fac">Nro. Factura</x-label>
            <x-input class="form-control-sm text-center" name="num_fac" value="{{$encnotaentrega->num_fac }}" readonly/>
        </div>
    </div>

    <!-- Divisor Solicitud-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Solicitud</h5>
        </div>

        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 offset-1">
        <div class="form-group col-4 ">
            <x-label for="jus_sol">Justificación</x-label>
            <textarea  wire:model="jus_sol"  id="jus_sol" name="jus_sol"  class="form-control {{ $errors->has('jus_sol') ? 'is-invalid' : '' }}" rows="3"  >{{ $encnotaentrega->jus_sol }}</textarea>
        </div>

        <div class="form-group col-4 ">
            <x-label for="observacion">Observación</x-label>
            <textarea  wire:model="observacion" id="observacion" name="observacion"   class="form-control {{ $errors->has('observacion') ? 'is-invalid' : '' }}" rows="3" >{{ $encnotaentrega->observacion }}</textarea>
        </div>
    </div>

    <!-- Divisor Status -->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Estado</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12  offset-1">
        <div class="form-group col-3">
            <x-label for="sta_ent">Entrega</x-label>
            <x-input class="form-control-sm text-center text-bold" style="font-size: 7mm" name="sta_ent" value="{{ $statusent }}" readonly/>
        </div>

        <div class="form-group col-3">
            <x-label for="stat_causacion">Comprobante</x-label>
            <x-input class="form-control-sm text-center text-bold" style="font-size: 7mm" name="sta_ent" value="{{ $statcomprob }}" readonly/>
        </div>
    </div>

    <!-- Divisor Recomendacion-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">&nbsp</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>
    <div class="row col-12 offset-1">
        <div class="form-group col-6 ">
            <x-label for="recomen">Recomendación</x-label>
            {{--  <x-input class="form-control-sm" name="recomen" type="text" rows="4" value="{{ $encnotaentrega->recomen }}" readonly/>  --}}
            <textarea  id="recomen" name="recomen"  class="form-control {{ $errors->has('recomen') ? 'is-invalid' : '' }}" rows="3" @if ( $valor=='iniciar' || $valor=='terminar'|| $valor=='modificar') readonly @endif >{{ $encnotaentrega->recomen }}</textarea>
        </div>
    </div>
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
            <x-input class="form-control-sm text-center" wire:model.defer="fec_act" name="fec_act" id="fec_act" type="date"  readonly/>
        </div>

        <div class="form-group col-2 ">
            <x-label for="revision">En Revision</x-label>
            <x-input class="form-control-sm text-center" wire:model.defer="revision" name="revision" id="revision" type="text" value="" readonly/>
        </div>

        <div class="form-group col-6 ">
            <x-label for="gerencia">Gerencia</x-label>
            <x-select  wire:model="gerencia" name="gerencia" id="gerencia" class="form-control-sm {{ $errors->has('gerencia') ? 'is-invalid' : '' }}" readonly>
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
    </div>

</div>

@push('scripts')
    <script type="text/javascript">
        window.livewire.on('enableModificar', param => {
            $('#cont_fis').attr('readonly', false);
            $('#jus_sol').attr('readonly', false);
            $('#observacion').attr('readonly', false);
            $('#gerencia').attr('readonly', false);
            $('#gerencia').css('pointer-events', '');
            $('#ced_con').attr('readonly', false);
            $('#nom_con').attr('readonly', false);

        });
        window.livewire.on('enableReimprimir', param => {
            $('#cont_fis').attr('readonly', true);
            $('#jus_sol').attr('readonly', true);
            $('#observacion').attr('readonly', true);
            $('#gerencia').attr('readonly', true);
            $('#gerencia').css('pointer-events', 'none');
            $('#ced_con').attr('readonly', true);
            $('#nom_con').attr('readonly', true);
            $('#gerencia').attr('readonly', true);
            $('#recomen').attr('readonly', true);
            $('#ced_hb').attr('readonly', true);
            $('#ced_hb').css('pointer-events', 'none');
        });
    /*  window.livewire.on('enableBoton', param => {
            $("#modificar").prop('disabled', false );
        });  */
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




