<?php

use App\Models\Shop\Cart\Cart;
use App\Models\Shop\Cart\CartStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(Cart::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->uuid('client_uuid');
            $table->tinyInteger('status')->default(CartStatusEnum::potential->value);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(Cart::TABLE_NAME);
    }
};
