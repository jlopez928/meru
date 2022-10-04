<div class="row col-sm" >

    <!-- Divisor Entrega HidroBolívar-->
    <div class="row col-12">
        <x-label for="tipo">&nbsp</x-label>
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Entrega HidroBolívar</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12">
        <div class="form-group col-1">
            <x-label for="id">ID</x-label>
            <x-input wire:model.defer="id" name="id" class="form-control-sm text-center " value="" maxlength="5" readonly/>
        </div>

        <div class="form-group col-1">
            <x-label for="fk_ano_pro">{{ __('Año') }}</x-label>
            <x-input wire:model.defer="fk_ano_pro" name="fk_ano_pro" class="form-control-sm text-center " value=" {{$periodos[2021]}}" maxlength="4" readonly/>
        </div>

        <div class="form-group col-2">
            <x-label for="grupo">Grupo</x-label>
            <x-select style="pointer-events:none" wire:model.defer="grupo" name="grupo" class="form-control-sm text-center " readonly >
                <option value="">Seleccione...</option>
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\Compras\GrupoEncNotaEntrega::cases() as $grupoencnotaentrega)
                    // @dump($grupoencnotaentrega->name)
                <option value="{{$grupoencnotaentrega->value}}" @selected(old('grupo', $grupo) === $grupoencnotaentrega->value)>
                         {{ $grupoencnotaentrega->name }}
                    </option>
                @endforeach
            </x-select>
        </div>
        <div class="invalid-feedback">
            @error('grupo') {{ $message }} @enderror
        </div>

        <div class="form-group col-1">
            <x-label for="nro_ent">Número</x-label>
            <x-input wire:model.defer="nro_ent" name="nro_ent" class="form-control-sm text-center" value="" maxlength="2" readonly/>
        </div>

        <div class="form-group col-2">
            <x-label for="fec_pos">Fecha Registro</x-label>
            <x-input wire:model.defer="fec_pos" name="fec_pos" class="form-control-sm text-center" type="date" value="" maxlength="2" readonly/>
        </div>

        <div class="form-group col-2">
            <x-label for="fec_ent">Fecha Entrega</x-label>
            <x-input wire:model.defer="fec_ent" name="fec_ent" class="form-control-sm text-center" type="date" value="" maxlength="2" readonly/>
        </div>

        <div class="form-group col-2">
            <x-label for="fec_com">Fecha Aprob. Cont.</x-label>
            <x-input wire:model.defer="fec_com" name="fec_com" class="form-control-sm text-center" type="datetime" value="" maxlength="2" readonly/>
        </div>
    </div>

    <!-- Divisor Orden de Servicio-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Orden de Servicio</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 ">

        <div class="form-group col-2">
            <x-label for="fk_tip_ord">Tipo Contrato</x-label>

            <x-select style="pointer-events:none" wire:model.defer="fk_tip_ord" name="fk_tip_ord" class="form-control-sm text-center" readonly>
                <option value="">Seleccione...</option>
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\Compras\TipoContEncNotaEntrega::cases() as $tipocontencnotaentrega)
                     <option value="{{ $tipocontencnotaentrega->value }}" @selected(old('fk_tip_ord', $fk_tip_ord) === $tipocontencnotaentrega->value)>
                         {{$tipocontencnotaentrega->name}}
                    </option>
                @endforeach
            </x-select>
        </div>
        <div class="invalid-feedback">
            @error('fk_tip_ord') {{ $message }} @enderror
        </div>

        <div class="form-group col-2">
            <x-label for="ano_ord_com">Año Contrato</x-label>
            <x-select wire:model.defer="ano_ord_com" name="ano_ord_com" class="form-control-sm {{ $errors->has('ano_ord_com') ? 'is-invalid' : '' }} " required>
                <option value="">--Seleccione Año--</option>
               @foreach ($this->Registrocontrol as $index => $registrocontrol)
                    <option value="{{ $index  }}" @selected(old('ano_ord_com',$ano_ord_com) == $index)>
                        {{ $registrocontrol }}
                    </option>
                @endforeach
            </x-select>
        </div>
        <div class="invalid-feedback">
            @error('grupo') {{ $message }} @enderror
        </div>

        <div class="form-group col-2">
            <x-label for="xnro_ord">Cont. Sist. Merú</x-label>
            <x-input name="xnro_ord"  wire:model.defer="xnro_ord" wire:keydown.tab.prevent="datosContrato('xnro_ord')"   class="form-control-sm text-center"  x-mask="CO-999" value=""/>
        </div>

        <div class="form-group col-2 ">
            <x-label for="cont_fis">Contrato Físico Nro.</x-label>
            <x-input class="form-control-sm" wire:model.defer="cont_fis" name="cont_fis" value="" readonly/>
        </div>

        <div class="form-group col-2 ">
            <x-label for="fec_ord">Fecha Contrato</x-label>
            <x-input class="form-control-sm text-center" wire:model.defer="fec_ord" name="fec_ord" type="date" value="" readonly/>
        </div>

        <div class="form-group col-2 ">
            <x-label for="mto_ord">Monto Contrato</x-label>
            <x-input class="form-control-sm text-sm-right" wire:model.defer="mto_ord" name="mto_ord" value="" readonly/>
        </div>

        {{--  <div class="form-group col-1 ">
            <x-label for="tip_ent">Tipo Entrada</x-label>
            <x-input class="form-control-sm" name="tip_ent" value="{{ $encnotaentrega->tip_ent }}" readonly/>
        </div>  --}}

        <div class="form-group col-2 ">
            <x-label for="tip_ent">Tipo Entrada</x-label>
            <x-select wire:model.defer="tip_ent" name="tip_ent" class="form-control-sm text-center " readonly>
                <option value="">Seleccione...</option>
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\Compras\TipoEntEncNotaEntrega::cases() as $tipoentencnotaentrega)
                     <option value="{{ $tipoentencnotaentrega->value }}"  @selected(old('tip_ent',$tip_ent) == $index)>
                         {{ $tipoentencnotaentrega->name }}
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
        <div class="form-group col-2 ">
            <x-label for="fk_rif_con">Proveedor</x-label>
            <x-input class="form-control-sm text-center" wire:model.defer="fk_rif_con" name="fk_rif_con" value="" readonly/>
        </div>
        <div class="form-group col-7">
            <x-label for="fk_rif_con_desc">&nbsp</x-label>
            <x-input class="form-control-sm" wire:model.defer="fk_rif_con_desc" name="fk_rif_con_desc" value="" readonly/>
        </div>
    </div>

    <!-- Divisor Fondo - Cuenta Contable-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">&nbsp</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 ">
        <div class="form-group col-2">
            <x-label for="fondos">Fondos</x-label>
            <x-select wire:model.defer="fondos" name="fondos" class="form-control-sm text-center " readonly>
                <option value="">Seleccione...</option>
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\Compras\FondoEncNotaEntrega::cases() as $fondoencnotaentrega)
                     <option value="{{ $fondoencnotaentrega->value }}" @selected(old('fondos',$fondos) == $index)>
                         {{ $fondoencnotaentrega->name }}
                    </option>
                @endforeach
            </x-select>
        </div>

        <div class="form-group col-3 ">
            <x-label for="cuenta_contable">Cuenta Contable</x-label>
            <x-input class="form-control-sm text-center" wire:model.defer="cuenta_contable" name="cuenta_contable" value="" readonly/>
        </div>
    </div>

    <!-- Divisor Facturas-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Factura</h5>
        </div>

        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12  ">
        <div class="form-group col-4 ">
            <x-label for="num_fac">Nro. Factura</x-label>
            <x-input class="form-control-sm text-center" wire:model.defer="num_fac" name="num_fac" value="" readonly/>
        </div>
    </div>

    <!-- Divisor Solicitud-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Solicitud</h5>
        </div>

        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 ">

        <input id="fec_notaentrega" type="hidden" wire:model.defer ="fec_notaentrega"   name="fec_notaentrega">
        <input id="nota_entrega" type="hidden" wire:model.defer ="nota_entrega"   name="nota_entrega">
        <input id="tipo_orden" type="hidden" wire:model.defer ="tipo_orden"   name="tipo_orden" value="1">


        <div class="form-group col-4 ">
            <x-label for="jus_sol">Justificación</x-label>
            {{--  <x-input class="form-control-sm" wire:model.defer="jus_sol" name="jus_sol" type="text"  rows="4" value="" readonly/>  --}}
            <textarea wire:model.defer ="jus_sol"   id="jus_sol" name="jus_sol" class="form-control {{ $errors->has('jus_sol') ? 'is-invalid' : '' }}" rows="3" readonly></textarea>
        </div>

        <div class="form-group col-4 ">
            <x-label for="observacion">Observación</x-label>
            {{--  <x-input class="form-control-sm" wire:model.defer="observacion" name="observacion" type="text" rows="4" value="" readonly/>  --}}
            <textarea wire:model.defer ="observacion"   id="observacion" name="observacion" class="form-control {{ $errors->has('observacion') ? 'is-invalid' : '' }}" rows="3" readonly></textarea>

        </div>
    </div>

    <!-- Divisor Status -->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Estado</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12  ">
        {{--  <div class="form-group col-1 ">
            <x-label for="sta_ent">Entrega</x-label>
            <x-input class="form-control-sm" name="sta_ent" value="{{ $encnotaentrega->sta_ent }}" readonly/>
        </div>  --}}
        <div class="form-group col-3">
            <x-label for="sta_ent">Entrega</x-label>
            <x-input class="form-control-sm text-center text-bold" wire:model.defer="sta_ent" name="sta_ent" value="" readonly/>
            {{--  <x-select name="sta_ent" class="form-control-sm text-center " readonly>
                <option value="">Seleccione...</option>
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\Compras\StatusASEncNotaEntrega::cases() as $statusasencnotaentrega)
                     <option value="{{ $statusasencnotaentrega->value }}"
                        @selected(old('sta_ent', $statusasencnotaentrega->value) == $encnotaentrega->sta_ent)> {{ $statusasencnotaentrega->name }}
                    </option>
                @endforeach
            </x-select>  --}}
        </div>

        {{--  <div class="form-group col-1 ">
            <x-label for="stat_causacion">Comprobante</x-label>
            <x-input class="form-control-sm" name="stat_causacion" value="{{ $encnotaentrega->stat_causacion  }}" readonly/>
        </div>  --}}
        <div class="form-group col-3">
            <x-label for="stat_causacion">Comprobante</x-label>
            <x-input class="form-control-sm text-center text-bold" wire:model.defer="stat_causacion" name="stat_causacion" value="" readonly/>
            {{--  <x-select name="stat_causacion" class="form-control-sm text-center " readonly>
                <option value="">Seleccione...</option>
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\Compras\StatusASEncNotaEntrega::cases() as $statusasencnotaentrega)
                     <option value="{{ $statusasencnotaentrega->value }}"
                        @selected(old('stat_causacion', $statusasencnotaentrega->value) == $encnotaentrega->stat_causacion)> {{ $statusasencnotaentrega->name }}
                    </option>
                @endforeach
            </x-select>  --}}
        </div>
    </div>

    <!-- Divisor Recomendacion-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">&nbsp</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>
    <div class="row col-12 ">
        <div class="form-group col-6 ">
            <x-label for="recomen">Recomendación</x-label>
            {{--  <x-input class="form-control-sm" wire:model.defer="recomen" name="recomen" type="text" rows="4" value="" readonly/>  --}}
            <textarea wire:model.defer ="recomen"   id="recomen" name="recomen" class="form-control {{ $errors->has('recomen') ? 'is-invalid' : '' }}" rows="3" readonly></textarea>
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
        {{--  @if ($encnotaentrega->acta)  --}}
            <div class="form-group col-4 ">
                <x-label for="ced_hb">Cédula Resp. HB</x-label>
                <x-input class="form-control-sm text-center" wire:model.defer="ced_hb" name="ced_hb" value="" readonly/>
            </div>

            <div class="form-group col-4 ">
                <x-label for="nom_hb">Nombre Resp. HB</x-label>
                <x-input class="form-control-sm" wire:model.defer="nom_hb" name="nom_hb" value="" readonly/>
            </div>

            <div class="form-group col-4 ">
                <x-label for="cargo_hb">Cargo Resp. HB</x-label>
                <x-input class="form-control-sm text-center" wire:model.defer="cargo_hb" name="cargo_hb" type="text" rows="4" value="" readonly/>
            </div>
        {{--  @else
            <div class="form-group col-4 ">
                <x-label for="ced_hb">Cédula Resp. HB</x-label>
                <x-input class="form-control-sm text-center" name="ced_hb" value="" readonly/>
            </div>

            <div class="form-group col-4 ">
                <x-label for="nom_hb">Nombre Resp. HB</x-label>
                <x-input class="form-control-sm" name="nom_hb" value="" readonly/>
            </div>

            <div class="form-group col-4 ">
                <x-label for="cargo_hb">Cargo Resp. HB</x-label>
                <x-input class="form-control-sm" name="cargo_hb" type="text" rows="4" value="" readonly/>
            </div>
        @endif  --}}
    </div>

    <!-- Divisor Lugar-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">&nbsp</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>
    <div class="row col-12 ">
        {{--  @if ($encnotaentrega->acta)  --}}
            <div class="form-group col-6 ">
                <x-label for="lug_reunion">Reunidos En</x-label>
                <x-input class="form-control-sm text-center" wire:model.defer="lug_reunion" name="lug_reunion" value="" readonly/>
            </div>

            <div class="form-group col-2 ">
                <x-label for="fecha_acta">En Fecha:</x-label>
                <x-input class="form-control-sm text-center" wire:model.defer="fecha_acta" name="fecha_acta" type="date" value="" readonly/>
            </div>

            <div class="form-group col-2 ">
                <x-label for="revision">En Revision</x-label>
                <x-input class="form-control-sm text-center" wire:model.defer="revision"name="revision" type="date" value="" readonly/>
            </div>

            <div class="form-group col-6 ">
                <x-label for="gerencia">Gerencia</x-label>
                <x-input class="form-control-sm text-center" wire:model.defer="gerencia" name="gerencia" type="text" value="" readonly/>
            </div>
        {{--  @else
            <div class="form-group col-6 ">
                <x-label for="lug_reunion">Reunidos En</x-label>
                <x-input class="form-control-sm text-center" name="lug_reunion" value="" readonly/>
            </div>

            <div class="form-group col-2 ">
                <x-label for="fecha_acta">En Fecha:</x-label>
                <x-input class="form-control-sm text-center" name="fecha_acta" type="date" value="" readonly/>
            </div>

            <div class="form-group col-2 ">
                <x-label for="revision">En Revision</x-label>
                <x-input class="form-control-sm text-center" name="fecha_acta" type="date" value="" readonly/>
            </div>

            <div class="form-group col-6 ">
                <x-label for="gerencia">Gerencia</x-label>
                <x-input class="form-control-sm text-center" name="fecha_acta" type="text" value="" readonly/>
            </div>
        @endif  --}}
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
            <x-input class="form-control-sm text-center" wire:model.defer="ced_con" name="ced_con" value="" readonly/>
        </div>

        <div class="form-group col-4 ">
            <x-label for="nom_con">Nombre Resp. Cont.</x-label>
            <x-input class="form-control-sm text-center" wire:model.defer="nom_con" name="nom_con" value="" readonly/>
        </div>
    </div>
</div>
