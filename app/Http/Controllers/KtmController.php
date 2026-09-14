<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KtmController extends Controller
{
    public function show(User $user)
    {
        $auth = Auth::user();

        if ($auth->role !== 'admin' && $auth->id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        if (!$user->ktm_path || !Storage::disk('local')->exists($user->ktm_path)) {
            abort(404, 'Bukti KTM tidak ditemukan.');
        }

        $mime = Storage::disk('local')->mimeType($user->ktm_path);
        $isPdf = $mime === 'application/pdf';

        return response(Storage::disk('local')->get($user->ktm_path))
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', $isPdf ? 'inline; filename="ktm.pdf"' : 'inline; filename="ktm.jpg"');
    }
}
