<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;

    protected $fillable = [
    'title',
    'id_category',
    'id_artist',
    'id_album',
    'duration',
    'size',
    'file_name',
    'file_url',
    'cover_image',
    'play_count',
    'likes',
    'is_featured'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class, 'id_artist');
    }

    public function album()
    {
        return $this->belongsTo(Album::class, 'id_album');
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }

    public function playlists()
    {
        return $this->belongsToMany(Lista::class, 'music_details', 'id_music', 'id_lista')->withPivot();
    }

}
