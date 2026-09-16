<x-guest-layout title="Daftar Akun — KIVU">
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight text-kivu-text">Daftar Akun KIVU</h1>
        <p class="mt-1.5 text-sm text-kivu-text-secondary">Mulai perjalanan micro-freelance Anda bersama Mahasiswa &amp; UMKM.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="space-y-5">
            <div>
                <span class="mb-1.5 block text-sm font-medium text-kivu-text">Saya adalah<span class="text-kivu-danger">*</span></span>
                <div class="grid grid-cols-2 gap-3">
                    <label class="kivu-focus flex cursor-pointer items-center gap-2.5 rounded-kivu-sm border border-kivu-border p-3.5 transition has-[:checked]:border-kivu-primary has-[:checked]:bg-kivu-primary-soft">
                        <input type="radio" name="role" value="student" class="peer sr-only" @checked(old('role', 'student') === 'student') required />
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-kivu-border transition peer-checked:border-kivu-primary peer-checked:bg-kivu-primary">
                            <span class="h-2 w-2 scale-0 rounded-full bg-white transition peer-checked:scale-100"></span>
                        </span>
                        <x-icon name="graduation-cap" :size="18" class="text-kivu-text-muted" />
                        <span class="text-sm font-medium text-kivu-text">Mahasiswa</span>
                    </label>

                    <label class="kivu-focus flex cursor-pointer items-center gap-2.5 rounded-kivu-sm border border-kivu-border p-3.5 transition has-[:checked]:border-kivu-primary has-[:checked]:bg-kivu-primary-soft">
                        <input type="radio" name="role" value="umkm" class="peer sr-only" @checked(old('role') === 'umkm') required />
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-kivu-border transition peer-checked:border-kivu-primary peer-checked:bg-kivu-primary">
                            <span class="h-2 w-2 scale-0 rounded-full bg-white transition peer-checked:scale-100"></span>
                        </span>
                        <x-icon name="store" :size="18" class="text-kivu-text-muted" />
                        <span class="text-sm font-medium text-kivu-text">UMKM</span>
                    </label>
                </div>
                @error('role')
                    <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="mb-1.5 block text-sm font-medium text-kivu-text">Nama Lengkap<span class="text-kivu-danger">*</span></label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap Anda" required
                    class="kivu-input h-11 px-3.5 text-sm" />
                @error('name')
                    <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                @enderror
            </div>

            <div id="umkm-fields" class="hidden">
                <label for="business_name" class="mb-1.5 block text-sm font-medium text-kivu-text">Nama Usaha/Bisnis<span class="text-kivu-danger">*</span></label>
                <div class="relative">
                    <span class="pointer-events-none absolute top-1/2 start-3.5 z-10 -translate-y-1/2 text-kivu-text-muted">
                        <x-icon name="store" :size="18" />
                    </span>
                    <input id="business_name" type="text" name="business_name" value="{{ old('business_name') }}" placeholder="Nama usaha atau bisnis Anda"
                        class="kivu-input h-11 ps-10 pe-3.5 text-sm" />
                </div>
                @error('business_name')
                    <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="mb-1.5 block text-sm font-medium text-kivu-text">Email<span class="text-kivu-danger">*</span></label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required
                    class="kivu-input h-11 px-3.5 text-sm" />
                @error('email')
                    <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-sm font-medium text-kivu-text">Password<span class="text-kivu-danger">*</span></label>
                <div class="relative">
                    <input id="password" type="password" name="password" placeholder="Minimal 8 karakter" required
                        class="kivu-input h-11 ps-3.5 pe-11 text-sm" />
                    <button type="button" data-password-toggle="password"
                        class="kivu-focus absolute top-1/2 right-3 z-10 -translate-y-1/2 rounded p-1 text-kivu-text-muted transition hover:text-kivu-text"
                        aria-label="Tampilkan password">
                        <x-icon data-icon-eye name="eye" :size="18" />
                        <x-icon data-icon-eye-off name="eye-off" :size="18" class="hidden" />
                    </button>
                </div>
                @error('password')
                    <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-kivu-text">Konfirmasi Password<span class="text-kivu-danger">*</span></label>
                <div class="relative">
                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Ulangi password" required
                        class="kivu-input h-11 ps-3.5 pe-11 text-sm" />
                    <button type="button" data-password-toggle="password_confirmation"
                        class="kivu-focus absolute top-1/2 right-3 z-10 -translate-y-1/2 rounded p-1 text-kivu-text-muted transition hover:text-kivu-text"
                        aria-label="Tampilkan password">
                        <x-icon data-icon-eye name="eye" :size="18" />
                        <x-icon data-icon-eye-off name="eye-off" :size="18" class="hidden" />
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                @enderror
            </div>

            <div id="info-mahasiswa" class="flex gap-2.5 rounded-kivu-sm border border-kivu-info/25 bg-kivu-info-soft p-3 text-xs leading-relaxed text-kivu-info">
                <x-icon name="info" :size="16" class="mt-0.5 shrink-0" />
                <p>Email <strong>.ac.id</strong> aktif &amp; terverifikasi otomatis. Email lain (mis. @gmail.com) perlu unggah KTM dan diverifikasi admin.</p>
            </div>

            <div id="info-umkm" class="hidden gap-2.5 rounded-kivu-sm border border-kivu-success/25 bg-kivu-success-soft p-3 text-xs leading-relaxed text-kivu-success">
                <x-icon name="circle-check" :size="16" class="mt-0.5 shrink-0" />
                <p>Akun UMKM <strong>aktif langsung</strong> setelah mendaftar. Anda bisa langsung membuat proyek.</p>
            </div>

            <x-ui.button type="submit" size="lg" class="w-full">Daftar</x-ui.button>
        </div>
    </form>

    <p class="mt-6 text-center text-sm text-kivu-text-secondary">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="kivu-focus rounded font-semibold text-kivu-primary transition hover:text-kivu-primary-hover">Masuk</a>
    </p>

    <script>
        (function () {
            var umkmFields = document.getElementById('umkm-fields');
            var infoMahasiswa = document.getElementById('info-mahasiswa');
            var infoUmkm = document.getElementById('info-umkm');
            var radios = document.querySelectorAll('input[name="role"]');

            function applyRole(value) {
                var isUmkm = value === 'umkm';
                if (umkmFields) umkmFields.classList.toggle('hidden', !isUmkm);
                if (infoMahasiswa) infoMahasiswa.classList.toggle('hidden', isUmkm);
                if (infoUmkm) infoUmkm.classList.toggle('hidden', !isUmkm);
            }

            radios.forEach(function (radio) {
                radio.addEventListener('change', function () {
                    if (radio.checked) applyRole(radio.value);
                });
                if (radio.checked) applyRole(radio.value);
            });

            applyRole(document.querySelector('input[name="role"]:checked')?.value || 'student');
        })();
    </script>
</x-guest-layout>
