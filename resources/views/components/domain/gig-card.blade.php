@props(['pf', 'student'])

@php
  $gradients = [
    'from-red-500 to-orange-400',
    'from-blue-500 to-cyan-400',
    'from-green-500 to-emerald-400',
    'from-purple-500 to-indigo-500',
    'from-amber-400 to-orange-500',
    'from-teal-500 to-cyan-500',
  ];
  $grad = $gradients[$pf->id % count($gradients)];
  $hasImg = $pf->file_path && $pf->isImage();
@endphp

<a href="{{ route('talents.show', $student->id) }}" wire:navigate class="group bg-white rounded-xl border border-kivu-border overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 flex flex-col">
  <div class="relative h-32 overflow-hidden">
    @if($hasImg)
      <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($pf->file_path) }}" alt="{{ $pf->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
    @else
      <div class="w-full h-full bg-gradient-to-br {{ $grad }} flex items-center justify-center">
        <span class="text-white font-black text-2xl tracking-tight opacity-90">{{ strtoupper(substr($pf->title, 0, 2)) }}</span>
      </div>
    @endif
    <div class="absolute top-2.5 left-2.5 bg-kivu-yellow text-kivu-yellow-text text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">Jasa</div>
  </div>
  <div class="p-4 flex-1 flex flex-col">
    <h3 class="font-bold text-sm text-kivu-text leading-snug line-clamp-2 group-hover:text-kivu-primary transition-colors mb-1.5">{{ $pf->title }}</h3>
    <div class="flex items-center gap-1.5 mb-2">
        <span class="text-xs font-bold text-kivu-primary">Rp {{ number_format($pf->price, 0, ',', '.') }}</span>
        <span class="text-[10px] text-kivu-text-muted">• {{ $pf->delivery_days ?? 3 }} hari</span>
    </div>
    <div class="mt-auto pt-3 border-t border-kivu-border flex items-center gap-2">
        <span class="w-6 h-6 rounded-full bg-kivu-primary-soft text-kivu-primary flex items-center justify-center text-[10px] font-bold">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
        <span class="text-xs font-medium text-kivu-text-secondary truncate">{{ $student->name }}</span>
    </div>
  </div>
</a>