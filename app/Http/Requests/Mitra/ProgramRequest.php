<?php

namespace App\Http\Requests\Mitra;

use App\Models\Program;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * S1-07 — Validasi peluang program (FR-11).
 */
class ProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (! $user?->isMitra()) {
            return false;
        }

        $program = $this->route('program');

        return $program === null || $program->mitra_id === $user->mitra?->id;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'jenis' => ['required', Rule::in(Program::JENIS)],
            'deskripsi' => ['required', 'string'],
            'syarat' => ['nullable', 'string'],
            'kuota' => [
                'required', 'integer', 'min:1',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $program = $this->route('program');

                    if ($program && (int) $value < $program->kuotaTerpakai()) {
                        $fail('Kuota tidak boleh kurang dari '.$program->kuotaTerpakai().' peserta yang sudah diterima.');
                    }
                },
            ],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'judul' => 'judul',
            'jenis' => 'jenis program',
            'deskripsi' => 'deskripsi',
            'syarat' => 'syarat',
            'kuota' => 'kuota',
            'tanggal_mulai' => 'tanggal mulai',
            'tanggal_selesai' => 'tanggal selesai',
        ];
    }
}
