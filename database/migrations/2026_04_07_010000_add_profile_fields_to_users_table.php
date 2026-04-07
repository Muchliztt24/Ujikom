<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('profile_visibility')->default('public')->after('bio');
            $table->boolean('email_notifications')->default(true)->after('profile_visibility');
            $table->boolean('reading_history_visible')->default(true)->after('email_notifications');
        });

        DB::table('users')->orderBy('id')->get()->each(function ($user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'username' => Str::slug($user->name).'-'.$user->id,
                ]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'bio',
                'profile_visibility',
                'email_notifications',
                'reading_history_visible',
            ]);
        });
    }
};
