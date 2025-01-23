<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    public function run()
    {
        // You can tweak the number of records to generate as needed (100k records in this case)
        Translation::factory()->count(100000)->create();
    }
}
