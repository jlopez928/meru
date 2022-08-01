<x-card>
    <x-slot:header>
        <h3 class="card-title text-bold">Registro de Proveedores</h3>
    </x-slot>
<x-slot:body>
        
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
        <button class="nav-link active" id="identificacion-tab" data-toggle="tab" data-target="#identificacion" type="button" role="tab" aria-controls="identificacion" aria-selected="true">Identificación</button>
        </li>
        <li class="nav-item" role="presentation">
        <button class="nav-link" id="otros-tab" data-toggle="tab" data-target="#otros" type="button" role="tab" aria-controls="otros" aria-selected="false">Otros Datos</button>
        </li>
        <li class="nav-item" role="presentation">
        <button class="nav-link" id="situacion-financiera-tab" data-toggle="tab" data-target="#situacion-financiera" type="button" role="tab" aria-controls="situacion-financiera" aria-selected="false">Situación Financiera</button>
        </li>
    </ul>
    
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="identificacion" role="tabpanel" aria-labelledby="identificacion-tab">
            @include('administrativo/meru_administrativo/proveedores/proceso/proveedor/partials/_identificacion')
        </div>
        <div class="tab-pane fade" id="otros" role="tabpanel" aria-labelledby="otros-tab">
            @include('administrativo/meru_administrativo/proveedores/proceso/proveedor/partials/_otros')
        </div>
        <div class="tab-pane fade" id="situacion-financiera" role="tabpanel" aria-labelledby="situacion-financiera-tab">...</div>
    </div>
      
</x-slot>
    
<x-slot:footer>       
    <x-input type="submit" value="Guardar" />
</x-slot>

</x-card>