<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Cukup satu saja di sini
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Logika untuk proses Login
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Jika email dan password cocok dengan database
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect ke halaman beranda setelah berhasil login
            return redirect()->intended('/');
        }

        // Jika salah, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau kata sandi tidak sesuai dengan data kami.',
        ])->onlyInput('email');
    }

    // Logika untuk proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // Mengarahkan pengguna ke halaman login Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required',
        ], [
            'terms.required' => 'Anda harus menyetujui Syarat & Ketentuan terlebih dahulu.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan login atau gunakan email lain.',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'user', // default role untuk pendaftar umum
        ]);

        auth()->login($user);

        return redirect('/')->with('success', 'Akun berhasil dibuat. Selamat datang, ' . $user->name . '!');
    }

    // Menangani kembalian data dari Google
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari user berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Jika belum terdaftar, buat akun otomatis
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(16)),
                    'role' => 'user', // default role
                ]);
            } else {
                // Jika sudah ada emailnya tapi google_id kosong, perbarui datanya
                $user->update([
                    'google_id' => $googleUser->getId()
                ]);
            }

            // Daftarkan session login
            Auth::login($user, true);

            return redirect()->intended('/');
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['email' => 'Gagal login menggunakan Google. Silakan coba lagi.']);
        }
    }
}
