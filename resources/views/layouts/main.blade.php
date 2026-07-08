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
                        yellow: { DEFAULT: '#FFEB00' }
                    }
                }
            }
        }
    </script>

    <!-- AOS Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        .nav-link { transition: all 0.3s ease; }
        .nav-link:hover { background-color: #FFEB00; color: #0B1528; }
        .active-nav { background-color: #FFEB00; color: #0B1528; }
        .feature-card, .event-card, .athlete-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .feature-card:hover, .event-card:hover, .athlete-card:hover { transform: translateY(-5px); }

        /* Tambahan efek bayangan halus saat navbar menempel di atas */
        header.sticky { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3); }
    </style>
</head>
<body class="bg-navy font-sans text-navy antialiased min-h-screen flex flex-col overflow-x-hidden">

    <!-- Navbar Global (Sekarang Sticky) -->
    <header class="bg-navy py-6 px-8 md:px-16 flex items-center justify-between z-50 sticky top-0 w-full border-b border-white/10 transition-all duration-300">
        <div class="logo">
            <a href="{{ url('/') }}" class="flex items-center gap-3 text-white text-xl md:text-2xl font-black uppercase tracking-wide group">
                <img src="{{ asset('images/logo.png') }}" alt="Logo FTI" class="h-8 md:h-10 object-contain group-hover:scale-105 transition-transform">
                <span>FTI LAMPUNG</span>
            </a>
        </div>
        <nav class="hidden md:flex space-x-2 items-center">
            <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active-nav' : 'text-white' }} px-4 py-1.5 font-bold text-sm uppercase tracking-wider rounded-sm">BERANDA</a>

            <!-- Dropdown Event -->
            <div class="relative group flex items-center h-full py-2">
                <a href="{{ url('/event') }}" class="nav-link {{ request()->is('event*') ? 'active-nav' : 'text-white' }} px-4 py-1.5 font-bold text-sm uppercase tracking-wider rounded-sm inline-flex items-center">
                    EVENT
                    <svg class="w-4 h-4 ml-1 transform group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                </a>

                <!-- Kotak Dropdown -->
                <div class="absolute left-0 top-full mt-0 w-48 bg-navy border border-white/10 shadow-xl rounded-sm opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 z-50 overflow-hidden">
                    <a href="{{ url('/event/kejurnas') }}" class="block px-5 py-3 text-white hover:bg-yellow hover:text-navy font-bold text-sm uppercase tracking-wider transition-colors border-b border-white/10">
                        KEJURNAS
                    </a>
                    <a href="{{ url('/event/open') }}" class="block px-5 py-3 text-white hover:bg-yellow hover:text-navy font-bold text-sm uppercase tracking-wider transition-colors">
                        OPEN
                    </a>
                </div>
            </div>

            <a href="{{ url('/pelatihan') }}" class="nav-link {{ request()->is('pelatihan*') ? 'active-nav' : 'text-white' }} px-4 py-1.5 font-bold text-sm uppercase tracking-wider rounded-sm">PELATIHAN</a>

            <a href="{{ url('/ranking') }}" class="nav-link {{ request()->is('ranking*') ? 'active-nav' : 'text-white' }} px-4 py-1.5 font-bold text-sm uppercase tracking-wider rounded-sm">RANKING</a>

            <!-- Logika Login / Logout -->
            @guest
                <a href="{{ url('/login') }}" class="nav-link text-white px-4 py-1.5 font-bold text-sm uppercase tracking-wider rounded-sm">LOGIN</a>
            @else
                <form action="{{ url('/logout') }}" method="POST" class="inline m-0 p-0">
                    @csrf
                    <button type="submit" class="nav-link text-white px-4 py-1.5 font-bold text-sm uppercase tracking-wider rounded-sm cursor-pointer">LOGOUT</button>
                </form>
            @endguest
        </nav>
    </header>

    <!-- Konten Dinamis -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Global -->
    <footer class="bg-navy py-8 px-8 md:px-16 border-t border-white/10 mt-auto">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center text-[11px] font-black text-white uppercase tracking-wider">
            <div class="flex space-x-8 mb-4 md:mb-0">
                <a href="#" class="hover:text-yellow transition-colors">ABOUT US</a>
                <a href="#" class="hover:text-yellow transition-colors">CONTACT</a>
                <a href="#" class="hover:text-yellow transition-colors">PRIVACY POLICY</a>
            </div>
            <div class="opacity-80">
                © 2026 TRIATLON LAMPUNG PORTAL
            </div>
        </div>
    </footer>

    <!-- AOS Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ once: true, offset: 50, duration: 600, easing: 'ease-in-out' });
    </script>
</body>
</html>
