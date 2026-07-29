<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('level_id')->nullable()->constrained()->nullOnDelete();

            // Owner note م12 — six axes, 1..5 each.
            $table->unsignedTinyInteger('content_quality');
            $table->unsignedTinyInteger('clarity');
            $table->unsignedTinyInteger('speaker_quality');
            $table->unsignedTinyInteger('technical_quality');
            $table->unsignedTinyInteger('ease_of_use');
            $table->unsignedTinyInteger('recommend');

            $table->text('notes')->nullable();          // open suggestions field
            $table->timestamps();

            $table->unique(['user_id', 'level_id']);    // one response per level per learner
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
};
