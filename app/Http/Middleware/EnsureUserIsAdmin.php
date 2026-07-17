<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        \Log::info('MIDDLEWARE ADMIN CHECK', [
            'url' => $request->fullUrl(),
            'auth_check' => auth()->check(),
            'user_id' => auth()->check() ? auth()->user()->id : null,
            'email' => auth()->check() ? auth()->user()->email : null,
            'role' => auth()->check() ? auth()->user()->role : null,
            'isAdmin' => auth()->check() ? auth()->user()->isAdmin() : null,
        ]);

        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Akses khusus administrator.');
        }

        return $next($request);
    }
}
