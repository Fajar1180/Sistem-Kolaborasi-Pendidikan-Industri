<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class SimpanLaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isMahasiswa() ?? false;
    }

    public function rules(): array
    {
        return [
            'berkas' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'berkas.mimes' => 'Laporan harus berformat PDF atau DOCX.',
            'berkas.max' => 'Ukuran laporan tidak boleh lebih dari 10 MB.',
        ];
    }
}
