<div {{ $attributes->merge(['class'=>'card card-outline card-primary elevation-4']) }}>
    @if (!is_null($header))
        <div class="card-header">
            {{ $header }}
        </div>
    @endif
    @if (!is_null($body))
        <div class="card-body">
            {{ $body }}
        </div>
    @endif

    @if (!is_null($footer))
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>
