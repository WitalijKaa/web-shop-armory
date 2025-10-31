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
            $table->string('action_id', 32)->nullable(true);
            $table->text('payload')->nullable(false);
            $table->timestamps();

            $table->index(['created_at', 'action_id'], 'ix_n_created_at_action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(Notification::TABLE_NAME);
    }
};
