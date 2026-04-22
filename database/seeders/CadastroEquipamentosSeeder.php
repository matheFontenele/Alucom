<?php

namespace Database\Seeders;

use App\Models\Equipamento;
use App\Models\Catalogo;
use App\Models\Categoria;
use App\Models\Estoque;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CadastroEquipamentosSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pegar referências necessárias
        $estoqueBase = Estoque::where('nome', 'Alucom Base')->first();
        $estoqueSC = Estoque::where('nome', 'Alucom SC')->first();

        // 2. Pegar todos os modelos cadastrados no CatalogoSeeder
        $catalogos = Catalogo::all();

        foreach ($catalogos as $item) {

            if ($item->tipo === 'equipamento') {
                // --- CRIAR EQUIPAMENTOS COM TOMBO (Patrimoniados) ---
                for ($i = 0; $i < 2; $i++) {
                    Equipamento::create([
                        'catalogo_id'  => $item->id,
                        'categoria_id' => $item->categoria_id,
                        'estoque_id'   => $estoqueBase->id,
                        'tipo'         => 'equipamento',
                        'serial'       => Str::upper(Str::random(10)),
                        'tombo'        => rand(10000, 99999), // Com Tombo
                        'status'       => 'Disponivel',
                        'condicao'     => 'Novo',
                    ]);
                }

                // --- CRIAR EQUIPAMENTOS SEM TOMBO (Para testar o alerta do Dashboard) ---
                // Criaremos apenas 1 de cada modelo sem tombo para teste
                Equipamento::create([
                    'catalogo_id'  => $item->id,
                    'categoria_id' => $item->categoria_id,
                    'estoque_id'   => $estoqueBase->id,
                    'tipo'         => 'equipamento',
                    'serial'       => 'S-TOMB-' . Str::upper(Str::random(5)),
                    'tombo'        => null, // SEM TOMBO - Ativa o alerta
                    'status'       => 'Disponivel',
                    'condicao'     => 'Novo',
                ]);
            } else {
                // --- CRIAR INSUMOS (Toners, Papéis, etc) ---
                // Insumos geralmente não têm tombo e muitas vezes nem serial único, 
                // mas seguindo sua estrutura de 'Equipamento' como tabela única:
                for ($i = 0; $i < 5; $i++) {
                    Equipamento::create([
                        'catalogo_id'  => $item->id,
                        'categoria_id' => $item->categoria_id,
                        'estoque_id'   => $estoqueBase->id,
                        'tipo'         => 'insumo',
                        'serial'       => 'LOTE-' . rand(100, 999),
                        'tombo'        => null,
                        'status'       => 'Disponivel',
                        'condicao'     => 'Novo',
                    ]);
                }
            }
        }
    }
}
