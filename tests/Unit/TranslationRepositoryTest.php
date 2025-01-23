<?php

namespace Tests\Unit;

use App\Models\Translation;
use App\Models\Tag;
use App\Repositories\TranslationRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranslationRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $translationRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->translationRepository = new TranslationRepository();
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

        $tags = ['greeting', 'morning'];

        $this->translationRepository->assignTagsToTranslation($translation, $tags);

        // Assert that the translation has the assigned tags
        $this->assertCount(2, $translation->tags);
        $this->assertTrue($translation->tags->contains('greeting'));
        $this->assertTrue($translation->tags->contains('morning'));
    }

    /**
     * Test assigning duplicate tags to a translation.
     */
    public function test_assign_duplicate_tags_to_translation()
    {
        $translation = Translation::factory()->create([
            'locale' => 'en',
            'key' => 'hello',
            'value' => 'Hello'
        ]);

        $tags = ['greeting', 'greeting'];

        $this->translationRepository->assignTagsToTranslation($translation, $tags);

        // Assert that only unique tags are assigned
        $this->assertCount(1, $translation->tags);
        $this->assertTrue($translation->tags->contains('greeting'));
    }

    /**
     * Test getting translations by tag.
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

        $translations = $this->translationRepository->getTranslationsByTag('greeting');

        // Assert that the translations returned have the 'greeting' tag
        $this->assertCount(1, $translations);
        $this->assertEquals('hello', $translations->first()->key);
    }

    /**
     * Test getting translations by a non-existing tag.
     */
    public function test_get_translations_by_non_existing_tag()
    {
        $translations = $this->translationRepository->getTranslationsByTag('nonexistent');

        // Assert that no translations are returned for a non-existing tag
        $this->assertCount(0, $translations);
    }

    /**
     * Test getting translations by multiple tags.
     */
    public function test_get_translations_by_multiple_tags()
    {
        $tag1 = Tag::create(['name' => 'greeting']);
        $tag2 = Tag::create(['name' => 'morning']);

        $translation1 = Translation::factory()->create([
            'locale' => 'en',
            'key' => 'hello',
            'value' => 'Hello'
        ]);
        $translation1->tags()->attach([$tag1->id, $tag2->id]);

        $translation2 = Translation::factory()->create([
            'locale' => 'en',
            'key' => 'good_morning',
            'value' => 'Good Morning'
        ]);
        $translation2->tags()->attach([$tag1->id, $tag2->id]);

        $translations = $this->translationRepository->getTranslationsByTag('greeting');

        // Assert that both translations with the 'greeting' tag are returned
        $this->assertCount(2, $translations);
    }

    /**
     * Test creating a translation and assigning tags.
     */
    public function test_create_translation_and_assign_tags()
    {
        $tags = ['greeting', 'morning'];

        $translation = Translation::create([
            'locale' => 'en',
            'key' => 'hello',
            'value' => 'Hello'
        ]);

        $this->translationRepository->assignTagsToTranslation($translation, $tags);

        // Assert that the translation has been created and tags are assigned
        $this->assertCount(2, $translation->tags);
        $this->assertTrue($translation->tags->contains('greeting'));
        $this->assertTrue($translation->tags->contains('morning'));
    }

    /**
     * Test assigning tags to a translation when the translation doesn't exist.
     */
    public function test_assign_tags_to_non_existent_translation()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $tags = ['greeting', 'morning'];
        $translation = Translation::findOrFail(999); // Non-existent ID

        $this->translationRepository->assignTagsToTranslation($translation, $tags);
    }

    /**
     * Test checking if translation tags can be removed properly.
     */
    public function test_remove_tags_from_translation()
    {
        $tags = ['greeting', 'morning'];

        $translation = Translation::factory()->create([
            'locale' => 'en',
            'key' => 'hello',
            'value' => 'Hello'
        ]);

        // Assign tags first
        $this->translationRepository->assignTagsToTranslation($translation, $tags);

        // Remove a tag
        $translation->tags()->detach(Tag::where('name', 'morning')->first());

        // Assert the tag 'morning' is removed, but 'greeting' remains
        $this->assertCount(1, $translation->tags);
        $this->assertTrue($translation->tags->contains('greeting'));
        $this->assertFalse($translation->tags->contains('morning'));
    }

    /**
     * Test retrieving translations without tags.
     */
    public function test_get_translations_without_tags()
    {
        $translation = Translation::factory()->create([
            'locale' => 'en',
            'key' => 'hello',
            'value' => 'Hello'
        ]);

        $translations = $this->translationRepository->getTranslationsByTag('nonexistent');

        // Assert that no translations are returned
        $this->assertCount(0, $translations);
    }

    /**
     * Test assigning multiple tags at once to a translation.
     */
    public function test_assign_multiple_tags_to_translation()
    {
        $translation = Translation::factory()->create([
            'locale' => 'en',
            'key' => 'hello',
            'value' => 'Hello'
        ]);

        $tags = ['greeting', 'world', 'morning'];

        $this->translationRepository->assignTagsToTranslation($translation, $tags);

        // Assert all tags are assigned
        $this->assertCount(3, $translation->tags);
        $this->assertTrue($translation->tags->contains('greeting'));
        $this->assertTrue($translation->tags->contains('world'));
        $this->assertTrue($translation->tags->contains('morning'));
    }
}
