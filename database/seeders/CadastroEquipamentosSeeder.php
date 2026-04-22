<?php

namespace Database\Seeders;

use App\Models\Equipamento;
use App\Models\Catalogo;
use App\Models\Estoque;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CadastroEquipamentosSeeder extends Seeder
{
    public function run(): void
    {
        $estoqueBase = Estoque::where('nome', 'Alucom Base')->first();
        $catalogos = Catalogo::all();

        foreach ($catalogos as $item) {

            if ($item->tipo === 'equipamento') {
                // --- EQUIPAMENTOS COM TOMBO ---
                for ($i = 0; $i < 2; $i++) {
                    Equipamento::create([
                        'catalogo_id'  => $item->id,
                        'categoria_id' => $item->categoria_id,
                        'estoque_id'   => $estoqueBase->id,
                        'tipo'         => 'equipamento',
                        'nome'         => $item->nome, // OK
                        'serial'       => Str::upper(Str::random(10)),
                        'tombo'        => rand(10000, 99999),
                        'status'       => 'Disponivel',
                        'condicao'     => 'Novo',
                    ]);
                }

                // --- EQUIPAMENTO SEM TOMBO (O erro estava aqui) ---
                Equipamento::create([
                    'catalogo_id'  => $item->id,
                    'categoria_id' => $item->categoria_id,
                    'estoque_id'   => $estoqueBase->id,
                    'tipo'         => 'equipamento',
                    'nome'         => $item->nome, // ADICIONADO: Faltava esta linha
                    'serial'       => 'S-TOMB-' . Str::upper(Str::random(5)),
                    'tombo'        => null,
                    'status'       => 'Disponivel',
                    'condicao'     => 'Novo',
                ]);
            } else {
                // --- INSUMOS ---
                for ($i = 0; $i < 5; $i++) {
                    Equipamento::create([
                        'catalogo_id'  => $item->id,
                        'categoria_id' => $item->categoria_id,
                        'estoque_id'   => $estoqueBase->id,
                        'tipo'         => 'insumo',
                        'nome'         => $item->nome, // OK
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
