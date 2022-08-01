<table id="{{ $id }}" {{ $attributes->merge(['class' => 'table table-bordered table-striped table-hover']) }}>
    <thead>
        @if (!is_null($mainHeader))
                {{ $mainHeader }}
        @endif
        <tr>
            @foreach ($headers as $header)
                @if (!is_null($header['sort']))
                    <th class="{{ $header['classes'] }}" wire:click="sortBy('{{ $header['sort'] }}')" style="cursor: pointer;" width="{{ $header['width'] }}">
                        {{ $header['name'] }}

                        @if ($sortby == $header['sort'])
                            <i class="fas {{ $iconClass }} float-md-right mt-1" />
                        @else
                            <i class="fas fa-sort float-md-right mt-1" />
                        @endif
                    </th>
                @else
                    <th class="{{ $header['classes'] }}" width="{{ $header['width'] }}">
                        {{ $header['name'] }}
                    </th>
                @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
        {{ $slot }}
    </tbody>
</table>
