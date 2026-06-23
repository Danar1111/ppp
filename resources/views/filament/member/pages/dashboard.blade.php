<x-filament-panels::page>
    {{-- ============================================================
         PORTAL ANGGOTA — Stitch "Majestic Green & Gold" Design
         ============================================================ --}}
    <div class="space-y-6 animate-fade-in-up">
        @php
            $member = $this->getMember();
            $events = $this->getUpcomingEvents();
        @endphp

        {{-- ROW 1 — Welcome & Overview --}}
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-12 bg-white rounded-xl shadow-sm overflow-hidden flex flex-col md:flex-row relative group border-l-4 border-[#005B2B] animate-fade-in-up">
                {{-- Subtle gradient overlay --}}
                <div class="absolute inset-0 bg-gradient-to-r from-[#00401E]/5 to-transparent pointer-events-none"></div>

                <div class="flex-1 p-6 md:p-8 relative z-10">
                    <div class="flex flex-wrap gap-2 items-center mb-4">
                        <span class="px-3 py-1 bg-amber-50 text-[#904d00] text-[10px] font-bold rounded-full uppercase tracking-widest">
                            Portal Anggota
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $member->status === 'Aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-250' : ($member->status === 'Pending' ? 'bg-amber-50 text-amber-700 border border-amber-250' : 'bg-rose-50 text-rose-700 border border-rose-250') }}">
                            Status: {{ $member->status }}
                        </span>
                    </div>

                    <h2 class="text-2xl font-bold text-[#002810] mb-3">
                        Selamat Datang, {{ $member->name }}!
                    </h2>
                    <p class="text-[15px] text-slate-500 mb-6 max-w-2xl leading-relaxed">
                        Terima kasih telah bergabung sebagai bagian penting dari perjuangan kami. Melalui portal ini, Anda dapat mengakses Kartu Tanda Anggota (KTA) digital Anda, memperbarui data pribadi, dan memantau kegiatan partai yang akan datang.
                    </p>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ url('/portal/profil-saya') }}"
                           class="inline-flex items-center gap-2 bg-[#00401E] text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-[#002810] transition-all shadow-md shadow-green-900/20">
                            <x-heroicon-s-user class="w-4 h-4" />
                            Edit Profil Saya
                        </a>
                        <button id="btn-portal-download-kta-1"
                           class="inline-flex items-center gap-2 border border-slate-200 bg-white text-[#002810] px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-slate-50 transition-all cursor-pointer">
                            <x-heroicon-s-arrow-down-tray class="w-4 h-4" />
                            Unduh KTA Digital
                        </button>
                    </div>
                </div>

                {{-- Decorative Right Panel --}}
                <div class="hidden md:flex w-1/4 bg-[#00401E]/5 relative items-center justify-center p-6 border-l border-slate-100">
                    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, #00401e 1px, transparent 0); background-size: 24px 24px;"></div>
                    <div class="relative z-10 text-center">
                        <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg rotate-3 p-2 border border-slate-100">
                            <img src="{{ asset('images/logo.svg') }}" class="w-full h-full object-contain" alt="Logo PPP">
                        </div>
                        <p class="text-[#00401E] text-[10px] font-bold uppercase tracking-wider">SIP - PPP</p>
                        <p class="text-slate-400 text-[9px]">Sistem Informasi Partai</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ROW 2 — KTA Card & Upcoming Events --}}
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            {{-- LEFT: Digital KTA Preview (7 cols) --}}
            <div class="lg:col-span-7 bg-white rounded-xl p-6 shadow-sm border border-slate-100 animate-fade-in-up delay-100">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-identification class="w-5 h-5 text-[#00401E]" />
                        <h3 class="text-base font-semibold text-[#002810]">KTA Digital Anda</h3>
                    </div>
                    <button id="btn-portal-download-kta-2" class="text-xs text-[#00401E] font-bold hover:underline flex items-center gap-1 cursor-pointer">
                        <x-heroicon-s-arrow-down-tray class="w-3.5 h-3.5" /> Unduh Gambar
                    </button>
                </div>

                {{-- The KTA Card Wrapper with Hover Effect --}}
                <div class="flex justify-center py-4">
                    <div id="member-kta-card-download" class="relative w-[450px] h-[280px] rounded-2xl p-4 shadow-xl border-[3px] border-[#D97706] transition-all duration-300 hover:scale-[1.03] hover:shadow-2xl overflow-hidden flex flex-col justify-between shrink-0"
                         style="background: linear-gradient(135deg, #ffffff 60%, #e6f7ed 100%);">
                        
                        <!-- Large Centered Watermark -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-5 pointer-events-none z-0">
                            <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents(public_path('images/logo.svg'))) }}" class="w-56 h-56 object-contain" alt="Watermark Logo">
                        </div>
                        
                        {{-- Top/Header --}}
                        <div class="flex justify-between items-center border-b border-[#D97706]/30 pb-2 mb-2 relative z-10">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-white p-0.5 rounded-lg flex items-center justify-center shadow-sm shrink-0 border border-slate-100">
                                    <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents(public_path('images/logo.svg'))) }}" class="w-full h-full object-contain" alt="Logo PPP">
                                </div>
                                <div>
                                    <h4 class="text-[11px] font-bold text-[#005B2B] leading-none uppercase">Partai Persatuan Pembangunan</h4>
                                    <p class="text-[7px] text-[#D97706] font-semibold tracking-wide uppercase mt-0.5">KARTU TANDA ANGGOTA DIGITAL</p>
                                </div>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="flex gap-3 items-center flex-1 relative z-10">
                            {{-- Photo --}}
                            <div class="w-20 aspect-[3/4] bg-slate-100 rounded-lg overflow-hidden border border-slate-200 shrink-0 flex items-center justify-center">
                                @if($member->photo && file_exists(public_path('storage/' . $member->photo)))
                                    <img src="{{ asset('storage/' . $member->photo) }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                    </svg>
                                @endif
                            </div>

                            {{-- Details --}}
                            <div class="flex-1 min-w-0 text-[10px] space-y-1.5">
                                <div>
                                    <span class="block text-[8px] font-bold text-slate-400 uppercase leading-none">Nama Lengkap</span>
                                    <span class="font-bold text-slate-800 leading-none truncate block">{{ $member->name }}</span>
                                </div>
                                <div>
                                    <span class="block text-[8px] font-bold text-slate-400 uppercase leading-none">Nomor NIK</span>
                                    <span class="font-mono font-semibold text-slate-700 leading-none">{{ $member->nik }}</span>
                                </div>
                                <div>
                                    <span class="block text-[8px] font-bold text-slate-400 uppercase leading-none">Kelurahan / Desa</span>
                                    <span class="font-semibold text-slate-700 leading-none">{{ $member->village->name ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="block text-[8px] font-bold text-slate-400 uppercase leading-none">Status</span>
                                    <span class="font-bold text-[#005B2B] leading-none">{{ $member->status }}</span>
                                </div>
                            </div>

                            {{-- QR Code --}}
                            <div class="w-16 h-16 shrink-0 bg-white p-1 rounded-lg border border-slate-100 flex items-center justify-center shadow-inner">
                                {!! QrCode::size(56)->margin(0)->generate($member->nik) !!}
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="border-t border-slate-100 pt-1 mt-1 text-center relative z-10">
                            <p class="text-[6px] text-slate-400">Sistem Informasi Partai Politik PPP - Dicetak secara digital pada {{ now()->format('d-m-Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Upcoming Events (5 cols) --}}
            <div class="lg:col-span-5 bg-white rounded-xl p-6 shadow-sm border border-slate-100 flex flex-col animate-fade-in-up delay-200">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-calendar class="w-5 h-5 text-[#00401E]" />
                        <h3 class="text-base font-semibold text-[#002810]">Agenda Terdekat</h3>
                    </div>
                    <a href="{{ url('/portal/events') }}" class="text-xs text-[#00401E] font-bold hover:underline">Lihat Semua</a>
                </div>

                <div class="space-y-4">
                    @forelse($events as $event)
                        <div class="p-4 bg-slate-50 hover:bg-slate-100/70 rounded-xl transition-all border border-slate-150/60 flex gap-4">
                            {{-- Date Badge --}}
                            <div class="flex flex-col items-center justify-center w-12 h-12 bg-white rounded-lg border border-slate-200 shrink-0 text-center">
                                <span class="text-[10px] font-extrabold text-[#D97706] uppercase tracking-wider leading-none">
                                    {{ $event->start_datetime->format('M') }}
                                </span>
                                <span class="text-lg font-black text-[#00401E] leading-none mt-0.5">
                                    {{ $event->start_datetime->format('d') }}
                                </span>
                            </div>

                            {{-- Details --}}
                            <div class="min-w-0 flex-1">
                                <h4 class="text-sm font-bold text-slate-800 truncate leading-snug">{{ $event->name }}</h4>
                                <p class="text-[11px] text-slate-500 flex items-center gap-1 mt-1">
                                    <x-heroicon-m-map-pin class="w-3.5 h-3.5 shrink-0 text-slate-400" />
                                    <span class="truncate">{{ $event->location }}</span>
                                </p>
                                <p class="text-[11px] text-slate-400 flex items-center gap-1 mt-0.5">
                                    <x-heroicon-m-clock class="w-3.5 h-3.5 shrink-0 text-slate-400" />
                                    <span>{{ $event->start_datetime->format('H:i') }} - {{ $event->end_datetime->format('H:i') }}</span>
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400">
                            <x-heroicon-o-calendar-days class="w-12 h-12 mx-auto mb-2 text-slate-300" />
                            <p class="text-sm">Belum ada agenda terdekat saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const handleDownload = function () {
                const card = document.getElementById('member-kta-card-download');
                html2canvas(card, {
                    useCORS: true,
                    scale: 3, // High-res capture
                    backgroundColor: null,
                    logging: false
                }).then(canvas => {
                    const link = document.createElement('a');
                    link.download = 'KTA_{{ str_replace(" ", "_", $member->name) }}.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                });
            };

            const btn1 = document.getElementById('btn-portal-download-kta-1');
            const btn2 = document.getElementById('btn-portal-download-kta-2');
            if (btn1) btn1.addEventListener('click', handleDownload);
            if (btn2) btn2.addEventListener('click', handleDownload);
        });
    </script>
</x-filament-panels::page>
