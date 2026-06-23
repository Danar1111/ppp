<div class="flex flex-col items-center justify-center p-6 text-center">
    <div class="mb-4 text-slate-500 text-sm leading-relaxed">
        Silakan scan QR Code di bawah ini menggunakan kamera handphone Anda untuk melakukan presensi kehadiran kegiatan.
    </div>

    <!-- The QR Code Image -->
    <div class="p-4 bg-white rounded-2xl shadow-premium border border-slate-100 mb-4">
        {!! QrCode::size(280)->margin(1)->generate(route('absen.event', $event->id)) !!}
    </div>

    <!-- The Route URL -->
    <div class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono select-all text-slate-600">
        {{ route('absen.event', $event->id) }}
    </div>

    <div class="mt-4 text-xs font-bold text-brand-green uppercase tracking-wider">
        Partai Persatuan Pembangunan (PPP)
    </div>
</div>
