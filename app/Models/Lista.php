<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lista extends Model
{
    use HasFactory;

    protected $table = 'lista'; // nombre personalizado
    protected $fillable = ['id_user', 'name', 'is_public'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function music()
    {
        return $this->belongsToMany(Music::class, 'music_details', 'id_lista', 'id_music');
    }
    

}
