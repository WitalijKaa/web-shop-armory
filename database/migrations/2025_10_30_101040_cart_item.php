<?php

use App\Models\Shop\Cart\Cart;
use App\Models\Shop\Cart\CartItem;
use App\Models\Shop\Product\ProductItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(CartItem::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('product_item_id');
            $table->unsignedBigInteger('amount');

            $table->foreign('cart_id', 'ix_ci_cart')
                ->references('id')
                ->on(Cart::TABLE_NAME)
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_item_id', 'ix_ci_product_item')
                ->references('id')
                ->on(ProductItem::TABLE_NAME)
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(CartItem::TABLE_NAME);
    }
};
