<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddTimestampsToReviewsTable extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Only add columns if they don't already exist
            if (!Schema::hasColumn('reviews', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('comment');
            }
            if (!Schema::hasColumn('reviews', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });

        // Backfill existing rows to avoid null-related update errors
        DB::table('reviews')->whereNull('created_at')->update([
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
            if (Schema::hasColumn('reviews', 'created_at')) {
                $table->dropColumn('created_at');
            }
        });
    }
}

