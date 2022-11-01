<div wire:init="cargar_emit" x-data="">

    <x-form method="{{ $accion =='create' ?  'post' : 'put'   }}" action="{{ $accion == 'create' ? route('cuentasxpagar.proceso.recepfactura.store') : route('cuentasxpagar.proceso.recepfactura.update', $recepfactura->id) }}">
     <x-card>
          <x-slot name="header">
              <h3 class="card-title text-bold">Devolver Recepción Factura</h3>
          </x-slot>
          <x-slot name="body">
            <ul class="nav nav-tabs" id="TabRecepFactura" role="tablist">
                <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="recepcion-tab" data-toggle="tab" data-target="#recepcion" type="button" role="tab" aria-controls="recepcion" aria-selected="true">Recepción Factura</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button  class="nav-link" id="devolucion-tab" data-toggle="tab" data-target="#devolucion" type="button" role="tab" aria-controls="devolucion" aria-selected="false">Devolución Factura</button>
                </li>
            </ul>


            <div class="tab-content" id="myTabContent">
                <div class="tab-pane  active" id="recepcion" role="tabpanel" aria-labelledby="recepcion-tab">
                    @include('administrativo/meru_administrativo/cuentasxpagar/proceso/recep_factura/partials_show/_recepcion')
                </div>
                <div  class="tab-pane fade" id="devolucion" role="tabpanel" aria-labelledby="devolucion-tab">
                    @include('administrativo/meru_administrativo/cuentasxpagar/proceso/recep_factura/partials/_devolucion')
                </div>
            </div>
          </x-slot>
          <x-slot name="footer">
              <x-input type="submit" class="btn btn-sm btn-primary text-bold float-right"  value="Devolver" />
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
        if (typeof devolucion !== 'undefined') {
            $("#hiddenDetalle").val(devolucion);
          }
      });


  </script>
@endpush
