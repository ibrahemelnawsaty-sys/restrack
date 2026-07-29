<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            // Owner note م10: the level certificate must show the score the learner passed with.
            $table->decimal('score', 5, 2)->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn('score');
        });
    }
};
