@section('sidebar')

    <div  class="brand-link logo-switch" style="padding-top:0px;">
        <div class="text-center">
            <img src="{{ asset('img/HB_Logo.png') }}" alt="logo sm" style="height:88px; width:88px; margin-bottom:10px; border-radius:50%;">
        </div>

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
