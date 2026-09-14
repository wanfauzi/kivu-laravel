@props(['project'])
<div class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-5 transition hover:border-blue-200 hover:shadow-sm">
    <div class="flex items-start justify-between gap-3">
        <h3 class="line-clamp-1 text-base font-semibold text-gray-900">{{ $project->title }}</h3>
        <x-ui.status-badge :value="$project->status" kind="project" />
    </div>
    <p class="line-clamp-2 min-h-[2.5rem] text-sm leading-relaxed text-gray-600">{{ $project->description }}</p>
    <div class="flex items-center justify-between gap-3 pt-3">
        <div class="min-w-0">
            <p class="text-lg font-bold text-blue-600">Rp {{ number_format($project->budget, 0, ',', '.') }}</p>
            <div class="mt-0.5 flex items-center gap-1.5">
                <x-ui.avatar :name="$project->owner->name ?? ''" size="xs" />
                <span class="truncate text-xs text-gray-500">{{ $project->owner->name ?? '-' }}</span>
            </div>
        </div>
        <a href="{{ route('student.project-detail', $project->id) }}" wire:navigate class="inline-flex shrink-0 items-center gap-1 rounded-lg bg-blue-600 px-3.5 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
            Detail
            <x-icon name="arrow-right" :size="16" />
        </a>
    </div>
</div>