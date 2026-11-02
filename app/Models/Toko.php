<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Toko extends Model
{
    use HasFactory;

    protected $table = 'toko';

    protected $fillable = [
        'nama',
        'alamat',
        'deskripsi',
        'media_sosial',
        'jam_operasional',
        'gambar',
        'rating_ulasan',
        'jumlah_ulasan',
        'latitude',
        'longitude',
        'pemilik_id',
        'status',
        'catatan_verifikasi',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'rating_ulasan'  => 'decimal:2',
        'jumlah_ulasan'  => 'integer',
        'latitude'       => 'decimal:8',
        'longitude'      => 'decimal:8',
    ];

    /**
     * Accessor: Konversi rating ulasan (skala 1-5) menjadi skor (skala 0-100)
     * menggunakan Bayesian Average untuk mempertimbangkan jumlah ulasan.
     *
     * Toko dengan ulasan sedikit skornya ditarik mendekati nilai tengah (60),
     * sehingga tidak bisa "tipu" sistem hanya dengan 1 ulasan bintang 5.
     *
     * Rumus: skor = [(n / (n + C)) × rating + (C / (n + C)) × m] / 5 × 100
     *   n = jumlah ulasan toko
     *   C = 10  (threshold: butuh ≥10 ulasan untuk dipercaya penuh)
     *   m = 3.0 (rating tengah skala 1-5, asumsi awal untuk toko baru)
     *
     * @return float|null
     */
    public function getSkorUlasanAttribute(): ?float
    {
        if ($this->rating_ulasan === null) {
            return null;
        }

        $n = (int) ($this->jumlah_ulasan ?? 0); // jumlah ulasan toko
        $C = 10;   // threshold kepercayaan minimum
        $m = 3.0;  // rating tengah (netral) skala 1-5

        // Bayesian Average: tarik rating ke nilai tengah sebanding dengan kurangnya ulasan.
        // Jika n = 0 (belum ada ulasan), hasilnya otomatis sama dengan m (skor netral 60),
        // sehingga rating tanpa jumlah ulasan tidak bisa dipakai untuk mendongkrak skor.
        $bayesianRating = ($n / ($n + $C)) * $this->rating_ulasan
                        + ($C / ($n + $C)) * $m;

        return round(($bayesianRating / 5) * 100, 1);
    }

    /**
     * Cek apakah jumlah ulasan toko kurang dari threshold representatif (10).
     *
     * @return bool
     */
    public function isUlasanKurangRepresentatif(): bool
    {
        return $this->jumlah_ulasan !== null && $this->jumlah_ulasan < 10;
    }

    /**
     * Accessor: Cek apakah toko sedang buka berdasarkan jam_operasional saat ini (misal "09:00 - 21:00").
     *
     * @return bool|null
     */
    public function getIsBukaAttribute(): ?bool
    {
        if (empty($this->jam_operasional)) {
            return null;
        }

        if (preg_match('/^(\d{1,2}:\d{2})\s*-\s*(\d{1,2}:\d{2})$/', trim($this->jam_operasional), $matches)) {
            $now = now()->format('H:i');
            $buka = str_pad($matches[1], 5, '0', STR_PAD_LEFT);
            $tutup = str_pad($matches[2], 5, '0', STR_PAD_LEFT);

            if ($buka <= $tutup) {
                return $now >= $buka && $now <= $tutup;
            } else {
                return $now >= $buka || $now <= $tutup;
            }
        }

        return null;
    }

    /**
     * Relasi ke Pemilik Toko (User)
     */
    public function pemilik(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pemilik_id');
    }

    /**
     * Relasi ke Nilai Alternatif
     */
    public function nilaiAlternatif(): HasMany
    {
        return $this->hasMany(NilaiAlternatif::class, 'toko_id');
    }

    /**
     * Scope query untuk hanya mengambil toko yang sudah disetujui (approved).
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope pencarian nama/alamat. Karakter wildcard LIKE (%, _) dari input dianggap
     * teks biasa. Escape char '!' dipakai karena didukung semua driver (MySQL, SQLite).
     */
    public function scopeCari($query, string $kata)
    {
        $pola = '%' . str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $kata) . '%';

        return $query->where(function ($q) use ($pola) {
            $q->whereRaw("nama LIKE ? ESCAPE '!'", [$pola])
              ->orWhereRaw("alamat LIKE ? ESCAPE '!'", [$pola]);
        });
    }

    /**
     * Cek apakah status toko sudah disetujui.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Cek apakah status toko masih menunggu verifikasi.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Cek apakah status toko ditolak oleh admin.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Scope query untuk hanya mengambil toko yang disetujui dan nilai kriterianya sudah lengkap.
     */
    public function scopeLengkap($query)
    {
        $totalKriteria = Kriteria::count();
        $query = $query->where('status', 'approved');

        if ($totalKriteria === 0) {
            return $query;
        }

        return $query->whereHas('nilaiAlternatif', function ($q) {
            $q->whereNotNull('nilai');
        }, '>=', $totalKriteria);
    }

    /**
     * Cek apakah semua nilai kriteria toko sudah terisi lengkap.
     *
     * @param int|null $totalKriteria
     * @return bool
     */
    public function isKriteriaLengkap(?int $totalKriteria = null): bool
    {
        $totalKriteria = $totalKriteria ?? Kriteria::count();

        if ($totalKriteria === 0) {
            return false;
        }

        if ($this->nilai_alternatif_count !== null) {
            return (int) $this->nilai_alternatif_count >= $totalKriteria;
        }

        if ($this->relationLoaded('nilaiAlternatif')) {
            return $this->nilaiAlternatif->whereNotNull('nilai')->unique('kriteria_id')->count() >= $totalKriteria;
        }

        return $this->nilaiAlternatif()->whereNotNull('nilai')->count() >= $totalKriteria;
    }
}

