<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guidelines', function (Blueprint $table) {
            // group_key, not `group` — reserved word in MySQL (CLAUDE.md §4).
            // saudi | reporting | ethics | publication
            $table->string('group_key', 20)->default('reporting')->after('name_en');
            $table->string('note_ar')->nullable()->after('group_key');   // what the standard covers
            $table->index('group_key');
        });
    }

    public function down(): void
    {
        Schema::table('guidelines', function (Blueprint $table) {
            $table->dropIndex(['group_key']);
            $table->dropColumn(['group_key', 'note_ar']);
        });
    }
};
