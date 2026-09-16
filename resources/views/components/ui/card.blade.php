@props(['padding' => 'p-5', 'as' => 'div'])

<{{ $as }} {{ $attributes->merge(['class' => "kivu-card {$padding}"]) }}>
    {{ $slot }}
</{{ $as }}>
