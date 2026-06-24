<x-filament-panels::page>
    <style>
        @media print {
            /* Hide Filament panel elements */
            .fi-sidebar,
            .fi-topbar,
            .fi-header,
            .fi-breadcrumbs,
            .fi-sidebar-close-overlay,
            .fi-sidebar-open-button,
            .fi-theme-toggle {
                display: none !important;
            }
            
            /* Hide print-hide designated elements */
            .print-hide {
                display: none !important;
            }
            
            /* Reset body and container wrappers backgrounds/paddings */
            body, html {
                background: white !important;
                color: black !important;
            }
            
            .fi-main-ctn,
            .fi-main,
            .fi-page,
            main,
            .fi-layout {
                padding: 0 !important;
                margin: 0 !important;
                background: none !important;
                box-shadow: none !important;
                border: none !important;
            }

            /* Adjust the card preview for print output */
            .print-card {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 auto !important;
                max-width: 600px !important;
                background: white !important;
            }

            .print-qr-container {
                padding: 0 !important;
                border: none !important;
                box-shadow: none !important;
                margin-top: 24px !important;
                margin-bottom: 24px !important;
                background: white !important;
            }

            .print-qr-container svg {
                width: 440px !important;
                height: 440px !important;
                margin: 0 auto !important;
            }
        }
    </style>

    <div class="space-y-6">
        <!-- Configuration Form -->
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm !overflow-visible print-hide">
            <div class="mb-4">
                <h3 class="text-sm font-bold text-slate-800">Konfigurasi Kantor Sekretariat</h3>
                <p class="text-xs text-slate-500 mt-1">Pilih kantor sekretariat atau posko pemenangan untuk menghasilkan QR Code presensi harian.</p>
            </div>
            <form>
                {{ $this->form }}
            </form>
        </div>

        <!-- Generated QR Code Preview -->
        <div class="flex flex-col items-center justify-center p-8 bg-white rounded-2xl border border-slate-100 shadow-premium max-w-lg mx-auto text-center print-card">
            <h2 class="text-xl font-bold text-brand-green mb-1">{{ $this->getOfficeName() }}</h2>
            <p class="text-slate-400 text-xs mb-6 uppercase tracking-wider font-semibold">QR Code Absensi Harian Kantor</p>
            
            <p class="text-slate-500 text-sm mb-6 leading-relaxed">
                Tempelkan QR Code ini di meja resepsionis atau tampilkan pada tablet/layar kantor sekretariat. Anggota PPP dapat memindai kode ini menggunakan handphone mereka untuk melakukan presensi kehadiran harian secara mandiri (terproteksi session portal).
            </p>

            <!-- Large QR Code -->
            <div class="p-6 bg-white rounded-3xl border border-slate-200 shadow-ambient mb-6 print-qr-container">
                {!! QrCode::size(340)->margin(1)->generate($this->getQrUrl()) !!}
            </div>

            <!-- Public URL -->
            <div class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-600 mb-6 w-full select-all print-hide">
                {{ $this->getQrUrl() }}
            </div>

            <div class="flex flex-col sm:flex-row gap-3 w-full justify-center print-hide">
                <!-- Print Button -->
                <a href="javascript:window.print();" class="inline-flex items-center justify-center px-4 py-2.5 bg-slate-100 hover:bg-slate-150 text-slate-700 font-semibold text-sm rounded-xl transition duration-250 cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.821V21h10.56v-7.179m-10.56 0H3.36a1.44 1.44 0 01-1.44-1.44V5.76a1.44 1.44 0 011.44-1.44h17.28a1.44 1.44 0 011.44 1.44v6.621a1.44 1.44 0 01-1.44 1.44h-3.36M6.72 13.821h10.56M6.72 13.821V3.84a1.44 1.44 0 011.44-1.44h7.68a1.44 1.44 0 011.44 1.44v9.981"/>
                    </svg>
                    Cetak QR Code
                </a>

                <!-- Open Public Route Button -->
                <a href="{{ $this->getQrUrl() }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-md transition duration-250 cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                    </svg>
                    Buka Link Absen
                </a>
            </div>

            <div class="mt-6 text-xs text-slate-400 print-hide">
                Sistem Manajemen PPP - Dicetak secara digital
            </div>
        </div>
    </div>
</x-filament-panels::page>
