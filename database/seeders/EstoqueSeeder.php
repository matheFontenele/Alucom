<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstoqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Estoque::create([
            'nome' => 'Alucom Base',
            'localizacao' => 'Sede Alucom - Fortaleza CE'
        ]);

        \App\Models\Estoque::create([
            'nome' => 'Alucom SC',
            'localizacao' => 'Florianopolis - SC'
        ]);
    }
}
