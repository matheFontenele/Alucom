<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Veiculo;
use Illuminate\Support\Facades\Hash;

class LogisticaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Criando Usuários com Funções Diferentes
        $usuarios = [
            [
                'name' => 'Imna',
                'email' => 'admin.admin@alucom.com',
                'password' => Hash::make('admin123'),
                'funcao' => 'Gerência',
            ],
            [
                'name' => 'Rogerio Nascimento',
                'email' => 'rogerio.motorista@alucom.com',
                'password' => Hash::make('12345678'),
                'funcao' => 'Motorista',
            ],
            [
                'name' => 'Victor Logística',
                'email' => 'victor.log@alucom.com',
                'password' => Hash::make('12345678'),
                'funcao' => 'Logística',
            ],
            [
                'name' => 'Dede',
                'email' => 'dede.motorista@alucom.com',
                'password' => Hash::make('12345678'),
                'funcao' => 'Motorista',
            ],
            [
                'name' => 'Carlos Jr',
                'email' => 'carlos.motorista@alucom.com',
                'password' => Hash::make('12345678'),
                'funcao' => 'Estoque',
            ],
        ];

        foreach ($usuarios as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }

        // 2. Criando a Frota de Veículos
        $veiculos = [
            [
                'placa' => 'ALU-1010',
                'modelo' => 'Fiat Fiorino Endurance',
                'marca' => 'Fiat',
                'ano' => '2023',
                'cor' => 'Branco'
            ],
            [
                'placa' => 'ALU-2020',
                'modelo' => 'Mercedes-Benz Sprinter',
                'marca' => 'Mercedes',
                'ano' => '2022',
                'cor' => 'Prata'
            ],
            [
                'placa' => 'ALU-3030',
                'modelo' => 'VW Delivery 9.170',
                'marca' => 'Volkswagen',
                'ano' => '2024',
                'cor' => 'Branco'
            ],
        ];

        foreach ($veiculos as $veiculo) {
            Veiculo::updateOrCreate(['placa' => $veiculo['placa']], $veiculo);
        }
    }
}
