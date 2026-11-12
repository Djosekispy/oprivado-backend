<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promoted_posts', function (Blueprint $table) {
            $table->unsignedInteger('impressions')->default(0)->after('total_amount');
            $table->unsignedInteger('clicks')->default(0)->after('impressions');
        });
    }

    public function down(): void
    {
        Schema::table('promoted_posts', function (Blueprint $table) {
            $table->dropColumn(['impressions', 'clicks']);
        });
    }
};
