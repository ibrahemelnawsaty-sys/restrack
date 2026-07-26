<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 60)->unique();
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->decimal('price', 8, 2)->default(0);      // VAT-inclusive SAR (illustrative)
            $table->string('interval', 10)->default('monthly'); // monthly | annual
            $table->json('features_ar')->nullable();
            $table->json('features_en')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
