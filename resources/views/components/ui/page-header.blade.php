@props(['title' => '', 'subtitle' => null, 'breadcrumbs' => [], 'back' => null, 'backLabel' => 'Kembali'])

<div class="space-y-4">
    @if (! empty($breadcrumbs))
        <nav aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center gap-1.5 text-xs text-kivu-text-muted">
                @foreach ($breadcrumbs as $crumb)
                    <li class="flex items-center gap-1.5">
                        @if (! empty($crumb['url']))
                            <a href="{{ $crumb['url'] }}" wire:navigate class="kivu-focus rounded transition hover:text-kivu-primary">{{ $crumb['label'] }}</a>
                        @else
                            <span class="text-kivu-text-secondary">{{ $crumb['label'] }}</span>
                        @endif
                        @unless ($loop->last)
                            <x-icon name="chevron-right" :size="12" class="text-kivu-text-muted/60" />
                        @endunless
                    </li>
                @endforeach
            </ol>
        </nav>
    @endif

    @if ($back)
        <a href="{{ $back }}" wire:navigate class="kivu-focus inline-flex items-center gap-1.5 rounded text-sm font-medium text-kivu-text-muted transition hover:text-kivu-primary">
            <x-icon name="arrow-left" :size="16" /> {{ $backLabel }}
        </a>
    @endif

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <h1 class="text-xl font-bold tracking-tight text-kivu-text sm:text-2xl">{{ $title }}</h1>
            @if ($subtitle)
                <p class="mt-1 text-sm text-kivu-text-secondary">{{ $subtitle }}</p>
            @endif
        </div>

        @if (isset($actions) && trim((string) $actions) !== '')
            <div class="flex flex-wrap items-center gap-2">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
