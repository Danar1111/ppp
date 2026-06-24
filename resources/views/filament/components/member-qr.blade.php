<div class="flex flex-col items-center justify-center p-4 bg-white rounded-xl border border-slate-100 shadow-inner w-full max-w-[220px] mx-auto">
    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3">QR NIK Anggota</span>
    <div class="bg-white p-1 rounded-lg border border-slate-100 shadow-sm">
        {!! QrCode::size(120)->margin(0)->generate($getRecord()->nik ?? $getRecord()->email ?? 'NO-NIK') !!}
    </div>
    <p class="text-[10px] font-mono font-semibold text-slate-600 mt-3 tracking-wider">{{ $getRecord()->nik ?? 'Belum diisi' }}</p>
</div>
