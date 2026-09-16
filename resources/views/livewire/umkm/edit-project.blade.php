<div class="mx-auto max-w-2xl space-y-6">
    <x-ui.page-header :breadcrumbs="[
        ['label' => 'Proyek Saya', 'url' => route('umkm.my-projects')],
        ['label' => 'Edit Proyek'],
    ]" :back="route('umkm.my-projects')" back-label="Kembali ke Proyek Saya" title="Edit Proyek"
        subtitle="Perbarui detail proyek selama masih berstatus terbuka.">
        <x-slot:actions>
            <x-ui.status-badge :value="$project->status" kind="project" :dot="true" />
        </x-slot:actions>
    </x-ui.page-header>

    @if ($project->status !== 'OPEN')
        <div class="flex gap-2.5 rounded-kivu border border-kivu-warning/25 bg-kivu-warning-soft p-4">
            <x-icon name="alert-circle" :size="18" class="mt-0.5 shrink-0 text-kivu-warning" />
            <p class="text-sm leading-relaxed text-kivu-warning">
                Proyek ini sudah tidak berstatus terbuka, jadi perubahan detail tidak akan memengaruhi pelamar yang sudah ada.
            </p>
        </div>
    @endif

    <div class="kivu-card p-6">
        <form wire:submit="update">
            <x-domain.project-form :categories="$categories" :skills="$skills" />

            <div class="mt-6 flex flex-col gap-2 border-t border-kivu-border pt-5 sm:flex-row sm:justify-between">
                <x-ui.button wire:click="confirmCancel" variant="secondary" class="text-kivu-danger">
                    <x-icon name="x" :size="16" /> Batalkan Proyek
                </x-ui.button>

                <div class="flex flex-col gap-2 sm:flex-row">
                    <x-ui.button href="{{ route('umkm.my-projects') }}" variant="secondary">Batal</x-ui.button>
                    <x-ui.button type="submit" loading-target="update" loading-label="Menyimpan...">Simpan Perubahan</x-ui.button>
                </div>
            </div>
        </form>
    </div>

    @if ($confirmingCancel)
        <x-ui.confirm-modal
            title="Batalkan Proyek"
            message="Proyek akan dibatalkan dan seluruh lamaran yang masih menunggu akan ditolak. Tindakan ini tidak dapat dibatalkan."
            confirm-label="Ya, Batalkan"
            confirm-method="cancelProject"
            cancel-method="closeCancel"
            tone="danger"
            loading-target="cancelProject" />
    @endif
</div>
