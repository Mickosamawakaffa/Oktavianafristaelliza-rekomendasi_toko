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
        'latitude',
        'longitude',
        'pemilik_id',
    ];

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
     * Scope query untuk hanya mengambil toko yang nilai kriterianya sudah lengkap.
     */
    public function scopeLengkap($query)
    {
        $totalKriteria = Kriteria::count();
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

