<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MusicDetail extends Model
{
    use HasFactory;

    protected $table = 'music_details';
    protected $fillable = ['id_lista', 'id_music', 'order'];

    public function music()
    {
        return $this->belongsTo(Music::class, 'id_music');
    }

    public function lista()
    {
        return $this->belongsTo(Lista::class, 'id_lista');
    }


}
