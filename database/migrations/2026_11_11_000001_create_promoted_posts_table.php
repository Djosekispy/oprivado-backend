<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('promoted_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id'); // int(10) unsigned
            $table->integer('update_id');         // int(11)
            $table->decimal('daily_price', 10, 2)->default(2000.00);
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->unsignedInteger('days')->default(1);
            $table->enum('status', ['active', 'removed', 'expired'])->default('active');
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->dateTime('removed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('update_id')->references('id')->on('updates')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promoted_posts');
    }
};
