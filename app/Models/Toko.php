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
}
