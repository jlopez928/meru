@if ($datafooter->hasPages())
    <div class="px-6 py-2 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <span >Mostrando desde el</span>
            <span class="mx-1">{{ ($datafooter->currentPage() - 1) * $datafooter->perPage() + 1 }}</span>
            <span>al</span>
            @if ($datafooter->currentPage() == $datafooter->lastPage())
                <span class="mx-1">{{  ($datafooter->total() - (($datafooter->currentPage() - 1) * $datafooter->perPage() + 1)) + (($datafooter->currentPage() - 1) * $datafooter->perPage() + 1) }}</span>
            @else
                <span class="mx-1">{{ ((($datafooter->currentPage() - 1) * $datafooter->perPage()) + $datafooter->perPage()) }}</span>
            @endif
            <span>de</span>
            <span class="mx-1">{{ $datafooter->total() }}</span>
            <span>resultados</span>
        </div>
        <div>
            {{ $datafooter->links() }}
        </div>
    </div>
@endif
