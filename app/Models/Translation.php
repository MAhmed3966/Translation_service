<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = [
        'locale',
        'key',
        'value',
    ];

    public static function getTranslation(string $locale, string $key)
    {
        // Fetch translation by locale and key
        return self::where('locale', $locale)
            ->where('key', $key)
            ->first()?->value ?? $key; // Return key if translation is not found
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'translation_tag');
    }
}
