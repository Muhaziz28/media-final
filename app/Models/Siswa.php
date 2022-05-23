<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'nama',
        'kelas',
        'password_siswa',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

}
