<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Cek dulu kolom yang belum ada sebelum ditambah
            if (!Schema::hasColumn('comments', 'sticker_url')) {
                $table->text('sticker_url')->nullable()->after('body');
            }
            if (!Schema::hasColumn('comments', 'type')) {
                $table->string('type', 20)->default('text')->after('sticker_url');
            }
            if (!Schema::hasColumn('comments', 'google_user_id')) {
                $table->unsignedBigInteger('google_user_id')->nullable()->after('artwork_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn(['sticker_url', 'type', 'google_user_id']);
        });
    }
};