<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Editable site copy (hero, about, ...) — the CMS "change anything" store.
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('page', 60);       // home
            $table->string('section', 60);    // hero
            $table->string('item_key', 60);   // title, subtitle... ("key" is reserved in MySQL)
            $table->text('value_ar')->nullable();
            $table->text('value_en')->nullable();
            $table->timestamps();

            $table->unique(['page', 'section', 'item_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_sections');
    }
};
