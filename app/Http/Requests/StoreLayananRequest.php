<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLayananRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'nama_layanan' => ['required', 'string', 'max:100', Rule::unique('layanans', 'nama_layanan')],
            'harga_per_kg' => ['required', 'integer', 'min:500', 'max:1000000'],
            'estimasi_waktu' => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'nama_layanan.unique' => 'Nama layanan sudah terdaftar.',
            'harga_per_kg.required' => 'Harga per kg wajib diisi.',
            'harga_per_kg.integer' => 'Harga per kg harus berupa angka bulat.',
            'harga_per_kg.min' => 'Harga per kg minimal Rp 500.',
            'estimasi_waktu.required' => 'Estimasi waktu pengerjaan wajib diisi.',
        ];
    }
}
