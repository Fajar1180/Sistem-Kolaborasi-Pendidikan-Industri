<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * S1-09 (NFR-08) — Pengunduhan berkas pengajuan dari storage privat.
 * Akses dibatasi pihak berwenang melalui DokumenPolicy
 * (pemilik, dosen pembimbing terkait, mitra penyelenggara, admin).
 */
class DokumenController extends Controller
{
    use AuthorizesRequests;

    public function unduh(Request $request, Dokumen $dokumen): StreamedResponse
    {
        $this->authorize('view', $dokumen);

        $disk = Storage::disk('local');

        abort_unless($disk->exists($dokumen->path), 404, 'Berkas tidak ditemukan.');

        return $disk->download($dokumen->path, $dokumen->nama);
    }
}
