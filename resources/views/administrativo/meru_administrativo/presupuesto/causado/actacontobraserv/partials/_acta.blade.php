<!-- Divisor Entrega HidroBolívar-->
    <div class="row col-12">
        <x-label for="tipo">&nbsp</x-label>
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Entrega HidroBolívar</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 offset-1">
        <div class="form-group col-1">
            <x-label for="id">ID</x-label>
            <x-input name="id" class="form-control-sm text-center " value="{{ $encnotaentrega->id }}" maxlength="5" disabled/>
        </div>

        <div class="form-group col-1">
            <x-label for="fk_ano_pro">Año</x-label>
            <x-input name="fk_ano_pro" class="form-control-sm text-center " value="{{ $encnotaentrega->fk_ano_pro }}" maxlength="4" disabled/>
        </div>

        <div class="form-group col-1">
            <x-label for="grupo">Grupo</x-label>
            <x-select name="grupo" class="form-control-sm text-center " disabled>
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
            <x-input name="nro_ent" class="form-control-sm text-center" value="{{ $encnotaentrega->nro_ent }}" maxlength="2" disabled/>
        </div>

        <div class="form-group col-2">
            <x-label for="fec_pos">Fecha Registro</x-label>
            <x-input name="fec_pos" class="form-control-sm text-center" type="date" value="{{ $encnotaentrega->fec_pos }}" maxlength="2" disabled/>
        </div>

        <div class="form-group col-2">
            <x-label for="fec_ent">Fecha Entrega</x-label>
            <x-input name="fec_ent" class="form-control-sm text-center" type="date" value="{{ $encnotaentrega->fec_pos }}" maxlength="2" disabled/>
        </div>

        <div class="form-group col-2">
            <x-label for="fec_com">Fecha Aprobación Contable</x-label>
            <x-input name="fec_com" class="form-control-sm text-center" type="datetime" value="{{ $encnotaentrega->fec_com }}" maxlength="2" disabled/>
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
        {{--  <div class="form-group col-1 ">
            <x-label for="fk_tip_ord">Tipo Contrato</x-label>
            <x-input class="form-control-sm" name="fk_tip_ord" value="{{ $encnotaentrega->fk_tip_ord }}" disabled/>
        </div>  --}}
        <div class="form-group col-2 offset-1 ">
            <x-label for="fk_tip_ord">Tipo Contrato</x-label>
            <x-select name="fk_tip_ord" class="form-control-sm text-center " disabled>
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
            <x-input class="form-control-sm text-center" name="ano_ord_com" value="{{ $encnotaentrega->ano_ord_com }}" disabled/>
        </div>

        <div class="form-group col-">
            <x-label for="xnro_ord">Contrato Sistema Merú</x-label>
            <x-input class="form-control-sm text-center" name="xnro_ord" value="{{ $encnotaentrega->xnro_ord }}" disabled/>
        </div>

        <div class="form-group col-2 ">
            <x-label for="cont_fis">Contrato Físico Nro.</x-label>
            <x-input class="form-control-sm" name="cont_fis" value="{{ $encnotaentrega->cont_fis }}" disabled/>
        </div>

        <div class="form-group col-2 ">
            <x-label for="fec_ord">Fecha Contrato</x-label>
            <x-input class="form-control-sm text-center" name="fec_ord" type="date" value="{{ $encnotaentrega->fec_ord }}" disabled/>
        </div>

        <div class="form-group col-2 ">
            <x-label for="mto_ord">Monto Contrato</x-label>
            <x-input class="form-control-sm text-sm-right" name="mto_ord" value="{{ $encnotaentrega->mto_ord }}" disabled/>
        </div>

        {{--  <div class="form-group col-1 ">
            <x-label for="tip_ent">Tipo Entrada</x-label>
            <x-input class="form-control-sm" name="tip_ent" value="{{ $encnotaentrega->tip_ent }}" disabled/>
        </div>  --}}

        <div class="form-group col-2 offset-1">
            <x-label for="tip_ent">Tipo Entrada</x-label>
            <x-select name="tip_ent" class="form-control-sm text-center " disabled>
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
            <x-input class="form-control-sm text-center" name="fk_rif_con" value="{{ $beneficiarios->rif_ben }}" disabled/>
        </div>
        <div class="form-group col-5 ">
            <x-label for="fk_rif_con_desc">&nbsp</x-label>
            <x-input class="form-control-sm" name="fk_rif_con_desc" value="{{ $beneficiarios->nom_ben }}" disabled/>
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
            <x-input class="form-control-sm" name="fondos" value="{{ $encnotaentrega->fondos }}" disabled/>
        </div>  --}}
        <div class="form-group col-2">
            <x-label for="fondos">Fondos</x-label>
            <x-select name="fondos" class="form-control-sm text-center " disabled>
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
            <x-input class="form-control-sm text-center" name="cuenta_contable" value="{{ $encnotaentrega->cuenta_contable }}" disabled/>
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
            <x-input class="form-control-sm text-center" name="num_fac" value="{{$encnotaentrega->num_fac }}" disabled/>
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
            <x-input class="form-control-sm" name="jus_sol" type="text"  rows="4" value="{{ $encnotaentrega->jus_sol }}" disabled/>
        </div>

        <div class="form-group col-4 ">
            <x-label for="observacion">Observación</x-label>
            <x-input class="form-control-sm" name="observacion" type="text" rows="4" value="{{ $encnotaentrega->observacion }}" disabled/>
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
        {{--  <div class="form-group col-1 ">
            <x-label for="sta_ent">Entrega</x-label>
            <x-input class="form-control-sm" name="sta_ent" value="{{ $encnotaentrega->sta_ent }}" disabled/>
        </div>  --}}
        <div class="form-group col-3">
            <x-label for="sta_ent">Entrega</x-label>
            <x-input class="form-control-sm text-center text-bold" name="sta_ent" value="{{ $statusent }}" disabled/>
            {{--  <x-select name="sta_ent" class="form-control-sm text-center " disabled>
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
            <x-input class="form-control-sm" name="stat_causacion" value="{{ $encnotaentrega->stat_causacion  }}" disabled/>
        </div>  --}}
        <div class="form-group col-3">
            <x-label for="stat_causacion">Comprobante</x-label>
            <x-input class="form-control-sm text-center text-bold" name="sta_ent" value="{{ $statcomprob }}" disabled/>
            {{--  <x-select name="stat_causacion" class="form-control-sm text-center " disabled>
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
    <div class="row col-12 offset-1">
        <div class="form-group col-6 ">
            <x-label for="recomen">Recomendación</x-label>
            <x-input class="form-control-sm" name="recomen" type="text" rows="4" value="{{ $encnotaentrega->recomen }}" disabled/>
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
        @if ($encnotaentrega->acta)
            <div class="form-group col-4 ">
                <x-label for="ced_hb">Cédula Resp. HB</x-label>
                <x-input class="form-control-sm text-center" name="ced_hb" value="{{ $encnotaentrega->acta->ced_hb }}" disabled/>
            </div>

            <div class="form-group col-4 ">
                <x-label for="nom_hb">Nombre Resp. HB</x-label>
                <x-input class="form-control-sm" name="nom_hb" value="{{ $encnotaentrega->acta->nom_hb}}" disabled/>
            </div>

            <div class="form-group col-4 ">
                <x-label for="cargo_hb">Cargo Resp. HB</x-label>
                <x-input class="form-control-sm text-center" name="cargo_hb" type="text" rows="4" value="{{ $encnotaentrega->acta->cargo_hb}}" disabled/>
            </div>
        @else
            <div class="form-group col-4 ">
                <x-label for="ced_hb">Cédula Resp. HB</x-label>
                <x-input class="form-control-sm text-center" name="ced_hb" value="" disabled/>
            </div>

            <div class="form-group col-4 ">
                <x-label for="nom_hb">Nombre Resp. HB</x-label>
                <x-input class="form-control-sm" name="nom_hb" value="" disabled/>
            </div>

            <div class="form-group col-4 ">
                <x-label for="cargo_hb">Cargo Resp. HB</x-label>
                <x-input class="form-control-sm" name="cargo_hb" type="text" rows="4" value="" disabled/>
            </div>
        @endif
    </div>

<!-- Divisor Lugar-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">&nbsp</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>
    <div class="row col-12 offset-1">
        @if ($encnotaentrega->acta)
            <div class="form-group col-6 ">
                <x-label for="lug_reunion">Reunidos En</x-label>
                <x-input class="form-control-sm text-center" name="lug_reunion" value="{{  $encnotaentrega->acta->lug_reunion }}" disabled/>
            </div>

            <div class="form-group col-2 ">
                <x-label for="fecha_acta">En Fecha:</x-label>
                <x-input class="form-control-sm text-center" name="fecha_acta" type="date" value="{{ $encnotaentrega->acta->fecha_acta }}" disabled/>
            </div>

            <div class="form-group col-2 ">
                <x-label for="revision">En Revision</x-label>
                <x-input class="form-control-sm text-center" name="fecha_acta" type="date" value="{{ $encnotaentrega->acta->revision }}" disabled/>
            </div>

            <div class="form-group col-6 ">
                <x-label for="gerencia">Gerencia</x-label>
                <x-input class="form-control-sm text-center" name="fecha_acta" type="text" value="{{ $encnotaentrega->acta->gerencia }}" disabled/>
            </div>
        @else
            <div class="form-group col-6 ">
                <x-label for="lug_reunion">Reunidos En</x-label>
                <x-input class="form-control-sm text-center" name="lug_reunion" value="" disabled/>
            </div>

            <div class="form-group col-2 ">
                <x-label for="fecha_acta">En Fecha:</x-label>
                <x-input class="form-control-sm text-center" name="fecha_acta" type="date" value="" disabled/>
            </div>

            <div class="form-group col-2 ">
                <x-label for="revision">En Revision</x-label>
                <x-input class="form-control-sm text-center" name="fecha_acta" type="date" value="" disabled/>
            </div>

            <div class="form-group col-6 ">
                <x-label for="gerencia">Gerencia</x-label>
                <x-input class="form-control-sm text-center" name="fecha_acta" type="text" value="" disabled/>
            </div>
        @endif
    </div>

<!-- Divisor Contratista -->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Contratista</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 offset-1">
        <div class="form-group col-4 ">
            <x-label for="ced_con">Cédula Resp. Cont.</x-label>
            <x-input class="form-control-sm text-center" name="ced_con" value="{{  $encnotaentrega->ced_con }}" disabled/>
        </div>

        <div class="form-group col-4 ">
            <x-label for="nom_con">Nombre Resp. Cont.</x-label>
            <x-input class="form-control-sm text-center" name="nom_con" value="{{  $encnotaentrega->nom_con }}" disabled/>
        </div>
    </div>

