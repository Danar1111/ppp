<div class="min-h-screen bg-slate-50">
    <main class="flex flex-col md:flex-row min-h-screen">
        <!-- Left Side: Branding & Visuals (Forest Green Gradient) -->
        <section class="w-full md:w-1/2 lg:w-3/5 bg-gradient-to-br from-[#005B2B] via-[#00401E] to-[#00210C] relative flex items-center justify-center p-8 lg:p-16 overflow-hidden">
            <!-- Subtle Geometric Pattern Overlay -->
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
            
            <div class="relative z-10 max-w-2xl text-center md:text-left flex flex-col items-center md:items-start gap-8 animate-fade-in-up">
                <!-- Brand Logo -->
                <div class="w-24 h-24 bg-white rounded-2xl flex items-center justify-center shadow-2xl p-4 transition-transform hover:rotate-3">
                    <img src="{{ asset('images/logo.svg') }}" class="w-full h-full object-contain" alt="Logo PPP">
                </div>
                
                <div class="space-y-4">
                    <h1 class="text-white font-extrabold text-3xl md:text-4xl lg:text-5xl leading-tight tracking-tight">
                        Sistem Informasi Anggota & Pengurus <span class="text-[#D97706]">DPC PPP Sumedang</span>
                    </h1>
                    <p class="text-slate-200 text-lg md:text-xl opacity-90 max-w-xl">
                        Akses layanan digital terpadu untuk koordinasi, pendaftaran anggota, dan manajemen administrasi Partai Persatuan Pembangunan.
                    </p>
                </div>
            </div>
            
            <!-- Footer Branding (Left Side) -->
            <div class="absolute bottom-8 left-8 lg:left-16 hidden md:block text-white/60 text-sm">
                © {{ date('Y') }} DPC PPP Sumedang. Hak Cipta Dilindungi.
            </div>
        </section>

        <!-- Right Side: Login Form -->
        <section class="w-full md:w-1/2 lg:w-2/5 bg-slate-50 flex items-center justify-center p-6 lg:p-12">
            <div class="w-full max-w-md space-y-8 animate-fade-in-up delay-100">
                <!-- Mobile Branding -->
                <div class="md:hidden flex flex-col items-center mb-8 gap-4">
                    <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center p-2 shadow-lg border border-[#D97706]/20">
                        <img src="{{ asset('images/logo.svg') }}" class="w-full h-full object-contain" alt="Logo PPP">
                    </div>
                    <h2 class="text-slate-800 text-2xl font-bold text-center">Sistem Informasi PPP</h2>
                </div>
                
                <div class="text-center md:text-left">
                    <h2 class="text-slate-900 text-2xl lg:text-3xl font-extrabold tracking-tight">Selamat Datang Kembali</h2>
                    <p class="text-slate-500 mt-2">Silakan masuk untuk mengelola data kepartaian.</p>
                </div>

                <!-- Filament Login Form -->
                {{ $this->content }}

                <!-- Divider -->
                <div class="relative flex items-center py-2">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink mx-4 text-slate-400 text-xs font-semibold uppercase tracking-wider">Atau</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <!-- Secondary Actions / Footer Links -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                    <a class="flex items-center gap-2 text-slate-500 hover:text-[#005B2B] font-semibold text-sm transition-colors group" href="{{ route('home') }}">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
                
                <!-- Footer (Mobile Only) -->
                <div class="md:hidden text-center text-slate-400 text-xs mt-12 pb-8">
                    © {{ date('Y') }} DPC PPP Sumedang. Hak Cipta Dilindungi.
                </div>
            </div>
        </section>
    </main>
</div>
