<div class=" col-12" wire:init="cargar_emit" x-data="">
    <x-form method="{{ $accion =='create' ?  'post' : 'put'   }}" action="{{ $accion == 'create' ? route('contratos.proceso.actacontratobraserv.store') : route('contratos.proceso.actacontratobraserv.update', $encnotaentrega->id) }}">
        <x-card>
            <x-slot name="header">
                <h3 class="card-title text-bold">Actas a Contratos de Obras/Servicios</h3>
            </x-slot>
            <x-slot name="body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                       <button class="nav-link active" id="acta-tab" data-toggle="tab" data-target="#acta" type="button" role="tab" aria-controls="identificacion" aria-selected="true">Acta de Servicio</button>
                    </li>
                    <li class="nav-item" role="presentation">
                       <button class="nav-link" id="detalle-tab" data-toggle="tab" data-target="#detalle" type="button" role="tab" aria-controls="otros" aria-selected="false">Detalle de Acta de Servicio</button>
                    </li>
                    @if ($accion !='create')
                        <li class="nav-item" role="presentation">
                        <button class="nav-link" id="comprobante-tab" data-toggle="tab" data-target="#comprobante" type="button" role="tab" aria-controls="situacion-financiera" aria-selected="false">Comprobantes de Nota de Entrega</button>
                        </li>
                    @endif
                </ul>


                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane active" id="acta" role="tabpanel" aria-labelledby="acta-tab">
                        @include('administrativo/meru_administrativo/contratos/procesos/actacontratoobraserv/partials/_acta')
                    </div>
                    <div class="tab-pane fade" id="detalle" role="tabpanel" aria-labelledby="detalle-tab">
                        @include('administrativo/meru_administrativo/contratos/procesos/actacontratoobraserv/partials/_detalle')
                    </div>
                    @if ($accion !='create')
                        <div class="tab-pane container fade" id="comprobante" role="tabpanel" aria-labelledby="comprobante-tab">

                        </div>
                    @endif
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-input id="gnuevo" name="gnuevo" type="submit" class="btn btn-sm btn-primary text-bold float-right"  value="Guardar" />
            </x-slot>
        </x-card>
    </x-form>
</div>
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
			$('.money-mask').keypress(function (e) {
				if (e.which != 8 && e.which != 0 && e.which != 44 && (e.which < 48 || e.which > 57)) {
					return false;
				}
			});
		});

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
        window.livewire.on('alert', param => {
            $(param['tab']).tab('show');
            $(param['onfocus']).focus();
            var detalle = JSON.stringify(param['det']);
            $("#hiddenDetalle").val(detalle);
            $("#hiddengastos").val(detallegasto);

        });

        window.livewire.on('swal:alert', param => {
            Swal.fire({title:param['titulo'],
            text:param['mensaje'],
            icon:param['tipo']
            })
        });
        window.livewire.on('enableComp', param => {
            $( "#fec_ent" ).prop( "disabled", false );
            $('#fec_ent').attr('readonly', false);
            $('#tip_ent').attr('readonly', false);
            $('#observacion').attr('readonly', false);
            $('#ano_ord_com').attr('readonly', true);
            $('#ano_ord_com').css('pointer-events', 'none');
            $('#xnro_ord').attr('readonly', true);
            $('#antc_amort').attr('readonly', false);
            $( "#gnuevo" ).prop( "disabled", false );
        });

        window.livewire.on('enableGasto', param => {
            $("#tituloGastos").attr("style", "visibility: none");
            $("#gridGastos").attr("style", "visibility: none");
        });

    </script>
@endpush
