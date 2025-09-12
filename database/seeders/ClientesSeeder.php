<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = [
            [
                'rut_empresa' => '76123456-7',
                'rubro' => 'Tecnología',
                'razon_social' => 'Innovación Tecnológica S.A.',
                'telefono' => '+56 2 2234 5678',
                'direccion' => 'Av. Providencia 1234, Providencia, Santiago',
                'nombre_contacto' => 'Roberto Silva',
                'email_contacto' => 'roberto.silva@innovaciontech.cl',
            ],
            [
                'rut_empresa' => '89765432-1',
                'rubro' => 'Retail',
                'razon_social' => 'Comercial Los Andes Ltda.',
                'telefono' => '+56 2 2876 5432',
                'direccion' => 'Calle Los Andes 567, Las Condes, Santiago',
                'nombre_contacto' => 'Patricia Morales',
                'email_contacto' => 'patricia.morales@losandes.cl',
            ],
            [
                'rut_empresa' => '65432198-5',
                'rubro' => 'Construcción',
                'razon_social' => 'Constructora del Norte S.A.',
                'telefono' => '+56 55 234 5678',
                'direccion' => 'Av. Brasil 890, Antofagasta',
                'nombre_contacto' => 'Manuel Herrera',
                'email_contacto' => 'manuel.herrera@constructoranorte.cl',
            ],
        ];

        foreach ($clientes as $cliente) {
            DB::table('clientes')->insert(array_merge($cliente, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
