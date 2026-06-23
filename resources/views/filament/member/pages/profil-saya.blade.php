<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="md:col-span-1 bg-white rounded-2xl border border-slate-150 p-6 flex flex-col items-center text-center shadow-ambient">
            <!-- Photo Uploader/Preview -->
            <div class="w-32 h-32 rounded-full overflow-hidden border-2 border-brand-green/20 bg-slate-50 flex items-center justify-center mb-4 relative shadow-inner">
                @if($member->photo && file_exists(public_path('storage/' . $member->photo)))
                    <img src="{{ asset('storage/' . $member->photo) }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-12 h-12 text-slate-350" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                @endif
            </div>

            <h2 class="text-lg font-bold text-slate-800">{{ $member->name }}</h2>
            <p class="text-xs font-mono text-slate-400 mt-0.5 tracking-wider">{{ $member->nik }}</p>
            
            <div class="mt-3">
                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold {{ $member->status === 'Aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-250' : ($member->status === 'Pending' ? 'bg-amber-50 text-amber-700 border border-amber-250' : 'bg-rose-50 text-rose-700 border border-rose-250') }}">
                    {{ $member->status }}
                </span>
            </div>

            <div class="w-full border-t border-slate-100 my-5"></div>

            <div class="w-full text-left space-y-3.5">
                <div>
                    <span class="block text-xxs font-bold text-slate-400 uppercase tracking-widest">Nomor Telepon</span>
                    <span class="text-sm font-semibold text-slate-700">{{ $member->phone ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xxs font-bold text-slate-400 uppercase tracking-widest">Alamat</span>
                    <span class="text-sm font-semibold text-slate-700 leading-relaxed">{{ $member->address }}</span>
                </div>
                <div>
                    <span class="block text-xxs font-bold text-slate-400 uppercase tracking-widest">Kelurahan / Desa</span>
                    <span class="text-sm font-semibold text-slate-700">{{ $member->village->name }}</span>
                </div>
            </div>
        </div>

        <!-- Password Form -->
        <div class="md:col-span-2 bg-white rounded-2xl border border-slate-150 p-6 shadow-ambient">
            <h3 class="text-md font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
                </svg>
                Keamanan & Ganti Password
            </h3>

            <form wire:submit="updatePassword" class="space-y-6">
                {{ $this->form }}

                <div class="flex justify-end gap-3 pt-2 border-t border-slate-100">
                    @foreach($this->getFormActions() as $action)
                        {{ $action }}
                    @endforeach
                </div>
            </form>
        </div>
    </div>
</x-filament-panels::page>
