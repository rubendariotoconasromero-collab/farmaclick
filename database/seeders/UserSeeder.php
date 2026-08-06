<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(
        [
            'name' => 'Administrador',
            'matricula' => '001',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'estado' => 1,
            'id_grupo' => 1,
            'id_personal' => 1
        ]
        );
    }
}
