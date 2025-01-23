<?php

namespace App\Repositories;

use App\Models\Translation;

class TranslationRepository
{
    public function createTranslation(array $data)
    {
        return Translation::create($data);
    }

    public function assignTagsToTranslation(Translation $translation, array $tags)
    {
        // Assuming you have a relationship setup for tags
        $translation->tags()->sync($tags);
    }

    public function getTranslationsByLocale($locale)
    {
        return Translation::where('locale', $locale)->get();
    }

    public function getTranslationByIdOrKey($identifier)
    {
        return Translation::where('key', $identifier)
            ->orWhere('id', $identifier)
            ->firstOrFail();
    }

    public function searchTranslations($query)
    {
        return Translation::where('key', 'like', "%$query%")
            ->orWhere('value', 'like', "%$query%")
            ->get();
    }

    public function getTranslationsByTag($tag)
    {
        return Translation::whereHas('tags', function ($query) use ($tag) {
            $query->where('name', $tag);
        })->get();
    }

    public function exportTranslations()
    {
        return Translation::with('tags')->get()->map(function ($translation) {
            return [
                'locale' => $translation->locale,
                'key' => $translation->key,
                'value' => $translation->value,
                'tags' => $translation->tags->pluck('name')->toArray(),
            ];
        });
    }
}

