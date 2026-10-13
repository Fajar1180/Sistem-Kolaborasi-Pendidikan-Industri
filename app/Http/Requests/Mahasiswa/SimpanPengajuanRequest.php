<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

/**
 * S1-09 (FR-04) — Validasi form pengajuan penempatan:
 * isian catatan dan lampiran berkas (NFR-08: PDF/JPG/PNG/DOCX, maks 5 MB).
 */
class SimpanPengajuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isMahasiswa() ?? false;
    }

    public function rules(): array
    {
        return [
            'catatan_mahasiswa' => ['nullable', 'string', 'max:2000'],
            'files' => ['nullable', 'array', 'max:5'],
            'files.*' => ['file', 'mimes:pdf,jpg,jpeg,png,docx', 'max:5120'],
            'hapus_dokumen' => ['nullable', 'array'],
            'hapus_dokumen.*' => ['integer', 'exists:dokumens,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'files.*.mimes' => 'Berkas harus berformat PDF, JPG, PNG, atau DOCX.',
            'files.*.max' => 'Ukuran tiap berkas tidak boleh lebih dari 5 MB.',
            'files.max' => 'Maksimal 5 berkas dapat diunggah sekaligus.',
        ];
    }

    public function attributes(): array
    {
        return [
            'catatan_mahasiswa' => 'catatan pengajuan',
            'files' => 'berkas lampiran',
        ];
    }
}
