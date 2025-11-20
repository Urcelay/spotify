<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = ['id_artist', 'name', 'cover_image', 'release_year', 'total_songs'];

    public function artist()
    {
        return $this->belongsTo(Artist::class, 'id_artist');
    }

    public function music()
    {
        return $this->hasMany(Music::class, 'id_album');
    }

}
