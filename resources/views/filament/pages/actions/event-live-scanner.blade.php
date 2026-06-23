<div x-data="{
    html5QrCode: null,
    lastScannedText: null,
    scanCooldown: false,
    playBeepSound: true,
    currentScannedNik: null,
    activeCameraId: null,

    logDebug(message) {
        console.log('[SCAN-DEBUG] ' + message);
    },

    beep() {
        if (!this.playBeepSound) return;
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            oscillator.type = 'sine';
            oscillator.frequency.setValueAtTime(880, audioCtx.currentTime);
            gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);

            oscillator.start();
            oscillator.stop(audioCtx.currentTime + 0.15);
        } catch (e) {
            this.logDebug('Gagal bunyi beep: ' + e.message);
        }
    },

    toggleSound() {
        this.playBeepSound = !this.playBeepSound;
        const btn = document.getElementById('soundToggleBtn');
        if (btn) {
            btn.innerText = this.playBeepSound ? 'AKTIF' : 'NONAKTIF';
            btn.className = this.playBeepSound ? 'font-bold text-emerald-600' : 'font-bold text-rose-500';
        }
    },

    onScanSuccess(decodedText) {
        if (this.scanCooldown || decodedText === this.lastScannedText) {
            return;
        }

        this.logDebug('Scan Berhasil: ' + decodedText);
        this.scanCooldown = true;
        this.lastScannedText = decodedText;
        this.currentScannedNik = decodedText;
        this.beep();

        this.fetchMemberDetails(decodedText);
    },

    fetchMemberDetails(nik) {
        const statusBullet = document.getElementById('status-bullet');
        const statusText = document.getElementById('status-text');
        const previewDiv = document.getElementById('member-preview');

        if (statusBullet) statusBullet.className = 'w-2.5 h-2.5 rounded-full bg-emerald-500 animate-spin border-t-2 border-emerald-800';
        if (statusText) {
            statusText.className = 'text-emerald-700 font-semibold';
            statusText.innerText = `Mengambil profil NIK: ${nik}...`;
        }

        this.logDebug('Mengirim AJAX untuk detail NIK...');
        fetch('{{ route("absen.event.scan-details", $event->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ nik: nik })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('NIK tidak ditemukan.');
            }
            return response.json();
        })
        .then(data => {
            const member = data.member;
            this.logDebug('Member ditemukan: ' + member.name);
            
            const nameEl = document.getElementById('member-name');
            const nikEl = document.getElementById('member-nik');
            const villageEl = document.getElementById('member-village');
            const statusSpan = document.getElementById('member-status');
            
            if (nameEl) nameEl.innerText = member.name;
            if (nikEl) nikEl.innerText = member.nik;
            if (villageEl) villageEl.innerText = member.village_name;
            
            if (statusSpan) {
                statusSpan.innerText = member.status;
                if (member.status === 'Aktif') {
                    statusSpan.className = 'inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-250';
                } else if (member.status === 'Pending') {
                    statusSpan.className = 'inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-250';
                } else {
                    statusSpan.className = 'inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-250';
                }
            }

            const avatarImg = document.getElementById('member-avatar');
            const avatarFallback = document.getElementById('member-avatar-fallback');
            if (avatarImg && avatarFallback) {
                if (member.photo_url) {
                    avatarImg.src = member.photo_url;
                    avatarImg.classList.remove('hidden');
                    avatarFallback.classList.add('hidden');
                } else {
                    avatarImg.src = '';
                    avatarImg.classList.add('hidden');
                    avatarFallback.classList.remove('hidden');
                }
            }

            if (previewDiv) previewDiv.classList.remove('hidden');

            const btnConfirm = document.getElementById('btnConfirm');
            if (statusBullet) statusBullet.className = 'w-2.5 h-2.5 rounded-full bg-blue-500';
            if (statusText) {
                statusText.className = 'text-blue-700 font-bold';
                statusText.innerText = data.already_scanned ? data.message : 'Profil Ditemukan';
            }

            if (btnConfirm) {
                if (data.already_scanned) {
                    btnConfirm.disabled = true;
                    btnConfirm.innerText = 'Sudah Presensi';
                    btnConfirm.className = 'w-full py-2 bg-slate-100 text-slate-400 font-semibold text-sm rounded-xl border border-slate-200 cursor-not-allowed';
                } else {
                    btnConfirm.disabled = false;
                    btnConfirm.innerText = 'Konfirmasi Hadir';
                    btnConfirm.className = 'w-full py-2 bg-[#005B2B] hover:bg-[#0B4A2D] text-white font-bold text-sm rounded-xl transition duration-200 shadow-md cursor-pointer';
                }
            }
        })
        .catch(error => {
            this.logDebug('Error fetch member: ' + error.message);
            if (statusBullet) statusBullet.className = 'w-2.5 h-2.5 rounded-full bg-rose-500';
            if (statusText) {
                statusText.className = 'text-rose-600 font-bold';
                statusText.innerText = 'Gagal: NIK ' + nik + ' tidak terdaftar.';
            }
            if (previewDiv) previewDiv.classList.add('hidden');
            
            setTimeout(() => {
                if (statusBullet) statusBullet.className = 'w-2.5 h-2.5 rounded-full bg-slate-300 animate-pulse';
                if (statusText) {
                    statusText.className = 'text-slate-500 font-semibold';
                    statusText.innerText = 'Siap memindai kode QR KTA berikutnya...';
                }
                this.scanCooldown = false;
                this.lastScannedText = null;
            }, 3000);
        });
    },

    commitAttendance() {
        if (!this.currentScannedNik) return;

        const btnConfirm = document.getElementById('btnConfirm');
        if (btnConfirm) {
            btnConfirm.disabled = true;
            btnConfirm.innerText = 'Menyimpan...';
        }

        const statusBullet = document.getElementById('status-bullet');
        const statusText = document.getElementById('status-text');
        this.logDebug('Mengonfirmasi kehadiran NIK: ' + this.currentScannedNik);

        fetch('{{ route("absen.event.scan-confirm", $event->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ nik: this.currentScannedNik })
        })
        .then(response => response.json())
        .then(data => {
            this.beep();
            this.logDebug('Kehadiran sukses dicatat!');
            if (statusBullet) statusBullet.className = 'w-2.5 h-2.5 rounded-full bg-emerald-500';
            if (statusText) {
                statusText.className = 'text-emerald-700 font-bold';
                statusText.innerText = data.message;
            }
            
            if (btnConfirm) {
                btnConfirm.innerText = 'Berhasil Terdaftar';
                btnConfirm.className = 'w-full py-2 bg-emerald-50 text-emerald-700 border border-emerald-250 font-bold text-sm rounded-xl cursor-not-allowed';
            }

            setTimeout(() => {
                const previewDiv = document.getElementById('member-preview');
                if (previewDiv) previewDiv.classList.add('hidden');
                if (statusBullet) statusBullet.className = 'w-2.5 h-2.5 rounded-full bg-slate-300 animate-pulse';
                if (statusText) {
                    statusText.className = 'text-slate-500 font-semibold';
                    statusText.innerText = 'Siap memindai kode QR KTA berikutnya...';
                }
                this.scanCooldown = false;
                this.lastScannedText = null;
                this.currentScannedNik = null;
            }, 3000);
        })
        .catch(error => {
            this.logDebug('Error konfirmasi: ' + error.message);
            if (statusBullet) statusBullet.className = 'w-2.5 h-2.5 rounded-full bg-rose-500';
            if (statusText) {
                statusText.className = 'text-rose-650 font-bold';
                statusText.innerText = 'Gagal menyimpan data kehadiran.';
            }
            if (btnConfirm) {
                btnConfirm.disabled = false;
                btnConfirm.innerText = 'Konfirmasi Hadir';
            }
        });
    },

    startScanner() {
        this.logDebug('startScanner: memulai...');
        const loadingOverlay = document.getElementById('camera-loading');
        const errorOverlay = document.getElementById('camera-error');
        
        if (loadingOverlay) loadingOverlay.classList.remove('hidden');
        if (errorOverlay) errorOverlay.classList.add('hidden');

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            this.logDebug('startScanner: mediaDevices.getUserMedia tidak didukung browser ini.');
            if (loadingOverlay) loadingOverlay.classList.add('hidden');
            this.showCameraError('Akses kamera diblokir atau tidak didukung. Gunakan HTTPS atau localhost.');
            return;
        }

        this.logDebug('startScanner: Meminta izin getUserMedia...');
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                this.logDebug('getUserMedia: Izin diberikan, menghentikan temp stream...');
                stream.getTracks().forEach(track => track.stop());
                
                try {
                    if (this.html5QrCode) {
                        this.logDebug('startScanner: Aliran lama terdeteksi, membersihkan...');
                        this.html5QrCode.stop().then(() => this.initializeScanner()).catch(() => this.initializeScanner());
                    } else {
                        this.initializeScanner();
                    }
                } catch (err) {
                    this.logDebug('startScanner catch: ' + err.message);
                    if (loadingOverlay) loadingOverlay.classList.add('hidden');
                    this.showCameraError('Inisialisasi gagal: ' + err.message);
                }
            })
            .catch(err => {
                this.logDebug('getUserMedia catch error: ' + err.name + ' - ' + err.message);
                if (loadingOverlay) loadingOverlay.classList.add('hidden');
                this.showCameraError('Izin kamera ditolak browser: ' + err.message);
            });
    },

    initializeScanner() {
        this.logDebug('initializeScanner: Menyiapkan instance Html5Qrcode...');
        const loadingOverlay = document.getElementById('camera-loading');
        const selectorWrapper = document.getElementById('camera-selector-wrapper');
        const selectEl = document.getElementById('camera-select');

        try {
            this.html5QrCode = new Html5Qrcode('reader');
            this.logDebug('initializeScanner: Instance dibuat. Mengambil daftar kamera...');

            Html5Qrcode.getCameras().then(devices => {
                this.logDebug('getCameras: Berhasil mendapatkan ' + devices.length + ' kamera.');
                if (loadingOverlay) loadingOverlay.classList.add('hidden');
                
                if (devices && devices.length > 0) {
                    if (selectEl) {
                        selectEl.innerHTML = '';
                        devices.forEach(device => {
                            const opt = document.createElement('option');
                            opt.value = device.id;
                            opt.innerText = device.label || `Kamera ${selectEl.children.length + 1}`;
                            selectEl.appendChild(opt);
                        });
                    }

                    if (devices.length > 1 && selectorWrapper) {
                        selectorWrapper.classList.remove('hidden');
                    }

                    let targetCameraId = devices[0].id;
                    const backCamera = devices.find(device => device.label.toLowerCase().includes('back') || device.label.toLowerCase().includes('rear'));
                    if (backCamera) {
                        this.logDebug('getCameras: Memilih kamera belakang/rear secara otomatis.');
                        targetCameraId = backCamera.id;
                        if (selectEl) selectEl.value = targetCameraId;
                    }

                    this.activeCameraId = targetCameraId;
                    this.startCamera(targetCameraId);
                } else {
                    this.logDebug('getCameras: Daftar kamera kosong.');
                    this.showCameraError('Tidak ada kamera yang terdeteksi pada perangkat.');
                }
            }).catch(err => {
                this.logDebug('getCameras catch error: ' + err.message);
                if (loadingOverlay) loadingOverlay.classList.add('hidden');
                this.showCameraError('Gagal mendeteksi kamera: ' + err.message);
            });
        } catch (err) {
            this.logDebug('initializeScanner catch error: ' + err.message);
            if (loadingOverlay) loadingOverlay.classList.add('hidden');
            this.showCameraError('Terjadi kesalahan inisialisasi: ' + err.message);
        }
    },

    startCamera(cameraId) {
        this.logDebug('startCamera: Memulai camera stream...');
        const loadingOverlay = document.getElementById('camera-loading');
        const errorOverlay = document.getElementById('camera-error');
        
        if (loadingOverlay) loadingOverlay.classList.remove('hidden');
        if (errorOverlay) errorOverlay.classList.add('hidden');

        try {
            this.html5QrCode.start(
                cameraId,
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                (decodedText, decodedResult) => this.onScanSuccess(decodedText),
                errorMessage => {}
            ).then(() => {
                this.logDebug('startCamera: Kamera berhasil aktif!');
                if (loadingOverlay) loadingOverlay.classList.add('hidden');
            }).catch(err => {
                this.logDebug('startCamera catch: ' + err);
                if (loadingOverlay) loadingOverlay.classList.add('hidden');
                this.showCameraError('Gagal memulai kamera: ' + err);
            });
        } catch (err) {
            this.logDebug('startCamera wrapper catch: ' + err.message);
            if (loadingOverlay) loadingOverlay.classList.add('hidden');
            this.showCameraError('Kamera error: ' + err.message);
        }
    },

    switchCamera(cameraId) {
        this.logDebug('switchCamera: Beralih ke kamera ID ' + cameraId);
        if (this.html5QrCode && this.html5QrCode.isScanning) {
            this.html5QrCode.stop().then(() => {
                this.activeCameraId = cameraId;
                this.startCamera(cameraId);
            });
        } else {
            this.activeCameraId = cameraId;
            this.startCamera(cameraId);
        }
    },

    showCameraError(msg) {
        this.logDebug('showCameraError: ' + msg);
        const errorOverlay = document.getElementById('camera-error');
        const errorMsg = document.getElementById('camera-error-message');
        if (errorOverlay) errorOverlay.classList.remove('hidden');
        if (errorMsg) errorMsg.innerText = msg;
    }
}" x-init="
    logDebug('Alpine x-init terpicu.');
    
    // Check if script is loaded, if not load it dynamically
    const runStart = () => {
        if (typeof Html5Qrcode !== 'undefined') {
            logDebug('Html5Qrcode library siap.');
            startScanner();
        } else {
            logDebug('Html5Qrcode belum dimuat. Memuat secara dinamis...');
            
            // Check if already injected in head to avoid double injections
            let existingScript = document.getElementById('html5-qrcode-script');
            if (existingScript) {
                logDebug('Script tag terdeteksi di head, menunggu load...');
                if (typeof Html5Qrcode !== 'undefined') {
                    startScanner();
                } else {
                    existingScript.addEventListener('load', () => {
                        logDebug('Script selesai dimuat.');
                        startScanner();
                    });
                }
                return;
            }

            const script = document.createElement('script');
            script.id = 'html5-qrcode-script';
            script.src = '{{ asset('js/html5-qrcode.min.js') }}';
            script.onload = () => {
                logDebug('Html5Qrcode berhasil dimuat secara dinamis!');
                startScanner();
            };
            script.onerror = () => {
                logDebug('Html5Qrcode gagal dimuat!');
                showCameraError('Gagal memuat library scanner.');
            };
            document.head.appendChild(script);
        }
    };
    runStart();

    // Listen for modal closed to stop camera stream
    document.addEventListener('close-modal', () => {
        logDebug('Menerima close-modal event.');
        if (html5QrCode && html5QrCode.isScanning) {
            logDebug('Menghentikan camera stream...');
            html5QrCode.stop().catch(err => logDebug('Stop camera error: ' + err));
        }
    });
" class="p-4 flex flex-col items-center justify-center text-center">

    <div class="mb-4 text-xs text-slate-500 max-w-sm">
        Pindai kode QR pada KTA fisik atau digital anggota. Sistem akan menampilkan profil anggota untuk dikonfirmasi.
    </div>

    <!-- Scanner Camera Wrapper with Loading State and min-height -->
    <div class="w-full max-w-md bg-slate-950 rounded-2xl overflow-hidden shadow-inner border-2 border-slate-800 relative mb-4 min-h-[280px] flex flex-col items-center justify-center text-slate-400">
        <!-- Camera Viewport -->
        <div id="reader" class="w-full h-full"></div>
        
        <!-- Loading overlay when camera is initializing -->
        <div id="camera-loading" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-950/90 gap-3 z-10">
            <svg class="animate-spin h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-xs font-semibold mt-2">Menginisialisasi Kamera...</span>
        </div>

        <!-- Error fallback -->
        <div id="camera-error" class="hidden absolute inset-0 p-6 flex flex-col items-center justify-center bg-slate-900 text-slate-300 text-xs text-center gap-3 z-10">
            <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span id="camera-error-message" class="leading-relaxed text-slate-400">Gagal mengakses kamera. Kamera tidak tersedia atau diblokir oleh browser.</span>
            
            <button type="button" @click="startScanner()" class="mt-2 px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 rounded-lg transition font-bold">
                Coba Lagi
            </button>
        </div>
    </div>

    <!-- Camera Selector Dropdown (Hidden if only 1 camera) -->
    <div id="camera-selector-wrapper" class="hidden w-full max-w-md mb-4 text-left">
        <label for="camera-select" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Pilih Kamera</label>
        <select id="camera-select" @change="switchCamera($event.target.value)" class="w-full text-xs bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-1 focus:ring-brand-green">
            <!-- Dynamic Options -->
        </select>
    </div>


    <!-- Live Scanner Logs & Feedback -->
    <div class="w-full max-w-md bg-slate-50 border border-slate-200 rounded-xl p-4 text-left space-y-4">
        <div>
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Hasil Pemindaian</h3>
            <div id="scan-status" class="text-sm font-semibold text-slate-500 flex items-center gap-2">
                <span id="status-bullet" class="w-2.5 h-2.5 rounded-full bg-slate-300 animate-pulse"></span>
                <span id="status-text">Siap memindai kode QR KTA...</span>
            </div>
        </div>

        <!-- Member Preview Card (Hidden by default) -->
        <div id="member-preview" class="hidden border border-slate-200 rounded-xl p-4 bg-white shadow-sm space-y-3">
            <div class="flex items-center gap-4">
                <div id="member-avatar-wrapper" class="w-16 h-16 rounded-full overflow-hidden border border-slate-200 bg-slate-100 flex-shrink-0 flex items-center justify-center">
                    <img id="member-avatar" src="" class="w-full h-full object-cover hidden">
                    <span id="member-avatar-fallback" class="text-slate-400 font-bold text-xs uppercase">PPP</span>
                </div>
                <div class="space-y-1">
                    <h4 id="member-name" class="text-sm font-bold text-slate-800">Nama Anggota</h4>
                    <p id="member-nik" class="text-xs font-mono text-slate-500">16 Digit NIK</p>
                    <div class="flex gap-2">
                        <span id="member-status" class="inline-block px-2 py-0.5 rounded text-[10px] font-bold">Status</span>
                        <span id="member-village" class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">Wilayah</span>
                    </div>
                </div>
            </div>

            <!-- Confirmation Action -->
            <div id="action-wrapper" class="pt-2 border-t border-slate-100 flex justify-end">
                <button type="button" id="btnConfirm" @click="commitAttendance()" 
                        class="w-full py-2 bg-[#005B2B] hover:bg-[#0B4A2D] text-white font-bold text-sm rounded-xl transition duration-200 shadow-md shadow-emerald-700/10 cursor-pointer">
                    Konfirmasi Hadir
                </button>
            </div>
        </div>

        <!-- Audio Beep Toggle -->
        <div class="pt-2 border-t border-slate-200 flex items-center justify-between text-xs text-slate-400">
            <span>Efek Suara Beep:</span>
            <button type="button" id="soundToggleBtn" @click="toggleSound()" class="font-bold text-emerald-600 hover:text-emerald-700">AKTIF</button>
        </div>
    </div>
</div>
