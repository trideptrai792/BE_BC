<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {

            // Cho phép content nullable (để tránh lỗi khi tạo bài viết)
            if (Schema::hasColumn('posts', 'content')) {
                $table->longText('content')->nullable()->change();
            }

            // Nếu API không dùng cột image → cho nullable hoặc xóa tùy bạn
            if (Schema::hasColumn('posts', 'image')) {
                $table->string('image')->nullable()->change();
            }

            // Nếu API không dùng description → cho nullable
            if (Schema::hasColumn('posts', 'description')) {
                $table->tinyText('description')->nullable()->change();
            }

            // Đảm bảo slug là unique
            if (Schema::hasColumn('posts', 'slug')) {
                $table->string('slug')->unique()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {

            // rollback lại như cũ (nếu cần)
            $table->longText('content')->nullable(false)->change();
            $table->string('image')->nullable(false)->change();
            $table->tinyText('description')->nullable(false)->change();
            $table->string('slug')->unique(false)->change();
        });
    }
};
