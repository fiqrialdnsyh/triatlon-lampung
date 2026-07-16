<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FTI LAMPUNG')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&family=Oswald:wght@700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                        oswald: ['Oswald', 'sans-serif'],
                    },
                    colors: {
                        navy: '#0B1528',
                        cream: '#F4F1E1',
                        yellow: {
                            DEFAULT: '#FFEB00'
                        }
                    }
                }
            }
        }
    </script>

    <!-- AOS Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        .nav-link {
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: #FFEB00;
            color: #0B1528;
        }

        .active-nav {
            background-color: #FFEB00;
            color: #0B1528;
        }

        .feature-card,
        .event-card,
        .athlete-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover,
        .event-card:hover,
        .athlete-card:hover {
            transform: translateY(-5px);
        }

        /* Tambahan efek bayangan halus saat navbar menempel di atas */
        header.sticky {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        /* Cegah body scroll saat menu mobile terbuka */
        body.mobile-menu-open {
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-navy font-sans text-navy antialiased min-h-screen flex flex-col overflow-x-hidden">

    <!-- Navbar Global (Latar Belakang Cream) -->
    <header class="bg-cream py-4 md:py-6 px-4 sm:px-8 md:px-16 flex items-center justify-between z-50 sticky top-0 w-full border-b border-gray-200 transition-all duration-300">
        <div class="logo">
            <a href="{{ url('/') }}" class="flex items-center gap-2 md:gap-3 text-navy text-base sm:text-xl md:text-2xl font-black uppercase tracking-wide group">
                <img src="{{ asset('images/logo.png') }}" alt="Logo FTI" class="h-7 md:h-10 object-contain group-hover:scale-105 transition-transform">
                <span>FTI LAMPUNG</span>
            </a>
        </div>

        <!-- Nav Desktop (Teks Gelap) -->
        <nav class="hidden md:flex space-x-2 items-center">
            <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active-nav' : 'text-navy' }} px-4 py-1.5 font-bold text-sm uppercase tracking-wider rounded-sm">BERANDA</a>
            <a href="{{ url('/event') }}" class="nav-link {{ request()->is('event*') ? 'active-nav' : 'text-navy' }} px-4 py-1.5 font-bold text-sm uppercase tracking-wider rounded-sm">EVENT</a>
            <a href="{{ url('/pelatihan') }}" class="nav-link {{ request()->is('pelatihan*') ? 'active-nav' : 'text-navy' }} px-4 py-1.5 font-bold text-sm uppercase tracking-wider rounded-sm">PELATIHAN</a>
            <a href="{{ url('/campaign') }}" class="nav-link {{ request()->is('campaign*') ? 'active-nav' : 'text-navy' }} px-4 py-1.5 font-bold text-sm uppercase tracking-wider rounded-sm">CAMPAIGN</a>
            <a href="{{ url('/ranking') }}" class="nav-link {{ request()->is('ranking*') ? 'active-nav' : 'text-navy' }} px-4 py-1.5 font-bold text-sm uppercase tracking-wider rounded-sm">RANKING</a>

            <!-- Logika Login / Logout -->
            @guest
                <a href="{{ url('/login') }}" class="nav-link text-navy px-4 py-1.5 font-bold text-sm uppercase tracking-wider rounded-sm">LOGIN</a>
            @else
                <form action="{{ url('/logout') }}" method="POST" class="inline m-0 p-0">
                    @csrf
                    <button type="submit" class="nav-link text-navy px-4 py-1.5 font-bold text-sm uppercase tracking-wider rounded-sm cursor-pointer">LOGOUT</button>
                </form>
            @endguest
        </nav>

        <!-- Tombol Hamburger (Mobile / Tablet) -->
        <button type="button" onclick="toggleMobileMenu()" class="md:hidden text-navy p-2 -mr-2 relative z-50" aria-label="Buka menu navigasi" aria-controls="mobileMenu" aria-expanded="false" id="mobileMenuBtn">
            <svg id="iconMenuOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <svg id="iconMenuClose" class="w-7 h-7 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </header>

    <!-- Panel Menu Mobile (Latar Belakang Cream) -->
    <div id="mobileMenu" class="hidden md:hidden fixed inset-0 z-40 bg-cream pt-24 px-6 pb-10 overflow-y-auto">
        <nav class="flex flex-col gap-1">
            <a href="{{ url('/') }}" onclick="closeMobileMenu()" class="{{ request()->is('/') ? 'bg-yellow text-navy' : 'text-navy hover:bg-white/50' }} px-4 py-3.5 font-bold text-base uppercase tracking-wider rounded-lg transition-colors">BERANDA</a>
            <a href="{{ url('/event') }}" onclick="closeMobileMenu()" class="{{ request()->is('event*') ? 'bg-yellow text-navy' : 'text-navy hover:bg-white/50' }} px-4 py-3.5 font-bold text-base uppercase tracking-wider rounded-lg transition-colors">EVENT</a>
            <a href="{{ url('/pelatihan') }}" onclick="closeMobileMenu()" class="{{ request()->is('pelatihan*') ? 'bg-yellow text-navy' : 'text-navy hover:bg-white/50' }} px-4 py-3.5 font-bold text-base uppercase tracking-wider rounded-lg transition-colors">PELATIHAN</a>
            <a href="{{ url('/campaign') }}" onclick="closeMobileMenu()" class="{{ request()->is('campaign*') ? 'bg-yellow text-navy' : 'text-navy hover:bg-white/50' }} px-4 py-3.5 font-bold text-base uppercase tracking-wider rounded-lg transition-colors">CAMPAIGN</a>
            <a href="{{ url('/ranking') }}" onclick="closeMobileMenu()" class="{{ request()->is('ranking*') ? 'bg-yellow text-navy' : 'text-navy hover:bg-white/50' }} px-4 py-3.5 font-bold text-base uppercase tracking-wider rounded-lg transition-colors">RANKING</a>

            <div class="border-t border-gray-300 mt-4 pt-4">
                @guest
                    <a href="{{ url('/login') }}" onclick="closeMobileMenu()" class="block text-center bg-navy text-yellow px-4 py-3.5 font-black text-base uppercase tracking-wider rounded-lg hover:bg-navy/90 transition-colors">LOGIN</a>
                @else
                    <form action="{{ url('/logout') }}" method="POST" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="w-full text-center bg-white text-navy hover:bg-gray-100 px-4 py-3.5 font-black text-base uppercase tracking-wider rounded-lg cursor-pointer transition-colors border border-gray-200">LOGOUT</button>
                    </form>
                @endguest
            </div>
        </nav>
    </div>

    <!-- Konten Dinamis -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Global -->
    <footer class="bg-navy py-8 px-4 sm:px-8 md:px-16 border-t border-white/10 mt-auto">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4 text-[11px] font-black text-white uppercase tracking-wider">
            <div class="flex flex-wrap justify-center items-center gap-5">
                <a href="https://www.instagram.com/triathlonlampung?igsh=MWJ3cGQ0ZWpwMmxqMw==" target="_blank" rel="noopener" class="hover:text-yellow transition-colors" title="Instagram">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                    </svg>
                </a>
                <a href="mailto:federasitriathlonlampung@gmail.com" class="hover:text-yellow transition-colors" title="Email">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </a>
            </div>
            <div class="opacity-80 text-center">
                © 2026 TRIATLON LAMPUNG PORTAL
            </div>
        </div>
    </footer>

    <!-- AOS Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            offset: 50,
            duration: 600,
            easing: 'ease-in-out'
        });

        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const isOpen = !menu.classList.contains('hidden');
            isOpen ? closeMobileMenu() : openMobileMenu();
        }

        function openMobileMenu() {
            document.getElementById('mobileMenu').classList.remove('hidden');
            document.getElementById('iconMenuOpen').classList.add('hidden');
            document.getElementById('iconMenuClose').classList.remove('hidden');
            document.getElementById('mobileMenuBtn').setAttribute('aria-expanded', 'true');
            document.body.classList.add('mobile-menu-open');
        }

        function closeMobileMenu() {
            document.getElementById('mobileMenu').classList.add('hidden');
            document.getElementById('iconMenuOpen').classList.remove('hidden');
            document.getElementById('iconMenuClose').classList.add('hidden');
            document.getElementById('mobileMenuBtn').setAttribute('aria-expanded', 'false');
            document.body.classList.remove('mobile-menu-open');
        }

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) closeMobileMenu();
        });
    </script>
</body>

</html>
