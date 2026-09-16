@auth
    @if (auth()->user()->role === 'admin')
        <x-layouts.admin>{{ $slot }}</x-layouts.admin>
    @else
        <x-layouts.app>{{ $slot }}</x-layouts.app>
    @endif
@endauth
