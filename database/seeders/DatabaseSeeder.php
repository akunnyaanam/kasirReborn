<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Toko;
use App\Models\Users;
use App\Models\Gudang;
use App\Models\Pemasok;
use App\Models\JenisBarang;
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

        // Seeding role 
        DB::table('user_roles')->insert([
            'role' => 'admin'
        ]);

        DB::table('user_roles')->insert([
            'role' => 'gudang'
        ]);

        DB::table('user_roles')->insert([
            'role' => 'kasir'
        ]);

        // Seeding Users
        Users::create([
            'name' => 'Admin',
            'username' => 'admin',
            'id_role' => '1',
            'password' => Hash::make('password'),
        ]);

        Users::create([
            'name' => 'Pegawai Gudang',
            'username' => 'gudang',
            'id_role' => '2',
            'password' => Hash::make('password'),
        ]);

        Users::create([
            'name' => 'Pegawai Kasir',
            'username' => 'kasir',
            'id_role' => '3',
            'password' => Hash::make('password'),
        ]);

        // Seeding Jenis Barang
        JenisBarang::create([
            'kode_jenis_barang' => 'JNBRG10001',
            'kategori_barang' => 'Meja',
        ]);

        JenisBarang::create([
            'kode_jenis_barang' => 'JNBRG10002',
            'kategori_barang' => 'Kursi',
        ]);

        JenisBarang::create([
            'kode_jenis_barang' => 'JNBRG10003',
            'kategori_barang' => 'Lemari',
        ]);

        // Seeding Pemasok
        Pemasok::create([
            'kode_pemasok' => 'PMSK10001',
            'nama' => 'Supri',
            'alamat' => 'Jepara',
            'no_telp' => '0812345678910',
        ]);

        Pemasok::create([
            'kode_pemasok' => 'PMSK10002',
            'nama' => 'Seno',
            'alamat' => 'Semarang',
            'no_telp' => '081206789240',
        ]);

        // Seeding Gudang
        Gudang::create([
            'kode_gudang' => 'GDNG10001',
            'nama' => 'A',
            'alamat' => 'Semarang',
        ]);

        Gudang::create([
            'kode_gudang' => 'GDNG10002',
            'nama' => 'B',
            'alamat' => 'Weleri',
        ]);

        // Seeding Toko
        Toko::create([
            'kode_toko' => 'TK10001',
            'nama' => 'Jati Atos',
            'alamat' => 'Weleri',
        ]);
        Toko::create([
            'kode_toko' => 'TK10002',
            'nama' => 'Maju Mapan',
            'alamat' => 'Tembalang',
        ]);

    }
}
