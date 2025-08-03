<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    User::updateOrCreate(
        ['email' => 'admin@desa.test'],
        [
            'name' => 'Admin Desa',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]
    );

    User::updateOrCreate(
        ['email' => 'sekretaris@desa.test'],
        [
            'name' => 'Sekretaris Desa',
            'password' => Hash::make('password'),
            'role' => 'sekretaris'
        ]
    );

    User::updateOrCreate(
        ['email' => 'bendahara@desa.test'],
        [
            'name' => 'Bendahara Desa',
            'password' => Hash::make('password'),
            'role' => 'bendahara'
        ]
    );
    }
}
