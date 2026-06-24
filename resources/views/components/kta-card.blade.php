<div class="flex flex-col items-center justify-center p-6 bg-slate-50/30 rounded-2xl border border-slate-100 shadow-inner">
    <!-- Card Container with fixed dimensions for pixel-perfect screenshot -->
    <div id="kta-capture" class="relative w-[450px] h-[280px] bg-gradient-to-br from-white via-slate-50 to-[#D97706]/10 rounded-2xl border-2 border-amber-500/20 shadow-xl overflow-hidden font-sans flex flex-col shrink-0 select-none">
        <!-- Background Pattern overlay -->
        <div class="absolute inset-0 opacity-[0.02] pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, currentColor 1px, transparent 0); background-size: 24px 24px;"></div>
        
        <!-- Large Centered Watermark -->
        <div class="absolute inset-0 flex items-center justify-center opacity-5 pointer-events-none z-0">
            <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents(public_path('images/logo.svg'))) }}" class="w-56 h-56 object-contain" alt="Watermark Logo">
        </div>
        
        <!-- Top Header Banner -->
        <div class="h-[64px] bg-[#005B2B] px-5 flex items-center justify-between border-b-2 border-[#fe932c]/80 shrink-0 z-10">
            <div class="flex items-center gap-3">
                <!-- Mini Logo SVG -->
                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-white p-1 shadow-sm shrink-0">
                    <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents(public_path('images/logo.svg'))) }}" class="w-8 h-8 object-contain" alt="Logo PPP">
                </div>
                <div class="text-left">
                    <h3 class="font-bold text-[11px] text-white tracking-wide leading-none">PARTAI PERSATUAN PEMBANGUNAN</h3>
                    <p class="text-[8px] font-bold text-[#fe932c] tracking-widest mt-1.5 uppercase">Kartu Tanda Anggota</p>
                </div>
            </div>
            <div class="text-right">
                <span class="text-[9px] font-extrabold text-white/40 tracking-wider uppercase">KTA Digital</span>
            </div>
        </div>

        <!-- Body -->
        <div class="flex-1 p-5 flex gap-5 items-center relative z-10">
            <!-- Member Photo on Left (With defensive container to hold layout) -->
            <div class="w-[95px] h-[126px] shrink-0 rounded-lg overflow-hidden border border-slate-200 bg-white shadow-md flex items-center justify-center z-10">
                <img src="{{ ($member->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($member->photo)) ? \Illuminate\Support\Facades\Storage::url($member->photo) : 'https://ui-avatars.com/api/?name='.urlencode($member->name).'&background=e2e8f0&color=475569' }}" 
                     class="w-full h-full object-cover" 
                     alt="Foto">
            </div>

            <!-- Details in Middle (With right padding to prevent overlap with QR code) -->
            <div class="flex-1 min-w-0 pr-[85px] flex flex-col gap-y-2.5 text-left py-0.5 z-10">
                <div class="text-left">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Nama Lengkap</span>
                    <span class="text-[15px] font-extrabold text-slate-800 leading-tight block truncate">{{ $member->name }}</span>
                </div>
                <div class="text-left">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Nomor NIK</span>
                    <span class="text-[12px] font-bold text-slate-700 font-mono tracking-wider block leading-none">{{ $member->nik ?? '-' }}</span>
                </div>
                <div class="text-left">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Wilayah Asal</span>
                    <span class="text-[11px] font-bold text-slate-600 leading-tight block truncate">
                        {{ $member->village?->name ?? '-' }}, <span class="text-[10px] text-slate-450 font-semibold font-sans">Kec. {{ $member->village?->district?->name ?? '-' }}</span>
                    </span>
                </div>
                <div class="text-left">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Status</span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wide leading-none {{ $member->status === 'Aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($member->status === 'Pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                        {{ $member->status }}
                    </span>
                </div>
            </div>

            <!-- QR Code on Bottom Right -->
            <div class="absolute bottom-5 right-5 shrink-0 bg-white p-1 rounded-xl border border-slate-200 shadow-md z-10">
                {!! QrCode::size(60)->margin(0)->generate($member->nik ?? $member->email ?? 'NO-NIK') !!}
            </div>
        </div>

        <!-- Bottom Decorative Bar -->
        <div class="h-2 bg-[#fe932c] w-full shrink-0 z-10"></div>
    </div>

    <!-- Actions Footer - Properly Sized and Centered Button -->
    <div class="w-full max-w-[450px] flex justify-center mt-6">
        <button id="btn-download-kta" class="inline-flex items-center justify-center gap-2 max-w-xs w-full py-2.5 px-4 bg-[#005B2B] hover:bg-green-800 text-white rounded-xl font-semibold text-sm shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-250 select-none cursor-pointer">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Simpan sebagai Gambar (PNG)
        </button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    document.getElementById('btn-download-kta').addEventListener('click', function () {
        const card = document.getElementById('kta-capture');
        
        html2canvas(card, {
            useCORS: true,
            scale: 3, // High-res 3x capture for crisp PNG
            backgroundColor: null,
            logging: false
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'KTA_{{ str_replace(" ", "_", $member->name) }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    });
</script>
