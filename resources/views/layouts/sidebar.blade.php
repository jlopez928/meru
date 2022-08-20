@section('sidebar')

    <div  class="brand-link logo-switch">
        {{--  
        <img src="{{ asset('img/lg_HB_blanco.png') }}" alt="logo sm" class="brand-image-xl logo-xs">
        <img src="{{ asset('img/Lg_HB_Logo_'.$app.'1.png') }}" alt="logo lg" class="brand-image-xs logo-xl">
        --}}
        @include('layouts/periodo-fiscal')
    </div>
    <div class="sidebar">
        <!-- Sidebar user -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            @if (Auth::check())
                <div class="image">
                    <img src="{{ asset('img/avatar.png') }}" class="img-circle elevation-2 text-white" alt="IMG">
                </div>
                <div class="info text-white">
                    {{ Auth::user()->name }}
                </div>
            @endif
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                @foreach ($menu as $key => $item)
                    @if ($item['padre'] != 0)
                        @break
                    @endif
                    @include('layouts/menu-item', ['item' => $item])
                @endforeach
            </ul>
        </nav>
    </div>
@endsection
