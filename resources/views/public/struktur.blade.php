@extends('public.layouts.app')

@section('title', 'Struktur Pengurus | DPC Partai Persatuan Pembangunan')

@section('content')
    <!-- Banner Header -->
    <section class="bg-slate-900 text-white py-16 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Struktur Kepengurusan</h1>
            <p class="text-slate-400 text-xs sm:text-sm max-w-2xl mx-auto leading-relaxed">
                Daftar lengkap pejabat dan fungsionaris Partai Persatuan Pembangunan yang bertugas menjalankan amanah perjuangan umat.
            </p>
        </div>
    </section>

    <!-- Organizational Chart Section -->
    <section class="py-16 bg-slate-50 min-h-[500px]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            @forelse($committees as $level => $levelCommittees)
                <!-- Hierarchy Level Block -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <span class="w-8 h-8 rounded-lg bg-[#005B2B] text-white font-extrabold flex items-center justify-center text-sm shadow-md">
                            {{ $level }}
                        </span>
                        <h2 class="text-xl sm:text-2xl font-extrabold text-slate-800">
                            Dewan Pimpinan {{ $level === 'DPP' ? 'Pusat' : ($level === 'DPW' ? 'Wilayah' : ($level === 'DPC' ? 'Cabang' : ($level === 'PAC' ? 'Kecamatan' : 'Ranting'))) }} ({{ $level }})
                        </h2>
                    </div>

                    @foreach($levelCommittees->groupBy('territory_label') as $territory => $members)
                        <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm">
                            <!-- Territory Header -->
                            @if($territory !== 'Nasional')
                                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-100">
                                    <svg class="w-5 h-5 text-[#005B2B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <h3 class="text-lg font-bold text-slate-800">{{ $territory }}</h3>
                                </div>
                            @endif

                            <!-- Cards Grid for Level Fungsionaris -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                                @foreach($members as $committee)
                                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-4 hover:shadow-md transition duration-200 hover:bg-white hover:border-slate-300">
                                        <!-- Avatar -->
                                        <div class="w-14 h-14 rounded-full overflow-hidden border border-slate-200 bg-white flex-shrink-0 flex items-center justify-center">
                                            @if($committee->member->photo)
                                                <img src="{{ asset('storage/' . $committee->member->photo) }}" class="w-full h-full object-cover">
                                            @else
                                                <!-- Initials / Fallback -->
                                                <span class="text-[#005B2B] font-extrabold text-xs uppercase">
                                                    {{ substr($committee->member->name, 0, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                        <!-- Details -->
                                        <div class="space-y-1 overflow-hidden">
                                            <h3 class="font-extrabold text-slate-800 text-sm truncate" title="{{ $committee->member->name }}">
                                                {{ $committee->member->name }}
                                            </h3>
                                            <p class="text-xs font-bold text-[#D97706] tracking-wide">
                                                {{ $committee->position->name }}
                                            </p>
                                            @if($committee->sk_number)
                                                <p class="text-[10px] text-slate-400 font-mono truncate" title="No. SK: {{ $committee->sk_number }}">
                                                    SK: {{ $committee->sk_number }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="py-16 text-center text-slate-400 text-sm bg-white rounded-3xl border border-slate-200 max-w-3xl mx-auto shadow-sm">
                    <svg class="h-12 w-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                    Belum ada struktur pengurus yang diaktifkan untuk publikasi.
                </div>
            @endforelse
        </div>
    </section>
@endsection
