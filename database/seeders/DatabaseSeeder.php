<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            ClientesSeeder::class,             // 1º Clientes
            EstoqueSeeder::class,              // 2º Estoques
            TecnicosSeeder::class,             // 4º Técnicos
            CatalogoSeeder::class,             // 5º Catálogo (Base para Equipamentos)
            CadastroEquipamentosSeeder::class, // 6º Itens manuais
            EquipamentoSeeder::class,          // 7º Importação do CSV (GuiaAdi)
            LogisticaSeeder::class,            // 8º importação de Logistica
        ]);
    }
}
