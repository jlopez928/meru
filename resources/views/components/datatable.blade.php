<x-card>

    <x-slot:header>
        <x-datatable-header :dataheader="$list" />
    </x-slot>

    <x-slot:body>
        {{ $slot }}
    </x-slot>

    <x-slot:footer>
        <x-datatable-footer :datafooter="$list" />
    </x-slot>

</x-card>
