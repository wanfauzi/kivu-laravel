@props(['steps' => [], 'size' => 'md'])

@php
    $dot = $size === 'sm' ? 'h-5 w-5' : 'h-6 w-6';
    $icon = $size === 'sm' ? 12 : 13;
@endphp

<ol {{ $attributes->merge(['class' => 'space-y-0']) }}>
    @foreach ($steps as $step)
        @php
            $state = $step['state'] ?? 'upcoming';
            $tone = match ($state) {
                'done' => 'bg-kivu-success text-white',
                'current' => 'bg-kivu-primary text-white',
                'error' => 'bg-kivu-danger text-white',
                'warning' => 'bg-kivu-warning text-white',
                default => 'bg-kivu-surface-muted text-kivu-text-muted',
            };
            $labelTone = match ($state) {
                'upcoming' => 'text-kivu-text-muted',
                default => 'text-kivu-text',
            };
        @endphp
        <li class="flex gap-3">
            <div class="flex flex-col items-center">
                <span class="{{ $dot }} {{ $tone }} flex shrink-0 items-center justify-center rounded-full">
                    @if ($state === 'done')
                        <x-icon name="check" :size="$icon" stroke="3" />
                    @elseif ($state === 'error')
                        <x-icon name="x" :size="$icon" stroke="3" />
                    @elseif ($state === 'warning')
                        <x-icon name="alert-circle" :size="$icon" stroke="2.5" />
                    @elseif ($state === 'current')
                        <x-icon name="circle-dot" :size="$icon + 2" />
                    @else
                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                    @endif
                </span>
                @unless ($loop->last)
                    <span class="w-px flex-1 bg-kivu-border"></span>
                @endunless
            </div>

            <div class="{{ $loop->last ? 'pb-1' : 'pb-5' }} min-w-0">
                <p class="text-sm font-semibold {{ $labelTone }}">{{ $step['label'] ?? '' }}</p>
                @if (! empty($step['description']))
                    <p class="mt-0.5 text-xs leading-relaxed text-kivu-text-muted">{{ $step['description'] }}</p>
                @endif
                @if (! empty($step['meta']))
                    <p class="mt-1 text-xs text-kivu-text-muted">{{ $step['meta'] }}</p>
                @endif
            </div>
        </li>
    @endforeach
</ol>
