    <!-- Divisor Recepción-->
    <div class="row col-12">
        <x-label for="tipo">&nbsp</x-label>
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Datos del Registro</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 ">
        <div class="form-group col-1 offset-2">
            <x-label for="nro_reng">Nro. Registro</x-label>
            <x-input name="nro_reng" class="form-control-sm text-sm-center " value="{{ $recepfactura->nro_reng }}"  readonly/>
        </div>

        <div class="form-group col-5">
            <x-label for="sta_fac">Estado</x-label>
            <x-input name="sta_fac" class="form-control-sm"  style="font-size: 5mm" value="{{ $recepfactura->getEstFac($recepfactura->sta_fac) }}"  readonly/>
        </div>
    </div>

    <!-- Divisor Datos del documento-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Datos Del Documento</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 offset-2">
        <div class="row col-12 ">
            <div class="form-group col-1">
                <x-label for="rif_prov">Proveedor</x-label>
                <x-input class="form-control-sm text-center" name="rif_prov" value="{{ $recepfactura->rif_prov }}" readonly/>
            </div>
            <div class="form-group col-5 ">
                <x-label for="fk_rif_con_desc">&nbsp</x-label>
                <x-input class="form-control-sm" name="fk_rif_con_desc" value="{{ $recepfactura->proveedor->nom_prov }}" readonly/>
            </div>
        </div>

        <div class="form-group col-2">
            <x-label for="documento">Documento</x-label>
            <x-input name="documento" class="form-control-sm"   value="{{ $recepfactura->tipo_doc ? $recepfactura->cxptipodocumento->descripcion_doc : '' }}"  readonly/>
        </div>

        <div class="form-group col-2">
            <x-label for="numero">Número</x-label>
            <x-input name="numero" class="form-control-sm text-sm-center"   value="{{ $recepfactura->nro_doc }}"  readonly/>
        </div>

        <div class="form-group col-2">
            <x-label for="ano_sol">Año Doc.</x-label>
            <x-input name="ano_sol" class="form-control-sm text-sm-center"   value="{{ $recepfactura->ano_sol }}"  readonly/>
        </div>
    </div>

    <div class="row col-12 offset-2">
        <div class="form-group col-6 ">
            <x-label for="concepto">Concepto</x-label>
            <textarea  id="concepto" name="concepto"   class="form-control" rows="3" readonly >{{ $recepfactura->concepto }}</textarea>
        </div>
    </div>

    <!-- Divisor Datos de la factura-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Datos De La Factura</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 offset-2">
        <div class="form-group col-2">
            <x-label for="ano_pro">Año Recep. Factura</x-label>
            <x-input name="ano_pro" class="form-control-sm text-sm-center"   value="{{ $recepfactura->ano_pro }}"  readonly/>
        </div>

        <div class="form-group col-2">
            <x-label for="num_fac">Factura</x-label>
            <x-input name="num_fac" class="form-control-sm text-sm-center"   value="{{ $recepfactura->num_fac }}"  readonly/>
        </div>

        <div class="form-group col-2">
            <x-label for="recibo">Tipo</x-label>
            {{--  <x-input name="recibot" class="form-control-sm text-sm-center"   value="{{ $recepfactura->getRecibo($recepfactura->recibo) }}"  readonly/>  --}}

                <x-select  id="recibo" name="recibo"  class="form-select  form-control-sm" readonly>
                    <option value="">-- Seleccione --</option>
                    <option value="F" @if($recepfactura->recibo=='F')  selected @endif>Factura</option>
                    <option value="R" @if($recepfactura->recibo=='R')  selected @endif>Recibo</option>
                </x-select>
        </div>
    </div>
    <div class="row col-12 offset-2">
        <div class="form-group col-2">
            <x-label for="fec_fac">Fecha de Factura</x-label>
            <x-input name="fec_fac" class="form-control-sm text-sm-center" type="text"  value="{{ ($recepfactura->fec_fac)? $recepfactura->fec_fac->format('d-m-Y'):''}}"  readonly/>
        </div>
        <div class="form-group col-2">
            <x-label for="mto_fac">Monto de Factura</x-label>
            <x-input name="mto_fac" class="form-control-sm text-sm-right" type="text"  value="{{ $recepfactura->mto_fac}}"  readonly/>
        </div>

        <div class="form-group col-2">
            <x-label for="fec_rec">Recepción</x-label>
            <x-input name="fec_rec" class="form-control-sm text-sm-center" type="text"  value="{{ ($recepfactura->fec_rec)? $recepfactura->fec_rec->format('d-m-Y') : ''}}"  readonly/>
        </div>
    </div>
    <div class="row col-12 offset-2">
        <div class="form-group col-2">
            <x-label for="lfec_entrega">Fecha de Entrega</x-label>
            <x-input  wire:model.defer="fec_entrega" name="fec_entrega" class="form-control-sm text-center" type="date"  value="{{ $recepfactura->fec_entrega}}"  readonly />
        </div>

        <div class="form-group col-4">
            <x-label for="lusu_rec">Recepcionado Por:</x-label>
            @if($recepfactura->usuario != '')
                <x-input name="usu_rec2" class="form-control-sm"  style="font-size: 5mm" value="{{strtoupper($recepfactura->usuario).' - '.$recepfactura->usuariorec->nombre }}"  readonly/>
            @else
                <x-input name="usu_rec2" class="form-control-sm"  style="font-size: 5mm" value="{{strtoupper($recepfactura->usuario)}}"  readonly/>
            @endif
            <x-input name="usu_rec" class="form-control-sm"  style="visibility: hidden;" value="{{strtoupper($recepfactura->usuario) }}"  readonly/>

        </div>

    </div>
