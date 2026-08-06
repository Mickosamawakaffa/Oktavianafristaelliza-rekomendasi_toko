<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $fillable = [
        'nama_kriteria',
        'jenis',
    ];

    /**
     * Relasi ke Nilai Alternatif
     */
    public function nilaiAlternatif(): HasMany
    {
        return $this->hasMany(NilaiAlternatif::class, 'kriteria_id');
    }

    /**
     * Relasi ke Preferensi User
     */
    public function preferensiUser(): HasMany
    {
        return $this->hasMany(PreferensiUser::class, 'kriteria_id');
    }
}
