<x-card>
    <x-slot:header>
        <h3 class="card-title text-bold">Solicitudes (Unidad)</h3>
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
            <div x-show="grupo === 'SV'">
                <li class="nav-item">
                    <button @click.prevent="tab = 'tab4'" :class="{'active' : tab === 'tab4'}" class="nav-link" data-toggle="tab">Bienes/Vehiculos</button>
                </li>
            </div>
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
                @include('administrativo/meru_administrativo/compras/proceso/solicitud-unidad/partials/_encabezado')
            </div>
            <div x-show="tab === 'tab2'" :class="{'show active' : tab === 'tab2'}" class="tab-pane fade">
                @include('administrativo/meru_administrativo/compras/proceso/solicitud-unidad/partials/_detalle')
            </div>
            <div x-show="tab === 'tab3'" :class="{'show active' : tab === 'tab3'}" class="tab-pane fade">
                @include('administrativo/meru_administrativo/compras/proceso/solicitud-unidad/partials/_producto')
            </div>
            <div x-show="tab === 'tab4'" :class="{'show active' : tab === 'tab4'}" class="tab-pane fade">
                @include('administrativo/meru_administrativo/compras/proceso/solicitud-unidad/partials/_bien-vehiculo')
            </div>
            <div x-show="tab === 'tab5'" :class="{'show active' : tab === 'tab5'}" class="tab-pane fade">
                @include('administrativo/meru_administrativo/compras/proceso/solicitud-unidad/partials/_contratante')
            </div>
            <div x-show="tab === 'tab6'" :class="{'show active' : tab === 'tab6'}" class="tab-pane fade">
                @include('administrativo/meru_administrativo/compras/proceso/solicitud-unidad/partials/_anulacion')
            </div>
            <div x-show="tab === 'tab7'" :class="{'show active' : tab === 'tab7'}" class="tab-pane fade">
                @include('administrativo/meru_administrativo/compras/proceso/solicitud-unidad/partials/_doc-asociados')
            </div>
        </div>
    </div>
</x-slot>

<x-slot:footer>
    @if ($accion == 'nuevo')
        {{--  <x-input class="float-right" type="submit" value="Guardar" />  --}}
        <button class="btn btn-primary float-right" onclick="confirmation(event, '¿Está seguro de CREAR la Solicitud?')">Guardar</button>
    @endif

    @if ($accion == 'editar')
        <button class="btn btn-primary float-right" onclick="confirmation(event, '¿Está seguro de MODIFICAR la Solicitud?')">Guardar</button>
    @endif

    @if ($accion == 'editar_anexos')
        <button class="btn btn-primary float-right" onclick="confirmation(event, '¿Está seguro de Modificar Anexos de la Solicitud?')">Guardar</button>
    @endif

    @if ($accion == 'anular')
        <button class="btn btn-primary float-right" onclick="confirmation(event, '¿Está seguro de ANULAR la Solicitud?')">Anular en Unidad</button>
    @endif

    @if ($accion == 'reversar')
        <button class="btn btn-primary float-right" onclick="confirmation(event, '¿Está seguro de ANULAR PRESUPUESTARIAMENTE la Solicitud?')">Anular Presupuesto</button>
    @endif

    @if ($accion == 'copiar')
        <button class="btn btn-primary float-right" onclick="confirmation(event, 'Está seguro de REACTIVAR EN UNIDAD la Solicitud?. Se Creara una nueva Solicitud copia de la actual')" title="Reactivar Registro de Solicitud en Unidad Solicitante">Copiar</button>
    @endif

    @if ($accion == 'precomprometer')
        <button class="btn btn-primary float-right" onclick="confirmation(event, 'Está seguro de PRE-COMPROMETER la Solicitud?')" title="Pre-Comprometer Registro de Solicitud de Compra">Aprobar Presupuestario</button>
    @endif
</x-slot>

</x-card>

@push('scripts')
    <script>

        function form(){
            return {
                tab: 'tab1',
                ano_pro: '',
                grupo: '',
                nro_req: '',
                cla_sol: '',
                clases: [],
                gru_ram: '',
                fk_cod_ger: '',
                cod_uni: '',
                unidades: [],
                tip_cod: '0',
                cod_pryacc: '0',
                cod_obj: '0',
                gerencia: '0',
                unidad: '0',
                pri_sol: '',
                aplica_pre: '',
                cierre: '',
                contratante: '',
                fk_cod_com: '',
                licita: '',
                fk_cod_cau: '',
                cau_dev: '',
                cau_reasig: '',
                centro_costo_unidades: [],
                ordenes: '',
                ofertas: '',
                cotizaciones: '',
                sta_sol: '',

                init(){
                    this.ano_pro        = "{{ old('ano_pro', $solicitudUnidad?->ano_pro ?? session('ano_pro')) }}"
                    this.grupo          = "{{ old('grupo', $solicitudUnidad->grupo) }}"
                    this.nro_req        = "{{ old('nro_req', $solicitudUnidad->nro_req ?? '') }}"
                    this.cla_sol        = "{{ old('cla_sol', $solicitudUnidad->cla_sol) }}"
                    this.gru_ram        = "{{ old('gru_ram', $solicitudUnidad->gru_ram) }}"
                    this.fk_cod_ger     = "{{ old('fk_cod_ger', $solicitudUnidad->fk_cod_ger ?? '') }}"
                    this.cod_uni        = "{{ old('cod_uni', $solicitudUnidad->cod_uni) }}"
                    this.pri_sol        = "{{ old('pri_sol', $solicitudUnidad->pri_sol ?? 'N') }}"
                    this.aplica_pre     = "{{ old('aplica_pre', $solicitudUnidad->aplica_pre ?? $opcion) }}"
                    this.cierre         = "{{ old('cierre', $solicitudUnidad->cierre ?? '0') }}"
                    this.contratante    = "{{ old('contratante', $solicitudUnidad->contratante ?? 'L') }}"
                    this.fk_cod_com     = "{{ old('fk_cod_com', $solicitudUnidad->fk_cod_com ?? '') }}"
                    this.licita         = "{{ old('licita', $solicitudUnidad->licita ?? '') }}"
                    this.fk_cod_cau     = "{{ old('fk_cod_cau', $solicitudUnidad->fk_cod_cau ?? '') }}"
                    this.cau_dev        = "{{ old('cau_dev', $solicitudUnidad->cau_dev ?? '') }}"
                    this.cau_reasig     = "{{ old('cau_reasig', $solicitudUnidad->cau_reasig ?? '') }}"
                    this.sta_sol        = "{{ old('sta_sol', $solicitudUnidad->sta_sol ?? '0') }}"
                    this.centroCostoUnidades()
                    this.getClases()
                    this.getUnidades()
                    this.getCentroCostoGerencia()
                    this.getProductos()
                    if (this.sta_sol == '10' || this.sta_sol == '11'){
                        this.getOrdenes()
                    }
                    if (this.sta_sol == '9' || this.sta_sol == '10' || this.sta_sol == '11'){
                        this.getOfertas()
                    }
                    if (this.sta_sol == '8' || this.sta_sol == '9' || this.sta_sol == '10' || this.sta_sol == '11'){
                        this.getCotizaciones()
                    }
                },

                getProductos(){
                    Livewire.emitTo('administrativo.meru-administrativo.compras.proceso.solicitud-unidad-detalle','getListaProductos', this.gru_ram, this.grupo, this.ano_pro, this.aplica_pre)
                },

                getCotizaciones(){
                    fetch('{{ env('APP_URL') }}/api/compras/cotizaciones/'+ this.ano_pro +'/'+ this.grupo +'/'+ this.nro_req)
                    .then(response => response.json())
                    .then(data => {
                        for (var i = 0; i < data.length; i++) {
                            this.cotizaciones += data[i].cotizacion + '\n';
                        };
                    })
                },

                getOfertas(){
                    fetch('{{ env('APP_URL') }}/api/compras/ofertas/'+ this.ano_pro +'/'+ this.grupo +'/'+ this.nro_req)
                    .then(response => response.json())
                    .then(data => {
                        for (var i = 0; i < data.length; i++) {
                            this.ofertas += data[i].ofertas + '\n';
                        };
                    })
                },

                getOrdenes(){
                    fetch('{{ env('APP_URL') }}/api/compras/ordenes/'+ this.ano_pro +'/'+ this.grupo +'/'+ this.nro_req)
                    .then(response => response.json())
                    .then(data => {
                        for (var i = 0; i < data.length; i++) {
                            this.ordenes += data[i].orden + '\n';
                        };
                    })
                },

                getCentroCostoGerencia(){
                    if(this.fk_cod_ger !== ''){
                        fetch('{{ env('APP_URL') }}/api/compras/centrocosto/'+ this.fk_cod_ger)
                        .then(response => response.json())
                        .then(data => {
                            if (data.length != 0){
                                this.tip_cod     = parseInt(data[0])
                                this.cod_pryacc  = parseInt(data[1])
                                this.cod_obj     = parseInt(data[2])
                                this.gerencia    = parseInt(data[3])
                                this.unidad      = parseInt(data[4])
                                Livewire.emitTo('administrativo.meru-administrativo.compras.proceso.solicitud-unidad-detalle','getCentroCosto', this.tip_cod, this.cod_pryacc, this.cod_obj, this.gerencia, this.unidad)
                            }
                        })
                    }
                },

                getClases(){
                    this.clases = []
                    if(this.grupo == 'BM'){
                        this.clases = [{'cod_cla': 'C', 'des_cla': 'COMPRA'},{'cod_cla': 'A', 'des_cla': 'ALMACEN'}]
                    }
                    if(this.grupo == 'SV' || this.grupo == 'SG'){
                        this.clases = [{'cod_cla': 'S', 'des_cla': 'SERVICIO'}]
                    }
                },

                updateGrupo(){
                    this.getClases()

                    Livewire.emitTo('administrativo.meru-administrativo.compras.proceso.solicitud-unidad-detalle','resetProductos')
                },

                getUnidades(){
                    fetch('{{ env('APP_URL') }}/api/compras/unidades/'+ this.fk_cod_ger)
                    .then(response => response.json())
                    .then(data => {
                        this.unidades = data
                    })
                },

                dameCreditoAdicional(cod_ger){
                    var tk = 0;
                    while (tk < this.centro_costo_unidades.length) {
                        if (cod_ger == this.centro_costo_unidades[tk].cod_ger) {
                            return this.centro_costo_unidades[tk].cre_adi;
                        }
                        tk++;
                    }
                    Swal.fire({
                        text: "Error Validando El centro de Costo de la Gerencia.Comuniquese con su administrador de Sistema",
                        icon: 'warning',
                    })

                    this.fk_cod_ger = ''

                    return 2
                },

                centroCostoUnidades(){
                    fetch('{{ env('APP_URL') }}/api/compras/centrocostounidades/'+ this.ano_pro)
                        .then(response => response.json())
                        .then(data => {
                            this.centro_costo_unidades = data
                        })
                },

                asignarCentroCosto(){
                    var gerencia_usuario = {{ !is_null(auth()->user()->usuario->gerencia->cod_ger) }}
                    var bandera = false
                    var credito_adicional = 'NO'

                    if (this.fk_cod_ger != '')
                    {
                        if (gerencia_usuario != 100) {
                            credito_adicional = this.dameCreditoAdicional(this.fk_cod_ger)

                            bandera = credito_adicional == 'NO' ? true : false
                        } else {
                            bandera = true;
                        }

                        if(bandera){
                            this.getUnidades()
                            fetch('{{ env('APP_URL') }}/api/compras/centrocosto/'+ this.fk_cod_ger)
                            .then(response => response.json())
                            .then(data => {
                                if (data.length != 0){
                                    this.tip_cod     = parseInt(data[0])
                                    this.cod_pryacc  = parseInt(data[1])
                                    this.cod_obj     = parseInt(data[2])
                                    this.gerencia    = parseInt(data[3])
                                    this.unidad      = parseInt(data[4])
                                    Livewire.emitTo('administrativo.meru-administrativo.compras.proceso.solicitud-unidad-detalle','getCentroCosto', this.tip_cod, this.cod_pryacc, this.cod_obj, this.gerencia, this.unidad)
                                }else{
                                    Swal.fire({
                                        html: "La Gerencia Selecionada no tiene un Centro de Costo Valido.<br>Por favor Verifique.",
                                        icon: 'warning',
                                    })
                                }
                            })
                        }else{
                            if (credito_adicional == 'SI') {
                                Swal.fire({
                                    html: "Usted No tiene Permiso para realizar Solicitudes por la Gerencia Seleccionada .<br>Por favor Verifique.",
                                    icon: 'warning',
                                })

                                this.fk_cod_ger = ''
                            }
                        }
                    }else{
                        this.tip_cod    = ''
                        this.cod_pryacc = ''
                        this.cod_obj    = ''
                        this.gerencia   = ''
                        this.unidad     = ''
                        Livewire.emitTo('administrativo.meru-administrativo.compras.proceso.solicitud-unidad-detalle','resetProductos')
                    }
                },

                updateGerencia(){
                    this.asignarCentroCosto()
                },

                updateGrupoRamo(){
                    Livewire.emitTo('administrativo.meru-administrativo.compras.proceso.solicitud-unidad-detalle','getListaProductos', this.gru_ram, this.grupo, this.ano_pro, this.aplica_pre)
                },

                evaluarTipoCompra(){
                    var bs_ut
                    var monto = 0

                    fetch('{{ env('APP_URL') }}/api/compras/ultimaunidadtributaria')
                    .then(response => response.json())
                    .then(data => {

                        bs_ut = data[0].bs_ut
                        if (data.length != 0)
                        {
                            fetch('{{ env('APP_URL') }}/api/compras/rangosunidadtributaria/'+ this.licita)
                            .then(response => response.json())
                            .then(data => {
                                if (data.length != 0) {
                                    var row2 = data[0]
                                    var mon_ut = monto / bs_ut

                                    if (row2.ut_bie_ser_has > 0) {
                                        if (!((mon_ut > row2.ut_bie_ser_des) && (mon_ut <= row2.ut_bie_ser_has))) {
                                            Swal.fire({
                                                html:   'El MONTO de la solicitud no corresponde al rango en UNIDADES <br>' +
                                                        'TRIBUTARIAS (UT) definidas para el TIPO DE COMPRA seleccionado, <br>' +
                                                        'porque debe encontrarse en un valor superior a ' + row2.ut_bie_ser_des + ' UT ' +
                                                        'hasta ' + row2.ut_bie_ser_has + ' UT:<br><br>' +
                                                        '* El monto de la Solicitud en Bs. ' + monto + '.<br>' +
                                                        '* El monto de la Solicitud en UT. ' + mon_ut + '.<br>' +
                                                        'Por favor Verifique.',
                                                icon: 'warning',
                                                width: 600
                                            })
                                        }
                                    } else {
                                        if (!(mon_ut > row2.ut_bie_ser_des)) {
                                            Swal.fire({
                                                html:   'El MONTO de la solicitud no corresponde al rango en UNIDADES <br>' +
                                                        'TRIBUTARIAS (UT) definidas para el TIPO DE COMPRA seleccionado, <br>' +
                                                        'porque debe encontrarse en un valor superior a ' + row2.ut_bie_ser_des + ' UT:<br><br>' +
                                                        '* El monto de la Solicitud en Bs. ' + monto + '.<br>' +
                                                        '* El monto de la Solicitud en UT. ' + mon_ut + '.<br>' +
                                                        'Por favor Verifique.',
                                                icon: 'warning',
                                                width: 600,
                                            })
                                        }
                                    }
                                }else{
                                        Swal.fire({
                                                    html: "El Tipo de Compra no existe en tabla.<br>Por favor Verifique.",
                                                    icon: 'warning',
                                        })
                                }
                            })
                        }else{
                            Swal.fire({
                                        html: 'No Existen valores de Conversion para la UNIDAD TRIBUTARIA.<br>Por favor Verifique.',
                                        icon: 'warning',
                            })
                        }
                    })
                }
            }
        }

        function confirmation(e, mensaje) {
            e.preventDefault()

            Swal.fire({
                icon: 'warning',
                text: mensaje,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Sí!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.value) {
                    document.formulario.submit()
                }
            })
        }
    </script>
@endpush
