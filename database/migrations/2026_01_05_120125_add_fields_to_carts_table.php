<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('status', 20)->default('active');
                $table->integer('shipping_fee')->default(0);
                $table->string('shipping_method', 50)->nullable();
                $table->string('coupon_code', 50)->nullable();
                $table->timestamps();
            });
            return;
        }

        Schema::table('carts', function (Blueprint $table) {
            if (!Schema::hasColumn('carts', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
            }
            if (!Schema::hasColumn('carts', 'status')) {
                $table->string('status', 20)->default('active')->after('user_id');
            }
            if (!Schema::hasColumn('carts', 'shipping_fee')) {
                $table->integer('shipping_fee')->default(0)->after('status');
            }
            if (!Schema::hasColumn('carts', 'shipping_method')) {
                $table->string('shipping_method', 50)->nullable()->after('shipping_fee');
            }
            if (!Schema::hasColumn('carts', 'coupon_code')) {
                $table->string('coupon_code', 50)->nullable()->after('shipping_method');
            }
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            if (Schema::hasColumn('carts', 'coupon_code')) {
                $table->dropColumn('coupon_code');
            }
            if (Schema::hasColumn('carts', 'shipping_method')) {
                $table->dropColumn('shipping_method');
            }
            if (Schema::hasColumn('carts', 'shipping_fee')) {
                $table->dropColumn('shipping_fee');
            }
            if (Schema::hasColumn('carts', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('carts', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
        });
    }
};
