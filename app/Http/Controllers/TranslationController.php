<?php

namespace App\Http\Controllers;

use App\Http\Requests\TranslationRequest;
use App\Services\TranslationService;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    protected $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'locale' => 'required|string',
            'key' => 'required|string|unique:translations,key',
            'value' => 'required|string',
            'tags' => 'nullable|array',
            'tags.*' => 'string|distinct'
        ]);

        $translation = $this->translationService->createTranslation($validated);
        return response()->json($translation, 201);
    }

    public function update(Request $request, $translationId)
    {
        $validated = $request->validate([
            'value' => 'required|string',
            'tags' => 'nullable|array',
            'tags.*' => 'string|distinct'
        ]);

        $translation = $this->translationService->updateTranslation($validated, $translationId);
        return response()->json($translation);
    }

    public function index($locale)
    {
        $translations = $this->translationService->getTranslationsByLocale($locale);
        return response()->json($translations);
    }

    public function show($identifier)
    {
        $translation = $this->translationService->getTranslationByIdOrKey($identifier);
        return response()->json($translation);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $translations = $this->translationService->searchTranslations($query);
        return response()->json($translations);
    }

    public function getTranslationsByTag(Request $request, $tag)
    {
        $translations = $this->translationService->getTranslationsByTag($tag);
        return response()->json($translations);
    }

    public function assignTags(Request $request, $translationId)
    {
        $validated = $request->validate([
            'tags' => 'required|array',
            'tags.*' => 'string|distinct'
        ]);

        $translation = $this->translationService->assignTagsToTranslation($validated['tags'], $translationId);
        return response()->json($translation);
    }

    public function export()
    {
        $translations = $this->translationService->exportTranslations();
        return response()->json($translations)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
