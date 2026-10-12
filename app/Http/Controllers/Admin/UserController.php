<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\MembuatProfilPengguna;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\ActivityLog;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * S1-06 (FR-14) — Admin mengelola akun empat peran.
 */
class UserController extends Controller
{
    use MembuatProfilPengguna;

    public function index(Request $request): View
    {
        $users = User::query()
            ->with(['mahasiswa', 'dosen', 'mitra'])
            ->when($request->filled('peran'), fn ($q) => $q->where('role', $request->string('peran')))
            ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->string('status')->toString() === 'aktif'))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%'.$request->string('q')->toString().'%';
                $q->where(fn ($qq) => $qq->where('name', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'filter' => $request->only(['peran', 'status', 'q']),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'programStudis' => ProgramStudi::query()->where('is_aktif', true)->orderBy('nama')->get(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => $data['role'],
                'is_active' => true,
            ]);

            $this->buatProfil($user, $data);

            return $user;
        });

        ActivityLog::catat('buat_pengguna', $user, 'Peran '.$user->role);

        return redirect()->route('admin.users.index')
            ->with('status', 'Pengguna '.$user->email.' berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user->load(['mahasiswa', 'dosen', 'mitra']),
            'programStudis' => ProgramStudi::query()->where('is_aktif', true)->orderBy('nama')->get(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($user, $data) {
            $user->name = $data['name'];
            $user->email = $data['email'];

            if (! empty($data['password'])) {
                $user->password = $data['password'];
            }

            // Peran sengaja tidak diubah dari sini agar relasi profil tetap konsisten.
            $user->save();

            $this->perbaruiProfil($user, $data);
        });

        ActivityLog::catat('ubah_pengguna', $user);

        return redirect()->route('admin.users.index')
            ->with('status', 'Data pengguna '.$user->email.' berhasil diperbarui.');
    }

    /**
     * Nonaktifkan / aktifkan akun (soft, bukan hapus fisik — FR-14).
     */
    public function toggleActive(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return back()->withErrors(['user' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.']);
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        ActivityLog::catat($user->is_active ? 'aktifkan_pengguna' : 'nonaktifkan_pengguna', $user);

        return back()->with('status', 'Akun '.$user->email.' kini '.($user->is_active ? 'aktif' : 'nonaktif').'.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return back()->withErrors(['user' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        if ($this->punyaRelasi($user)) {
            return back()->withErrors([
                'user' => 'Akun memiliki data terkait sehingga tidak boleh dihapus. Nonaktifkan akun ini.',
            ]);
        }

        $email = $user->email;
        $user->delete();

        ActivityLog::catat('hapus_pengguna', null, 'Menghapus akun '.$email);

        return redirect()->route('admin.users.index')->with('status', 'Pengguna '.$email.' dihapus.');
    }

    private function punyaRelasi(User $user): bool
    {
        if ($user->mahasiswa && $user->mahasiswa->pendaftarans()->exists()) {
            return true;
        }

        if ($user->mitra && $user->mitra->programs()->exists()) {
            return true;
        }

        if ($user->dosen && method_exists($user->dosen, 'pendaftarans') && $user->dosen->pendaftarans()->exists()) {
            return true;
        }

        return $user->notifikasis()->exists() || $user->activityLogs()->exists();
    }
}
