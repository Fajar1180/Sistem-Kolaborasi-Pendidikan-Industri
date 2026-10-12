<?php

namespace App\Http\Requests\Admin;

use App\Models\ProgramStudi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProgramStudiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $programStudi = $this->route('program_studi');

        return [
            'kode' => ['required', 'string', 'max:20', Rule::unique('program_studis', 'kode')->ignore($programStudi?->id)],
            'nama' => ['required', 'string', 'max:255'],
            'jenjang' => ['required', Rule::in(ProgramStudi::JENJANG)],
            'is_aktif' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return ['kode' => 'kode', 'nama' => 'nama program studi', 'jenjang' => 'jenjang'];
    }
}
