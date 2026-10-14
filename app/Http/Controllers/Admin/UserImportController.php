<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\MembuatProfilPengguna;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ImportUsersRequest;
use App\Models\ActivityLog;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * S1-06 (FR-14) — Impor data awal pengguna dari berkas CSV.
 */
class UserImportController extends Controller
{
    use MembuatProfilPengguna;

    public const KOLOM = [
        'nama', 'email', 'role', 'nim', 'prodi', 'angkatan',
        'no_hp', 'nidn', 'nama_perusahaan', 'alamat', 'kontak',
    ];

    public const KATA_SANDI_AWAL = 'password';

    public function index(): View
    {
        return view('admin.import.index', [
            'kolom' => self::KOLOM,
            'kataSandiAwal' => self::KATA_SANDI_AWAL,
            'hasil' => session('impor_hasil'),
        ]);
    }

    public function template(): StreamedResponse
    {
        $contoh = [
            'Mahasiswa Contoh', 'mhs.contoh@skp.test', 'mahasiswa', '2024132999',
            'Teknik Informatika', '2024', '081200000000', '', '', '', '',
        ];

        return response()->streamDownload(function () use ($contoh) {
            $out = fopen('php://output', 'w');
            fputcsv($out, self::KOLOM);
            fputcsv($out, $contoh);
            fclose($out);
        }, 'template-impor-pengguna.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function store(ImportUsersRequest $request): RedirectResponse
    {
        $handle = fopen($request->file('file')->getRealPath(), 'r');
        $header = fgetcsv($handle);

        if ($header === false) {
            fclose($handle);

            return back()->with('error', 'Berkas CSV kosong. Tidak ada data yang diimpor.');
        }

        $header = array_map(fn ($kolom) => strtolower(trim((string) $kolom)), $header);

        foreach (['nama', 'email', 'role'] as $wajib) {
            if (! in_array($wajib, $header, true)) {
                fclose($handle);

                return back()->with('error', "Format header CSV tidak valid: kolom '{$wajib}' tidak ditemukan. Tidak ada data yang diimpor.");
            }
        }

        $berhasil = [];
        $gagal = [];
        $nomorBaris = 1;

        DB::transaction(function () use ($handle, $header, &$berhasil, &$gagal, &$nomorBaris) {
            while (($baris = fgetcsv($handle)) !== false) {
                $nomorBaris++;

                if (count(array_filter($baris, fn ($nilai) => trim((string) $nilai) !== '')) === 0) {
                    continue;
                }

                $data = array_combine($header, array_pad($baris, count($header), null));

                $validator = Validator::make($data, $this->aturanBaris());

                if ($validator->fails()) {
                    $gagal[] = [
                        'baris' => $nomorBaris,
                        'email' => $data['email'] ?? '-',
                        'alasan' => implode(' ', $validator->errors()->all()),
                    ];

                    continue;
                }

                $user = User::create([
                    'name' => $data['nama'],
                    'email' => $data['email'],
                    'password' => self::KATA_SANDI_AWAL,
                    'role' => $data['role'],
                    'is_active' => true,
                ]);

                $this->buatProfil($user, $data);

                $berhasil[] = $data['email'];
            }
        });

        fclose($handle);

        ActivityLog::catat('impor_pengguna', null, count($berhasil).' berhasil, '.count($gagal).' gagal');

        return redirect()->route('admin.import.index')->with('impor_hasil', [
            'berhasil' => $berhasil,
            'gagal' => $gagal,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function aturanBaris(): array
    {
        $prodi = ['required_if:role,mahasiswa,dosen', 'nullable', 'string', 'max:255'];

        if (ProgramStudi::query()->exists()) {
            $prodi[] = Rule::exists('program_studis', 'nama');
        }

        return [
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in([
                User::ROLE_MAHASISWA, User::ROLE_DOSEN, User::ROLE_MITRA, User::ROLE_ADMIN,
            ])],
            'nim' => ['required_if:role,mahasiswa', 'nullable', 'string', 'max:20', 'unique:mahasiswas,nim'],
            'prodi' => $prodi,
            'angkatan' => ['required_if:role,mahasiswa', 'nullable', 'integer', 'min:2000', 'max:2100'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'nidn' => ['required_if:role,dosen', 'nullable', 'string', 'max:20', 'unique:dosens,nidn'],
            'nama_perusahaan' => ['required_if:role,mitra', 'nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'kontak' => ['nullable', 'string', 'max:50'],
        ];
    }
}
