<?php

namespace App\Services;

use App\Models\Translation;
use App\Repositories\TranslationRepository;

class TranslationService
{
    protected $translationRepository;

    public function __construct(TranslationRepository $translationRepository)
    {
        $this->translationRepository = $translationRepository;
    }

    public function createTranslation(array $validated)
    {
        // Create the translation
        $translation = Translation::create([
            'locale' => $validated['locale'],
            'key' => $validated['key'],
            'value' => $validated['value'],
        ]);

        // Assign tags if provided
        if (isset($validated['tags'])) {
            $this->translationRepository->assignTagsToTranslation($translation, $validated['tags']);
        }

        return $translation;
    }

    public function updateTranslation(array $validated, $translationId)
    {
        // Find the translation
        $translation = Translation::findOrFail($translationId);

        // Update translation value
        $translation->update(['value' => $validated['value']]);

        // Update tags if provided
        if (isset($validated['tags'])) {
            $this->translationRepository->assignTagsToTranslation($translation, $validated['tags']);
        }

        return $translation;
    }

    public function getTranslationsByLocale($locale)
    {
        return Translation::where('locale', $locale)->get();
    }

    public function getTranslationByIdOrKey($identifier)
    {
        return Translation::where('key', $identifier)->orWhere('id', $identifier)->firstOrFail();
    }

    public function searchTranslations($query)
    {
        return Translation::where('key', 'like', "%$query%")
            ->orWhere('value', 'like', "%$query%")
            ->get();
    }

    public function getTranslationsByTag($tag)
    {
        return $this->translationRepository->getTranslationsByTag($tag);
    }

    public function assignTagsToTranslation(array $tags, $translationId)
    {
        $translation = Translation::findOrFail($translationId);
        $this->translationRepository->assignTagsToTranslation($translation, $tags);
        return $translation;
    }

    public function exportTranslations()
    {
        // Fetch the latest translations with their associated tags
        $translations = Translation::with('tags')->get();

        // Format translations for frontend
        return $translations->map(function ($translation) {
            return [
                'locale' => $translation->locale,
                'key' => $translation->key,
                'value' => $translation->value,
                'tags' => $translation->tags->pluck('name')->toArray(),
            ];
        });
    }
}
