<div>
<nav class="fixed w-full z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-kivu-border" id="kivu-navbar">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-20">
      <a href="/" class="flex-shrink-0 flex items-center gap-2">
        <img src="{{ asset('images/kivu-logo.png') }}" alt="KIVU" class="h-8 w-auto">
      </a>
      <div class="hidden md:flex space-x-8 items-center">
        <a href="#tentang" class="text-gray-600 hover:text-kivu-primary font-medium text-sm transition-colors">Tentang</a>
        <a href="#kategori" class="text-gray-600 hover:text-kivu-primary font-medium text-sm transition-colors">Kategori</a>
        <a href="#cara-kerja" class="text-gray-600 hover:text-kivu-primary font-medium text-sm transition-colors">Cara Kerja</a>
        <a href="#testimoni" class="text-gray-600 hover:text-kivu-primary font-medium text-sm transition-colors">Testimoni</a>
      </div>
      <div class="hidden md:flex items-center space-x-4">
        @auth
          <a href="{{ url('/dashboard') }}" wire:navigate class="text-kivu-primary border border-kivu-primary hover:bg-kivu-primary-soft font-semibold rounded-full px-6 py-2.5 text-sm transition-colors">Dashboard</a>
          <a href="{{ route('register') }}" class="bg-kivu-yellow hover:bg-kivu-yellow-hover text-kivu-yellow-text font-bold rounded-full px-6 py-2.5 text-sm shadow-lg shadow-kivu-yellow/20">Daftar</a>
        @else
          <a href="{{ route('login') }}" class="text-kivu-primary border border-kivu-primary hover:bg-kivu-primary-soft font-semibold rounded-full px-6 py-2.5 text-sm transition-colors">Masuk</a>
          <a href="{{ route('register') }}" class="bg-kivu-yellow hover:bg-kivu-yellow-hover text-kivu-yellow-text font-bold rounded-full px-6 py-2.5 text-sm shadow-lg shadow-kivu-yellow/20 transition-colors">Daftar</a>
        @endauth
      </div>
      <div class="md:hidden flex items-center">
        <button id="kivu-hamburger" type="button" class="text-gray-600 hover:text-gray-900 p-2" aria-label="Menu">
          <i class="fa-solid fa-bars text-2xl"></i>
        </button>
      </div>
    </div>
  </div>
  <div id="kivu-mobile-menu" class="hidden md:hidden border-t border-kivu-border bg-white">
    <div class="px-4 py-4 space-y-1">
      <a href="#tentang" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-kivu-surface-muted">Tentang</a>
      <a href="#kategori" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-kivu-surface-muted">Kategori</a>
      <a href="#cara-kerja" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-kivu-surface-muted">Cara Kerja</a>
      <a href="#testimoni" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-kivu-surface-muted">Testimoni</a>
      <div class="pt-3 flex flex-col gap-2">
        @auth
          <a href="{{ url('/dashboard') }}" class="w-full text-center bg-kivu-primary text-white font-semibold rounded-full px-6 py-3 text-sm">Dashboard</a>
        @else
          <a href="{{ route('login') }}" class="w-full text-center border border-kivu-primary text-kivu-primary font-semibold rounded-full px-6 py-3 text-sm">Masuk</a>
          <a href="{{ route('register') }}" class="w-full text-center bg-kivu-yellow text-kivu-yellow-text font-bold rounded-full px-6 py-3 text-sm">Daftar</a>
        @endauth
      </div>
    </div>
  </div>
</nav>

<section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-white">
  <div class="hero-blob hidden lg:block"></div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
      <div class="max-w-2xl">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-kivu-primary-soft border border-kivu-primary/20 text-kivu-primary text-xs font-semibold mb-6">
          <span class="w-2 h-2 rounded-full bg-kivu-primary"></span> Platform Talenta Mahasiswa untuk UMKM
        </div>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
          Talenta Muda <br /> untuk UMKM <br /> yang <span class="text-kivu-primary relative inline-block">Lebih Maju
            <svg class="absolute w-full h-3 -bottom-1 left-0 text-kivu-yellow opacity-70" viewBox="0 0 100 10" preserveAspectRatio="none"><path d="M0 5 Q 50 10 100 0" stroke="currentColor" stroke-width="4" fill="none"/></svg>
          </span>
        </h1>
        <p class="text-lg text-gray-600 mb-8 max-w-lg leading-relaxed">
          KIVU menghubungkan mahasiswa berbakat dengan UMKM di seluruh Indonesia untuk mengerjakan berbagai tugas secara fleksibel, terjangkau, dan berkualitas. Bangun portofolio, bantu UMKM tumbuh.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 mb-10">
          <a href="{{ route('register') }}" class="bg-kivu-yellow hover:bg-kivu-yellow-hover text-kivu-yellow-text font-bold rounded-full px-8 py-3.5 text-base shadow-lg shadow-kivu-yellow/20 flex items-center justify-center gap-2 group">
            Mulai Sekarang <i class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-1"></i>
          </a>
        </div>
        <div class="flex flex-wrap items-center gap-6 text-sm text-gray-500 font-medium">
          <div class="flex items-center gap-2"><i class="fa-solid fa-shield-halved text-kivu-primary"></i> Talenta Terverifikasi</div>
          <div class="flex items-center gap-2"><i class="fa-solid fa-lock text-kivu-primary"></i> Proses Aman &amp; Transparan</div>
          <div class="flex items-center gap-2"><i class="fa-solid fa-headset text-kivu-primary"></i> Dukungan Penuh</div>
        </div>
      </div>
      <div class="relative mt-10 lg:mt-0">
        <div class="absolute -inset-4 bg-gradient-to-br from-kivu-primary/25 via-transparent to-kivu-yellow/20 blur-2xl rounded-[3rem] -z-10"></div>
        <div class="relative w-full aspect-[4/3] max-w-[600px] mx-auto animate-float">
          <div class="absolute inset-0 rounded-[2.5rem] overflow-hidden shadow-2xl ring-1 ring-white/50">
            <img src="{{ asset('images/orang1.png') }}" alt="Mahasiswa Berbakat" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-kivu-primary/20 via-transparent to-transparent"></div>
            <div class="absolute top-4 right-4 glass-panel rounded-full px-3 py-1.5 flex items-center gap-1.5 shadow-md">
              <i class="fa-solid fa-circle-check text-kivu-primary"></i>
              <span class="text-xs font-bold text-gray-900">Talenta Terverifikasi</span>
            </div>
            <div class="absolute bottom-4 left-4 glass-panel rounded-xl px-3 py-2 flex items-center gap-2 shadow-md">
              <span class="w-8 h-8 bg-kivu-primary text-white rounded-lg flex items-center justify-center"><i class="fa-solid fa-bolt"></i></span>
              <div>
                <p class="text-xs font-extrabold text-gray-900 leading-tight">Siap bantu bisnis kamu</p>
                <p class="text-[10px] text-gray-500">cepat &amp; berkualitas</p>
              </div>
            </div>
          </div>

          <div class="absolute -top-6 -left-6 lg:-left-12 glass-panel rounded-2xl p-4 shadow-xl flex items-center gap-4 animate-float [animation-delay:1.5s]">
            <div class="w-12 h-12 bg-kivu-primary-soft text-kivu-primary rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-users"></i></div>
            <div>
              <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Ribuan</p>
              <p class="text-sm font-bold text-gray-900 leading-tight">talenta siap membantu<br>bisnis kamu</p>
            </div>
          </div>

          <div class="absolute top-1/4 -right-4 lg:-right-8 glass-panel rounded-2xl p-4 shadow-xl flex items-center gap-3 animate-float [animation-delay:2.5s]">
            <div class="w-10 h-10 bg-kivu-yellow-soft text-kivu-yellow-800 rounded-full flex items-center justify-center text-lg"><i class="fa-solid fa-lightbulb"></i></div>
            <div>
              <p class="text-sm font-bold text-gray-900">Solusi tugas kreatif,</p>
              <p class="text-xs text-gray-500">cepat dan berkualitas</p>
            </div>
          </div>

          <div class="absolute -bottom-6 left-1/2 -translate-x-1/2 glass-panel rounded-full py-2 px-4 shadow-xl flex items-center gap-3 w-max">
            <div>
              <p class="text-sm font-extrabold text-gray-900">{{ $studentCount }}+</p>
              <p class="text-[10px] text-gray-500 uppercase tracking-wide font-semibold">Talenta aktif</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-10 border-y border-kivu-border bg-kivu-bg-soft">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <p class="text-center text-sm font-semibold text-gray-500 mb-8 tracking-wide">Dipercaya oleh UMKM dan Talenta di Seluruh Indonesia</p>
    <div class="flex flex-wrap justify-center items-center gap-x-12 gap-y-8 opacity-60 grayscale hover:grayscale-0 transition-all duration-300">
      <div class="flex items-center gap-2 font-bold text-xl text-gray-800"><i class="fa-solid fa-cube text-kivu-primary"></i> BakulKita</div>
      <div class="flex items-center gap-2 font-bold text-xl text-gray-800"><i class="fa-solid fa-store text-kivu-yellow"></i> TokoLokal</div>
      <div class="flex items-center gap-2 font-bold text-xl text-gray-800"><i class="fa-solid fa-utensils text-green-500"></i> DapurNusantara</div>
      <div class="flex items-center gap-2 font-bold text-xl text-gray-800"><i class="fa-solid fa-palette text-purple-500"></i> KreasiID</div>
      <div class="flex items-center gap-2 font-bold text-xl text-gray-800"><i class="fa-solid fa-cart-shopping text-red-500"></i> LokaMart</div>
      <div class="flex items-center gap-2 font-bold text-xl text-gray-800"><i class="fa-solid fa-handshake text-blue-500"></i> SahabatUMKM</div>
    </div>
  </div>
</section>

<section id="tentang" class="py-24 bg-white relative overflow-hidden">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-2 gap-16 items-center">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-kivu-primary-soft text-kivu-primary text-xs font-bold uppercase tracking-wider mb-6">
          <i class="fa-solid fa-angles-right"></i> MENGENAL KIVU
        </div>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-6">Apa itu KIVU?</h2>
        <p class="text-lg text-gray-600 mb-8 leading-relaxed">
          KIVU adalah platform yang mempertemukan mahasiswa dengan UMKM untuk menyelesaikan berbagai tugas, mulai dari desain, konten, riset, hingga pengembangan digital. Bersama KIVU, lebih banyak peluang untuk semua.
        </p>
        <div class="flex flex-wrap items-center gap-4">
          <a href="{{ route('register') }}" class="bg-kivu-yellow hover:bg-kivu-yellow-hover text-kivu-yellow-text font-bold rounded-full px-8 py-3 text-sm shadow-lg shadow-kivu-yellow/20 flex items-center gap-2">
            Pelajari Lebih Lanjut <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>
      <div class="relative">
        <div class="absolute -inset-3 bg-gradient-to-br from-kivu-primary/20 to-kivu-yellow/15 blur-2xl rounded-3xl -z-10"></div>
        <div class="rounded-3xl overflow-hidden shadow-2xl relative border border-white/50 animate-float">
          <img src="{{ asset('images/orang1.png') }}" alt="Mahasiswa Bekerja" class="w-full h-auto object-cover">
          <div class="absolute inset-0 bg-gradient-to-t from-kivu-primary/15 via-transparent to-transparent"></div>
          <div class="absolute top-6 left-6 glass-panel rounded-xl p-3 flex items-center gap-3">
            <div class="w-8 h-8 bg-kivu-yellow-soft text-kivu-yellow-800 rounded-lg flex items-center justify-center"><i class="fa-solid fa-comment-dots"></i></div>
            <div>
              <p class="text-xs font-bold text-gray-900">Ide jadi nyata</p>
              <p class="text-[10px] text-gray-500">Bersama talenta muda</p>
            </div>
          </div>
        </div>
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-kivu-primary-soft rounded-full blur-3xl -z-10"></div>
      </div>
    </div>
  </div>
</section>

<section id="kategori" class="py-20 bg-kivu-bg-soft" x-data="{ showAll: false }">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
      <div class="max-w-2xl">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-kivu-primary-soft text-kivu-primary text-xs font-bold uppercase tracking-wider mb-4">
          <i class="fa-solid fa-angles-right"></i> JELAJAHI PELUANG
        </div>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">Berbagai Kategori untuk Berbagai Kebutuhan</h2>
        <p class="text-gray-600">Temukan talenta mahasiswa di bidang yang kamu butuhkan. Dari tugas kreatif hingga teknis, semua ada di KIVU.</p>
      </div>
      <button type="button" @click="showAll = !showAll" class="kivu-focus flex-shrink-0 text-kivu-primary font-semibold hover:text-kivu-primary-hover flex items-center gap-2 bg-white px-5 py-2.5 rounded-full border border-kivu-border shadow-sm hover:shadow">
        <span x-show="!showAll">Lihat Semua Kategori</span>
        <span x-show="showAll" x-cloak>Lihat Lebih Sedikit</span>
        <i class="fa-solid fa-arrow-right"></i>
      </button>
    </div>

    @php
      $catMap = [
        'desain-grafis' => ['fa-pen-nib','bg-gradient-to-br from-red-500 to-orange-400'],
        'penulisan-konten' => ['fa-file-pen','bg-gradient-to-br from-blue-500 to-cyan-400'],
        'social-media-management' => ['fa-share-nodes','bg-gradient-to-br from-green-500 to-emerald-400'],
        'web-it' => ['fa-laptop-code','bg-gradient-to-br from-purple-500 to-indigo-500'],
        'input-data-admin' => ['fa-database','bg-gradient-to-br from-indigo-500 to-blue-500'],
        'digital-marketing' => ['fa-chart-pie','bg-gradient-to-br from-amber-400 to-orange-500'],
        'video-animasi' => ['fa-video','bg-gradient-to-br from-yellow-400 to-amber-500'],
        'fotografi-produk' => ['fa-camera','bg-gradient-to-br from-teal-500 to-cyan-500'],
        'penerjemahan' => ['fa-language','bg-gradient-to-br from-cyan-500 to-sky-500'],
        'konsultasi-bisnis' => ['fa-briefcase','bg-gradient-to-br from-fuchsia-500 to-purple-500'],
        'lainnya' => ['fa-tag','bg-kivu-primary-soft'],
      ];
      $catFallback = ['fa-tag','bg-kivu-primary-soft'];
    @endphp

    <div class="relative" x-show="!showAll">
      <div class="flex items-center gap-2 sm:gap-3">
        <button type="button" @click="$refs.catScroll.scrollBy({ left: -320, behavior: 'smooth' })"
          class="kivu-focus hidden md:flex shrink-0 h-12 w-12 items-center justify-center rounded-full border border-kivu-border bg-white text-kivu-primary shadow-sm transition hover:border-kivu-primary/40 hover:bg-kivu-primary-soft"
          aria-label="Geser kategori ke kiri">
          <i class="fa-solid fa-chevron-left"></i>
        </button>

        <div x-ref="catScroll" class="no-scrollbar flex flex-1 scroll-smooth gap-3 sm:gap-4 overflow-x-auto px-1 py-2 snap-x snap-proximity">
          @foreach($categories as $cat)
            <x-domain.category-card :cat="$cat" :m="$catMap[$cat->slug] ?? $catFallback" :isActive="$category === $cat->slug" variant="scroll" />
          @endforeach
        </div>

        <button type="button" @click="$refs.catScroll.scrollBy({ left: 320, behavior: 'smooth' })"
          class="kivu-focus hidden md:flex shrink-0 h-12 w-12 items-center justify-center rounded-full border border-kivu-border bg-white text-kivu-primary shadow-sm transition hover:border-kivu-primary/40 hover:bg-kivu-primary-soft"
          aria-label="Geser kategori ke kanan">
          <i class="fa-solid fa-chevron-right"></i>
        </button>
      </div>
      <p class="mt-4 text-xs text-center text-gray-400">
        Geser untuk melihat semua kategori <span class="hidden md:inline">atau gunakan tombol panah &rarr;</span><span class="md:hidden">&rarr;</span>
      </p>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 sm:gap-4" x-show="showAll" x-cloak>
      @foreach($categories as $cat)
        <x-domain.category-card :cat="$cat" :m="$catMap[$cat->slug] ?? $catFallback" :isActive="$category === $cat->slug" variant="grid" />
      @endforeach
    </div>
  </div>
</section>

<section id="cara-kerja" class="py-24 bg-kivu-bg-soft">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-3xl mx-auto mb-16">
      <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-kivu-primary-soft text-kivu-primary text-xs font-bold uppercase tracking-wider mb-4"><i class="fa-solid fa-angles-right"></i> CARA KERJA</div>
      <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">Dari Ide ke Dampak, Lebih Mudah Bersama KIVU</h2>
      <p class="text-gray-600">Hanya 4 langkah sederhana untuk memulai.</p>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 relative">
      <div class="hidden lg:block absolute top-12 left-[10%] right-[10%] h-0.5 bg-gray-200 z-0 border-t-2 border-dashed border-gray-300"></div>
      @foreach([
        ['Daftar','Buat akun sebagai mahasiswa atau pemilik UMKM.','fa-user','bg-blue-100','text-kivu-primary','border-kivu-primary'],
        ['Temukan Tugas','Pilih tugas yang tersedia atau posting kebutuhan kamu.','fa-file-lines','bg-indigo-100','text-indigo-600','border-indigo-500'],
        ['Kerjakan','Selesaikan tugas dengan bimbingan yang jelas.','fa-paper-plane','bg-sky-100','text-sky-600','border-sky-500'],
        ['Raih Dampak','Dapatkan pembayaran dan bangun portofolio.','fa-trophy','bg-purple-100','text-purple-600','border-purple-500']
      ] as $i => $s)
        <div class="bg-white rounded-2xl p-8 border border-kivu-border shadow-sm relative z-10 text-center hover:-translate-y-2 transition-transform">
          <div class="w-16 h-16 mx-auto {{ $s[3] }} {{ $s[4] }} rounded-full flex items-center justify-center text-2xl mb-6 shadow-inner relative">
            <span class="absolute -top-2 -left-2 w-8 h-8 bg-white border-2 {{ $s[5] }} rounded-full flex items-center justify-center text-sm font-bold {{ $s[4] }}">{{ $i+1 }}</span>
            <i class="fa-solid {{ $s[2] }}"></i>
          </div>
          <h3 class="font-bold text-xl text-gray-900 mb-3">{{ $s[0] }}</h3>
          <p class="text-sm text-gray-600">{{ $s[1] }}</p>
        </div>
      @endforeach
    </div>
    <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-8 max-w-4xl mx-auto">
      <div class="text-center"><div class="w-12 h-12 mx-auto bg-kivu-primary-soft text-kivu-primary rounded-full flex items-center justify-center text-xl mb-3"><i class="fa-solid fa-user-graduate"></i></div><p class="text-3xl font-extrabold text-gray-900">{{ $studentCount }}+</p><p class="text-sm text-gray-500 font-medium">Talenta Aktif</p></div>
      <div class="text-center"><div class="w-12 h-12 mx-auto bg-kivu-yellow-soft text-kivu-yellow-800 rounded-full flex items-center justify-center text-xl mb-3"><i class="fa-solid fa-briefcase"></i></div><p class="text-3xl font-extrabold text-gray-900">{{ $openCount }}+</p><p class="text-sm text-gray-500 font-medium">Tugas Tersedia</p></div>
      <div class="text-center"><div class="w-12 h-12 mx-auto bg-green-50 text-green-500 rounded-full flex items-center justify-center text-xl mb-3"><i class="fa-solid fa-building"></i></div><p class="text-3xl font-extrabold text-gray-900">{{ $completedCount }}+</p><p class="text-sm text-gray-500 font-medium">UMKM Bergabung</p></div>
      <div class="text-center"><div class="w-12 h-12 mx-auto bg-purple-50 text-purple-500 rounded-full flex items-center justify-center text-xl mb-3"><i class="fa-solid fa-star"></i></div><p class="text-3xl font-extrabold text-gray-900">98%</p><p class="text-sm text-gray-500 font-medium">Kepuasan Pengguna</p></div>
    </div>
  </div>
</section>

<section id="testimoni" class="py-24 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
      <div class="max-w-2xl">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-kivu-primary-soft text-kivu-primary text-xs font-bold uppercase tracking-wider mb-4"><i class="fa-solid fa-angles-right"></i> APA KATA MEREKA?</div>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">Dipercaya dan Direkomendasikan</h2>
        <p class="text-gray-600">Dengarkan pengalaman langsung dari talenta dan pemilik UMKM.</p>
      </div>
    </div>
    <div class="grid md:grid-cols-3 gap-6">
      @forelse($talent->take(3) as $student)
        <div class="bg-kivu-bg-soft rounded-3xl p-8 border border-kivu-border flex flex-col h-full">
          <div class="text-kivu-yellow flex gap-1 mb-6 text-sm"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
          <p class="text-gray-700 leading-relaxed mb-8 flex-1 italic">"KIVU membantu saya mendapatkan penghasilan dan pengalaman nyata. Sangat direkomendasikan!"</p>
          <div class="flex items-center gap-4 mt-auto">
            <span class="w-12 h-12 rounded-full bg-kivu-primary-soft text-kivu-primary flex items-center justify-center font-bold">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
            <div><h4 class="font-bold text-gray-900 text-sm">{{ $student->name }}</h4><p class="text-xs text-gray-500">Mahasiswa</p></div>
          </div>
        </div>
      @empty
        <div class="bg-kivu-bg-soft rounded-3xl p-8 border border-kivu-border"><div class="text-kivu-yellow flex gap-1 mb-6 text-sm"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div><p class="text-gray-700 italic">"KIVU membantu saya mendapatkan tambahan penghasilan sekaligus pengalaman profesional."</p><div class="flex items-center gap-4 mt-6"><h4 class="font-bold text-sm text-gray-900">Rani Putri</h4><p class="text-xs text-gray-500">Mahasiswa Desain</p></div></div>
        <div class="bg-kivu-bg-soft rounded-3xl p-8 border border-kivu-border"><div class="text-kivu-yellow flex gap-1 mb-6 text-sm"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div><p class="text-gray-700 italic">"Talenta di KIVU sangat kreatif dan responsif. Hasil kerjanya melebihi ekspektasi."</p><div class="flex items-center gap-4 mt-6"><h4 class="font-bold text-sm text-gray-900">Budi Santoso</h4><p class="text-xs text-gray-500">Pemilik UMKM</p></div></div>
        <div class="bg-kivu-bg-soft rounded-3xl p-8 border border-kivu-border"><div class="text-kivu-yellow flex gap-1 mb-6 text-sm"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div><p class="text-gray-700 italic">"Platform yang sangat membantu UMKM seperti kami. Harga terjangkau dengan kualitas memuaskan."</p><div class="flex items-center gap-4 mt-6"><h4 class="font-bold text-sm text-gray-900">Sari Melati</h4><p class="text-xs text-gray-500">Pemilik UMKM</p></div></div>
      @endforelse
    </div>
  </div>
</section>

<section class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
  <div class="bg-kivu-primary rounded-[2.5rem] p-10 md:p-16 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-10">
    <div class="absolute top-0 right-0 w-64 h-64 bg-kivu-primary-active rounded-full mix-blend-multiply filter blur-3xl opacity-70 -translate-y-1/2 translate-x-1/2"></div>
    <div class="relative z-10 max-w-2xl">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 text-white text-xs font-bold uppercase tracking-wider mb-6 backdrop-blur-sm border border-white/30"><i class="fa-solid fa-rocket"></i> MULAI SEKARANG</div>
      <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4 leading-tight">Siap Jadi Bagian dari Perubahan?</h2>
      <p class="text-blue-100 text-lg">Bergabung dengan KIVU dan temukan lebih banyak peluang untuk mengembangkan potensi kamu.</p>
    </div>
    <div class="relative z-10 flex flex-col items-center sm:items-end w-full md:w-auto flex-shrink-0">
      <a href="{{ route('register') }}" class="w-full sm:w-auto bg-kivu-yellow hover:bg-kivu-yellow-hover text-kivu-yellow-text font-bold rounded-full px-8 py-4 text-lg shadow-xl flex items-center justify-center gap-2">Daftar Sekarang <i class="fa-solid fa-arrow-right"></i></a>
      <p class="text-blue-200 text-xs mt-3 flex items-center gap-2"><i class="fa-solid fa-check"></i> Gratis &bull; Proses cepat &bull; Tanpa ribet</p>
    </div>
  </div>
</section>

<footer class="bg-white pt-20 pb-10 border-t border-kivu-border">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 mb-16">
      <div class="lg:col-span-2">
        <a href="/" class="flex items-center gap-2 mb-6"><img src="{{ asset('images/kivu-logo.png') }}" alt="KIVU" class="h-8 w-auto"></a>
        <p class="text-gray-500 text-sm mb-6 max-w-sm">Lebih Banyak Peluang untuk Semua. Platform yang menghubungkan talenta muda berbakat dengan UMKM di seluruh Indonesia.</p>
        <div class="flex gap-4">
          <a href="#" class="w-10 h-10 rounded-full bg-kivu-bg-soft flex items-center justify-center text-gray-500 hover:bg-kivu-primary hover:text-white transition-colors"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" class="w-10 h-10 rounded-full bg-kivu-bg-soft flex items-center justify-center text-gray-500 hover:bg-kivu-primary hover:text-white transition-colors"><i class="fa-brands fa-linkedin-in"></i></a>
          <a href="#" class="w-10 h-10 rounded-full bg-kivu-bg-soft flex items-center justify-center text-gray-500 hover:bg-kivu-primary hover:text-white transition-colors"><i class="fa-brands fa-youtube"></i></a>
          <a href="#" class="w-10 h-10 rounded-full bg-kivu-bg-soft flex items-center justify-center text-gray-500 hover:bg-kivu-primary hover:text-white transition-colors"><i class="fa-brands fa-tiktok"></i></a>
        </div>
      </div>
      <div><h4 class="font-bold text-gray-900 mb-6 uppercase text-sm tracking-wider">Untuk UMKM</h4><ul class="space-y-4 text-sm text-gray-600"><li><a href="{{ route('register') }}" class="hover:text-kivu-primary">Posting Tugas</a></li><li><a href="#peluang" class="hover:text-kivu-primary">Cari Talenta</a></li><li><a href="{{ route('register') }}" class="hover:text-kivu-primary">Daftar UMKM</a></li></ul></div>
      <div><h4 class="font-bold text-gray-900 mb-6 uppercase text-sm tracking-wider">Untuk Talenta</h4><ul class="space-y-4 text-sm text-gray-600"><li><a href="#peluang" class="hover:text-kivu-primary">Cari Tugas</a></li><li><a href="{{ route('register') }}" class="hover:text-kivu-primary">Daftar Mahasiswa</a></li></ul></div>
      <div><h4 class="font-bold text-gray-900 mb-6 uppercase text-sm tracking-wider">Tentang</h4><ul class="space-y-4 text-sm text-gray-600"><li><a href="#tentang" class="hover:text-kivu-primary">Tentang KIVU</a></li><li><a href="#cara-kerja" class="hover:text-kivu-primary">Cara Kerja</a></li><li><a href="#" class="hover:text-kivu-primary">Kebijakan Privasi</a></li></ul></div>
    </div>
    <div class="pt-8 border-t border-kivu-border flex flex-col md:flex-row justify-between items-center gap-4">
      <p class="text-sm text-gray-500">&copy; {{ date('Y') }} KIVU. Semua hak dilindungi.</p>
      <div class="flex gap-6 text-sm text-gray-500"><a href="#" class="hover:text-gray-900">Kebijakan Privasi</a><a href="#" class="hover:text-gray-900">Syarat &amp; Ketentuan</a></div>
    </div>
  </div>
</footer>

<script>
  document.getElementById('kivu-hamburger')?.addEventListener('click', function(){
    document.getElementById('kivu-mobile-menu')?.classList.toggle('hidden');
  });
  document.querySelectorAll('#kivu-mobile-menu a').forEach(function(a){
    a.addEventListener('click', function(){ document.getElementById('kivu-mobile-menu')?.classList.add('hidden'); });
  });
  window.addEventListener('scroll', function(){
    var nav = document.getElementById('kivu-navbar');
    if (nav && window.scrollY > 10) nav.classList.add('shadow-sm'); else if (nav) nav.classList.remove('shadow-sm');
  });
</script>
</div>