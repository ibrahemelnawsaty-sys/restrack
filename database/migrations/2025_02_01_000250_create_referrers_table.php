<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Directory of referrers (doctors) — some are linked to a user account, some are name-only.
        Schema::create('referrers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('referral_code', 24)->nullable()->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('referrer_id')->nullable()->after('role')->constrained('referrers')->nullOnDelete();
        });

        // Backfill: give each account-holding referrer a directory row, then remap old attributions.
        if (Schema::hasColumn('users', 'referral_code')) {
            $now = now();
            $map = []; // old referrer user id => new referrers row id

            foreach (DB::table('users')->whereNotNull('referral_code')->get() as $u) {
                $map[$u->id] = DB::table('referrers')->insertGetId([
                    'name' => $u->name,
                    'referral_code' => $u->referral_code,
                    'user_id' => $u->id,
                    'is_active' => true,
                    'sort_order' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            foreach (DB::table('users')->whereNotNull('referred_by')->get() as $u) {
                if (isset($map[$u->referred_by])) {
                    DB::table('users')->where('id', $u->id)->update(['referrer_id' => $map[$u->referred_by]]);
                }
            }
        }

        if (Schema::hasColumn('users', 'referred_by')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropConstrainedForeignId('referred_by');
            });
        }
        if (Schema::hasColumn('users', 'referral_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique(['referral_code']);
                $table->dropColumn('referral_code');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code', 24)->nullable();
            $table->foreignId('referred_by')->nullable()->constrained('users')->nullOnDelete();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('referrer_id');
        });
        Schema::dropIfExists('referrers');
    }
};
