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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('password');
            $table->string('google_id')->nullable()->unique()->after('avatar');
            $table->string('facebook_id')->nullable()->unique()->after('google_id');
            $table->string('x_id')->nullable()->unique()->after('facebook_id');
            $table->string('discord_id')->nullable()->unique()->after('x_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['google_id']);
            $table->dropUnique(['facebook_id']);
            $table->dropUnique(['x_id']);
            $table->dropUnique(['discord_id']);
            $table->dropColumn(['avatar', 'google_id', 'facebook_id', 'x_id', 'discord_id']);
        });
    }
};
