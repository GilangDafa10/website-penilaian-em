<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $primaryKey = 'id_penilaian';
    protected $fillable = [
        'nim_penilai',
        'nim_dinilai',
        'id_kriteria',
        'nilai',
        'tanggal_penilaian',
    ];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria');
    }

    public function penilai()
    {
        return $this->belongsTo(User::class, 'nim_penilai', 'nim');
    }

    public function dinilai()
    {
        return $this->belongsTo(User::class, 'nim_dinilai', 'nim');
    }
}
