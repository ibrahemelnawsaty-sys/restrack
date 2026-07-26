<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('slug', 120)->unique();
            $table->string('name_ar');   // الباحث المبتدئ
            $table->string('name_en');   // Beginner Researcher
            $table->string('focus_ar')->nullable();
            $table->string('focus_en')->nullable();
            $table->json('topics_ar')->nullable();
            $table->json('topics_en')->nullable();
            $table->json('outcomes_ar')->nullable();
            $table->json('outcomes_en')->nullable();
            $table->unsignedTinyInteger('pass_threshold')->default(70);
            $table->unsignedInteger('exam_questions_count')->default(10);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
