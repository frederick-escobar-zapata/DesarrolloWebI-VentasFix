<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'rut' => '12345678-9',
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'email' => 'juan.perez@ventasfix.cl',
                'password' => Hash::make('password123'),
            ],
            [
                'rut' => '98765432-1',
                'nombre' => 'María',
                'apellido' => 'González',
                'email' => 'maria.gonzalez@ventasfix.cl',
                'password' => Hash::make('password123'),
            ],
            [
                'rut' => '11223344-5',
                'nombre' => 'Carlos',
                'apellido' => 'Rodríguez',
                'email' => 'carlos.rodriguez@ventasfix.cl',
                'password' => Hash::make('password123'),
            ],
            [
                'rut' => '55667788-0',
                'nombre' => 'Ana',
                'apellido' => 'Martínez',
                'email' => 'ana.martinez@ventasfix.cl',
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
