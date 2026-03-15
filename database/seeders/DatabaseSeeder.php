<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Criação de usuario teste
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        //Criação de testes
        $this->call([
            ClientesSeeder::class,
            EstoqueSeeder::class,
            CategoriaEquipamentoSeeder::class, 
            CadastroEquipamentosSeeder::class,
            TecnicosSeeder::class,
            EquipamentoSeeder::class,
        ]);
    }
}
