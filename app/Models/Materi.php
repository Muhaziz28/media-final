<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kd_id',
        'judul',
        'image',
        'video',
        'file',
    ];

    public function kd()
    {
        return $this->belongsTo(Kd::class, 'kd_id', 'id');
    }
}
