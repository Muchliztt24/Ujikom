<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = collect(['admin', 'uploader', 'user'])
            ->mapWithKeys(fn (string $name) => [$name => Role::query()->create(['name' => $name])]);

        User::query()->create([
            'name' => 'Admin Nokomi',
            'username' => 'admin-nokomi',
            'email' => 'admin@nokomi.test',
            'password' => Hash::make('password'),
            'role_id' => $roles['admin']->id,
            'bio' => 'Mengelola katalog, moderasi, dan kualitas rilis di Nokomi.',
        ]);

        collect([
            ['name' => 'Uploader Asep', 'email' => 'asep@nokomi.test'],
            ['name' => 'Uploader Nayla', 'email' => 'nayla@nokomi.test'],
            ['name' => 'Uploader Raka', 'email' => 'raka@nokomi.test'],
            ['name' => 'Allay User', 'email' => 'allay@nokomi.test'],
            ['name' => 'Rani Reader', 'email' => 'rani@nokomi.test'],
            ['name' => 'Bagas Reader', 'email' => 'bagas@nokomi.test'],
        ])->each(function (array $user) use ($roles) {
            User::query()->create([
                'name' => $user['name'],
                'username' => str($user['email'])->before('@')->toString(),
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'role_id' => str_starts_with(strtolower($user['name']), 'uploader')
                    ? $roles['uploader']->id
                    : $roles['user']->id,
                'bio' => str_starts_with(strtolower($user['name']), 'uploader')
                    ? 'Uploader aktif yang merapikan katalog karya real untuk library Nokomi.'
                    : 'Pembaca aktif yang menyimpan progress, bookmark, dan komentar di Nokomi.',
            ]);
        });

        collect([
            'Action',
            'Adventure',
            'Comedy',
            'Dark Fantasy',
            'Drama',
            'Fantasy',
            'Horror',
            'Martial Arts',
            'Mystery',
            'Psychological',
            'Sci-Fi',
            'Supernatural',
            'Thriller',
        ])->each(fn (string $genre) => Genre::query()->create(['name' => $genre]));

        $this->call(SampleContentSeeder::class);
    }
}
