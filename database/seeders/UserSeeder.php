<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'foto' => null,
            'email' => 'admin@example.com',
            'jabatan' => 'admin',
            'password' => Hash::make('123'),
        ]);

        // User::create([
        //     'name' => 'Kepala Madrasah',
        //     'foto' => null,
        //     'email' => 'kepala@example.com',
        //     'jabatan' => 'kepala_madrasah',
        //     'password' => Hash::make('123'),
        // ]);

        // User::create([
        //     'name' => 'Wakil Kurikulum',
        //     'foto' => null,
        //     'email' => 'wakil@example.com',
        //     'jabatan' => 'wakil',
        //     'password' => Hash::make('123'),
        // ]);

        // User::create([
        //     'name' => 'Guru Matematika',
        //     'foto' => null,
        //     'email' => 'guru1@example.com',
        //     'jabatan' => 'guru',
        //     'password' => Hash::make('123'),
        // ]);

        // User::create([
        //     'name' => 'Guru Bahasa Indonesia',
        //     'foto' => null,
        //     'email' => 'guru2@example.com',
        //     'jabatan' => 'guru',
        //     'password' => Hash::make('123'),
        // ]);

        // User::create([
        //     'name' => 'Guru IPA',
        //     'foto' => null,
        //     'email' => 'guru3@example.com',
        //     'jabatan' => 'guru',
        //     'password' => Hash::make('123'),
        // ]);

        // User::create([
        //     'name' => 'Kaur Kurikulum',
        //     'foto' => null,
        //     'email' => 'kaur1@example.com',
        //     'jabatan' => 'kaur',
        //     'password' => Hash::make('123'),
        // ]);

        // User::create([
        //     'name' => 'Kaur Kesiswaan',
        //     'foto' => null,
        //     'email' => 'kaur2@example.com',
        //     'jabatan' => 'kaur',
        //     'password' => Hash::make('123'),
        // ]);

        // User::create([
        //     'name' => 'Tata Usaha 1',
        //     'foto' => null,
        //     'email' => 'tu1@example.com',
        //     'jabatan' => 'tu',
        //     'password' => Hash::make('123'),
        // ]);

        // User::create([
        //     'name' => 'Tata Usaha 2',
        //     'foto' => null,
        //     'email' => 'tu2@example.com',
        //     'jabatan' => 'tu',
        //     'password' => Hash::make('123'),
        // ]);
    }
}
