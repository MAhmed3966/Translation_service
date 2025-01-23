<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Translations extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 10);
            $table->string('key_name', 255);
            $table->text('content');
            $table->json('tags')->nullable();
            $table->timestamps();
            $table->unique(['locale', 'key_name']);

            // Indexes for faster searches
            $table->index('locale');
            $table->index('key_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
