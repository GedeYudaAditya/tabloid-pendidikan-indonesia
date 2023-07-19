<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function komentar()
    {
        return $this->hasMany(Komentar::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
