<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SecureFileController extends Controller
{
    public function show(Request $request, $path)
    {
        // Hanya user yang login yang boleh akses file sensitif
        if (!auth()->check()) {
            abort(403, 'Silakan login untuk mengakses dokumen ini.');
        }

        if (!Storage::disk('private')->exists($path)) {
            abort(404);
        }

        $user = auth()->user();

        // Admin boleh akses semua file
        if ($user->isAdmin()) {
            return Storage::disk('private')->response($path);
        }

        // Non-admin: verifikasi kepemilikan file berdasarkan pola path
        // Contoh: event/pembayaran/xxx_tf_{user_id}_xxx.jpg → cek user_id di nama file
        if (!str_contains($path, '_' . $user->id . '_') && !str_contains($path, '_' . $user->id . '.')) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        return Storage::disk('private')->response($path);
    }
}
