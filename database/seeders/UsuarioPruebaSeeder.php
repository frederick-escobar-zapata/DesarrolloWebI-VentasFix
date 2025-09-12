<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuarioPruebaSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario de prueba para la autenticación API
        User::create([
            'rut' => '15430259-k',
            'nombre' => 'Frederick',
            'apellido' => 'Escobar',
            'email' => 'frederick.escobar@ventasfix.com',
            'password' => Hash::make('password'),
        ]);        
        
    }
}
