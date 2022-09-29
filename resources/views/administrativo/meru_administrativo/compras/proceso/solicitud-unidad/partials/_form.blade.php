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
        </ul>

        <div class="tab-content">
            <div x-show="tab === 'tab1'" :class="{'show active' : tab === 'tab1'}" class="tab-pane fade">
            {{--   @include('administrativo/meru_administrativo/compras/proceso/solicitud-unidad/partials/_encabezado') --}}
            </div>
            <div x-show="tab === 'tab2'" :class="{'show active' : tab === 'tab2'}" class="tab-pane fade">
            {{--   @include('administrativo/meru_administrativo/compras/proceso/solicitud-unidad/partials/_detalle') --}}
            </div>
            <div x-show="tab === 'tab3'" :class="{'show active' : tab === 'tab3'}" class="tab-pane fade">
            {{-- @include('administrativo/meru_administrativo/compras/proceso/solicitud-unidad/partials/_producto') --}}
            </div>
            <div x-show="tab === 'tab4'" :class="{'show active' : tab === 'tab4'}" class="tab-pane fade">
                {{-- @include('administrativo/meru_administrativo/compras/proceso/solicitud-unidad/partials/_bien-vehiculo') --}}
            </div>
        </div>
    </div>
</x-slot>

<x-slot:footer>
    <x-input class="float-right" type="submit" value="Guardar" />
</x-slot>

</x-card>

@push('scripts')
    <script>
        function form(){
            return {
                tab: 'tab1',
                ano_pro: '',
                grupo: '',
                cla_sol: '',
                clases: [],
                gru_ram: '',
                fk_cod_ger: '',
                cod_uni: '',
                unidades: [],
                tip_cod: '',
                cod_pryacc: '',
                cod_obj: '',
                gerencia: '',
                unidad: '',
                pri_sol: '',
                aplica_pre: '',
                cierre: '',

                init(){
                    this.ano_pro    = "{{ old('ano_pro', $solicitudUnidad?->ano_pro ?? session('ano_pro')) }}"
                    this.grupo      = "{{ old('grupo', $solicitudUnidad->grupo) }}"
                    this.cla_sol    = "{{ old('cla_sol', $solicitudUnidad->cla_sol) }}"
                    this.gru_ram    = "{{ old('gru_ram', $solicitudUnidad->gru_ram) }}"
                    this.fk_cod_ger = "{{ old('fk_cod_ger', $solicitudUnidad->fk_cod_ger) }}"
                    this.cod_uni    = "{{ old('cod_uni', $solicitudUnidad->cod_uni) }}"
                    this.tip_cod    = "{{ old('tip_cod', $solicitudUnidad->tip_cod ?? 0) }}"
                    this.cod_pryacc = "{{ old('cod_pryacc', $solicitudUnidad->cod_pryacc ?? 0) }}"
                    this.cod_obj    = "{{ old('cod_obj', $solicitudUnidad->cod_obj ?? 0) }}"
                    this.gerencia   = "{{ old('gerencia', $solicitudUnidad->gerencia ?? '') }}"
                    this.unidad     = "{{ old('unidad', $solicitudUnidad->unidad ?? '') }}"
                    this.pri_sol    = "{{ old('pri_sol', $solicitudUnidad->pri_sol ?? 'N') }}"
                    this.aplica_pre = "{{ old('aplica_pre', $solicitudUnidad->aplica_pre ?? '1') }}"
                    this.cierre     = "{{ old('cierre', $solicitudUnidad->cierre ?? '0') }}"
                },

                updateGrupo(){
                    this.cla_sol = ''
                    this.clases = []

                    if(this.grupo == 'BM'){
                        this.clases = [{'cod_cla': 'C', 'des_cla': 'COMPRA'},{'cod_cla': 'A', 'des_cla': 'ALMACEN'}]
                    }
                    if(this.grupo == 'SV' || this.grupo == 'SG'){
                        this.clases = [{'cod_cla': 'S', 'des_cla': 'SERVICIO'}]
                    }
                },

                getUnidades(){
                    this.cod_uni = ''
                    fetch('{{ env('APP_URL') }}/api/compras/unidades/'+ this.fk_cod_ger)
                    .then(response => response.json())
                    .then(data => {
                        this.unidades = data
                    })
                },

                getCentroCosto(){
                    this.tip_cod    = ''
                    this.cod_pryacc = ''
                    this.cod_obj    = ''
                    this.gerencia   = ''
                    this.unidad     = ''
                    fetch('{{ env('APP_URL') }}/api/compras/centrocosto/'+ this.fk_cod_ger)
                    .then(response => response.json())
                    .then(data => {
                        this.tip_cod     = parseInt(data[0])
                        this.cod_pryacc  = parseInt(data[1])
                        this.cod_obj     = parseInt(data[2])
                        this.gerencia    = parseInt(data[3])
                        this.unidad      = parseInt(data[4])
                    })
                },

                updateGerencia(){
                    this.getUnidades()
                    this.getCentroCosto()
                },

                updateGrupoRamo(){
                    console.log(this.gru_ram)
                    Livewire.emit('getProductos', this.gru_ram, this.grupo)
                }

            }
        }
    </script>
@endpush
