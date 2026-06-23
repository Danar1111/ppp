{{-- Sidebar footer profile — mirrors the Stitch design --}}
<div class="mt-auto px-3 py-4 border-t" style="border-color: rgba(255,255,255,0.10); background-color: var(--color-dark-green);" x-data="{}">
    <div class="flex items-center gap-3 px-3 py-2 rounded-lg" style="background-color: rgba(255,255,255,0.05);"
         x-bind:class="{ 'justify-center !px-1': ! $store.sidebar.isOpen }">
        {{-- Avatar initials --}}
        <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 text-sm font-bold" style="background-color: rgba(254,147,44,0.20); color: #fe932c; border: 1.5px solid rgba(254,147,44,0.4);">
            {{ collect(explode(' ', auth()->user()?->name ?? 'U'))->take(2)->map(fn($p) => strtoupper(substr($p,0,1)))->join('') }}
        </div>
        
        <div class="overflow-hidden flex-1 min-w-0" 
             x-show="$store.sidebar.isOpen" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0">
            <p class="text-white text-[13px] font-semibold truncate leading-none">
                {{ auth()->user()?->name ?? 'Guest' }}
            </p>
            <p class="text-[11px] truncate mt-0.5" style="color: rgba(255,255,255,0.45);">
                {{ auth()->user()?->roles?->first()?->name ?? 'Anggota' }}
            </p>
        </div>
        
        <div style="color: rgba(255,255,255,0.40);" 
             x-show="$store.sidebar.isOpen"
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01"/>
            </svg>
        </div>
    </div>
</div>
