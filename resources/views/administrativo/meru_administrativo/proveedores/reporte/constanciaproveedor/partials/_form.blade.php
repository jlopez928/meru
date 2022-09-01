<x-card>
    <x-slot name="header">
        <h3 class="card-title text-bold">Constancia Proveedor</h3>
    </x-slot>

    <x-slot name="body">
        <div class="row col-12">
           <x-field class="form-group col-1 offset-1" >
                <x-label for="id">{{ __('ID') }}</x-label>
                <x-input class="text-center form-control-sm " name="id" value="{{  old('id', $constanciaproveedore->id) }}" readonly/>
            </x-field>
            <x-field class="form-group col-2" >
                <x-label for="rif_prov">Rif Proveedor</x-label>
                <x-input name="rif_prov" class=" form-control-sm {{ $errors->has('rif_prov') ? 'is-invalid' : '' }}" type="text" placeholder="Ingrese Rif Proveedor" value="{{ old('rif_prov', $constanciaproveedore->rif_prov) }}" readonly />
                <div class="invalid-feedback">
                    @error('rif_prov') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="form-group col-5" >
                <x-label for="nom_prov">Nombre Proveedor</x-label>
                <x-input name="nom_prov" class=" form-control-sm {{ $errors->has('nom_prov') ? 'is-invalid' : '' }}" type="text" placeholder="Ingrese Nombre" value="{{ old('nom_prov', $constanciaproveedore->nom_prov) }}"  readonly />
                <div class="invalid-feedback">
                    @error('nom_prov') {{ $message }} @enderror
                </div>
            </x-field>
        </div>

        <!-- Divisor-->
        <div class="row col-12">
            <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
        </div>

        {{--  <div class="form-control row col-12">
            <x-field class="form-group col-1" >
                <x-input name="uno" class=" form-control-sm" type="checkbox" value=""   />
            </x-field>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="flexCheckCheckedDisabled" >
            <label class="form-check-label" for="flexCheckCheckedDisabled">
              Disabled checked checkbox
            </label>
          </div>  --}}

        <div class="row col-12">
            <div class="form-check offset-3">
                <input class="form-check-input" style="width:20px; height:20px;" type="checkbox" name="Checkbox1" value="1">
                <label class="form-check-label" style="font-size: 120%" for="inlineCheckbox1">REGISTRO MERCANTIL</label>
              </div>
        </div>
        <div class="row col-12">
            <div class="form-check offset-3">
                <input class="form-check-input" style="width:20px; height:20px;" type="checkbox" name="Checkbox2" value="1">
                <label class="form-check-label" style="font-size: 120%" for="inlineCheckbox1">COPIA DE CEDULA DEL REPRESENTANTE LEGAL</label>
              </div>
        </div>
        <div class="row col-12">
            <div class="form-check offset-3">
                <input class="form-check-input" style="width:20px; height:20px;" type="checkbox" name="Checkbox3" value="1">
                <label class="form-check-label" style="font-size: 120%" for="inlineCheckbox1">COPIA DE CEDULA Y CARTA DE AUTORZACION DEL AUTORIZADO A COBRAR</label>
              </div>
        </div>
        <div class="row col-12">
            <div class="form-check offset-3">
                <input class="form-check-input" style="width:20px; height:20px;" type="checkbox" name="Checkbox4" value="1">
                <label class="form-check-label" style="font-size: 120%" for="inlineCheckbox1">COPIA DE RIF</label>
              </div>
        </div>
        <div class="row col-12">
            <div class="form-check offset-3">
                <input class="form-check-input" style="width:20px; height:20px;" type="checkbox" name="Checkbox5" value="1">
                <label class="form-check-label" style="font-size: 120%" for="inlineCheckbox1">COPIA DE LA SOLVENCIA LABORAL (GOBERNACION DEL ESTADO BOLIVAR) </label>
              </div>
        </div>
        <div class="row col-12">
            <div class="form-check offset-3">
                <input class="form-check-input" style="width:20px; height:20px;" type="checkbox" name="Checkbox6" value="1">
                <label class="form-check-label" style="font-size: 120%" for="inlineCheckbox1">COPIA DE LA SOLVENCIA EMITIDA POR HIDROBOLIVAR</label>
              </div>
        </div>
        <div class="row col-12">
            <div class="form-check offset-3">
                <input class="form-check-input" style="width:20px; height:20px;" type="checkbox" name="Checkbox7" value="1">
                <label class="form-check-label" style="font-size: 120%" for="inlineCheckbox1">LISTADO DE BIENES O SERVICIOS A OFRECER</label>
              </div>
        </div>
        <div class="row col-12">
            <div class="form-check offset-3">
                <input class="form-check-input" style="width:20px; height:20px;" type="checkbox" name="Checkbox8" value="1">
                <label class="form-check-label" style="font-size: 120%" for="inlineCheckbox1">INSCRIPCION DEL SERVICIO NACIONAL DE CONTRATISTAS 	</label>
              </div>
        </div>
        <div class="row col-12">
            <div class="form-check offset-3">
                <input class="form-check-input" style="width:20px; height:20px;" type="checkbox" name="Checkbox9" value="1">
                <label class="form-check-label" style="font-size: 120%" for="inlineCheckbox1">INSCRIPCION DEL SUNACOOP 	</label>
              </div>
        </div>
        <div class="row col-12">
            <div class="form-check offset-3">
                <input class="form-check-input" style="width:20px; height:20px;" type="checkbox" name="Checkbox10" value="1">
                <label class="form-check-label" style="font-size: 120%" for="inlineCheckbox1">SOLVENCIA INCE 	</label>
              </div>
        </div>
        <div class="row col-12">
            <div class="form-check offset-3">
                <input class="form-check-input" style="width:20px; height:20px;" type="checkbox" name="Checkbox11" value="1">
                <label class="form-check-label" style="font-size: 120%" for="inlineCheckbox1">SOLVENCIA DEL SEGURO SOCIAL</label>
              </div>
        </div>

    </x-slot>

    <x-slot:footer>
        <button type="submit" class="btn btn-sm btn-primary text-bold float-right" title="Generar PDF">
            <i class="fas fa-download"> Generar PDF</i>
        </button>
</x-slot:footer>
</x-card>
