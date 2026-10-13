<?php

namespace App\Http\Requests\Dosen;

use App\Models\Pendaftaran;
use App\Support\RubrikPenilaian;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * S2-05 — Validasi nilai keempat komponen rubrik + flag final.
 * Kunci array `nilai` harus tepat keempat kunci rubrik (dicek via Rule::in).
 */
class SimpanPenilaianRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $pendaftaran = $this->route('pendaftaran');

        return $user?->isDosen()
            && $pendaftaran instanceof Pendaftaran
            && $pendaftaran->dosen_pembimbing_id !== null
            && $pendaftaran->dosen_pembimbing_id === $user->dosen?->id;
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
        // tepat keempat kunci rubrik.
        $validator->after(function ($validator) {
            $cek = Validator::make(
                ['komponen' => array_keys((array) $this->input('nilai', []))],
                [
                    'komponen' => ['required', 'array', 'size:4'],
                    'komponen.*' => [Rule::in(RubrikPenilaian::keys())],
                ],
                ['komponen.*.in' => 'Komponen :input tidak dikenal dalam rubrik penilaian.']
            );

            if ($cek->fails()) {
                $validator->messages()->merge($cek->messages());
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return ['nilai' => 'nilai', 'final' => 'finalisasi'];
    }
}
