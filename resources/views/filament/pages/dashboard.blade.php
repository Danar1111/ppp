<x-filament-panels::page>
    {{-- ============================================================
         DASHBOARD — Stitch "Majestic Green & Gold" Design
         ============================================================ --}}
    <div class="space-y-6 animate-fade-in-up">

        {{-- ROW 1 — Stats Overview (3-column grid) --}}
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Card 1: Total Anggota --}}
            <div class="bg-white rounded-xl p-5 shadow-sm border-t-[3px] border-[#00401E] hover:shadow-md transition-all group cursor-default animate-fade-in-up">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Total Anggota</p>
                        <h3 class="text-[28px] font-bold text-[#002810] leading-none">{{ number_format($this->getTotalAnggota()) }}</h3>
                    </div>
                    <div class="p-3 bg-green-50 rounded-lg group-hover:scale-110 transition-transform">
                        <x-heroicon-s-users class="w-6 h-6 text-[#00401E]" />
                    </div>
                </div>
                <p class="text-sm text-slate-500 mt-4 flex items-center gap-1">
                    <span class="text-[#00401E] font-bold">Jumlah</span> seluruh anggota terdaftar
                </p>
            </div>

            {{-- Card 2: Total Pengurus --}}
            <div class="bg-white rounded-xl p-5 shadow-sm border-t-[3px] border-[#2c374a] hover:shadow-md transition-all group cursor-default animate-fade-in-up delay-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Total Pengurus</p>
                        <h3 class="text-[28px] font-bold text-[#172233] leading-none">{{ number_format($this->getTotalPengurus()) }}</h3>
                    </div>
                    <div class="p-3 bg-slate-100 rounded-lg group-hover:scale-110 transition-transform">
                        <x-heroicon-s-identification class="w-6 h-6 text-[#3c475a]" />
                    </div>
                </div>
                <p class="text-sm text-slate-500 mt-4 flex items-center gap-1">
                    <span class="text-[#172233] font-bold">Jumlah</span> fungsionaris & pengurus
                </p>
            </div>

            {{-- Card 3: Agenda Mendatang --}}
            <div class="bg-white rounded-xl p-5 shadow-sm border-t-[3px] border-[#fe932c] hover:shadow-md transition-all group cursor-default animate-fade-in-up delay-200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Agenda Mendatang</p>
                        <h3 class="text-[28px] font-bold text-[#904d00] leading-none">{{ $this->getAgendaMendatang() }}</h3>
                    </div>
                    <div class="p-3 bg-orange-50 rounded-lg group-hover:scale-110 transition-transform">
                        <x-heroicon-s-calendar class="w-6 h-6 text-[#904d00]" />
                    </div>
                </div>
                <p class="text-sm text-slate-500 mt-4 flex items-center gap-1">
                    <span class="text-[#904d00] font-bold">Kegiatan</span> yang akan datang
                </p>
            </div>

        </section>

        {{-- ROW 2 — Welcome + Chart --}}
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

            {{-- LEFT: Welcome Card (8 cols) --}}
            <div class="lg:col-span-8 bg-white rounded-xl shadow-sm overflow-hidden flex flex-col md:flex-row relative group animate-fade-in-up delay-300">
                {{-- Subtle gradient overlay --}}
                <div class="absolute inset-0 bg-gradient-to-r from-[#00401E]/5 to-transparent pointer-events-none"></div>

                <div class="flex-1 p-8 relative z-10">
                    <span class="px-3 py-1 bg-amber-50 text-[#904d00] text-[10px] font-bold rounded-full uppercase tracking-widest mb-4 inline-block">
                        Ikhtisar Dasbor
                    </span>
                    <h2 class="text-2xl font-bold text-[#002810] mb-3">
                        Selamat Datang, {{ Auth::user()->name }}!
                    </h2>
                    <p class="text-[15px] text-slate-500 mb-8 max-w-xl leading-relaxed">
                        Kelola operasional partai dengan efisien melalui sistem manajemen terpadu. Pantau keanggotaan, inventaris, dan agenda nasional dalam satu pusat kendali yang aman dan modern.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ url('/admin/members/create') }}"
                           class="inline-flex items-center gap-2 bg-[#00401E] text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-[#002810] transition-all shadow-md shadow-green-900/20">
                            <x-heroicon-s-user-plus class="w-4 h-4" />
                            Tambah Anggota
                        </a>
                        <a href="{{ url('/admin/members') }}"
                           class="inline-flex items-center gap-2 border border-slate-200 text-[#002810] px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-slate-50 transition-all">
                            <x-heroicon-s-document-text class="w-4 h-4" />
                            Lihat Semua Anggota
                        </a>
                    </div>
                </div>

                {{-- Decorative Right Panel --}}
                <div class="hidden md:flex w-1/3 bg-[#00401E]/8 relative items-center justify-center p-6">
                    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, #00401e 1px, transparent 0); background-size: 24px 24px;"></div>
                    <div class="relative z-10 text-center">
                        <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg rotate-3 p-3">
                            <img src="{{ asset('images/logo.svg') }}" class="w-full h-full object-contain" alt="Logo PPP">
                        </div>
                        <p class="text-[#00401E] text-xs font-bold uppercase tracking-widest">Sistem Manajemen PPP</p>
                        <p class="text-slate-400 text-[10px] mt-1">Partai Persatuan Pembangunan</p>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Doughnut Chart (4 cols) --}}
            @php $chart = $this->getChartData(); @endphp
            <div class="lg:col-span-4 bg-white rounded-xl p-6 shadow-sm flex flex-col justify-between animate-fade-in-up delay-400">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-base font-semibold text-[#002810]">Status Keanggotaan</h3>
                    <button class="p-1.5 hover:bg-slate-100 rounded-lg transition-colors">
                        <x-heroicon-o-ellipsis-vertical class="w-5 h-5 text-slate-400" />
                    </button>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-6 py-2 flex-grow">
                    {{-- SVG Doughnut Ring --}}
                    <div class="relative w-32 h-32 shrink-0">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 192 192">
                            {{-- Track --}}
                            <circle cx="96" cy="96" fill="transparent" r="80"
                                    stroke="#eceef0" stroke-width="14"/>
                            {{-- Aktif (Forest Green) --}}
                            <circle cx="96" cy="96" fill="transparent" r="80"
                                    stroke="#00401E"
                                    stroke-width="14"
                                    stroke-dasharray="{{ $chart['circumference'] }}"
                                    stroke-dashoffset="{{ $chart['aktif_offset'] }}"
                                    stroke-linecap="round"/>
                            {{-- Pending (Gold) --}}
                            <circle cx="96" cy="96" fill="transparent" r="80"
                                    stroke="#fe932c"
                                    stroke-width="14"
                                    stroke-dasharray="{{ $chart['circumference'] }}"
                                    stroke-dashoffset="{{ $chart['pending_offset'] }}"
                                    stroke-linecap="round"
                                    style="transform-origin: 96px 96px; transform: rotate({{ $chart['pending_rotate'] }}deg)"/>
                            {{-- Nonaktif (Error Red) --}}
                            <circle cx="96" cy="96" fill="transparent" r="80"
                                    stroke="#ba1a1a"
                                    stroke-width="14"
                                    stroke-dasharray="{{ $chart['circumference'] }}"
                                    stroke-dashoffset="{{ $chart['nonaktif_offset'] }}"
                                    stroke-linecap="round"
                                    style="transform-origin: 96px 96px; transform: rotate({{ $chart['nonaktif_rotate'] }}deg)"/>
                        </svg>
                        {{-- Center Label --}}
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-xl font-bold text-[#002810] leading-none">{{ number_format($chart['total']) }}</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-1">Total</span>
                        </div>
                    </div>

                    {{-- Legend --}}
                    <div class="flex-1 w-full grid grid-cols-1 gap-2.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-[#00401E]"></div>
                                <span class="text-xs font-semibold text-slate-600">Aktif</span>
                            </div>
                            <span class="text-xs font-bold text-slate-800">{{ number_format($chart['aktif']) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-[#fe932c]"></div>
                                <span class="text-xs font-semibold text-slate-600">Pending</span>
                            </div>
                            <span class="text-xs font-bold text-slate-800">{{ number_format($chart['pending']) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-[#ba1a1a]"></div>
                                <span class="text-xs font-semibold text-slate-600">Nonaktif</span>
                            </div>
                            <span class="text-xs font-bold text-slate-800">{{ number_format($chart['nonaktif']) }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        {{-- ROW 3 — Recent Activity Table --}}
        <section class="bg-white rounded-xl shadow-sm overflow-hidden animate-fade-in-up delay-400">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-base font-semibold text-[#002810]">Aktivitas Terbaru</h3>
                <a href="{{ url('/admin/members') }}"
                   class="text-sm font-semibold text-[#00401E] hover:underline">Lihat Semua</a>
            </div>

            @php $activities = $this->getRecentActivities(); @endphp

            @if($activities->isEmpty())
                <div class="p-12 text-center text-slate-400">
                    <x-heroicon-o-inbox class="w-12 h-12 mx-auto mb-3 opacity-40" />
                    <p class="text-sm">Belum ada aktivitas terbaru.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/80">
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Anggota</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Aksi</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($activities as $activity)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-[#00401E]/10 flex items-center justify-center text-[#00401E] font-bold text-xs shrink-0">
                                                {{ $activity['initials'] }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-800">{{ $activity['name'] }}</p>
                                                <p class="text-[11px] text-slate-400">{{ $activity['role'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $activity['action'] }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-400">{{ $activity['time'] }}</td>
                                    <td class="px-6 py-4">
                                        @if($activity['status'] === 'Aktif')
                                            <span class="px-2.5 py-1 bg-green-50 text-green-700 text-[11px] font-bold rounded-full">AKTIF</span>
                                        @elseif($activity['status'] === 'Pending')
                                            <span class="px-2.5 py-1 bg-amber-50 text-amber-700 text-[11px] font-bold rounded-full">PENDING</span>
                                        @else
                                            <span class="px-2.5 py-1 bg-red-50 text-red-700 text-[11px] font-bold rounded-full">NONAKTIF</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

    </div>
</x-filament-panels::page>
