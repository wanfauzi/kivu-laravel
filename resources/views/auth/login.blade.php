<x-guest-layout title="Masuk — KIVU">
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight text-kivu-text">Masuk</h1>
        <p class="mt-1.5 text-sm text-kivu-text-secondary">Masukkan email dan password untuk melanjutkan ke akun KIVU Anda.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" autocomplete="off">
        @csrf

        <div class="space-y-5">
            <div>
                <label for="email" class="mb-1.5 block text-sm font-medium text-kivu-text">Email<span class="text-kivu-danger">*</span></label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus autocomplete="off"
                    class="kivu-input h-11 px-3.5 text-sm" />
                @error('email')
                    <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-sm font-medium text-kivu-text">Password<span class="text-kivu-danger">*</span></label>
                <div class="relative">
                    <input id="password" type="password" name="password" placeholder="Masukkan password" required autocomplete="new-password"
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

            <label for="remember" class="flex w-fit cursor-pointer select-none items-center gap-2.5">
                <input id="remember" type="checkbox" name="remember" class="peer sr-only" />
                <span class="flex h-5 w-5 items-center justify-center rounded-[6px] border border-kivu-border transition peer-checked:border-kivu-primary peer-checked:bg-kivu-primary">
                    <x-icon name="check" :size="13" stroke="3" class="text-white opacity-0 transition peer-checked:opacity-100" />
                </span>
                <span class="text-sm text-kivu-text-secondary">Ingat saya</span>
            </label>

            <x-ui.button type="submit" size="lg" class="w-full">Masuk</x-ui.button>
        </div>
    </form>

    <p class="mt-6 text-center text-sm text-kivu-text-secondary">
        Belum punya akun?
        <a href="{{ route('register') }}" class="kivu-focus rounded font-semibold text-kivu-primary transition hover:text-kivu-primary-hover">Daftar</a>
    </p>
</x-guest-layout>
