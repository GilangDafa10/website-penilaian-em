<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $primaryKey = 'id_jabatan';
    protected $fillable = [
        'nama_jabatan',
        'bidang',
        'divisi',
    ];

    public function user()
    {
        return $this->hasMany(User::class, 'jabatan_id', 'id_jabatan', 'user_id');
    }
}
