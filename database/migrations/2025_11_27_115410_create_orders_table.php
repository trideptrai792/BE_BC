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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->unsignedInteger('user_id');
        $table->string('name');
        $table->string('email');
        $table->string('phone');
        $table->string('address');
        $table->tinyText('note')->nullable();
        $table->timestamps();
        $table->unsignedInteger('created_by')->default(1);
        $table->unsignedInteger('updated_by')->nullable();
        $table->unsignedTinyInteger('status')->default(1);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
