<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use WebShop\Notifications\Models\Notification;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(Notification::TABLE_NAME, function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->smallInteger('type')->unsigned()->nullable(false);
            $table->string('action_id');
            $table->text('payload');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(Notification::TABLE_NAME);
    }
};
