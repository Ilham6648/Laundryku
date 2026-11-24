<?php

namespace App\Models;

use Database\Factories\TransaksiFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'kode_transaksi',
    'nama_pelanggan',
    'no_hp',
    'layanan_id',
    'berat_kg',
    'total_harga',
    'status_bayar',
    'status_pengerjaan',
])]
class Transaksi extends Model
{
    /** @use HasFactory<TransaksiFactory> */
    use HasFactory;

    public const string STATUS_BAYAR_LUNAS = 'Lunas';

    public const string STATUS_BAYAR_BELUM_LUNAS = 'Belum Lunas';

    public const string STATUS_PENGERJAAN_PROSES = 'Proses';

    public const string STATUS_PENGERJAAN_SELESAI = 'Selesai';

    /** @var array<string, mixed> */
    protected $attributes = [
        'status_pengerjaan' => self::STATUS_PENGERJAAN_PROSES,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'berat_kg' => 'float',
            'total_harga' => 'integer',
        ];
    }

    /**
     * Layanan cuci yang dipilih pada transaksi ini.
     */
    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class);
    }

    /**
     * Filter transaksi berdasarkan status pengerjaan, status bayar, dan kata kunci.
     *
     * @param  array{status_pengerjaan?: string|null, status_bayar?: string|null, search?: string|null}  $filters
     */
    #[Scope]
    protected function filter(Builder $query, array $filters): Builder
    {
        $statusPengerjaan = $filters['status_pengerjaan'] ?? null;
        $statusBayar = $filters['status_bayar'] ?? null;
        $search = trim((string) ($filters['search'] ?? ''));

        return $query
            ->when(
                in_array($statusPengerjaan, [self::STATUS_PENGERJAAN_PROSES, self::STATUS_PENGERJAAN_SELESAI], true),
                fn (Builder $q) => $q->where('status_pengerjaan', $statusPengerjaan),
            )
            ->when(
                in_array($statusBayar, [self::STATUS_BAYAR_LUNAS, self::STATUS_BAYAR_BELUM_LUNAS], true),
                fn (Builder $q) => $q->where('status_bayar', $statusBayar),
            )
            ->when(
                $search !== '',
                fn (Builder $q) => $q->where(function (Builder $sub) use ($search): void {
                    $sub->where('kode_transaksi', 'like', "%{$search}%")
                        ->orWhere('nama_pelanggan', 'like', "%{$search}%")
                        ->orWhere('no_hp', 'like', "%{$search}%");
                }),
            );
    }

    /**
     * Hitung total harga transaksi secara akurat berdasarkan berat dan tarif layanan.
     */
    public static function hitungTotalHarga(float $beratKg, int $hargaPerKg): int
    {
        return (int) round($beratKg * $hargaPerKg);
    }

    /**
     * Buat kode transaksi unik harian dengan format TRX-YYYYMMDD-XXXX.
     */
    public static function generateKodeTransaksi(): string
    {
        $prefix = 'TRX-'.now()->format('Ymd').'-';

        $latest = static::query()
            ->where('kode_transaksi', 'like', $prefix.'%')
            ->orderByDesc('kode_transaksi')
            ->value('kode_transaksi');

        $nextNumber = $latest ? ((int) substr($latest, strlen($prefix))) + 1 : 1;

        while (static::query()->where('kode_transaksi', $prefix.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT))->exists()) {
            $nextNumber++;
        }

        return $prefix.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function isProses(): bool
    {
        return $this->status_pengerjaan === self::STATUS_PENGERJAAN_PROSES;
    }

    public function isSelesai(): bool
    {
        return $this->status_pengerjaan === self::STATUS_PENGERJAAN_SELESAI;
    }

    public function isLunas(): bool
    {
        return $this->status_bayar === self::STATUS_BAYAR_LUNAS;
    }
}
