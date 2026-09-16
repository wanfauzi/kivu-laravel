<div class="mx-auto max-w-2xl space-y-6">
    <x-ui.page-header :breadcrumbs="[
        ['label' => 'Proyek Saya', 'url' => route('umkm.my-projects')],
        ['label' => 'Buat Proyek'],
    ]" :back="route('umkm.my-projects')" back-label="Kembali ke Proyek Saya" title="Buat Proyek Baru"
        subtitle="Isi detail proyek agar mahasiswa memahami kebutuhan Anda." />

    <form wire:submit="store" class="kivu-card p-6">
        <x-domain.project-form :categories="$categories" :skills="$skills" />

        <div class="mt-6 flex flex-col gap-2 border-t border-kivu-border pt-5 sm:flex-row sm:justify-end">
            <x-ui.button href="{{ route('umkm.my-projects') }}" variant="secondary">Batal</x-ui.button>
            <x-ui.button type="submit" loading-target="store" loading-label="Memposting...">
                <x-icon name="plus-circle" :size="16" /> Posting Proyek
            </x-ui.button>
        </div>
    </form>
</div>
