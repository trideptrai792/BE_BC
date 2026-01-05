<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cart_items')) {
            Schema::create('cart_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cart_id')->constrained('carts')->cascadeOnDelete();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->integer('qty')->default(1);
                $table->string('variant_key', 64)->default('');
                $table->json('variant_json')->nullable();
                $table->timestamps();
                $table->unique(['cart_id', 'product_id', 'variant_key'], 'cart_items_cart_id_product_id_variant_key_unique');
            });
            return;
        }

        Schema::table('cart_items', function (Blueprint $table) {
            if (!Schema::hasColumn('cart_items', 'cart_id')) {
                $table->foreignId('cart_id')->after('id')->constrained('carts')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('cart_items', 'product_id')) {
                $table->foreignId('product_id')->after('cart_id')->constrained('products')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('cart_items', 'qty')) {
                $table->integer('qty')->default(1)->after('product_id');
            }
            if (!Schema::hasColumn('cart_items', 'variant_key')) {
                $table->string('variant_key', 64)->default('')->after('qty');
            }
            if (!Schema::hasColumn('cart_items', 'variant_json')) {
                $table->json('variant_json')->nullable()->after('variant_key');
            }
        });

        Schema::table('cart_items', function (Blueprint $table) {
            if (!Schema::hasIndex('cart_items', 'cart_items_cart_id_product_id_variant_key_unique')) {
                $table->unique(['cart_id', 'product_id', 'variant_key'], 'cart_items_cart_id_product_id_variant_key_unique');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            if (Schema::hasIndex('cart_items', 'cart_items_cart_id_product_id_variant_key_unique')) {
                $table->dropUnique('cart_items_cart_id_product_id_variant_key_unique');
            }
            if (Schema::hasColumn('cart_items', 'variant_json')) {
                $table->dropColumn('variant_json');
            }
            if (Schema::hasColumn('cart_items', 'variant_key')) {
                $table->dropColumn('variant_key');
            }
            if (Schema::hasColumn('cart_items', 'qty')) {
                $table->dropColumn('qty');
            }
            if (Schema::hasColumn('cart_items', 'product_id')) {
                $table->dropConstrainedForeignId('product_id');
            }
            if (Schema::hasColumn('cart_items', 'cart_id')) {
                $table->dropConstrainedForeignId('cart_id');
            }
        });
    }
};
