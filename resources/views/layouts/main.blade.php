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
            position: relative;
            transition: color 0.25s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -2px;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background-color: #0B1528;
            transition: width 0.25s ease;
        }

        .nav-link:hover::after,
        .nav-link.active-nav::after {
            width: 60%;
        }

        .nav-link.active-nav {
            color: #0B1528;
            font-weight: 900;
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

        header.sticky {
            box-shadow: 0 6px 20px -8px rgba(11, 21, 40, 0.15);
        }

        body.mobile-menu-open {
            overflow: hidden;
        }

        .mobile-nav-link {
            transition: all 0.2s ease;
        }
    </style>
</head>

<body class="bg-navy font-sans text-navy antialiased min-h-screen flex flex-col overflow-x-hidden">

    <!-- Navbar Global (Latar Belakang Cream) -->
    <header class="bg-cream/95 backdrop-blur-sm py-3.5 md:py-5 px-4 sm:px-8 md:px-16 flex items-center justify-between z-50 sticky top-0 w-full border-b border-navy/10 transition-all duration-300">
        <div class="logo">
            <a href="{{ url('/') }}" class="flex items-center gap-2 md:gap-3 text-navy text-base sm:text-xl md:text-2xl font-black uppercase tracking-wide group">
                <img src="{{ asset('images/logo.png') }}" alt="Logo FTI" class="h-8 md:h-11 object-contain group-hover:scale-105 transition-transform">
                <span>FTI LAMPUNG</span>
            </a>
        </div>

        <!-- Nav Desktop -->
        <nav class="hidden md:flex items-center gap-1">
            <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active-nav' : 'text-navy/70' }} px-4 py-2 font-bold text-sm uppercase tracking-wider">BERANDA</a>
            <a href="{{ url('/event') }}" class="nav-link {{ request()->is('event*') ? 'active-nav' : 'text-navy/70' }} px-4 py-2 font-bold text-sm uppercase tracking-wider">EVENT</a>
            <a href="{{ url('/pelatihan') }}" class="nav-link {{ request()->is('pelatihan*') ? 'active-nav' : 'text-navy/70' }} px-4 py-2 font-bold text-sm uppercase tracking-wider">PELATIHAN</a>
            <a href="{{ url('/ranking') }}" class="nav-link {{ request()->is('ranking*') ? 'active-nav' : 'text-navy/70' }} px-4 py-2 font-bold text-sm uppercase tracking-wider">RANKING</a>

            <div class="w-px h-6 bg-navy/15 mx-3"></div>

            <!-- Logika Login / Logout -->
            @guest
                <a href="{{ url('/login') }}" class="bg-navy text-yellow px-5 py-2.5 font-black text-xs uppercase tracking-wider rounded-lg hover:bg-navy/90 transition-colors shadow-sm">LOGIN</a>
            @else
                <form action="{{ url('/logout') }}" method="POST" class="inline m-0 p-0">
                    @csrf
                    <button type="submit" class="bg-white text-navy border border-navy/15 px-5 py-2.5 font-black text-xs uppercase tracking-wider rounded-lg hover:bg-navy hover:text-yellow hover:border-navy transition-colors cursor-pointer">LOGOUT</button>
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

    <!-- Panel Menu Mobile -->
    <div id="mobileMenu" class="hidden md:hidden fixed inset-0 z-40 bg-cream pt-6 px-6 pb-10 overflow-y-auto">
        <div class="flex items-center justify-between mb-8 pt-2">
            <a href="{{ url('/') }}" onclick="closeMobileMenu()" class="flex items-center gap-2 text-navy text-base font-black uppercase tracking-wide">
                <img src="{{ asset('images/logo.png') }}" alt="Logo FTI" class="h-8 object-contain">
                <span>FTI LAMPUNG</span>
            </a>
        </div>

        <nav class="flex flex-col gap-1.5">
            <a href="{{ url('/') }}" onclick="closeMobileMenu()" class="mobile-nav-link {{ request()->is('/') ? 'bg-navy text-yellow shadow-md' : 'text-navy hover:bg-white' }} px-5 py-4 font-black text-base uppercase tracking-wider rounded-xl">BERANDA</a>
            <a href="{{ url('/event') }}" onclick="closeMobileMenu()" class="mobile-nav-link {{ request()->is('event*') ? 'bg-navy text-yellow shadow-md' : 'text-navy hover:bg-white' }} px-5 py-4 font-black text-base uppercase tracking-wider rounded-xl">EVENT</a>
            <a href="{{ url('/pelatihan') }}" onclick="closeMobileMenu()" class="mobile-nav-link {{ request()->is('pelatihan*') ? 'bg-navy text-yellow shadow-md' : 'text-navy hover:bg-white' }} px-5 py-4 font-black text-base uppercase tracking-wider rounded-xl">PELATIHAN</a>
            <a href="{{ url('/ranking') }}" onclick="closeMobileMenu()" class="mobile-nav-link {{ request()->is('ranking*') ? 'bg-navy text-yellow shadow-md' : 'text-navy hover:bg-white' }} px-5 py-4 font-black text-base uppercase tracking-wider rounded-xl">RANKING</a>

            <div class="border-t border-navy/10 mt-4 pt-5">
                @guest
                    <a href="{{ url('/login') }}" onclick="closeMobileMenu()" class="flex items-center justify-center gap-2 bg-navy text-yellow px-4 py-4 font-black text-base uppercase tracking-wider rounded-xl hover:bg-navy/90 transition-colors shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        LOGIN
                    </a>
                @else
                    <form action="{{ url('/logout') }}" method="POST" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-white text-navy hover:bg-gray-100 px-4 py-4 font-black text-base uppercase tracking-wider rounded-xl cursor-pointer transition-colors border border-navy/10 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            LOGOUT
                        </button>
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
