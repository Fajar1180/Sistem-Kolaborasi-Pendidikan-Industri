<?php

namespace App\Http\Requests\Mitra;

use Illuminate\Foundation\Http\FormRequest;

/**
 * S1-07 — Validasi profil perusahaan mitra (FR-11).
 */
class ProfilMitraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isMitra();
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'nama_perusahaan' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'kontak' => ['nullable', 'string', 'max:50'],
            'deskripsi' => ['nullable', 'string'],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:5120'],
        ];
    }
}
