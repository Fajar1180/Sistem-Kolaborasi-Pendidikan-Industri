<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class SimpanKegiatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isMahasiswa() ?? false;
    }

    public function rules(): array
    {
        return [
            'tanggal' => ['required', 'date'],
            'uraian' => ['required', 'string', 'max:2000'],
            'bukti' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,docx', 'max:5120'],
            'hapus_bukti' => ['nullable', 'array'],
            'hapus_bukti.*' => ['integer', 'exists:kegiatan,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'bukti.mimes' => 'Berkas lampiran harus berformat PDF, JPG, PNG, atau DOCX.',
            'bukti.max' => 'Ukuran berkas lampiran tidak boleh lebih dari 5 MB.',
            'hapus_bukti.integer' => 'Hapus berkas harus memilih nomor ID yang valid.',
        ];
    }
}