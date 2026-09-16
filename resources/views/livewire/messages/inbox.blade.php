<div class="space-y-6">
    <x-ui.page-header title="Pesan" subtitle="Komunikasi privat per proyek — hanya Anda dan lawan bicara yang bisa melihat." />

    <div class="kivu-card overflow-hidden">
        <div class="grid lg:grid-cols-[320px_1fr]">
            <aside class="border-b border-kivu-border lg:border-r lg:border-b-0 {{ $activeThread ? 'hidden lg:block' : 'block' }}"
                wire:poll.20s>
                @forelse ($threads as $thread)
                    @php
                        $last = $thread->messages->first();
                        $isActive = $activeThread && $activeThread->conversation_key === $thread->conversation_key;
                        $otherName = $thread->peer->name ?? ($thread->owner->name ?? 'UMKM');
                    @endphp
                    <button type="button" wire:click="selectThreadByKey('{{ $thread->conversation_key }}')"
                        class="kivu-focus flex w-full items-start gap-3 border-b border-kivu-border px-4 py-3.5 text-left transition last:border-b-0 {{ $isActive ? 'bg-kivu-primary-soft' : 'hover:bg-kivu-surface-muted' }}">
                        <x-ui.avatar :name="$otherName" size="sm" />
                        <span class="min-w-0 flex-1">
                            <span class="flex items-center justify-between gap-2">
                                <span class="truncate text-sm font-semibold text-kivu-text">{{ $otherName }}</span>
                                @if ($thread->unread_count > 0)
                                    <span class="flex h-5 min-w-5 shrink-0 items-center justify-center rounded-full bg-kivu-primary px-1.5 text-[10px] font-bold text-white">
                                        {{ $thread->unread_count }}
                                    </span>
                                @endif
                            </span>
                            <span class="block truncate text-xs text-kivu-text-muted">{{ $thread->title }}</span>
                            <span class="mt-0.5 block truncate text-xs text-kivu-text-muted">
                                {{ $last?->body ?? 'Belum ada pesan — mulai percakapan' }}
                            </span>
                        </span>
                    </button>
                @empty
                    <div class="px-4 py-12 text-center">
                        <x-icon name="message-square" :size="28" class="mx-auto text-kivu-text-muted/50" />
                        <p class="mt-2 text-sm text-kivu-text-muted">Belum ada percakapan.</p>
                        <p class="mt-1 text-xs text-kivu-text-muted">Mulai dari detail proyek atau daftar lamaran.</p>
                    </div>
                @endforelse
            </aside>

            <section class="{{ $activeThread ? 'flex' : 'hidden lg:flex' }} min-h-[520px] flex-col">
                @if ($activeThread)
                    <header class="flex items-center gap-3 border-b border-kivu-border px-4 py-3">
                        <button type="button" wire:click="closeThread"
                            class="kivu-focus -ml-1 rounded p-1 text-kivu-text-muted transition hover:text-kivu-text lg:hidden"
                            aria-label="Kembali ke daftar">
                            <x-icon name="arrow-left" :size="18" />
                        </button>
                        <x-ui.avatar :name="$activeThread->peer->name ?? $activeThread->owner->name ?? 'UMKM'" size="sm" />
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-kivu-text">{{ $activeThread->peer->name ?? $activeThread->owner->name }}</p>
                            <p class="truncate text-xs text-kivu-text-muted">{{ $activeThread->title }} · {{ $activeThread->peer->name !== $activeThread->owner->name ? $activeThread->owner->name : '' }}</p>
                        </div>
                        <x-ui.status-badge :value="$activeThread->status" kind="project" />
                    </header>

                    <div class="flex-1 space-y-3 overflow-y-auto bg-kivu-surface-muted/40 p-4" wire:poll.20s>
                        @forelse ($messages as $message)
                            @php $mine = $message->sender_id === auth()->id(); @endphp
                            <div class="flex {{ $mine ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[80%]">
                                    <div class="rounded-kivu px-3.5 py-2.5 text-sm leading-relaxed {{ $mine ? 'bg-kivu-primary text-white' : 'border border-kivu-border bg-kivu-surface text-kivu-text' }}">
                                        {{ $message->body }}
                                    </div>
                                    <p class="mt-1 text-[11px] text-kivu-text-muted {{ $mine ? 'text-right' : '' }}">
                                        {{ $message->sender->name ?? '' }} · {{ $message->created_at->format('d M H:i') }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="py-10 text-center text-sm text-kivu-text-muted">Mulai percakapan dengan mengirim pesan pertama. Hanya Anda dan lawan bicara yang bisa melihat.</p>
                        @endforelse
                    </div>

                    <form wire:submit="send" class="border-t border-kivu-border p-3">
                        <div class="flex items-end gap-2">
                            <textarea wire:model="body" rows="1" placeholder="Tulis pesan..."
                                class="kivu-input min-h-11 flex-1 resize-none px-3.5 py-2.5 text-sm"></textarea>
                            <x-ui.button type="submit" loading-target="send" loading-label="Kirim..." class="h-11 w-11 px-0!">
                                <x-icon name="send" :size="18" />
                            </x-ui.button>
                        </div>
                        @error('body')
                            <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                        @enderror
                    </form>
                @else
                    <div class="flex flex-1 flex-col items-center justify-center px-6 py-16 text-center">
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-kivu-primary-soft text-kivu-primary">
                            <x-icon name="message-square" :size="26" />
                        </div>
                        <h3 class="mt-4 text-base font-semibold text-kivu-text">Pilih percakapan</h3>
                        <p class="mt-1 max-w-sm text-sm text-kivu-text-muted">Pilih salah satu percakapan di samping atau mulai baru dari detail proyek.</p>
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>