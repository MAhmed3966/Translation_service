<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Define a many-to-many relationship with translations
    public function translations()
    {
        return $this->belongsToMany(Translation::class, 'translation_tag');
    }
}
