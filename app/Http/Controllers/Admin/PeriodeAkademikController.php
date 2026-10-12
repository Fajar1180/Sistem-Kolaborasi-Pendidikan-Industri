<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PeriodeAkademikRequest;
use App\Models\ActivityLog;
use App\Models\PeriodeAkademik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * S1-06 — CRUD data master periode akademik.
 */
class PeriodeAkademikController extends Controller
{
    public function index(): View
    {
        return view('admin.master.periode-akademik', [
            'items' => PeriodeAkademik::query()->orderByDesc('tanggal_mulai')->get(),
        ]);
    }

    public function store(PeriodeAkademikRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_aktif'] = $request->boolean('is_aktif');

        $item = DB::transaction(function () use ($data) {
            if ($data['is_aktif']) {
                PeriodeAkademik::query()->update(['is_aktif' => false]);
            }

            return PeriodeAkademik::create($data);
        });

        ActivityLog::catat('buat_periode_akademik', $item, $item->nama);

        return back()->with('status', 'Periode akademik '.$item->nama.' ditambahkan.');
    }

    public function update(PeriodeAkademikRequest $request, PeriodeAkademik $periodeAkademik): RedirectResponse
    {
        $data = $request->validated();
        $data['is_aktif'] = $request->boolean('is_aktif');

        DB::transaction(function () use ($periodeAkademik, $data) {
            if ($data['is_aktif']) {
                PeriodeAkademik::query()->whereKeyNot($periodeAkademik->id)->update(['is_aktif' => false]);
            }

            $periodeAkademik->update($data);
        });

        ActivityLog::catat('ubah_periode_akademik', $periodeAkademik, $periodeAkademik->nama);

        return back()->with('status', 'Periode akademik diperbarui.');
    }

    public function destroy(PeriodeAkademik $periodeAkademik): RedirectResponse
    {
        $nama = $periodeAkademik->nama;
        $periodeAkademik->delete();

        ActivityLog::catat('hapus_periode_akademik', null, $nama);

        return back()->with('status', 'Periode akademik '.$nama.' dihapus.');
    }
}
