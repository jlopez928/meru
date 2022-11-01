<div wire:init="cargar_emit" x-data="">

    <x-form method="post" action="{{ route('cuentasxpagar.proceso.recepfactura.modificar', $recepfactura->id) }}">
     <x-card>
          <x-slot name="header">
              <h3 class="card-title text-bold">Modificar Factura</h3>
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
                    @include('administrativo/meru_administrativo/cuentasxpagar/proceso/recep_factura/partials/_modrecepcion')
                </div>
                <div  class="tab-pane fade" id="devolucion" role="tabpanel" aria-labelledby="devolucion-tab">
                  @include('administrativo/meru_administrativo/cuentasxpagar/proceso/recep_factura/partials/_moddevolucion')
                </div>
            </div>
          </x-slot>
          <x-slot name="footer">
              <x-input type="submit" class="btn btn-sm btn-primary text-bold float-right"  value="Modificar" />
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
        $("#hiddenDetalle").val(detalle);
        $("#hiddengastos").val(detallegasto);

    });
    window.livewire.on('enableFacDev', param => {
        $('#mto_fac').attr('readonly', true);
        $('#ano_sol').attr('readonly', true);
        $('#nro_doc').attr('readonly', true);
        $('#tipo_doc').attr('readonly', true);
        $('#recibo').attr('readonly', true);
        $(".marcar").prop('checked', true);

    });
    window.livewire.on('enableFacMod', param => {
        $('#mto_fac').attr('readonly', false);
        $('#ano_sol').attr('readonly', false);
        $('#nro_doc').attr('readonly', false);
        $('#tipo_doc').attr('readonly', false);
        $('#recibo').attr('readonly', false);
        $('#fec_dev').attr('readonly', true);
        $('#resp_dev').attr('readonly', true);
        $('#observaciones').attr('readonly', true);



    });enableFacMod
  </script>
@endpush

