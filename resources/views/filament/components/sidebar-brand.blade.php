{{-- Custom sidebar brand logo matching the Stitch design --}}
<div class="flex items-center gap-3 py-1">
    <div class="w-11 h-11 flex items-center justify-center rounded-md shrink-0 fi-brand-logo-box">
        <img src="{{ asset('images/logo.svg') }}" class="w-9 h-9 object-contain" alt="Logo PPP">
    </div>
    <div x-show="$store.sidebar.isOpen" 
         x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         class="flex flex-col justify-center">
        <p class="font-bold text-[14px] leading-none text-white uppercase tracking-wide fi-brand-name">SISTEM MANAJEMEN</p>
        <p class="text-[8px] font-bold uppercase tracking-wider mt-1 text-[#D97706]/90 fi-brand-subtitle">PARTAI PERSATUAN PEMBANGUNAN</p>
    </div>
</div>
