<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liputan extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'isi',
        'kecamatan_id',
        'reporter_id',
        'slug',
        'status',
        'gambar',
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }
}
