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
    Schema::table('orders', function (Blueprint $table) {
        $table->unsignedBigInteger('user_id')->change();
        $table->unsignedBigInteger('created_by')->change();
        $table->unsignedBigInteger('updated_by')->nullable()->change();

        $table->decimal('subtotal', 12, 2)->default(0);
        $table->decimal('shipping_fee', 12, 2)->default(0);
        $table->decimal('discount_total', 12, 2)->default(0);
        $table->decimal('total', 12, 2)->default(0);

        $table->string('payment_method', 30)->default('cod'); // cod, vnpay, momo...
        $table->string('payment_status', 20)->default('unpaid'); // unpaid, pending, paid, failed
        $table->string('payment_ref')->nullable(); // mã giao dịch cổng
        $table->timestamp('paid_at')->nullable();
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn([
            'subtotal','shipping_fee','discount_total','total',
            'payment_method','payment_status','payment_ref','paid_at'
        ]);
    });
}
};

