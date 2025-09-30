<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $primaryKey = 'id_penilaian';
    protected $fillable = [
        'id_penilai',
        'id_dinilai',
        'id_kriteria',
        'skor',
        'tanggal_penilaian',
    ];

    // Relasi ke user yang menilai
    public function penilai()
    {
        return $this->belongsTo(User::class, 'id_penilai', 'id_user');
    }

    // Relasi ke user yang dinilai
    public function dinilai()
    {
        return $this->belongsTo(User::class, 'id_dinilai', 'id_user');
    }

    // Relasi ke kriteria
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria', 'id_kriteria');
    }
}
