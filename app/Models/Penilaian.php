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
}
