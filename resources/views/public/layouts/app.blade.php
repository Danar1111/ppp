<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Sistem Informasi & Portal Anggota Partai PPP')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/svg+xml">
    
    <!-- Meta SEO -->
    <meta name="description" content="Portal Resmi Partai Persatuan Pembangunan (PPP) Wilayah. Akses keanggotaan, cetak KTA digital, dan pantau kegiatan partai secara dinamis.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js for interactive elements (Dropdowns, mobile menus, AJAX NIK search) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col antialiased">

    <!-- Header Navigation -->
    <header class="sticky top-0 z-50 bg-[#005B2B]/95 backdrop-blur-md border-b border-[#0B4A2D] text-white shadow-md" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo & Brand -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <!-- Custom Stylized Party Logo (Kaaba Emblem) -->
                        <div class="relative shrink-0 group-hover:scale-105 transition duration-200">
                            <img src="{{ asset('images/logo.svg') }}" class="h-10 w-10 object-contain" alt="Logo PPP">
                        </div>
                        <div class="flex flex-col">
                            <span class="font-extrabold text-lg tracking-tight uppercase leading-none">DPC PPP</span>
                            <span class="text-[10px] text-[#D97706] font-bold uppercase tracking-wider">Kabupaten/Kota</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-semibold">
                    <a href="{{ route('home') }}" class="hover:text-[#D97706] transition {{ request()->routeIs('home') ? 'text-[#D97706]' : '' }}">Beranda</a>
                    <a href="{{ route('public.berita.index') }}" class="hover:text-[#D97706] transition {{ request()->routeIs('public.berita.*') ? 'text-[#D97706]' : '' }}">Berita</a>
                    <a href="{{ route('public.struktur') }}" class="hover:text-[#D97706] transition {{ request()->routeIs('public.struktur') ? 'text-[#D97706]' : '' }}">Struktur Pengurus</a>
                </nav>

                <!-- Desktop CTA -->
                <div class="hidden md:flex items-center gap-4">
                    @if(auth()->check())
                        @if(auth()->user()->hasAnyRole([
                            'Super Admin',
                            'Admin Pusat (DPP)',
                            'Admin Wilayah (DPW)',
                            'Admin Cabang (DPC)',
                            'Admin Kecamatan (PAC)'
                        ]))
                            <a href="/admin" class="px-5 py-2.5 bg-[#D97706] hover:bg-[#B45309] text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition duration-200">
                                Dashboard Admin
                            </a>
                        @else
                            <a href="/portal" class="px-5 py-2.5 bg-[#D97706] hover:bg-[#B45309] text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition duration-200">
                                Portal Anggota
                            </a>
                        @endif
                    @else
                        <a href="/portal/login" class="px-5 py-2.5 border border-[#D97706] hover:bg-[#D97706]/10 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-200">
                            Login Anggota
                        </a>
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden">
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="text-white hover:text-[#D97706] focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" x-show="!mobileMenuOpen" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" x-show="mobileMenuOpen" style="display: none;" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden bg-[#004D24] border-t border-[#0B4A2D]" x-show="mobileMenuOpen" style="display: none;" @click.away="mobileMenuOpen = false">
            <div class="px-2 pt-3 pb-4 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg font-medium text-white hover:bg-[#005B2B] hover:text-[#D97706] transition">Beranda</a>
                <a href="{{ route('public.berita.index') }}" class="block px-3 py-2 rounded-lg font-medium text-white hover:bg-[#005B2B] hover:text-[#D97706] transition">Berita</a>
                <a href="{{ route('public.struktur') }}" class="block px-3 py-2 rounded-lg font-medium text-white hover:bg-[#005B2B] hover:text-[#D97706] transition">Struktur Pengurus</a>
                <div class="pt-4 pb-2 border-t border-[#0B4A2D] px-3">
                    @if(auth()->check())
                        @if(auth()->user()->hasAnyRole([
                            'Super Admin',
                            'Admin Pusat (DPP)',
                            'Admin Wilayah (DPW)',
                            'Admin Cabang (DPC)',
                            'Admin Kecamatan (PAC)'
                        ]))
                            <a href="/admin" class="block w-full text-center py-2.5 bg-[#D97706] hover:bg-[#B45309] text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-200">
                                Dashboard Admin
                            </a>
                        @else
                            <a href="/portal" class="block w-full text-center py-2.5 bg-[#D97706] hover:bg-[#B45309] text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-200">
                                Portal Anggota
                            </a>
                        @endif
                    @else
                        <a href="/portal/login" class="block w-full text-center py-2.5 border border-[#D97706] hover:bg-[#D97706]/10 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-200">
                            Login Anggota
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Branding -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="shrink-0">
                            <img src="{{ asset('images/logo.svg') }}" class="h-8 w-8 object-contain" alt="Logo PPP">
                        </div>
                        <span class="text-white font-extrabold text-md tracking-tight uppercase">PARTAI PERSATUAN PEMBANGUNAN</span>
                    </div>
                    <p class="text-xs leading-relaxed max-w-sm">
                        Membangun Indonesia yang berdaulat, adil, makmur, dan diridhoi Tuhan Yang Maha Esa, berlandaskan prinsip persatuan dan kebersamaan umat.
                    </p>
                </div>

                <!-- Links -->
                <div>
                    <h3 class="text-white font-bold text-sm tracking-wider uppercase mb-4">Navigasi</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="{{ route('public.berita.index') }}" class="hover:text-white transition">Berita & Informasi</a></li>
                        <li><a href="{{ route('public.struktur') }}" class="hover:text-white transition">Struktur Organisasi</a></li>
                        <li><a href="/portal/login" class="hover:text-white transition">Login Portal Anggota</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-white font-bold text-sm tracking-wider uppercase mb-4">Sekretariat</h3>
                    <p class="text-xs leading-relaxed">
                        DPC PPP Kabupaten/Kota<br>
                        Jl. Braga No. 10, Braga, Kec. Sumur Bandung,<br>
                        Kota Bandung, Jawa Barat 40111
                    </p>
                </div>
            </div>

            <div class="mt-12 pt-6 border-t border-slate-800 flex flex-col md:flex-row items-center justify-between text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} DPC Partai Persatuan Pembangunan. Hak Cipta Dilindungi.</p>
                <div class="flex gap-6 mt-4 md:mt-0">
                    <a href="/admin" class="hover:text-white transition">Administrasi (Staff)</a>
                    <a href="#" class="hover:text-white transition">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
