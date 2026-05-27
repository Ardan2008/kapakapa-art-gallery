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
        Schema::table('art_works', function (Blueprint $table) {
            $table->string('birthplace')->nullable();
            $table->string('career')->nullable();
            $table->text('artist_desc')->nullable();
            $table->text('art_desc')->nullable();
            $table->string('painter_ref')->nullable();
            $table->integer('stock')->default(0);
            $table->integer('max_limit')->default(0);
            $table->decimal('base_price', 15, 2)->nullable();
            $table->decimal('sale_price', 15, 2)->nullable();
            $table->json('images')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('art_works', function (Blueprint $table) {
            //
        });
    }
};
