@if ($item['submenu'] == [])
    <li class="nav-item">
        <a href="{{ url($item['url_destino']) }}" class="nav-link">
            <! -- /*empty($item['url_destino']) ? '#' : route($item['url_destino'])*/ -->
            <i class="nav-icon fas {{ $item['icono'] }}"></i>
            <p>
                {{ $item['nombre'] }}
            </p>
        </a>
    </li>
@else
    <li class="nav-item has-treeview ">
        <a href="{{ Route::has($item['url_destino']) ? route($item['url_destino']) : '#' }}" class="nav-link ">
            <i class="nav-icon fas {{ $item['icono'] }} {{ $item['padre'] == 0 ? 'text-md': 'text-xs'}}"></i>
            <p>
                {{ $item['nombre'] }}
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @foreach ($item['submenu'] as $submenu)
                @if ($submenu['submenu'] == [])
                    <li class="nav-item">
                        <a href="{{ Route::has($submenu['url_destino']) ? route($submenu['url_destino']) : '#' }}" class="nav-link text-sm" style="padding-left:20px !important">
                            <p>
                                {{ $submenu['nombre'] }}
                            </p>
                        </a>
                    </li>
                @else
                    @include('layouts/menu-item', [ 'item' => $submenu ])
                @endif
            @endforeach
        </ul>
    </li>
@endif