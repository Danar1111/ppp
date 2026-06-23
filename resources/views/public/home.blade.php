@extends('public.layouts.app')

@section('title', 'Beranda | Partai Persatuan Pembangunan')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-[#005B2B] via-[#00401E] to-slate-950 text-white overflow-hidden py-24 sm:py-32">
        <!-- Abstract Decorative Glow Rings -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 rounded-full bg-[#D97706]/10 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 rounded-full bg-[#005B2B]/20 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Text Content -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#D97706]/20 border border-[#D97706]/35 text-[#D97706] rounded-full text-xs font-bold uppercase tracking-wider animate-hero-up">
                        Satu Umat, Satu Barisan, Satu Tujuan
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight animate-hero-up delay-100">
                        Sistem Informasi & <br class="hidden sm:inline">
                        <span class="text-[#D97706]">Portal Keanggotaan</span> PPP
                    </h1>
                    <p class="text-slate-300 text-base sm:text-lg max-w-2xl mx-auto lg:mx-0 leading-relaxed animate-hero-up delay-200">
                        Selamat datang di portal resmi Partai Persatuan Pembangunan. Wadah konsolidasi kader, manajemen informasi operasional, dan transparansi administrasi partai menuju kejayaan bersama.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 animate-hero-up delay-300">
                        @auth('member')
                            <a href="/portal" class="w-full sm:w-auto px-8 py-3 bg-[#D97706] hover:bg-[#B45309] text-white font-bold rounded-xl transition duration-200 shadow-lg text-center">
                                Portal Saya
                            </a>
                        @else
                            <a href="/portal/login" class="w-full sm:w-auto px-8 py-3 bg-[#D97706] hover:bg-[#B45309] text-white font-bold rounded-xl transition duration-200 shadow-lg text-center">
                                Login Anggota
                            </a>
                        @endauth
                        <a href="#cek-nik" class="w-full sm:w-auto px-8 py-3 bg-white/10 hover:bg-white/20 text-white border border-white/20 font-bold rounded-xl transition duration-200 text-center">
                            Cek Keanggotaan
                        </a>
                    </div>
                </div>

                <!-- Right Side Logo Banner (Wow Design Accent) -->
                <div class="lg:col-span-5 flex justify-center animate-hero-scale delay-400">
                    <div class="relative bg-white/5 p-8 sm:p-12 rounded-3xl border border-white/10 backdrop-blur-sm shadow-2xl group">
                        <div class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-[#D97706] to-[#005B2B] opacity-30 blur-lg group-hover:opacity-50 transition duration-500"></div>
                        <div class="relative p-2 transition-transform duration-300 hover:scale-105">
                            <img src="{{ asset('images/logo.svg') }}" class="h-48 w-48 object-contain rounded-2xl shadow-2xl" alt="Logo PPP">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- NIK Verification Section -->
    <section id="cek-nik" class="py-16 bg-white border-b border-slate-200">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            <div class="bg-gradient-to-br from-slate-900 to-slate-950 text-white rounded-3xl shadow-xl overflow-hidden border border-slate-800 p-8 sm:p-12 space-y-6 reveal-on-scroll"
                 x-data="{
                     nik: '',
                     loading: false,
                     result: null,
                     error: null,
                     submitNik() {
                         this.nik = this.nik.replace(/\D/g, '');
                         if (this.nik.length !== 16) {
                             this.error = 'Nomor NIK harus tepat 16 digit angka.';
                             this.result = null;
                             return;
                         }
                         this.loading = true;
                         this.result = null;
                         this.error = null;
                         fetch('{{ route("public.cek-nik") }}', {
                             method: 'POST',
                             headers: {
                                 'Content-Type': 'application/json',
                                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                             },
                             body: JSON.stringify({ nik: this.nik })
                         })
                         .then(res => res.json())
                         .then(data => {
                             this.loading = false;
                             if (data.success) {
                                 this.result = data.message;
                             } else {
                                 this.error = data.message;
                             }
                         })
                         .catch(err => {
                             this.loading = false;
                             this.error = 'Terjadi kesalahan sistem. Silakan coba beberapa saat lagi.';
                         });
                     }
                 }">
                
                <div class="text-center space-y-2">
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Verifikasi Keanggotaan Publik</h2>
                    <p class="text-slate-400 text-xs sm:text-sm">
                        Masukkan 16 digit Nomor Induk Kependudukan (NIK) Anda untuk memeriksa status keaktifan sebagai anggota partai.
                    </p>
                </div>

                <form @submit.prevent="submitNik()" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-grow">
                        <input type="text" maxlength="16" x-model="nik" placeholder="Contoh: 3273010203040001"
                               class="w-full px-5 py-4 bg-slate-800 border border-slate-700 rounded-2xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-[#D97706] text-sm tracking-wider font-mono">
                        <div class="absolute right-4 top-4" x-show="loading" style="display: none;">
                            <svg class="animate-spin h-5 w-5 text-[#D97706]" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                    <button type="submit" :disabled="loading"
                            class="px-8 py-4 bg-[#D97706] hover:bg-[#B45309] disabled:bg-slate-700 text-white font-extrabold text-sm rounded-2xl shadow-lg transition duration-200 whitespace-nowrap cursor-pointer">
                        Periksa NIK
                    </button>
                </form>

                <!-- Feedback Message (Green / Red Alert) -->
                <div x-show="result" class="p-4 bg-emerald-950/80 border border-emerald-500/35 text-emerald-300 rounded-2xl flex items-start gap-3 shadow-inner" style="display: none;">
                    <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-xs sm:text-sm font-semibold" x-text="result"></span>
                </div>

                <div x-show="error" class="p-4 bg-rose-950/80 border border-rose-500/35 text-rose-300 rounded-2xl flex items-start gap-3 shadow-inner" style="display: none;">
                    <svg class="w-5 h-5 text-rose-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="text-xs sm:text-sm font-semibold" x-text="error"></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest News Section -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-baseline justify-between mb-12 gap-4 reveal-on-scroll">
                <div class="space-y-1">
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">Kabar & Publikasi Terbaru</h2>
                    <p class="text-slate-500 text-xs sm:text-sm">Pantau berita aktual dan rilis pers resmi dari kepengurusan partai.</p>
                </div>
                <a href="{{ route('public.berita.index') }}" class="text-xs sm:text-sm font-bold text-[#005B2B] hover:text-[#D97706] flex items-center gap-1 group">
                    Lihat Semua Berita
                    <span class="group-hover:translate-x-1 transition duration-200">&rarr;</span>
                </a>
            </div>

            <!-- News Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($articles as $index => $article)
                    <article class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition duration-300 flex flex-col h-full reveal-on-scroll reveal-delay-{{ ($index % 3) * 100 }}">
                        <div class="relative h-48 bg-slate-200 overflow-hidden">
                            @if($article->image)
                                <img src="{{ asset('storage/' . $article->image) }}" class="w-full h-full object-cover" alt="{{ $article->title }}">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-[#005B2B]/30 to-[#005B2B]/10 flex items-center justify-center">
                                    <svg class="h-12 w-12 text-[#005B2B]/40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                            <div class="space-y-2">
                                <time class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">
                                    {{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }}
                                </time>
                                <h3 class="font-bold text-slate-800 text-lg hover:text-[#005B2B] transition line-clamp-2">
                                    <a href="{{ route('public.berita.show', $article->slug) }}">{{ $article->title }}</a>
                                </h3>
                                <p class="text-xs text-slate-500 leading-relaxed line-clamp-3">
                                    {{ Str::limit(strip_tags($article->content), 120) }}
                                </p>
                            </div>
                            <a href="{{ route('public.berita.show', $article->slug) }}" class="inline-flex items-center text-xs font-extrabold text-[#005B2B] hover:text-[#D97706] gap-1 group">
                                Baca Selengkapnya
                                <span class="group-hover:translate-x-0.5 transition duration-200">&rarr;</span>
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="col-span-3 py-12 text-center text-slate-400 text-sm">
                        Belum ada berita yang dipublikasikan.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Upcoming Events Section -->
    <section class="py-20 bg-slate-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[#005B2B]/5 mix-blend-overlay"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl mx-auto text-center space-y-2 mb-16 reveal-on-scroll">
                <h2 class="text-3xl font-extrabold tracking-tight">Agenda & Kegiatan Partai</h2>
                <p class="text-slate-400 text-xs sm:text-sm">Pantau koordinasi kerja dan agenda kegiatan partai yang akan datang.</p>
            </div>

            <!-- Events Timeline -->
            <div class="max-w-3xl mx-auto space-y-6">
                @forelse($events as $index => $event)
                    <div class="bg-slate-800/80 border border-slate-700/60 rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 hover:border-slate-600 transition reveal-on-scroll reveal-delay-{{ ($index % 3) * 100 }}">
                        <!-- Date & Details -->
                        <div class="flex items-start gap-4">
                            <!-- Date Icon badge -->
                            <div class="flex-shrink-0 w-16 h-16 bg-[#005B2B] text-white rounded-xl flex flex-col items-center justify-center shadow-md">
                                <span class="text-xl font-extrabold leading-none">{{ $event->start_datetime->format('d') }}</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider mt-1">{{ $event->start_datetime->format('M') }}</span>
                            </div>
                            <div class="space-y-1">
                                <h3 class="font-bold text-md text-white">{{ $event->name }}</h3>
                                <p class="text-xs text-slate-400 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                    {{ $event->location }}
                                </p>
                                <p class="text-xs text-slate-400 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $event->start_datetime->format('H:i') }} - {{ $event->end_datetime->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>

                        <!-- Status badge / button -->
                        <div>
                            @if($event->status === 'Sedang Berjalan')
                                <span class="inline-block px-3 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/35 rounded-full text-xs font-bold uppercase tracking-wider animate-pulse">
                                    Sedang Berjalan
                                </span>
                            @elseif($event->status === 'Akan Datang')
                                <span class="inline-block px-3 py-1 bg-[#D97706]/20 text-[#D97706] border border-[#D97706]/35 rounded-full text-xs font-bold uppercase tracking-wider">
                                    Akan Datang
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 bg-slate-700 text-slate-400 rounded-full text-xs font-semibold uppercase tracking-wider">
                                    Selesai
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-500 text-sm">
                        Belum ada agenda kegiatan yang terdaftar.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
