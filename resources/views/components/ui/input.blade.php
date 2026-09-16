@props(['label' => null, 'name' => null, 'id' => null, 'error' => null, 'hint' => null, 'required' => false])

@php
    $fieldId = $id ?? $name ?? 'field-'.\Illuminate\Support\Str::random(6);
    $errorId = $fieldId.'-error';
    $hintId = $fieldId.'-hint';
    $describedBy = trim(($error ? $errorId : '').' '.($hint ? $hintId : ''));
@endphp

<div class="space-y-1.5" {{ $attributes->only('class') }}>
    @if ($label)
        <label for="{{ $fieldId }}" class="block text-sm font-medium text-kivu-text">
            {{ $label }}@if ($required)<span class="text-kivu-danger">*</span>@endif
        </label>
    @endif

    <input
        id="{{ $fieldId }}"
        @if ($name) name="{{ $name }}" @endif
        @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
        @if ($error) aria-invalid="true" @endif
        @if ($required) required @endif
        {{ $attributes->except('class')->merge(['class' => 'kivu-input h-11 px-3.5 text-sm']) }} />

    @if ($hint && ! $error)
        <p id="{{ $hintId }}" class="text-xs text-kivu-text-muted">{{ $hint }}</p>
    @endif

    @if ($error)
        <p id="{{ $errorId }}" class="text-xs font-medium text-kivu-danger">{{ $error }}</p>
    @endif
</div>
