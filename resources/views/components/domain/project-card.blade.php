@props(['project'])

<a href="{{ route('student.project-detail', $project->id) }}" wire:navigate
    {{ $attributes->merge(['class' => 'kivu-card kivu-card-hover relative flex flex-col p-5']) }}>
    <div class="flex flex-wrap items-center gap-2">
        <x-ui.status-badge :value="$project->status" kind="project" :dot="true" />
    </div>

    <h3 class="mt-2.5 line-clamp-2 text-base font-semibold text-kivu-text hover:text-kivu-primary">{{ $project->title }}</h3>

    <p class="mt-1.5 line-clamp-2 flex-1 text-sm leading-relaxed text-kivu-text-secondary">{{ $project->description }}</p>

    @if ($project->skills->isNotEmpty())
        <div class="mt-3 flex flex-wrap gap-1.5">
            @foreach ($project->skills->take(3) as $skill)
                <span class="rounded-full bg-kivu-surface-muted px-2 py-0.5 text-[11px] font-medium text-kivu-text-secondary">{{ $skill->name }}</span>
            @endforeach
        </div>
    @endif

    @if ($project->category)
        <p class="mt-3 inline-flex items-center gap-1.5 text-xs text-kivu-text-muted">
            <x-icon :name="$project->category->icon ?: 'tag'" :size="13" /> {{ $project->category->name }}
        </p>
    @endif

    <div class="mt-4 flex items-end justify-between gap-3 border-t border-kivu-border pt-4">
        <div class="flex min-w-0 items-center gap-2">
            <x-ui.avatar :name="$project->owner->name ?? ''" size="xs" />
            <span class="line-clamp-1 text-xs text-kivu-text-secondary">{{ $project->owner->name ?? '-' }}</span>
        </div>
        <div class="text-right">
            @if ($project->due_date)
                <p class="inline-flex items-center gap-1 text-xs text-kivu-text-muted">
                    <x-icon name="calendar" :size="13" /> {{ $project->due_date->translatedFormat('d M') }}
                </p>
            @endif
            <p class="mt-1 font-bold text-kivu-primary">
                        @if ($project->hasBudgetRange())
                            <span class="text-sm">Rp {{ number_format($project->min_budget, 0, ',', '.') }} &ndash;</span>
                            <span class="text-lg">{{ number_format($project->max_budget, 0, ',', '.') }}</span>
                        @else
                            <span class="text-lg">Rp {{ number_format($project->budget, 0, ',', '.') }}</span>
                        @endif
                    </p>
        </div>
    </div>
</a>