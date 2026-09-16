<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class SubmissionFileController extends Controller
{
    public function show(Submission $submission)
    {
        if (! Gate::allows('view', $submission)) {
            abort(403, 'Akses ditolak.');
        }

        if (! $submission->file_path || ! Storage::disk('local')->exists($submission->file_path)) {
            abort(404, 'Berkas hasil tidak ditemukan.');
        }

        $mime = Storage::disk('local')->mimeType($submission->file_path);
        $disposition = $mime === 'application/pdf' ? 'inline; filename="hasil.pdf"' : 'inline';

        return response(Storage::disk('local')->get($submission->file_path))
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', $disposition)
            ->header('Cache-Control', 'private, no-store')
            ->header('X-Content-Type-Options', 'nosniff');
    }
}