<x-guest-layout>
    <div class="mb-5 sm:mb-8">
        <h1 class="text-title-sm sm:text-title-md mb-2 font-semibold text-gray-800">Masuk</h1>
        <p class="text-sm text-gray-500">Masukkan email dan password Anda untuk masuk ke akun KIVU.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="space-y-5">
            <div>
                <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700">Email<span class="text-error-500">*</span></label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com"
                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden" required autofocus/>
                @error('email')
                    <p class="mt-1.5 text-xs text-error-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700">Password<span class="text-error-500">*</span></label>
                <div class="relative">
                    <input id="password" type="password" name="password" placeholder="Masukkan password"
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

            <div class="flex items-center justify-between">
                <label for="remember" class="flex cursor-pointer items-center select-none">
                    <input id="remember" type="checkbox" name="remember" class="peer sr-only"/>
                    <span class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] border-gray-300 transition peer-checked:border-brand-500 peer-checked:bg-brand-500 rtl:mr-0 rtl:ml-3">
                        <x-icon name="check" :size="14" stroke="3" class="text-white opacity-0 transition peer-checked:opacity-100" />
                    </span>
                    <span class="text-sm font-normal text-gray-700">Ingat saya</span>
                </label>
            </div>

            @if ($errors->any())
                <div class="rounded-lg border border-error-100 bg-error-50 p-3 text-xs text-error-600">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <button type="submit" class="shadow-theme-xs bg-brand-500 hover:bg-brand-600 flex w-full items-center justify-center rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                Masuk
            </button>
        </div>
    </form>

    <div class="mt-5">
        <p class="text-center text-sm font-normal text-gray-700 sm:text-start rtl:sm:text-end">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-brand-500 hover:text-brand-600">Daftar</a>
        </p>
    </div>
</x-guest-layout>