<x-card>
    <x-slot:header>
        <h3 class="card-title text-bold">Registro de Proveedores</h3>
    </x-slot>
<x-slot:body>

    <div x-data="form()">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <button @click.prevent="tab = 'tab1'" :class="{'active' : tab === 'tab1'}" class="nav-link" data-toggle="tab">Identificación</button>
            </li>
            <li class="nav-item">
                <button @click.prevent="tab = 'tab2'" :class="{'active' : tab === 'tab2'}" class="nav-link" data-toggle="tab">Otros Datos</button>
            </li>
            <li class="nav-item">
                <button @click.prevent="tab = 'tab3'" :class="{'active' : tab === 'tab3'}" class="nav-link" data-toggle="tab">Situación Financiera</button>
            </li>
        </ul>

        <div class="tab-content">
            <div x-show="tab === 'tab1'" :class="{'show active' : tab === 'tab1'}" class="tab-pane fade">
                @include('administrativo/meru_administrativo/proveedores/proceso/proveedor/partials/_identificacion')
            </div>
            <div x-show="tab === 'tab2'" :class="{'show active' : tab === 'tab2'}" class="tab-pane fade">
                @include('administrativo/meru_administrativo/proveedores/proceso/proveedor/partials/_otros')
            </div>
            <div x-show="tab === 'tab3'" :class="{'show active' : tab === 'tab3'}" class="tab-pane fade">
                @include('administrativo/meru_administrativo/proveedores/proceso/proveedor/partials/_situacion')
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
                tip_emp: '',
                tip_reg: '',
                sta_emp: '',
                sta_con: '',

                init(){
                    this.tip_emp = "{{ old('tip_emp', $proveedor->tip_emp?->value) }}"
                    this.tip_reg = "{{ old('tip_reg', $proveedor->tip_reg?->value ?? 'A') }}"
                    this.sta_emp = "{{ old('sta_emp', $proveedor->sta_emp?->value ?? 'N') }}"
                    this.sta_con = "{{ $proveedor->sta_con?->value ?? '0' }}"
                }
            }
        }
    </script>
@endpush
