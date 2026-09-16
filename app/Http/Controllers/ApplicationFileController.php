<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ApplicationFileController extends Controller
{
    public function show(Application $application)
    {
        if (! Gate::allows('view', $application)) {
            abort(403, 'Akses ditolak.');
        }

        if (! $application->file_path || ! Storage::disk('local')->exists($application->file_path)) {
            abort(404, 'Berkas lamaran tidak ditemukan.');
        }

        $mime = Storage::disk('local')->mimeType($application->file_path);
        $disposition = $mime === 'application/pdf' ? 'inline; filename="lamaran.pdf"' : 'inline';

        return response(Storage::disk('local')->get($application->file_path))
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', $disposition)
            ->header('Cache-Control', 'private, no-store')
            ->header('X-Content-Type-Options', 'nosniff');
    }
}