<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'cover_image', 'bio'];

    public function albums()
    {
        return $this->hasMany(Album::class, 'id_artist');
    }

    public function music()
    {
        return $this->hasMany(Music::class, 'id_artist');
    }

}
