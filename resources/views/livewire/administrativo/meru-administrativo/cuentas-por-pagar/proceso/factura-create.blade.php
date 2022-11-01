<div wire:init="cargar_emit" x-data="">

    <x-form method="{{ $accion =='create' ?  'post' : 'put'   }}" action="{{ $accion == 'create' ? route('cuentasxpagar.proceso.factura.store') : route('cuentasxpagar.proceso.factura.update', $factura->id) }}">
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
              <x-input type="submit" name="guardar" id="guardar" class="btn btn-sm btn-primary text-bold float-right"  value="Guardar" disabled/>
          </x-slot>
      </x-card>
  </x-form>

  </div>


@push('scripts')
    <script type="text/javascript">

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

        window.livewire.on('enableCreatefactura', param => {
            $('.select2bs4').attr('readonly', true);
            $('#guardar').prop('disabled', false);
           // $('guardar').attr('disabled', false);
            $('#num_fac').attr('readonly', true);
            $('#ano_pro').attr('readonly', true);
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


        window.livewire.on('alert', param => {
            $(param['tab']).tab('show');
            $(param['onfocus']).focus();
            var detalle = JSON.stringify(param['det']);
            if (typeof facturas !== 'undefined') {
                $("#hiddenDetalle").val(facturas);
            }
        });
        window.livewire.on('swal:alert', param => {
            Swal.fire({title:param['titulo'],
            text:param['mensaje'],
            icon:param['tipo']
            })
        });

  </script>
@endpush
