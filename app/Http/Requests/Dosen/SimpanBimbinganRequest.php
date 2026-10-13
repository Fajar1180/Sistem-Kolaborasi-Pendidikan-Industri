<?php

namespace App\Http\Requests\Dosen;

use App\Models\Pendaftaran;
use Illuminate\Foundation\Http\FormRequest;

/**
 * S2-03 — Validasi catatan bimbingan dosen pembimbing.
 */
class SimpanBimbinganRequest extends FormRequest
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
            'catatan' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return ['catatan' => 'catatan bimbingan'];
    }
}
