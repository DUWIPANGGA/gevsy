<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = ['Direktur', 'Manajer', 'Admin', 'Staff'];

        foreach ($jabatans as $nama) {
            Jabatan::firstOrCreate(['nama_jabatan' => $nama]);
        }

        $jabatanCollection = Jabatan::pluck('id', 'nama_jabatan');

        $users = [
            ['name' => 'Admin', 'email' => 'admin@dev.com', 'password' => Hash::make('admin')],
            ['name' => 'User1', 'email' => 'user1@dev.com', 'password' => Hash::make('user')],
            ['name' => 'User2', 'email' => 'user2@dev.com', 'password' => Hash::make('user')],
            ['name' => 'User3', 'email' => 'user3@dev.com', 'password' => Hash::make('user')],
        ];

        foreach ($users as $userData) {

            if ($userData['name'] === 'Admin') {

                $userData['id_jabatan'] = $jabatanCollection['Admin'];

                $user = User::create($userData);

                $user->assignRole('super_admin');

            } else {

                $jabatanNonAdmin = $jabatanCollection->except(['Admin']);
                $userData['id_jabatan'] = $jabatanNonAdmin->random();

                $user = User::create($userData);

                $user->assignRole('user');
            }
        }
    }
}
