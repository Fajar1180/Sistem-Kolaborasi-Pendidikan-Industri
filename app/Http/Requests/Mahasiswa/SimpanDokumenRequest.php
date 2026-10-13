<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class SimpanDokumenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isMahasiswa() ?? false;
    }

    public function rules(): array
    {
        return [
            'berkas' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,docx', 'max:5120'],
            'nama' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'berkas.mimes' => 'Berkas harus berformat PDF, JPG, PNG, atau DOCX.',
            'berkas.max' => 'Ukuran berkas tidak boleh lebih dari 5 MB.',
        ];
    }
}
