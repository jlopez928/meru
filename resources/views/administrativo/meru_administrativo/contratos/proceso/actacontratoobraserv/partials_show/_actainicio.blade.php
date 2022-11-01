<br>
<div wire:init="cargar_emit" x-data="">
    <!-- Divisor Entrega HidroBolívar-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Entrega HidroBolívar</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 offset-1">

        <x-input name="id"    id="id" type="hidden" class="form-control-sm text-center " value="{{ $encnotaentrega->id }}" maxlength="5" readonly/>
        <x-input name="acta"  id="acta" type="hidden" class="form-control-sm text-center " value="{{ $valor }}" maxlength="5" readonly/>

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
            <x-input class="form-control-sm" name="cont_fis" x-mask="CO-999" id="cont_fis" value="{{ $encnotaentrega->xnro_ord }}" readonly/>
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
            <textarea  id="jus_sol" name="jus_sol"  class="form-control {{ $errors->has('jus_sol') ? 'is-invalid' : '' }}" rows="3" @if ( $valor!='iniciar' && $valor!='terminar') readonly @endif >{{ $encnotaentrega->jus_sol }}</textarea>
        </div>

        <div class="form-group col-4 ">
            <x-label for="observacion">Observación</x-label>
            <textarea  id="observacion" name="observacion"   class="form-control {{ $errors->has('observacion') ? 'is-invalid' : '' }}" rows="3" @if ($valor!='iniciar' && $valor!='terminar') readonly @endif>{{ $encnotaentrega->observacion }}</textarea>
            {{--  <x-input class="form-control-sm" name="observacion" type="text" rows="4" value="{{ $encnotaentrega->observacion }}" readonly/>  --}}
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
 {{--  se incluyen los combos que actualizan el código a traves de un componente  --}}
 <livewire:administrativo.meru-administrativo.contratos.proceso.inicio-tab-acta-contrato-obra-serv :encnotaentrega="$encnotaentrega" :valor="$valor"/>

</div>
