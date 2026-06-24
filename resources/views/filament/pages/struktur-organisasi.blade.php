<x-filament-panels::page>

    <div class="space-y-6">
        <!-- Level Tabs -->
        <x-filament::tabs label="Structural Levels" class="w-full bg-white dark:bg-gray-900 shadow rounded-xl p-2 flex overflow-x-auto">
            @foreach(['DPW' => 'Pengurus Wilayah', 'DPC' => 'Pengurus Cabang', 'PAC' => 'Pengurus PAC', 'Ranting' => 'Pengurus Ranting'] as $level => $label)
                <x-filament::tabs.item
                    :active="$activeLevel === $level"
                    wire:click="$set('activeLevel', '{{ $level }}')"
                >
                    {{ $label }}
                </x-filament::tabs.item>
            @endforeach
        </x-filament::tabs>

        <!-- Content Area -->
        <div>
            @if ($selectedRegionId === null)
                <!-- STATE 1: REGION GRID -->
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
                    @foreach ($this->regions as $region)
                        <x-filament::card class="flex flex-col h-full hover:border-primary-500 transition cursor-pointer" wire:click="selectRegion({{ $region->id }})">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-primary-50 dark:bg-primary-900/50 text-primary-600 dark:text-primary-400 p-2 rounded-lg shrink-0">
                                        <x-filament::icon
                                            icon="{{ $activeLevel === 'PAC' ? 'heroicon-o-map-pin' : ($activeLevel === 'Ranting' ? 'heroicon-o-home' : 'heroicon-o-building-office') }}"
                                            class="h-5 w-5"
                                        />
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-tight">
                                        @if ($activeLevel === 'PAC') Kec. @elseif ($activeLevel === 'Ranting') Desa @endif
                                        {{ $region->name }}
                                    </h3>
                                </div>
                            </div>
                            
                            <div class="mt-4 flex items-center justify-between border-t border-gray-100 dark:border-gray-800 pt-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengurus Aktif</div>
                                <div class="font-bold text-lg text-primary-600 dark:text-primary-400">{{ $region->committees_count }} <span class="text-xs font-normal text-gray-500">Orang</span></div>
                            </div>
                            
                            <x-filament::button class="mt-4 w-full" color="gray" outlined size="sm" wire:click.stop="selectRegion({{ $region->id }})">
                                Lihat Anggota &rarr;
                            </x-filament::button>
                        </x-filament::card>
                    @endforeach
                </div>
            @else
                <!-- STATE 2: PEOPLE GRID -->
                <div class="space-y-6">
                    <div class="mb-6">
                        <x-filament::button wire:click="backToRegions" color="gray" icon="heroicon-m-arrow-left" class="mb-6">
                            Kembali ke Daftar Wilayah
                        </x-filament::button>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-800 pb-4">
                            Struktur Organisasi: {{ $this->regionName }}
                        </h2>
                    </div>

                    @if ($this->members->isEmpty())
                        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-12 flex flex-col items-center justify-center text-center border border-gray-200 dark:border-gray-800">
                            <x-filament::icon icon="heroicon-o-user-group" class="h-12 w-12 text-gray-400 mb-4" />
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Belum Ada Pengurus</h3>
                            <p class="text-gray-500 mt-1">Wilayah ini belum memiliki anggota pengurus yang terdaftar dalam sistem.</p>
                        </div>
                    @else
                        @php
                            $members = $this->members;
                            
                            $ketua = $members->filter(fn($m) => preg_match('/^ketua\b/i', $m->position->name ?? '') && !preg_match('/wakil/i', $m->position->name ?? ''));
                            $wakil = $members->filter(fn($m) => preg_match('/wakil ketua/i', $m->position->name ?? ''));
                            $sekretaris = $members->filter(fn($m) => preg_match('/sekretaris/i', $m->position->name ?? ''));
                            $bendahara = $members->filter(fn($m) => preg_match('/bendahara/i', $m->position->name ?? ''));
                            
                            $lainnya = $members->reject(function($m) {
                                $name = strtolower($m->position->name ?? '');
                                $isKetua = preg_match('/^ketua\b/i', $name) && !preg_match('/wakil/i', $name);
                                $isWakil = preg_match('/wakil ketua/i', $name);
                                $isSekretaris = preg_match('/sekretaris/i', $name);
                                $isBendahara = preg_match('/bendahara/i', $name);
                                return $isKetua || $isWakil || $isSekretaris || $isBendahara;
                            });

                            $renderCard = function($committee, $borderColorClass) {
                                $avatar = !empty($committee->member->photo) 
                                    ? asset('storage/' . $committee->member->photo) 
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($committee->member->name ?? 'A') . '&background=0D8A4E&color=fff&size=128';
                                $name = $committee->member->name ?? 'Tidak Diketahui';
                                $position = $committee->position->name ?? 'Jabatan Tidak Diketahui';
                                $sk = $committee->sk_number;

                                return "
                                <div class=\"w-64 h-full bg-white dark:bg-gray-900 shadow-sm hover:shadow-md rounded-xl p-5 border-t-4 {$borderColorClass} border-x border-b border-gray-200 dark:border-gray-800 flex flex-col items-center text-center mx-auto transition-all\">
                                    <img src=\"{$avatar}\" class=\"w-20 h-20 rounded-full object-cover shadow-sm ring-4 ring-gray-50 dark:ring-gray-800 mb-3 shrink-0\">
                                    <h4 class=\"text-sm font-bold text-gray-900 dark:text-white mb-1\">{$name}</h4>
                                    <span class=\"inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 mb-2\">{$position}</span>
                                    " . ($sk ? "<div class=\"text-[10px] text-gray-500 flex items-center mt-auto pt-4 border-t border-gray-100 dark:border-gray-800 w-full justify-center\"><svg class=\"w-4 h-4 mr-1 shrink-0 text-gray-400\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z\"></path></svg><span class=\"truncate\">{$sk}</span></div>" : "") . "
                                </div>
                                ";
                            };
                        @endphp

                        <div class="flex flex-col items-center py-12 bg-gray-50/50 dark:bg-gray-900/20 rounded-2xl border border-gray-100 dark:border-gray-800">
                            
                            {{-- KETUA --}}
                            @if($ketua->isNotEmpty())
                                <div class="flex flex-col items-center w-full px-4 max-w-4xl mx-auto" style="margin-top: 1rem; margin-bottom: 1rem;">
                                    <div class="w-full bg-white/60 dark:bg-gray-800/60 p-6 rounded-2xl border border-gray-200/50 dark:border-gray-700/50">
                                        <h3 class="text-sm font-black text-gray-400 uppercase tracking-[0.2em] mb-6 text-center border-b border-gray-200/50 dark:border-gray-700/50 pb-3">Pimpinan Ketua</h3>
                                        <div class="flex flex-wrap justify-center items-stretch gap-6">
                                            @foreach($ketua as $committee)
                                                {!! $renderCard($committee, 'border-t-primary-600') !!}
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- WAKIL KETUA --}}
                            @if($wakil->isNotEmpty())
                                <div class="flex flex-col items-center w-full px-4 max-w-5xl mx-auto" style="margin-bottom: 1rem;">
                                    <div class="w-full bg-white/60 dark:bg-gray-800/60 p-6 rounded-2xl border border-gray-200/50 dark:border-gray-700/50">
                                        <h3 class="text-sm font-black text-gray-400 uppercase tracking-[0.2em] mb-6 text-center border-b border-gray-200/50 dark:border-gray-700/50 pb-3">Wakil Ketua</h3>
                                        <div class="flex flex-wrap justify-center items-stretch gap-6">
                                            @foreach($wakil as $committee)
                                                {!! $renderCard($committee, 'border-t-primary-400') !!}
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- SEKRETARIS & BENDAHARA --}}
                            @if($sekretaris->isNotEmpty() || $bendahara->isNotEmpty())
                                <div class="flex flex-col lg:flex-row justify-center items-start gap-8 lg:gap-12 w-full max-w-6xl mx-auto px-4" style="margin-top: 1rem; margin-bottom: 1rem;">
                                    @if($sekretaris->isNotEmpty())
                                    <div class="flex-1 w-full bg-white/60 dark:bg-gray-800/60 p-6 rounded-2xl border border-gray-200/50 dark:border-gray-700/50">
                                        <h3 class="text-sm font-black text-gray-400 uppercase tracking-[0.2em] mb-6 text-center border-b border-gray-200/50 dark:border-gray-700/50 pb-3">Sekretariat</h3>
                                        <div class="flex flex-wrap justify-center items-stretch gap-6">
                                            @foreach($sekretaris as $committee)
                                                {!! $renderCard($committee, 'border-t-info-500') !!}
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    @if($bendahara->isNotEmpty())
                                    <div class="flex-1 w-full bg-white/60 dark:bg-gray-800/60 p-6 rounded-2xl border border-gray-200/50 dark:border-gray-700/50">
                                        <h3 class="text-sm font-black text-gray-400 uppercase tracking-[0.2em] mb-6 text-center border-b border-gray-200/50 dark:border-gray-700/50 pb-3">Kebendaharaan</h3>
                                        <div class="flex flex-wrap justify-center items-stretch gap-6">
                                            @foreach($bendahara as $committee)
                                                {!! $renderCard($committee, 'border-t-warning-500') !!}
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            @endif

                            {{-- LAINNYA --}}
                            @if($lainnya->isNotEmpty())
                                <div class="w-full max-w-7xl mx-auto px-4 pt-4">
                                    <div class="flex items-center justify-center mb-8">
                                        <div class="h-px bg-gray-200 dark:bg-gray-800 flex-1"></div>
                                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] px-6 text-center">Bidang / Departemen / Lainnya</h3>
                                        <div class="h-px bg-gray-200 dark:bg-gray-800 flex-1"></div>
                                    </div>
                                    
                                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.5rem;">
                                        @foreach($lainnya as $committee)
                                            {!! str_replace('w-64', 'w-full', $renderCard($committee, 'border-t-gray-400')) !!}
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

</x-filament-panels::page>
