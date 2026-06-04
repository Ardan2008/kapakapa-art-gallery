<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('google_users', function (Blueprint $table) {
            $table->id();
            $table->string('google_id')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('avatar')->nullable();
            $table->timestamps();
        });

        // Add google_user_id FK to comments
        Schema::table('comments', function (Blueprint $table) {
            $table->foreignId('google_user_id')
                  ->nullable()
                  ->constrained('google_users')
                  ->nullOnDelete()
                  ->after('artwork_id');
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('google_user_id');
        });
        Schema::dropIfExists('google_users');
    }
};