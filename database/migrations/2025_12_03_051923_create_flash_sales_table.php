<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
           $table->decimal('flash_price', 12, 2);   // giá khuyến mãi
            $table->unsignedTinyInteger('discount_percent')->nullable(); // % giảm

            $table->unsignedInteger('sold')->default(0);     // 4115 đã bán

            // label & benefit để FE hiển thị giống hình
            $table->string('discount_label', 50)->nullable();  // "Giảm 45%"
            $table->string('badge_left', 50)->nullable();      // "FREESHIP"
            $table->string('badge_right', 50)->nullable();     // "TẶNG QUÀ" / "ƯU ĐÃI SỐC"
            $table->string('benefit_1', 100)->nullable();      // "Miễn phí giao hàng"
            $table->string('benefit_2', 100)->nullable()    ;      // "Tặng kèm bình lắc"

            $table->timestamp('start_at')->nullable();         // thời gian bắt đầu
            $table->timestamp('end_at')->nullable();           // thời gian kết thúc (để FE đếm ngược)
            $table->tinyInteger('status')->default(1);         // 1: đang chạy, 0: tắt

            $table->unsignedInteger('sort_order')->default(0); // để sắp xếp

            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flash_sales');
    }
};
