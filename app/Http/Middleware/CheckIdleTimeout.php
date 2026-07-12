<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIdleTimeout
{
    protected int $timeoutInSeconds = 1200; // 20 menit = 20 * 60

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivity = session('last_activity_time');

            if ($lastActivity && (time() - $lastActivity) > $this->timeoutInSeconds) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/login')->with('error', 'Sesi Anda telah berakhir karena tidak ada aktivitas selama 20 menit. Silakan login kembali.');
            }

            session(['last_activity_time' => time()]);
        }

        return $next($request);
    }
}
