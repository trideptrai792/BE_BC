<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // cho phép nullable
            if (Schema::hasColumn('posts', 'content')) {
                $table->longText('content')->nullable()->change();
            }
            if (Schema::hasColumn('posts', 'image')) {
                $table->string('image')->nullable()->change();
            }
            if (Schema::hasColumn('posts', 'description')) {
                $table->tinyText('description')->nullable()->change();
            }

            // đảm bảo slug unique, drop index cũ nếu có
            if (Schema::hasColumn('posts', 'slug')) {
                try {
                    DB::statement('ALTER TABLE `posts` DROP INDEX `posts_slug_unique`');
                } catch (\Throwable $e) {
                    // ignore nếu chưa có index
                }
                $table->string('slug')->unique()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // rollback về not null nếu cần
            if (Schema::hasColumn('posts', 'content')) {
                $table->longText('content')->nullable(false)->change();
            }
            if (Schema::hasColumn('posts', 'image')) {
                $table->string('image')->nullable(false)->change();
            }
            if (Schema::hasColumn('posts', 'description')) {
                $table->tinyText('description')->nullable(false)->change();
            }
            if (Schema::hasColumn('posts', 'slug')) {
                try {
                    DB::statement('ALTER TABLE `posts` DROP INDEX `posts_slug_unique`');
                } catch (\Throwable $e) {
                    // ignore
                }
                $table->string('slug')->change();
            }
        });
    }
};
