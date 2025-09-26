<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'nim',
        'nama',
        'password',
        'role_id',
        'id_jabatan',
    ];

    public function role()
    {
        return $this->hasOne(Role::class, 'role_id');
    }
    public function jabatan()
    {
        return $this->hasOne(Jabatan::class, 'id_jabatan');
    }
}
