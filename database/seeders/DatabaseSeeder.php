<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Users;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Seeding role and user
        DB::table('user_roles')->insert([
            'role' => 'admin'
        ]);

        DB::table('user_roles')->insert([
            'role' => 'gudang'
        ]);

        DB::table('user_roles')->insert([
            'role' => 'kasir'
        ]);

        \App\Models\Users::create([
            'name' => 'Admin',
            'username' => 'admin',
            'id_role' => '1',
            'password' => Hash::make('password'),
        ]);

        \App\Models\Users::create([
            'name' => 'Pegawai Gudang',
            'username' => 'gudang',
            'id_role' => '2',
            'password' => Hash::make('password'),
        ]);

        \App\Models\Users::create([
            'name' => 'Pegawai Kasir',
            'username' => 'kasir',
            'id_role' => '3',
            'password' => Hash::make('password'),
        ]);

    }
}
