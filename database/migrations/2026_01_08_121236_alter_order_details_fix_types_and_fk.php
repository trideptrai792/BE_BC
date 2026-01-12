<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('order_details', function (Blueprint $table) {
        $table->unsignedBigInteger('order_id')->change();
        $table->unsignedBigInteger('product_id')->change();

        $table->json('variant_json')->nullable();
        $table->timestamps();

        $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
    });
}

public function down()
{
    Schema::table('order_details', function (Blueprint $table) {
        $table->dropForeign(['order_id']);
        $table->dropForeign(['product_id']);
        $table->dropColumn(['variant_json']);
        $table->dropTimestamps();
    });
}

};
