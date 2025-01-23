<?php

namespace Database\Factories;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranslationFactory extends Factory
{
    protected $model = Translation::class;

    public function definition()
    {
        return [
            'locale' => $this->faker->randomElement(['en', 'fr', 'es']),
            'key' => $this->faker->unique()->word,
            'value' => $this->faker->sentence,
        ];
    }
}

