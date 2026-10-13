<?php

namespace App\Http\Requests\Mitra;

use Illuminate\Foundation\Http\FormRequest;

/**
 * S2-02 — Validasi keputusan mitra atas pelamar (terima/tolak).
 * Alasan/catatan wajib diisi untuk aksi "tolak".
 */
class KeputusanMitraRequest extends FormRequest
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

            if ($aksi === 'tolak' && blank($this->input('catatan'))) {
                $validator->errors()->add('catatan', 'Alasan penolakan wajib diisi.');
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
