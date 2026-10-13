<?php

namespace App\Http\Requests\Dosen;

use Illuminate\Foundation\Http\FormRequest;

/**
 * S2-01 — Validasi keputusan dosen atas pengajuan penempatan.
 * Catatan/alasan wajib diisi untuk aksi "tolak" dan "revisi".
 */
class KeputusanPengajuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'catatan' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $aksi = $this->route()?->getActionMethod();

            if (in_array($aksi, ['tolak', 'revisi'], true) && blank($this->input('catatan'))) {
                $validator->errors()->add(
                    'catatan',
                    $aksi === 'tolak'
                        ? 'Alasan penolakan wajib diisi.'
                        : 'Catatan revisi wajib diisi.'
                );
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return ['catatan' => 'catatan/alasan'];
    }
}
