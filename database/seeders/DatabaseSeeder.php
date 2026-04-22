<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Criar Usuário Administrador para Acesso Inicial
        User::firstOrCreate(
            ['email' => 'admin@alucom.com.br'],
            [
                'name' => 'Administrador Alucom',
                'password' => Hash::make('admin123'),
                'funcao' => 'Direção', // Conforme seu middleware de permissão
            ]
        );

        // 2. Sequência de Seeders (Ordem de Dependência)
        $this->call([
            // Infraestrutura e Cadastros Base
            ClientesSeeder::class,             // Clientes (Unidades e Sedes)
            EstoqueSeeder::class,              // Locais de Armazenamento
            TecnicosSeeder::class,             // Técnicos/Colaboradores

            // Catálogo e Licitações (Regras de Negócio)
            BiddingSeeder::class,              // Contratos e Itens de Licitação
            CatalogoSeeder::class,             // Modelos de Máquinas e Insumos

            // Itens Físicos (Onde o estoque ganha vida)
            CadastroEquipamentosSeeder::class, // Itens Manuais, Insumos e Pendentes de Tombo
            EquipamentoSeeder::class,          // Importação do CSV (Fluxo GuiaAdi)

            // Movimentações e Histórico
            LogisticaSeeder::class,            // Requisições, Rotas e Movimentações
        ]);
    }
}
