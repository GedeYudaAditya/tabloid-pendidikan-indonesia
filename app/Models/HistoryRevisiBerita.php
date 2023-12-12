<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryRevisiBerita extends Model
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

    public function liputan()
    {
        return $this->belongsTo(Liputan::class);
    }

    public function saranRevisi()
    {
        return $this->hasMany(SaranRevisi::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function berita()
    {
        return $this->belongsTo(Berita::class);
    }
}
