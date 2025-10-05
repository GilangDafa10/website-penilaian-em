<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'nama_role',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }
}
