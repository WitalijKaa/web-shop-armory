<?php

use App\Models\Shop\Product\Product;
use App\Models\Shop\Product\ProductItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(ProductItem::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable(false);
            $table->unsignedInteger('amount')->nullable(false);
            $table->unsignedInteger('amount_reserved')->nullable(false)->default(0);

            $table->foreign('product_id', 'ix_pi_product')
                ->references('id')
                ->on(Product::TABLE_NAME)
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(ProductItem::TABLE_NAME);
    }
};
