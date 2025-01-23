<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranslationTagTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('translation_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('translation_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('translation_tag');
    }
}

;
