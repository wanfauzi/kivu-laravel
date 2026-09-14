<x-guest-layout>
    <div class="mb-5 sm:mb-8">
        <h1 class="text-title-sm sm:text-title-md mb-2 font-semibold text-gray-800">Daftar Akun KIVU</h1>
        <p class="text-sm text-gray-500">Mulai perjalanan micro-freelance Anda bersama Mahasiswa &amp; UMKM.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="space-y-5">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Saya adalah...<span class="text-error-500">*</span></label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex cursor-pointer items-center rounded-xl border border-gray-300 p-4 transition has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50 has-[:checked]:shadow-focus-ring">
                        <input type="radio" name="role" value="student" class="peer sr-only" required/>
                        <span class="mr-2 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-gray-300 transition peer-checked:border-brand-500 peer-checked:bg-brand-500 rtl:mr-0 rtl:ml-2">
                            <span class="h-2 w-2 scale-0 rounded-full bg-white transition peer-checked:scale-100"></span>
                        </span>
                        <x-icon name="graduation-cap" :size="18" class="mr-2 text-gray-500" />
                        <span class="text-sm font-medium text-gray-800">Mahasiswa</span>
                    </label>
                    <label class="flex cursor-pointer items-center rounded-xl border border-gray-300 p-4 transition has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50 has-[:checked]:shadow-focus-ring">
                        <input type="radio" name="role" value="umkm" class="peer sr-only" required/>
                        <span class="mr-2 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-gray-300 transition peer-checked:border-brand-500 peer-checked:bg-brand-500 rtl:mr-0 rtl:ml-2">
                            <span class="h-2 w-2 scale-0 rounded-full bg-white transition peer-checked:scale-100"></span>
                        </span>
                        <x-icon name="store" :size="18" class="mr-2 text-gray-500" />
                        <span class="text-sm font-medium text-gray-800">UMKM</span>
                    </label>
                </div>
                @error('role')
                    <p class="mt-1.5 text-xs text-error-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700">Nama Lengkap<span class="text-error-500">*</span></label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap Anda"
                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden" required/>
                @error('name')
                    <p class="mt-1.5 text-xs text-error-600">{{ $message }}</p>
                @enderror
            </div>

            <div id="umkm-fields" class="hidden">
                <label for="business_name" class="mb-1.5 block text-sm font-medium text-gray-700">Nama Usaha/Bisnis<span class="text-error-500">*</span></label>
                <div class="relative">
                    <span class="pointer-events-none absolute top-1/2 start-4 z-30 -translate-y-1/2 text-gray-500">
                        <x-icon name="store" :size="20" />
                    </span>
                    <input id="business_name" type="text" name="business_name" value="{{ old('business_name') }}" placeholder="Nama usaha atau bisnis Anda"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 ps-11 pe-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden"/>
                </div>
                @error('business_name')
                    <p class="mt-1.5 text-xs text-error-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700">Email<span class="text-error-500">*</span></label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com"
                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden" required/>
                @error('email')
                    <p class="mt-1.5 text-xs text-error-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700">Password<span class="text-error-500">*</span></label>
                <div class="relative">
                    <input id="password" type="password" name="password" placeholder="Buat password"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 ps-4 pe-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden" required/>
                    <button type="button" data-password-toggle="password" class="absolute top-1/2 right-4 z-30 -translate-y-1/2 cursor-pointer text-gray-500" aria-label="Tampilkan password">
                        <x-icon data-icon-eye name="eye" :size="20" />
                        <x-icon data-icon-eye-off name="eye-off" :size="20" class="hidden" />
                    </button>
                </div>
                @error('password')
                    <p class="mt-1.5 text-xs text-error-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-gray-700">Konfirmasi Password<span class="text-error-500">*</span></label>
                <div class="relative">
                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Ulangi password"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 ps-4 pe-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden" required/>
                    <button type="button" data-password-toggle="password_confirmation" class="absolute top-1/2 right-4 z-30 -translate-y-1/2 cursor-pointer text-gray-500" aria-label="Tampilkan password">
                        <x-icon data-icon-eye name="eye" :size="20" />
                        <x-icon data-icon-eye-off name="eye-off" :size="20" class="hidden" />
                    </button>
                </div>
            </div>

            <div id="info-mahasiswa" class="rounded-lg border border-success-100 bg-success-50 p-3 text-xs text-success-600">
                <p><strong>Info:</strong> Email <strong>.ac.id</strong> terverifikasi otomatis sebagai mahasiswa. Email lain perlu unggah KTM di halaman Profil untuk diverifikasi admin.</p>
            </div>

            <div id="info-umkm" class="hidden rounded-lg border border-brand-100 bg-brand-50 p-3 text-xs text-brand-600">
                <p><strong>Info:</strong> Akun UMKM aktif langsung saat pendaftaran dan dapat langsung membuat proyek.</p>
            </div>

        

            @if ($errors->any())
                <div class="rounded-lg border border-error-100 bg-error-50 p-3 text-xs text-error-600">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <button type="submit" class="shadow-theme-xs bg-brand-500 hover:bg-brand-600 flex w-full items-center justify-center rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                Daftar
            </button>
        </div>
    </form>

    <script>
        (function () {
            const umkmFields = document.getElementById('umkm-fields');
            const infoMahasiswa = document.getElementById('info-mahasiswa');
            const infoUmkm = document.getElementById('info-umkm');
            const radios = document.querySelectorAll('input[name="role"]');

            function applyRole(value) {
                const isUmkm = value === 'umkm';
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
        })();
    </script>

    <div class="mt-5">
        <p class="text-center text-sm font-normal text-gray-700 sm:text-start rtl:sm:text-end">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-brand-500 hover:text-brand-600">Masuk</a>
        </p>
    </div>
</x-guest-layout>