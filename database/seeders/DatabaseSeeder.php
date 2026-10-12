<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Bimbingan;
use App\Models\Dokumen;
use App\Models\Dosen;
use App\Models\Kegiatan;
use App\Models\Laporan;
use App\Models\Mahasiswa;
use App\Models\Mitra;
use App\Models\Notifikasi;
use App\Models\Pendaftaran;
use App\Models\PenilaianDosen;
use App\Models\PenilaianMitra;
use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Akun tetap (semua password: "password") ----
        $admin = User::create([
            'name' => 'Admin Sistem',
            'email' => 'admin@skp.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $dosenUsers = collect([
            ['name' => 'Nana Suryana, S.T., M.Kom.', 'email' => 'dosen1@skp.test', 'nidn' => '0401019001', 'prodi' => 'Teknik Informatika'],
            ['name' => 'Budi Santoso, M.Kom.', 'email' => 'dosen2@skp.test', 'nidn' => '0401019002', 'prodi' => 'Sistem Informasi'],
        ])->map(function (array $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => User::ROLE_DOSEN,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            return Dosen::create([
                'user_id' => $user->id,
                'nidn' => $data['nidn'],
                'prodi' => $data['prodi'],
            ]);
        });

        $mahasiswaList = collect(range(1, 5))->map(function (int $i) {
            $user = User::create([
                'name' => 'Mahasiswa '.$i,
                'email' => 'mhs'.$i.'@skp.test',
                'password' => Hash::make('password'),
                'role' => User::ROLE_MAHASISWA,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            return Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => '2024132007'.$i,
                'prodi' => $i % 2 === 0 ? 'Sistem Informasi' : 'Teknik Informatika',
                'angkatan' => 2024,
                'no_hp' => '08123456789'.$i,
            ]);
        });

        $mitraList = collect([
            ['name' => 'PT Teknologi Nusantara', 'email' => 'mitra1@skp.test', 'alamat' => 'Bandung', 'kontak' => '081200000001'],
            ['name' => 'CV Digital Kreatif', 'email' => 'mitra2@skp.test', 'alamat' => 'Jakarta', 'kontak' => '081200000002'],
        ])->map(function (array $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => User::ROLE_MITRA,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            return Mitra::create([
                'user_id' => $user->id,
                'nama_perusahaan' => $data['name'],
                'alamat' => $data['alamat'],
                'kontak' => $data['kontak'],
                'deskripsi' => 'Mitra industri '.$data['name'].'.',
            ]);
        });

        // ---- Program: 3 published + 1 draft ----
        $programs = collect([
            ['mitra' => 0, 'judul' => 'Magang Backend Developer', 'jenis' => 'magang', 'kuota' => 4, 'status' => 'published'],
            ['mitra' => 0, 'judul' => 'Kerja Praktik Data Analyst', 'jenis' => 'kerja_praktik', 'kuota' => 3, 'status' => 'published'],
            ['mitra' => 1, 'judul' => 'Proyek Kolaborasi UI/UX', 'jenis' => 'proyek_kolaborasi', 'kuota' => 2, 'status' => 'published'],
            ['mitra' => 1, 'judul' => 'Kunjungan Industri Tahunan', 'jenis' => 'kunjungan_industri', 'kuota' => 20, 'status' => 'draft'],
        ])->map(function (array $data) use ($mitraList) {
            return Program::create([
                'mitra_id' => $mitraList[$data['mitra']]->id,
                'judul' => $data['judul'],
                'jenis' => $data['jenis'],
                'deskripsi' => 'Deskripsi program '.$data['judul'].'.',
                'syarat' => 'Mahasiswa aktif, IPK minimal 3.00.',
                'kuota' => $data['kuota'],
                'tanggal_mulai' => now()->addWeek()->toDateString(),
                'tanggal_selesai' => now()->addMonths(3)->toDateString(),
                'status_publikasi' => $data['status'],
            ]);
        });

        // ---- Pengajuan tiap status utama ----
        $statuses = [
            Pendaftaran::STATUS_DRAF,
            Pendaftaran::STATUS_DIAJUKAN,
            Pendaftaran::STATUS_PERLU_REVISI,
            Pendaftaran::STATUS_DISETUJUI_DOSEN,
            Pendaftaran::STATUS_DITOLAK,
            Pendaftaran::STATUS_DITERIMA_MITRA,
            Pendaftaran::STATUS_BERJALAN,
            Pendaftaran::STATUS_SELESAI,
        ];

        $pendaftarans = collect($statuses)->map(function (string $status, int $i) use ($mahasiswaList, $programs, $dosenUsers) {
            $mahasiswa = $mahasiswaList[$i % $mahasiswaList->count()];
            $program = $programs[$i % 3];

            $attributes = [
                'mahasiswa_id' => $mahasiswa->id,
                'program_id' => $program->id,
                'status' => $status,
                'catatan' => 'Contoh pengajuan status '.$status.'.',
            ];

            if (in_array($status, [
                Pendaftaran::STATUS_DISETUJUI_DOSEN,
                Pendaftaran::STATUS_DITERIMA_MITRA,
                Pendaftaran::STATUS_BERJALAN,
                Pendaftaran::STATUS_SELESAI,
            ], true)) {
                $attributes['dosen_pembimbing_id'] = $dosenUsers[$i % $dosenUsers->count()]->id;
                $attributes['disetujui_dosen_at'] = now()->subDays(10);
            }

            if (in_array($status, [
                Pendaftaran::STATUS_DITERIMA_MITRA,
                Pendaftaran::STATUS_BERJALAN,
                Pendaftaran::STATUS_SELESAI,
            ], true)) {
                $attributes['diterima_mitra_at'] = now()->subDays(5);
            }

            if ($status === Pendaftaran::STATUS_SELESAI) {
                $attributes['selesai_at'] = now();
            }

            if (in_array($status, [
                Pendaftaran::STATUS_DIAJUKAN,
                Pendaftaran::STATUS_PERLU_REVISI,
                Pendaftaran::STATUS_DISETUJUI_DOSEN,
                Pendaftaran::STATUS_DITOLAK,
                Pendaftaran::STATUS_DITERIMA_MITRA,
                Pendaftaran::STATUS_BERJALAN,
                Pendaftaran::STATUS_SELESAI,
            ], true)) {
                $attributes['diajukan_at'] = now()->subDays(15);
            }

            return Pendaftaran::create($attributes);
        });

        // ---- Kelengkapan untuk pengajuan berjalan / selesai ----
        $aktif = $pendaftarans->whereIn('status', [
            Pendaftaran::STATUS_BERJALAN,
            Pendaftaran::STATUS_SELESAI,
            Pendaftaran::STATUS_DITERIMA_MITRA,
        ]);

        foreach ($aktif as $pendaftaran) {
            Kegiatan::factory()->count(3)->create(['pendaftaran_id' => $pendaftaran->id]);
            Dokumen::factory()->count(2)->create(['pendaftaran_id' => $pendaftaran->id]);
            Laporan::factory()->create(['pendaftaran_id' => $pendaftaran->id]);

            if ($pendaftaran->dosen_pembimbing_id) {
                Bimbingan::factory()->create([
                    'pendaftaran_id' => $pendaftaran->id,
                    'dosen_id' => $pendaftaran->dosen_pembimbing_id,
                ]);
            }

            Notifikasi::factory()->create(['user_id' => $pendaftaran->mahasiswa->user_id]);
            ActivityLog::factory()->create(['user_id' => $pendaftaran->mahasiswa->user_id]);
        }

        // ---- Penilaian untuk pengajuan selesai ----
        foreach ($pendaftarans->where('status', Pendaftaran::STATUS_SELESAI) as $pendaftaran) {
            foreach (['Sikap', 'Kehadiran', 'Laporan Akhir'] as $komponen) {
                PenilaianDosen::create([
                    'pendaftaran_id' => $pendaftaran->id,
                    'komponen' => $komponen,
                    'nilai' => random_int(60, 100),
                ]);
            }

            foreach (['Kinerja', 'Disiplin', 'Kerja Sama'] as $komponen) {
                PenilaianMitra::create([
                    'pendaftaran_id' => $pendaftaran->id,
                    'komponen' => $komponen,
                    'nilai' => random_int(60, 100),
                ]);
            }
        }

        Notifikasi::factory()->count(3)->create(['user_id' => $admin->id]);
        ActivityLog::factory()->count(5)->create(['user_id' => $admin->id]);

        $this->command->info('Seeder selesai: 1 admin, 2 dosen, 5 mahasiswa, 2 mitra, 4 program, '.count($statuses).' pengajuan contoh.');
    }
}
