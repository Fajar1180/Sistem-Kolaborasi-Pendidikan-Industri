<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProgramStudiRequest;
use App\Models\ActivityLog;
use App\Models\ProgramStudi;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * S1-06 — CRUD data master program studi.
 */
class ProgramStudiController extends Controller
{
    public function index(): View
    {
        return view('admin.master.program-studi', [
            'items' => ProgramStudi::query()->orderBy('nama')->get(),
        ]);
    }

    public function store(ProgramStudiRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_aktif'] = $request->boolean('is_aktif', true);

        $item = ProgramStudi::create($data);

        ActivityLog::catat('buat_program_studi', $item, $item->nama);

        return back()->with('status', 'Program studi '.$item->nama.' ditambahkan.');
    }

    public function update(ProgramStudiRequest $request, ProgramStudi $programStudi): RedirectResponse
    {
        $data = $request->validated();
        $data['is_aktif'] = $request->boolean('is_aktif');

        $programStudi->update($data);

        ActivityLog::catat('ubah_program_studi', $programStudi, $programStudi->nama);

        return back()->with('status', 'Program studi diperbarui.');
    }

    public function destroy(ProgramStudi $programStudi): RedirectResponse
    {
        $nama = $programStudi->nama;
        $programStudi->delete();

        ActivityLog::catat('hapus_program_studi', null, $nama);

        return back()->with('status', 'Program studi '.$nama.' dihapus.');
    }
}
