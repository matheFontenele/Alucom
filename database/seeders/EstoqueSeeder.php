<?php

namespace Database\Seeders;

use App\Models\Estoque;
use Illuminate\Database\Seeder;

class EstoqueSeeder extends Seeder
{
    public function run(): void
    {
        Estoque::create(['nome' => 'Alucom Base', 'localizacao' => 'Sede Alucom - Fortaleza CE']);
        Estoque::create(['nome' => 'Alucom SC', 'localizacao' => 'Florianópolis - SC']);
        Estoque::create(['nome' => 'Laboratório Técnico', 'localizacao' => 'Setor de Reparos - Fortaleza CE']);
    }
}