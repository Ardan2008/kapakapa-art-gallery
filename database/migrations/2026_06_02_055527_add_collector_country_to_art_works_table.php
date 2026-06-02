<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('art_works', function (Blueprint $table) {
            $table->string('collector_country')->nullable()->after('collector_name');
            $table->string('collector_country_code', 5)->nullable()->after('collector_country');
        });
    }

    public function down(): void
    {
        Schema::table('art_works', function (Blueprint $table) {
            $table->dropColumn(['collector_country', 'collector_country_code']);
        });
    }
};
