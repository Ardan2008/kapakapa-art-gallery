<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('country_name')->nullable();
            $table->string('country_code', 2)->nullable();  // id, us, jp
            $table->string('city')->nullable();
            $table->string('url')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();  // created_at = waktu kunjungan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
