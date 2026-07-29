<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('speakers', function (Blueprint $table) {
            // The owner's speaker card = name · specialty · one numeric achievement.
            $table->string('credential_ar')->nullable()->after('title_en');
            $table->string('credential_en')->nullable()->after('credential_ar');
            $table->string('highlight_ar')->nullable()->after('credential_en');
            $table->string('highlight_en')->nullable()->after('highlight_ar');
            $table->boolean('is_featured')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('speakers', function (Blueprint $table) {
            $table->dropColumn(['credential_ar', 'credential_en', 'highlight_ar', 'highlight_en', 'is_featured']);
        });
    }
};
