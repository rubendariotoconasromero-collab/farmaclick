<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
        // User::creFITate(
            User::create(
            [
                'name' => 'Administrador',
                'matricula' => '001',
                'email' => 'admin@admin.com',
                'password' => '$2a$12$MJsuBM8Nicn6MaVySt4nyOQFBjh.O03PRO93pE5l3d0hgHmLfoKBy',
                'estado' => 1,
                'id_grupo' => 1,
                'id_personal' => 1
            ]
        );_
    }
}
