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
        User::create([
            'name' => 'Admin Desa',
            'email' => 'admin@desa.test',
            'password' => Hash::make('123'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Sekretaris Desa',
            'email' => 'sekretaris@desa.test',
            'password' => Hash::make('123'),
            'role' => 'sekretaris'
        ]);

        User::create([
            'name' => 'Bendahara Desa',
            'email' => 'bendahara@desa.test',
            'password' => Hash::make('123'),
            'role' => 'bendahara'
        ]);
    }
}
