<?php

namespace Tests\Feature;

use App\Models\Translation;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class TranslationControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a new translation.
     */
    public function test_create_translation()
    {
        $response = $this->postJson('/api/translations', [
            'locale' => 'en',
            'key' => 'hello',
            'value' => 'Hello',
            'tags' => ['greeting']
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id', 'locale', 'key', 'value', 'created_at', 'updated_at'
        ]);

        $this->assertDatabaseHas('translations', [
            'key' => 'hello',
            'locale' => 'en',
            'value' => 'Hello',
        ]);

        $translation = Translation::first();
        $this->assertCount(1, $translation->tags);
    }

    /**
     * Test updating a translation.
     */
    public function test_update_translation()
    {
        $translation = Translation::factory()->create([
            'locale' => 'en',
            'key' => 'hello',
            'value' => 'Hello'
        ]);

        $response = $this->putJson('/api/translations/' . $translation->id, [
            'value' => 'Hello World',
            'tags' => ['greeting', 'world']
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'value' => 'Hello World',
        ]);

        $this->assertDatabaseHas('translations', [
            'key' => 'hello',
            'locale' => 'en',
            'value' => 'Hello World',
        ]);
    }

    /**
     * Test fetching a specific translation by key.
     */
    public function test_show_translation_by_key()
    {
        $translation = Translation::factory()->create([
            'locale' => 'en',
            'key' => 'hello',
            'value' => 'Hello'
        ]);

        $response = $this->getJson('/api/translations/' . $translation->key);

        $response->assertStatus(200);
        $response->assertJson([
            'key' => 'hello',
            'value' => 'Hello',
        ]);
    }

    /**
     * Test searching translations by key.
     */
    public function test_search_translations()
    {
        $translation = Translation::factory()->create([
            'locale' => 'en',
            'key' => 'hello',
            'value' => 'Hello'
        ]);

        $response = $this->getJson('/api/translations/search?query=hello');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['key' => 'hello']);
    }

    /**
     * Test exporting all translations.
     */
    public function test_export_translations()
    {
        $translation = Translation::factory()->create([
            'locale' => 'en',
            'key' => 'hello',
            'value' => 'Hello'
        ]);

        $response = $this->getJson('/api/translations/export');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['locale', 'key', 'value', 'tags']
        ]);
    }

    /**
     * Test assigning tags to a translation.
     */
    public function test_assign_tags_to_translation()
    {
        $translation = Translation::factory()->create([
            'locale' => 'en',
            'key' => 'hello',
            'value' => 'Hello'
        ]);

        $response = $this->postJson('/api/translations/' . $translation->id . '/tags', [
            'tags' => ['greeting', 'morning']
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id', 'tags'
        ]);

        $this->assertCount(2, $translation->tags);
    }

    /**
     * Test fetching translations by tag.
     */
    public function test_get_translations_by_tag()
    {
        $tag = Tag::create(['name' => 'greeting']);
        $translation = Translation::factory()->create([
            'locale' => 'en',
            'key' => 'hello',
            'value' => 'Hello'
        ]);
        $translation->tags()->attach($tag);

        $response = $this->getJson('/api/translations/tags/greeting');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['key' => 'hello']);
    }
}
