<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FTI LAMPUNG</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&family=Oswald:wght@700&display=swap"
        rel="stylesheet">

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
                            DEFAULT: '#FFEB00',
                        }
                    }
                }
            }
        }
    </script>
</head>

<body
    class="bg-navy font-sans text-navy antialiased min-h-screen flex items-center justify-center relative overflow-hidden">

    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/beranda.jpeg') }}" alt="Background" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-navy/70 mix-blend-multiply"></div>
    </div>

    <a href="{{ url('/') }}"
        class="absolute top-8 left-8 z-20 inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        KEMBALI KE BERANDA
    </a>

    <div class="relative z-10 w-full max-w-md px-6">
        <div class="bg-cream rounded-[2rem] p-8 md:p-10 shadow-2xl">

            <div class="text-center mb-8">
                <h1 class="font-oswald text-3xl font-bold uppercase text-navy tracking-wide mb-2">SELAMAT DATANG</h1>
                <p class="text-sm font-semibold text-navy/70">Silakan masuk ke akun Anda untuk melanjutkan.</p>
            </div>

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl font-bold text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <a href="{{ route('google.login') }}"
                class="w-full flex items-center justify-center bg-white border border-gray-300 rounded-xl px-4 py-3.5 text-sm font-bold text-navy hover:bg-gray-50 hover:border-gray-400 transition-all shadow-sm mb-6">
                <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                        fill="#4285F4" />
                    <path
                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                        fill="#34A853" />
                    <path
                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                        fill="#FBBC05" />
                    <path
                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                        fill="#EA4335" />
                </svg>
                MASUK DENGAN GOOGLE
            </a>

            <div class="flex items-center mb-6">
                <div class="flex-grow border-t border-gray-300"></div>
                <span class="px-4 text-[10px] font-black text-navy/40 uppercase tracking-widest">Atau</span>
                <div class="flex-grow border-t border-gray-300"></div>
            </div>

            @error('email')
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-xs font-bold"
                    role="alert">
                    <span class="block sm:inline">{{ $message }}</span>
                </div>
            @enderror

            <form action="{{ url('/login') }}" method="POST" class="space-y-5">
                @csrf <div>
                    <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3.5 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm"
                        placeholder="nama@email.com" required>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-bold text-navy uppercase tracking-wide">Kata Sandi</label>
                        <a href="#"
                            class="text-[11px] font-bold text-navy/60 hover:text-navy transition-colors">Lupa Sandi?</a>
                    </div>
                    <input type="password" name="password"
                        class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3.5 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm"
                        placeholder="••••••••" required>
                </div>

                <div class="flex items-center">
                    <label class="flex items-center space-x-2 cursor-pointer group">
                        <input type="checkbox" name="remember"
                            class="w-4 h-4 text-navy focus:ring-yellow border-gray-300 rounded cursor-pointer">
                        <span class="text-xs font-bold text-navy/70 group-hover:text-navy transition-colors">Ingat
                            Saya</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-yellow text-navy py-4 rounded-xl font-black text-sm md:text-base uppercase tracking-wider hover:bg-navy hover:text-yellow transition-colors shadow-lg transform hover:-translate-y-1">
                        MASUK SEKARANG
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center">
                <p class="text-xs font-semibold text-navy/70">
                    Belum punya akun?
                    <a href="{{ url('/register') }}"
                        class="font-black text-navy uppercase hover:text-yellow transition-colors ml-1 border-b border-navy hover:border-yellow">Daftar
                        Di Sini</a>
                </p>
            </div>

        </div>
    </div>

</body>

</html>
