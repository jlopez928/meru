<div wire:init="cargar_emit" x-data="">
     <x-form method="{{ ($accion =='create') ?  'post' : 'get'   }}"
             action="{{ ($accion == 'create' ? route('cuentasxpagar.proceso.factura.store')
                            :($accion == 'cambiar' ? route('cuentasxpagar.proceso.factura.cambiar', [$factura->id,'cambiar'])
                                :($accion == 'anular' ? route('cuentasxpagar.proceso.factura.anular', [$factura->id,'anular'])
                                    :($accion == 'aprobar' ?  route('cuentasxpagar.proceso.factura.aprobar', [$factura->id,'aprobar'])
                                        :($accion == 'reversar' ?  route('cuentasxpagar.proceso.factura.reversar', [$factura->id,'reversar'])
                                            :($accion == 'modificar' ?  route('cuentasxpagar.proceso.factura.modificar', [$factura->id,'modificar']):'' )
                                         )
                                      )
                                 )
                             )
                        )
                    }} ">
      <x-card>
          <x-slot name="header">
              <h3 class="card-title text-bold">Ingreso de Facturas/Recibos</h3>
          </x-slot>
          <x-slot name="body">
            <ul class="nav nav-tabs" id="TabFactura" role="tablist">
                <li class="nav-item" role="presentation">
                     <button class="nav-link active" id="facturas-tab" data-toggle="tab" data-target="#facturas" type="button" role="tab" aria-controls="facturas" aria-selected="true">Datos de la Factura/Recibo</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button  class="nav-link" id="detalle-tab" data-toggle="tab" data-target="#detalle" type="button" role="tab" aria-controls="detalle" aria-selected="false">Detalle de Factura/Recibo</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button  class="nav-link" id="gastos-tab" data-toggle="tab" data-target="#gastos" type="button" role="tab" aria-controls="gastos" aria-selected="false">Estructura de Gastos</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button  class="nav-link" id="asiento-tab" data-toggle="tab" data-target="#asiento" type="button" role="tab" aria-controls="asiento" aria-selected="false">Asiento Contable</button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane  active" id="facturas" role="tabpanel" aria-labelledby="facturas-tab">
                    @include('administrativo/meru_administrativo/cuentasxpagar/proceso/factura/partials/_factura')
                </div>
                <div  class="tab-pane fade" id="detalle" role="tabpanel" aria-labelledby="detalle-tab">
                     @include('administrativo/meru_administrativo/cuentasxpagar/proceso/factura/partials/_detalle')
                </div>
                <div  class="tab-pane fade" id="gastos" role="tabpanel" aria-labelledby="gastos-tab">
                    @include('administrativo/meru_administrativo/cuentasxpagar/proceso/factura/partials/_gastos')
               </div>
               <div  class="tab-pane fade" id="asiento" role="tabpanel" aria-labelledby="asiento-tab">
                @include('administrativo/meru_administrativo/cuentasxpagar/proceso/factura/partials/_asiento')
           </div>
            </div>
        </x-slot>
          <x-slot name="footer">
            @if ($accion == 'create')
                <input type="submit" name="guardar" id="guardar" class="btn btn-sm btn-primary text-bold float-right"  value="Guardar" {{ ($accion =='create')? 'disabled' : '' }}/>
            @elseif ($accion == 'cambiar')
                <input type="submit" name="guardar" id="guardar" class="btn btn-sm btn-primary text-bold float-right"  value="Cambiar" {{ ($accion =='create')? 'disabled' : '' }}/>
            @elseif ($accion == 'anular')
                <input type="submit" name="guardar" id="guardar" class="btn btn-sm btn-primary text-bold float-right"  value="Anular" {{ ($accion =='create')? 'disabled' : '' }}/>
            @elseif ($accion == 'aprobar')
                <input type="submit" name="guardar" id="guardar" class="btn btn-sm btn-primary text-bold float-right"  value="Aprobar" {{ ($accion =='create')? 'disabled' : '' }}/>
            @elseif ($accion == 'reversar')
                <input type="submit" name="guardar" id="guardar" class="btn btn-sm btn-primary text-bold float-right"  value="Reversar" {{ ($accion =='create')? 'disabled' : '' }}/>
            @elseif ($accion == 'modificar')
                <input type="submit" name="guardar" id="guardar" class="btn btn-sm btn-primary text-bold float-right"  value="Modificar Asiento" {{ ($accion =='create')? 'disabled' : '' }}/>
            @endif
          </x-slot>
      </x-card>
  </x-form>

  </div>


@push('scripts')
    <script type="text/javascript">
        window.livewire.on('alert', param => {
            $(param['tab']).tab('show');
            $(param['onfocus']).focus();
            var detalle = JSON.stringify(param['det']);
            if (typeof facturas !== 'undefined') {
                $("#hiddenDetalle").val(facturas);
            }
        });
        window.livewire.on('enableBoton', param => {
            alert('por aqui');
            $('#guardar').prop('disabled', true);
          //  var detalle = JSON.stringify(param['det']);
           // $("#hiddenDetalle").val(detalle);
           // $("#hiddengastos").val(detallegasto);
        });
    window.livewire.on('enableNuevafactura', param => {
        //alert('Nuevo');
        $('#guardar').prop('disabled', false);
        // $('guardar').attr('disabled', false);
        $('#num_fac').attr('readonly', false);
        $('#ano_pro').attr('readonly', false);
        $('#recibo').attr('readonly', true);
        $('#fondo').attr('readonly', false);
        $('#fondo').css('pointer-events','');
        $('#ncr_sn').css('pointer-events','none');
        $('#num_ctrl').attr('readonly', false);
        $('#por_iva').attr('readonly', false);
        $('#base_imponible').attr('readonly', false);
        $('#base_excenta').attr('readonly', false);
        $('#mto_iva').attr('readonly', false);

    });
    window.livewire.on('enableCambiarfactura', param => {
        //alert('Cambiar');
        $('#guardar').prop('disabled', false);
        $('#num_fac').attr('readonly', true);
        $('#recibo').attr('readonly', true);
        $('#fondo').attr('readonly', false);
        $('#fondo').css('pointer-events','');
        $('#ncr_sn').css('pointer-events','none');
        $('#num_ctrl').attr('readonly', false);
        $('#por_iva').attr('readonly', false);
        $('#base_imponible').attr('readonly', false);
        $('#base_excenta').attr('readonly', false);
        $('#mto_iva').attr('readonly', false);
        $('#ano_pro').attr('readonly', true);
        $("#base_imponible")[0].disabled = false;
        $("#base_excenta")[0].disabled = false;
        $("#lrecibo")[0].disabled = true;
       // $("#rif_prov").select2("readonly", true);
        //$('#rif_prov').css('pointer-events','none');
    });
    window.livewire.on('enableAnularfactura', param => {
        //alert('Anular');
        $('#guardar').prop('disabled', false);
        $("#num_fac").attr('readonly', true);
        $("#ano_pro").attr('readonly', true);
        $('#recibo').attr('readonly', true);
        $('#fondo').attr('readonly', true);
        $('#fondo').css('pointer-events','none');
        $('#ncr_sn').css('pointer-events','none');
        $('#num_ctrl').attr('readonly', true);
        $('#por_iva').attr('readonly', true);
        $('#base_imponible').attr('readonly', true);
        $('#base_excenta').attr('readonly', true);
        $('#mto_iva').attr('readonly', true);
        $('#ano_pro').attr('readonly', true);
        $("#base_imponible")[0].disabled = true;
        $("#base_excenta")[0].disabled = true;

       // $("#rif_prov").select2("readonly", true);
        //$('#rif_prov').css('pointer-events','none');
    });

    window.livewire.on('swal:alert', param => {
        Swal.fire({title:param['titulo'],
        text:param['mensaje'],
        icon:param['tipo']
        })
    });
    if ($('#guardar').prop("disabled")){
        $(function () {
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                minimumInputLength: 2,
                language: {
                    noResults: function() {
                        return 'No se encontraron resultados';
                    },
                    inputTooShort: function() {
                        return 'Ingrese al menos dos letras';
                    }
                }
            }).on('change', function(event){
                Livewire.emit('changeSelect', $(this).val(), event.target.id)
            });
        })
    }else{

        $(function () {
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                minimumInputLength: 2,
                readonly:true,
                language: {
                    noResults: function() {
                        return 'No se encontraron resultados';
                    },
                    inputTooShort: function() {
                        return 'Ingrese al menos dos letras';
                    }
                }
            }).on('change', function(event){
                Livewire.emit('changeSelect', $(this).val(), event.target.id)
            });
        })
    }

  </script>
@endpush
