<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory; //habilitamos datos falsos

    protected $fillable = ['name', 'cover_image', 'description'];

    public function music()
    {
        return $this->hasMany(Music::class, 'id_category');
    }
}
