<div class="min-h-screen bg-gray-50">
    <header class="border-b border-gray-200 bg-white">
        <div class="mx-auto flex h-16 max-w-5xl items-center justify-between px-4 sm:px-6">
            <a href="{{ url('/') }}" class="flex items-center">
                <img src="{{ asset('images/kivu-logo.png') }}" alt="KIVU" class="h-8 w-auto">
            </a>
            @auth
                <a href="{{ url('/dashboard') }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">Masuk</a>
            @endauth
        </div>
    </header>

    <main class="mx-auto max-w-5xl space-y-6 px-4 py-8 sm:px-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                <x-ui.avatar :name="$student->name" size="lg" />
                <div class="min-w-0">
                    <p class="flex flex-wrap items-center gap-2 text-2xl font-bold text-gray-900">
                        {{ $student->name }}
                        <x-ui.verified-badge :user="$student" />
                    </p>
                    <p class="mt-0.5 text-sm text-gray-500">Mahasiswa Talent KIVU &middot; Bergabung {{ $trust['member_since']?->translatedFormat('F Y') }}</p>
                    @if($student->bio)
                        <p class="mt-2 text-sm text-gray-600">{{ $student->bio }}</p>
                    @endif
                </div>
            </div>

            <div class="mt-5 grid grid-cols-3 gap-3 border-t border-gray-100 pt-5">
                <div class="rounded-xl bg-gray-50 p-3 text-center">
                    <p class="text-xl font-bold text-gray-900">{{ $trust['completed_projects'] }}</p>
                    <p class="text-xs text-gray-500">Proyek Selesai</p>
                </div>
                <div class="rounded-xl bg-gray-50 p-3 text-center">
                    <p class="flex items-center justify-center gap-1 text-xl font-bold text-gray-900">
                        <x-icon name="star" :size="16" :filled="true" /> {{ $trust['rating_avg'] ?? '-' }}
                    </p>
                    <p class="text-xs text-gray-500">{{ $trust['reviews_count'] }} Ulasan</p>
                </div>
                <div class="rounded-xl bg-gray-50 p-3 text-center">
                    <p class="text-xl font-bold {{ $trust['verified'] ? 'text-blue-600' : 'text-gray-400' }}">{{ $trust['verified'] ? 'Ya' : 'Belum' }}</p>
                    <p class="text-xs text-gray-500">Terverifikasi</p>
                </div>
            </div>
        </div>

        @if(!empty($student->skills))
            <div class="rounded-2xl border border-gray-200 bg-white p-6">
                <h3 class="text-lg font-bold text-gray-900">Keahlian</h3>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($student->skills as $skill)
                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">{{ $skill }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <h3 class="text-lg font-bold text-gray-900">Portofolio</h3>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                @forelse($portfolios as $item)
                    <div class="rounded-xl border border-gray-200 p-4">
                        @if($item->file_path && $item->isImage())
                            <img src="{{ Storage::disk('public')->url($item->file_path) }}" alt="{{ $item->title }}" class="mb-3 h-40 w-full rounded-lg object-cover">
                        @endif
                        <p class="text-sm font-semibold text-gray-800">{{ $item->title }}</p>
                        @if($item->description)<p class="mt-1 text-xs text-gray-500">{{ $item->description }}</p>@endif
                        <div class="mt-2 flex flex-wrap gap-3">
                            @if($item->url)
                                <a href="{{ $item->url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 hover:underline"><x-icon name="arrow-right" :size="12" /> Tautan</a>
                            @endif
                            @if($item->file_path && !$item->isImage())
                                <a href="{{ Storage::disk('public')->url($item->file_path) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 hover:underline"><x-icon name="file-text" :size="12" /> Lihat File</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Belum ada portofolio.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <h3 class="text-lg font-bold text-gray-900">Ulasan dari UMKM</h3>
            <div class="mt-4 space-y-3">
                @forelse($reviews as $review)
                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-gray-800">{{ $review->reviewer->name ?? 'UMKM' }}</p>
                            <div class="flex text-amber-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <x-icon name="star" :size="14" :filled="$i <= $review->rating" class="{{ $i <= $review->rating ? '' : 'text-gray-300' }}" />
                                @endfor
                            </div>
                        </div>
                        @if($review->comment)<p class="mt-1 text-sm text-gray-600">{{ $review->comment }}</p>@endif
                        <p class="mt-1 text-xs text-gray-400">{{ $review->created_at->translatedFormat('d F Y') }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Belum ada ulasan.</p>
                @endforelse
            </div>
        </div>
    </main>
</div>
