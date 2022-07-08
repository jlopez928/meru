@if ($item['submenu'] == [])
    <li class="nav-item">
        <a href="{{ url($item['url_destino']) }}" class="nav-link">
            <i class="nav-icon fas {{ $item['icono'] }}"></i>
            <p>
                {{ $item['nombre'] }}
            </p>
        </a>
    </li>
@else
    <li class="nav-item has-treeview ">
        <a href="{{ url($item['url_destino']) }}" class="nav-link ">
            <i class="nav-icon fas {{ $item['icono'] }}"></i>
            <p>
                {{ $item['nombre'] }}
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @foreach ($item['submenu'] as $submenu)
                @if ($submenu['submenu'] == [])
                    <li class="nav-item">
                        <a href="{{ url($submenu['url_destino']) }}" class="nav-link ">
                            <i class="far {{ $submenu['icono'] }} nav-icon"></i>
                            {{ $submenu['nombre'] }}
                        </a>
                    </li>
                @else
                    @include('layouts/menu-item', [ 'item' => $submenu ])
                @endif
            @endforeach
        </ul>
    </li>
@endif