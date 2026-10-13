<?php

namespace App\Http\Requests\Mitra;

use App\Models\Pendaftaran;
use Illuminate\Foundation\Http\FormRequest;

/**
 * S2-06 — Validasi nilai mitra per rubrik bersama + flag final.
 * Otorisasi: mitra penyelenggara program (bukan dosen).
 */
class SimpanPenilaianMitraRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $pendaftaran = $this->route('pendaftaran');

        return $user?->isMitra()
            && $pendaftaran instanceof Pendaftaran
            && $pendaftaran->program?->mitra_id !== null
            && $pendaftaran->program?->mitra_id === $user->mitra?->id;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nilai' => ['required', 'array', 'size:4'],
            'nilai.*' => ['required', 'integer', 'min:0', 'max:100'],
            'final' => ['sometimes', 'boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        // Laravel tak menolak kunci array asing: pastikan kunci `nilai`
        // tepat keempat kunci rubrik (mirror SimpanPenilaianRequest S2-05).
        $validator->after(function ($validator) {
            $cek = \Illuminate\Support\Facades\Validator::make(
                ['komponen' => array_keys((array) $this->input('nilai', []))],
                [
                    'komponen' => ['required', 'array', 'size:4'],
                    'komponen.*' => [\Illuminate\Validation\Rule::in(\App\Support\RubrikPenilaian::keys())],
                ],
            );

            if ($cek->fails()) {
                $validator->errors()->add('nilai', 'Empat komponen rubrik harus lengkap dan valid.');
            }
        });
    }
}
