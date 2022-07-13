<select id="{{ $name }}" name="{{ $name }}" {{ $attributes->merge(['class' => 'form-control']) }}>
    {{ $slot }}
</select>
