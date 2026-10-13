<?php

namespace App\Support;

/**
 * S2-05/S2-06 — Rubrik penilaian bersama dosen & mitra (disepakati SA).
 * Bobot total 100: kedisiplinan 25, penguasaan 30, kerjasama 20, laporan 25.
 */
final class RubrikPenilaian
{
    public const KOMPONEN = [
        'kedisiplinan' => ['label' => 'Kedisiplinan', 'bobot' => 25],
        'penguasaan' => ['label' => 'Penguasaan Materi/Pekerjaan', 'bobot' => 30],
        'kerjasama' => ['label' => 'Kerjasama & Etika', 'bobot' => 20],
        'laporan' => ['label' => 'Kualitas Laporan', 'bobot' => 25],
    ];

    public static function keys(): array
    {
        return array_keys(self::KOMPONEN);
    }

    public static function isValid(string $komponen): bool
    {
        return isset(self::KOMPONEN[$komponen]);
    }

    public static function bobot(string $komponen): int
    {
        return self::KOMPONEN[$komponen]['bobot'] ?? 0;
    }

    /**
     * Nilai akhir terbobot 0-100 dari koleksi [komponen => nilai].
     */
    public static function nilaiAkhir(iterable $nilaiPerKomponen): ?float
    {
        $total = 0;
        $terisi = 0;

        foreach (self::KOMPONEN as $key => $meta) {
            $nilai = $nilaiPerKomponen[$key] ?? null;
            if ($nilai === null) {
                continue;
            }
            $total += ((float) $nilai) * $meta['bobot'] / 100;
            $terisi++;
        }

        return $terisi === count(self::KOMPONEN) ? round($total, 2) : null;
    }
}
