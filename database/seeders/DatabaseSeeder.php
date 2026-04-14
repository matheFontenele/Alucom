<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Criar Usuário Administrador
        // Verificamos se o usuário já existe para não dar erro ao rodar o seeder várias vezes
        User::firstOrCreate(
            ['email' => 'admin@alucom.com'], // Busca por este e-mail
            [
                'name' => 'Admin Alucom',
                'password' => Hash::make('admin123'), // Define a senha padrão
                'funcao' => 'Direção', // Garante que ele tenha acesso a tudo
            ]
        );

        // 2. Ordem Hierárquica (Pai -> Filho)
        $this->call([
            ClientesSeeder::class,             // 1º Clientes
            EstoqueSeeder::class,              // 2º Estoques
            CategoriaEquipamentoSeeder::class,  // 3º Categorias e Subs
            TecnicosSeeder::class,             // 4º Técnicos
            CatalogoSeeder::class,             // 5º Catálogo (Base para Equipamentos)
            CadastroEquipamentosSeeder::class, // 6º Itens manuais
            EquipamentoSeeder::class,          // 7º Importação do CSV (GuiaAdi)
            LogisticaSeeder::class,            // 8º importação de Logistica
        ]);
    }
}
