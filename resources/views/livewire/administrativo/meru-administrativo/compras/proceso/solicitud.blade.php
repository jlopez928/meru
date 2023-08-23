<x-card>
    <x-slot:header>
        <h3 class="card-title text-bold">{{ $descripcionModulo }}</h3>
    </x-slot>
<x-slot:body>

    <div x-data="form()">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <button @click.prevent="tab = 'tab1'" :class="{'active' : tab === 'tab1'}" class="nav-link" data-toggle="tab">Encabezado</button>
            </li>
            <li class="nav-item">
                <button @click.prevent="tab = 'tab2'" :class="{'active' : tab === 'tab2'}" class="nav-link" data-toggle="tab">Detalle</button>
            </li>
            <li class="nav-item">
                <button @click.prevent="tab = 'tab3'" :class="{'active' : tab === 'tab3'}" class="nav-link" data-toggle="tab">Producto</button>
            </li>
            @if ($grupo === 'SV')
                <li class="nav-item">
                    <button @click.prevent="tab = 'tab4'" :class="{'active' : tab === 'tab4'}" class="nav-link" data-toggle="tab">Bienes/Vehiculos</button>
                </li>
            @endif
            <li class="nav-item">
                <button @click.prevent="tab = 'tab5'" :class="{'active' : tab === 'tab5'}" class="nav-link" data-toggle="tab">Contratante</button>
            </li>
            <li class="nav-item">
                <button @click.prevent="tab = 'tab6'" :class="{'active' : tab === 'tab6'}" class="nav-link" data-toggle="tab">Anulación</button>
            </li>
            <li class="nav-item">
                <button @click.prevent="tab = 'tab7'" :class="{'active' : tab === 'tab7'}" class="nav-link" data-toggle="tab">Doc. Asociados</button>
            </li>
        </ul>

        <div class="tab-content">
            <div x-show="tab === 'tab1'" :class="{'show active' : tab === 'tab1'}" class="tab-pane fade">
                @include('administrativo/meru_administrativo/compras/proceso/solicitud/partials/_encabezado')
            </div>
            <div x-show="tab === 'tab2'" :class="{'show active' : tab === 'tab2'}" class="tab-pane fade">
                @include('administrativo/meru_administrativo/compras/proceso/solicitud/partials/_detalle')
            </div>
            <div x-show="tab === 'tab3'" :class="{'show active' : tab === 'tab3'}" class="tab-pane fade">
                @include('administrativo/meru_administrativo/compras/proceso/solicitud/partials/_producto')
            </div>
            <div x-show="tab === 'tab4'" :class="{'show active' : tab === 'tab4'}" class="tab-pane fade">
                @include('administrativo/meru_administrativo/compras/proceso/solicitud/partials/_bien-vehiculo')
            </div>
            <div x-show="tab === 'tab5'" :class="{'show active' : tab === 'tab5'}" class="tab-pane fade">
                @include('administrativo/meru_administrativo/compras/proceso/solicitud/partials/_contratante')
            </div>
            <div x-show="tab === 'tab6'" :class="{'show active' : tab === 'tab6'}" class="tab-pane fade">
                @include('administrativo/meru_administrativo/compras/proceso/solicitud/partials/_anulacion')
            </div>
            <div x-show="tab === 'tab7'" :class="{'show active' : tab === 'tab7'}" class="tab-pane fade">
                @include('administrativo/meru_administrativo/compras/proceso/solicitud/partials/_doc-asociados')
            </div>
        </div>
    </div>
</x-slot>

<x-slot:footer>
    @if ($modulo == 'unidad')
        @if ($accion == 'nuevo')
            <button class="btn btn-primary float-right" wire:click="confirmCrearSolicitud" title="Guardar Datos de la Solicitud">Guardar</button>
        @endif

        @if ($accion == 'editar')
            <button class="btn btn-primary float-right" wire:click="confirmModificarSolicitud" title="Actualizar Datos de Solicitud">Guardar</button>
        @endif

        @if ($accion == 'editar_anexos')
            <button class="btn btn-primary float-right" wire:click="confirmModificarAnexoSolicitud" title="Modificar Anexos de Solicitud">Guardar</button>
        @endif

        @if ($accion == 'precomprometer')
            <button class="btn btn-primary float-right" wire:click="confirmPrecomprometerSolicitud" title="Pre-Comprometer Registro de Solicitud de Compra">Aprobar Presupuestario</button>
        @endif

        @if ($accion == 'anular')
            <button class="btn btn-primary float-right" wire:click="confirmAnularSolicitud" title="Anular Solicitud de Compra">Anular en Unidad</button>
        @endif

        @if ($accion == 'reversar')
            <button class="btn btn-primary float-right" wire:click="confirmReversarSolicitud" title="Anular Presupuesto Solicitud de Compra">Anular Presupuesto</button>
        @endif

        @if ($accion == 'copiar')
            <button class="btn btn-primary float-right" wire:click="confirmCopiarSolicitud" title="Crea Copia de Solicitud de Compras Anulada">Copiar</button>
        @endif

        @if ($accion == 'activar')
            <button class="btn btn-primary float-right" wire:click="confirmActivarSolicitud" title="Activar Registro de Solicitud en Unidad Solicitante">Reactivar</button>
        @endif
    @endif

    @if ($modulo == 'compra_recibir')
        @if ($accion == 'recepcionar')
            <button class="btn btn-primary float-right" wire:click="confirmRecepcionarSolicitud" title="Recepcionar la Solicitud">Recibir</button>
        @endif

        @if ($accion == 'devolver')
            <button class="btn btn-primary float-right" wire:click="confirmDevolverSolicitud" title="Devolver Solicitud">Devolver</button>
        @endif

        @if ($accion == 'asignar_comprador')
            <button class="btn btn-primary float-right" wire:click="confirmAsignarCompradorSolicitud" title="Asignar Comprador a la Solicitud">Asignar Comprador</button>
        @endif

        @if ($accion == 'reasignar')
            <button class="btn btn-primary float-right" wire:click="confirmReasignarSolicitud" title="Reasignar Unidad Contratante de la Solicitud">Reasignar Solicitud</button>
        @endif
    @endif

    @if ($modulo == 'contratacion_recibir')
        @if ($accion == 'contratacion_recepcionar')
            <button class="btn btn-primary float-right" wire:click="confirmContratacionRecepcionarSolicitud" title="Recepcionar la Solicitud">Recibir</button>
        @endif

        @if ($accion == 'contratacion_devolver')
            <button class="btn btn-primary float-right" wire:click="confirmContratacionDevolverSolicitud" title="Devolver Solicitud">Devolver</button>
        @endif

        @if ($accion == 'contratacion_comprador')
            <button class="btn btn-primary float-right" wire:click="confirmContratacionCompradorSolicitud" title="Asignar Comprador a la Solicitud">Asignar Comprador</button>
        @endif

        @if ($accion == 'contratacion_reasignar')
            <button class="btn btn-primary float-right" wire:click="confirmContratacionReasignarSolicitud" title="Reasignar Unidad Contratante de la Solicitud">Reasignar Solicitud</button>
        @endif
    @endif

    @if ($modulo == 'presupuesto')
        @if ($accion == 'presupuesto_aprobar')
            <button class="btn btn-primary float-right" wire:click="confirmPresupuestoAprobarSolicitud" title="Conformar Registro de Solicitud">Conformar</button>
        @endif

        @if ($accion == 'presupuesto_reversar')
            <button class="btn btn-primary float-right" wire:click="confirmPresupuestoReversarSolicitud" title="Anular Presupuesto Solicitud de Compra">Anular Presupuesto</button>
        @endif
    @endif


</x-slot>

</x-card>

@push('scripts')
    <script>

        Livewire.on('swal:alert', param => {
            Swal.fire({
                html:  param['mensaje'],
                icon:  param['tipo'],
                width: param['width']
            })
        })

        Livewire.on('swal:confirm', param => {
            Swal.fire({
                title: param['titulo'],
                html: param['mensaje'],
                icon: param['tipo'],
                width: param['width'],
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: '¡Sí!',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if(result.isConfirmed){
                    Livewire.emitTo('administrativo.meru-administrativo.compras.proceso.solicitud', param['funcion'], param['ano_pro'], param['grupo'], param['nro_req'])
                }
            })
        })

        function form(){
            return {
                tab: @entangle('showTab'),
            }
        }

    </script>
@endpush
