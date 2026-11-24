<?php

namespace App\Http\Requests;

use App\Models\Transaksi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransaksiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'nama_pelanggan' => ['required', 'string', 'max:150'],
            'no_hp' => ['required', 'string', 'max:25', 'regex:/^[0-9+\-\s()]+$/'],
            'layanan_id' => ['required', 'integer', 'exists:layanans,id'],
            'berat_kg' => ['required', 'numeric', 'min:0.1', 'max:1000'],
            'status_bayar' => [
                'required',
                'string',
                Rule::in([Transaksi::STATUS_BAYAR_LUNAS, Transaksi::STATUS_BAYAR_BELUM_LUNAS]),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi.',
            'no_hp.required' => 'Nomor HP pelanggan wajib diisi.',
            'no_hp.regex' => 'Format nomor HP tidak valid.',
            'layanan_id.required' => 'Jenis layanan wajib dipilih.',
            'layanan_id.exists' => 'Jenis layanan tidak ditemukan.',
            'berat_kg.required' => 'Berat cucian (kg) wajib diisi.',
            'berat_kg.numeric' => 'Berat cucian harus berupa angka.',
            'berat_kg.min' => 'Berat cucian minimal 0,1 kg.',
            'status_bayar.required' => 'Status pembayaran wajib dipilih.',
            'status_bayar.in' => 'Status pembayaran tidak valid.',
        ];
    }
}
