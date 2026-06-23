<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi Kegiatan - {{ $event->name }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            green: '#005B2B',
                            greenDark: '#0B4A2D',
                            gold: '#D97706',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 font-sans text-slate-800">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
        <!-- Brand Header -->
        <div class="bg-gradient-to-br from-brand-greenDark to-brand-green p-6 text-center text-white relative">
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
            <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-3 border border-white/20">
                <span class="text-xl font-bold tracking-wider text-white">PPP</span>
            </div>
            <h1 class="text-lg font-bold">SIP - PRESENSI KEGIATAN</h1>
            <p class="text-xs text-brand-gold font-semibold uppercase tracking-wider mt-1">{{ $event->name }}</p>
        </div>

        <div class="p-6">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-5 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('info') || $exists)
                <div class="mb-5 bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-xl">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-amber-800">
                                {{ session('info') ?? 'Anda sudah melakukan presensi untuk kegiatan ini.' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if($errors->has('location'))
                <div class="mb-5 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-rose-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-rose-800">{{ $errors->first('location') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Location Status Alert -->
            <div id="locationAlert" class="mb-5 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-xl">
                <div class="flex">
                    <div class="flex-shrink-0" id="locationIcon">
                        <svg class="animate-spin h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-blue-850" id="locationText">Mendapatkan koordinat GPS keamanan Anda...</p>
                    </div>
                </div>
            </div>

            <!-- Member Identity Preview Card -->
            <div class="mb-6 bg-slate-50 border border-slate-150 rounded-2xl p-4 flex items-center gap-4 text-left">
                <div class="w-14 h-14 rounded-full overflow-hidden bg-slate-200 border border-brand-green/20 flex-shrink-0 flex items-center justify-center">
                    @if($member->photo && file_exists(public_path('storage/' . $member->photo)))
                        <img src="{{ asset('storage/' . $member->photo) }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                    @endif
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">{{ $member->name }}</h3>
                    <p class="text-xs font-mono text-slate-500">{{ $member->nik }}</p>
                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-250">{{ $member->status }}</span>
                </div>
            </div>

            <!-- Attendance Form -->
            <form id="attendanceForm" action="{{ route('absen.event.submit', $event->id) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                <div class="text-xs text-slate-400 text-center leading-relaxed">
                    * Sistem akan memverifikasi lokasi GPS Anda untuk memastikan Anda berada dalam radius 50 meter dari lokasi kegiatan.
                </div>

                @if(!$exists)
                    <button type="submit" id="submitBtn" disabled
                        class="w-full py-3 bg-slate-300 text-slate-500 font-semibold rounded-xl transition duration-200 cursor-not-allowed">
                        Mengunci Lokasi GPS...
                    </button>
                @else
                    <button type="button" disabled
                        class="w-full py-3 bg-slate-100 text-slate-400 font-semibold rounded-xl border border-slate-200 cursor-not-allowed">
                        Presensi Sudah Tercatat
                    </button>
                @endif
            </form>
        </div>
    </div>

    <!-- Geolocation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');
            const locationAlert = document.getElementById('locationAlert');
            const locationIcon = document.getElementById('locationIcon');
            const locationText = document.getElementById('locationText');
            const submitBtn = document.getElementById('submitBtn');

            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        // Success
                        latitudeInput.value = position.coords.latitude;
                        longitudeInput.value = position.coords.longitude;

                        locationAlert.classList.remove('bg-blue-50', 'border-blue-500');
                        locationAlert.classList.add('bg-emerald-50', 'border-emerald-500');
                        locationIcon.innerHTML = `
                            <svg class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                        `;
                        locationText.innerText = "Lokasi GPS berhasil terkunci!";
                        locationText.className = "text-sm font-semibold text-emerald-800";

                        // Enable submit button if it exists
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerText = "Kirim Kehadiran (Presensi)";
                            submitBtn.className = "w-full py-3 bg-brand-green hover:bg-brand-greenDark text-white font-semibold rounded-xl shadow-lg hover:shadow-brand-green/20 transition duration-200 cursor-pointer";
                        }
                    },
                    function (error) {
                        // Error
                        locationAlert.classList.remove('bg-blue-50', 'border-blue-500');
                        locationAlert.classList.add('bg-rose-50', 'border-rose-500');
                        locationIcon.innerHTML = `
                            <svg class="h-5 w-5 text-rose-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        `;
                        locationText.innerText = "Gagal memverifikasi lokasi: Harap izinkan akses lokasi (GPS) browser.";
                        locationText.className = "text-sm font-semibold text-rose-800";
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            } else {
                locationAlert.classList.remove('bg-blue-50', 'border-blue-500');
                locationAlert.classList.add('bg-rose-50', 'border-rose-500');
                locationText.innerText = "Browser tidak mendukung fitur GPS Geolocation.";
                locationText.className = "text-sm font-semibold text-rose-800";
            }
        });
    </script>
</body>
</html>
