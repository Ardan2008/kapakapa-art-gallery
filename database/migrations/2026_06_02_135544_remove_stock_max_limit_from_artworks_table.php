<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('art_works', function (Blueprint $table) {
            $table->dropColumn(['stock', 'max_limit']);
        });
    }

    public function down(): void
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->integer('stock')->default(0);
            $table->integer('max_limit')->default(0);
        });
    }
};
