<div class="min-h-screen bg-kivu-bg">
    <header class="sticky top-0 z-40 border-b border-kivu-border bg-kivu-surface/90 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-5xl items-center justify-between px-4 sm:px-6">
            <a href="{{ url('/') }}" class="kivu-focus flex items-center rounded-kivu-sm">
                <img src="{{ asset('images/kivu-logo.png') }}" alt="KIVU" class="h-8 w-auto">
            </a>

            <div class="flex items-center gap-2">
                @auth
                    <x-ui.button href="{{ url('/dashboard') }}" variant="secondary">Dashboard</x-ui.button>
                @else
                    <x-ui.button href="{{ route('login') }}">Masuk</x-ui.button>
                @endauth
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-5xl space-y-6 px-4 py-8 sm:px-6">
        <x-ui.page-header :breadcrumbs="[
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Talent'],
            ['label' => $student->name],
        ]" :title="$student->name" />

        {{-- Profile --}}
        <div class="kivu-card p-6">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-start">
                <x-ui.avatar :name="$student->name" size="xl" />

                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-xl font-bold text-kivu-text">{{ $student->name }}</h2>
                        <x-ui.verified-badge :user="$student" />
                    </div>

                    <p class="mt-1 text-sm text-kivu-text-muted">
                        Talent KIVU &middot; Bergabung {{ $trust['member_since']?->translatedFormat('F Y') ?? '-' }}
                    </p>

                    @if ($student->bio)
                        <p class="mt-3 text-sm leading-relaxed text-kivu-text-secondary">{{ $student->bio }}</p>
                    @endif

                    @if (! empty($student->skills))
                        <div class="mt-4 flex flex-wrap gap-1.5">
                            @foreach ($student->skills as $skill)
                                <span class="rounded-full bg-kivu-primary-soft px-2.5 py-1 text-xs font-medium text-kivu-primary">{{ $skill }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 grid grid-cols-3 gap-3 border-t border-kivu-border pt-5">
                <div class="rounded-kivu-sm bg-kivu-surface-muted p-3 text-center">
                    <p class="text-xl font-bold text-kivu-text">{{ $trust['completed_projects'] }}</p>
                    <p class="text-xs text-kivu-text-muted">Proyek Selesai</p>
                </div>
                <div class="rounded-kivu-sm bg-kivu-surface-muted p-3 text-center">
                    <p class="text-xl font-bold text-kivu-text">{{ $trust['rating_avg'] ?? '-' }}</p>
                    <p class="text-xs text-kivu-text-muted">{{ $trust['reviews_count'] }} Ulasan</p>
                </div>
                <div class="rounded-kivu-sm bg-kivu-surface-muted p-3 text-center">
                    <p class="text-xl font-bold {{ $trust['verified'] ? 'text-kivu-primary' : 'text-kivu-text-muted' }}">
                        {{ $trust['verified'] ? 'Ya' : 'Belum' }}
                    </p>
                    <p class="text-xs text-kivu-text-muted">Terverifikasi</p>
                </div>
            </div>
        </div>

        {{-- Portfolio --}}
        <div class="kivu-card p-6">
            <h3 class="text-lg font-bold text-kivu-text">Portofolio</h3>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                @forelse ($portfolios as $item)
                    <div class="rounded-kivu border border-kivu-border p-4">
                        @if ($item->file_path && $item->isImage())
                            <img src="{{ Storage::disk('public')->url($item->file_path) }}" alt="{{ $item->title }}"
                                class="mb-3 h-40 w-full rounded-kivu-sm object-cover" loading="lazy">
                        @endif
                        <p class="text-sm font-semibold text-kivu-text">{{ $item->title }}</p>
                        @if ($item->description)
                            <p class="mt-1 text-xs text-kivu-text-muted">{{ $item->description }}</p>
                        @endif

                        <div class="mt-2.5 flex flex-wrap gap-3">
                            @if ($item->url)
                                <a href="{{ $item->url }}" target="_blank" rel="noopener"
                                    class="kivu-focus inline-flex items-center gap-1 rounded text-xs font-medium text-kivu-primary hover:underline">
                                    <x-icon name="external-link" :size="12" /> Tautan
                                </a>
                            @endif
                            @if ($item->file_path && ! $item->isImage())
                                <a href="{{ Storage::disk('public')->url($item->file_path) }}" target="_blank"
                                    class="kivu-focus inline-flex items-center gap-1 rounded text-xs font-medium text-kivu-primary hover:underline">
                                    <x-icon name="file-text" :size="12" /> Lihat File
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-kivu-text-muted">Belum ada portofolio.</p>
                @endforelse
            </div>
        </div>

        {{-- Reviews --}}
        <div class="kivu-card p-6">
            <h3 class="text-lg font-bold text-kivu-text">Ulasan dari UMKM</h3>

            <div class="mt-4 space-y-3">
                @forelse ($reviews as $review)
                    <div class="rounded-kivu border border-kivu-border bg-kivu-surface-muted/60 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-kivu-text">{{ $review->reviewer->name ?? 'UMKM' }}</p>
                            <x-ui.rating-stars :value="$review->rating" :size="14" />
                        </div>
                        @if ($review->comment)
                            <p class="mt-1.5 text-sm leading-relaxed text-kivu-text-secondary">{{ $review->comment }}</p>
                        @endif
                        <p class="mt-1.5 text-xs text-kivu-text-muted">{{ $review->created_at->translatedFormat('d F Y') }}</p>
                    </div>
                @empty
                    <p class="text-sm text-kivu-text-muted">Belum ada ulasan.</p>
                @endforelse
            </div>
        </div>
    </main>
</div>
