<div wire:init="cargar_emit" x-data="">
    <x-form method="{{ $accion =='create' ?  'post' : 'put'   }}" action="{{ $accion == 'create' ? route('contratos.proceso.certificacioncontrato.store') : route('contratos.proceso.certificacioncontrato.update', $certificacionservicio->id) }}">
     <x-card>
          <x-slot name="header">
            @if($nombreRuta =='contrato')
              <h3 class="card-title text-bold">Certificación de Contratos</h3>
            @else
                <h3 class="card-title text-bold">Certificación de Contratos Addendum</h3>
            @endif
          </x-slot>
          <x-slot name="body">
              <ul class="nav nav-tabs" id="TabCertificacion" role="tablist">
                  <li class="nav-item" role="presentation">
                       <button class="nav-link active" id="identificacion-tab" data-toggle="tab" data-target="#identificacion" type="button" role="tab" aria-controls="identificacion" aria-selected="true">Identificación</button>
                  </li>
                  <li class="nav-item" role="presentation">
                      <button  class="nav-link" id="detalle-tab" data-toggle="tab" data-target="#detalle" type="button" role="tab" aria-controls="detalle" aria-selected="false">Detalle Del Servicio</button>
                  </li>
                  <li class="nav-item" role="presentation">
                       <button class="nav-link" id="condiciones-tab" data-toggle="tab" data-target="#condiciones" type="button" role="tab" aria-controls="condiciones" aria-selected="false">Justificaciones</button>
                  </li>
              </ul>

              <div class="tab-content">
                  <div class="tab-pane container active" id="identificacion" role="tabpanel" aria-labelledby="identificacion-tab">
                    @if($nombreRuta =='contrato')
                      @include('administrativo/meru_administrativo/contratos/proceso/certificacion_contrato/partials/_identificacion')
                    @else
                      @include('administrativo/meru_administrativo/contratos/proceso/certificacion_contrato/partials/_identificacionAddendum')
                    @endif
                  </div>
                  <div  class="tab-pane container fade" id="detalle" role="tabpanel" aria-labelledby="detalle-tab">
                        @include('administrativo/meru_administrativo/contratos/proceso/certificacion_contrato/partials/_detalle')
                   </div>
                  <div   class="tab-pane container fade" id="condiciones" role="tabpanel" aria-labelledby="condiciones-tab">
                      @include('administrativo/meru_administrativo/contratos/proceso/certificacion_contrato/partials/_condiciones')
                  </div>
              </div>
          </x-slot>
          <x-slot name="footer">
            @if($nombreRuta =='contrato'||  $habilitar==true)
              <x-input type="submit" class="btn btn-sm btn-primary text-bold float-right"  value="Guardar" />
            @endif
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
            if (typeof detalle !== 'undefined') {
                $("#hiddenDetalle").val(detalle);
            }

          });
          window.livewire.on('swal:alert', param => {
              Swal.fire({title:param['titulo'],
              text:param['mensaje'],
              icon:param['tipo']
              })
              $(param['onfocus']).focus();
          });
          Livewire.on('swal:confirm', param => {
              Swal.fire({
                  title: param['titulo'],
                  text: param['mensaje'],
                  icon: param['tipo'],
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: '¡Sí!',
                  cancelButtonColor: '#d33',
                  cancelButtonText: 'Cancelar',
              }).then((result) => {
                  if(result.isConfirmed){
                      Livewire.emitTo('administrativo.meru-administrativo.contratos.proceso.tab-certificacion-contrato', param['funcion'],param['posicion'])
                 }else{
                      Livewire.emitTo('administrativo.meru-administrativo.contratos.proceso.tab-certificacion-contrato', param['funcion2'],param['posicion'])
                 }
              })
          })
      </script>
  @endpush
