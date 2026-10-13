<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mitra\ProfilMitraRequest;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * S1-07 (FR-11) — Mitra mengelola profil perusahaannya sendiri.
 */
class ProfilMitraController extends Controller
{
    public function edit(Request $request): View
    {
        return view('mitra.profil.edit', ['mitra' => $request->user()->mitra]);
    }

    public function update(ProfilMitraRequest $request): RedirectResponse
    {
        $mitra = $request->user()->mitra;
        $data = $request->safe()->except('logo');

        if ($request->hasFile('logo')) {
            if ($mitra->logo_path) {
                Storage::disk('public')->delete($mitra->logo_path);
            }

            $data['logo_path'] = $request->file('logo')->store('logo-mitra', 'public');
        }

        $mitra->update($data);

        ActivityLog::catat('ubah_profil_mitra', $mitra, 'Profil '.$mitra->nama_perusahaan);

        return redirect()->route('mitra.profil.edit')
            ->with('status', 'Profil perusahaan berhasil diperbarui.');
    }
}
