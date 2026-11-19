<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_video_views', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('media_id');
            $table->string('period_key', 20);
            $table->timestamp('first_played_at')->useCurrent();

            $table->unique(
                ['user_id', 'media_id', 'period_key'],
                'post_video_views_user_media_period_unique'
            );
            $table->index(['user_id', 'period_key'], 'post_video_views_user_period_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_video_views');
    }
};
