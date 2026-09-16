@props(['cat', 'm', 'isActive', 'variant' => 'scroll'])

@php
    $size = $variant === 'scroll'
        ? 'flex-shrink-0 w-40 sm:w-44 snap-start'
        : 'w-full';
@endphp

<button type="button"
  wire:click="selectCategory('{{ $cat->slug }}')"
  x-on:click="document.getElementById('peluang').scrollIntoView({behavior:'smooth'})"
  class="kivu-focus group {{ $size }} select-none bg-white rounded-2xl p-4 text-center border shadow-sm transition-all duration-300 hover:-translate-y-1 {{ $isActive ? 'border-kivu-primary ring-2 ring-kivu-primary shadow-md' : 'border-kivu-border hover:border-kivu-primary/40' }}">
  <div class="w-12 h-12 mx-auto {{ $m[1] }} text-white rounded-xl flex items-center justify-center text-xl mb-2.5 group-hover:scale-110 transition-transform">
    <i class="fa-solid {{ $m[0] }}"></i>
  </div>
  <h3 class="font-bold text-gray-900 text-sm leading-tight mb-0.5 line-clamp-2">{{ $cat->name }}</h3>
  <p class="text-[11px] text-gray-500">{{ $cat->open_projects_count }} peluang</p>
</button>