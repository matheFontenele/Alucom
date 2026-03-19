<?php

namespace Database\Seeders;

use App\Models\Equipamento;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Clientes;
use App\Models\Estoque;
use Illuminate\Database\Seeder;

class CadastroEquipamentosSeeder extends Seeder
{
    public function run(): void
    {
        // Localização de IDs necessários
        $catComp = Categoria::firstOrCreate(['nome' => 'Computadores']);
        $subNote = Subcategoria::firstOrCreate(['nome' => 'Notebook', 'categoria_id' => $catComp->id]);
        
        $catSup = Categoria::firstOrCreate(['nome' => 'Suprimentos']);
        $subToner = Subcategoria::firstOrCreate(['nome' => 'Toner', 'categoria_id' => $catSup->id]);

        $estoqueBase = Estoque::where('nome', 'Alucom Base')->first();
        $estoqueSC = Estoque::where('nome', 'Alucom SC')->first();
        $estoqueLab = Estoque::where('nome', 'Laboratório Técnico')->first();
        $cliente = Clientes::where('tipo', 'unidade')->first();

        // 1. Populando ALUCOM BASE (5 itens para teste de agrupamento)
        for ($i = 1; $i <= 5; $i++) {
            Equipamento::create([
                'categoria_id' => $catComp->id,
                'subcategoria_id' => $subNote->id,
                'tombo' => '500' . $i,
                'nome' => 'Notebook Lenovo L14', // Nomes iguais para testar seu novo agrupamento
                'serial' => 'SN-LENO-BASE-' . $i,
                'status' => 'Disponivel',
                'estoque_id' => $estoqueBase->id,
                'data_movimentacao' => now(),
            ]);
        }

        // 2. Populando ALUCOM SC (5 itens)
        for ($i = 1; $i <= 5; $i++) {
            Equipamento::create([
                'categoria_id' => $catComp->id,
                'subcategoria_id' => $subNote->id,
                'tombo' => '600' . $i,
                'nome' => 'Notebook Dell Latitude 3420',
                'serial' => 'SN-DELL-SC-' . $i,
                'status' => 'Disponivel',
                'estoque_id' => $estoqueSC->id,
                'data_movimentacao' => now(),
            ]);
        }

        // 3. Populando LABORATÓRIO (5 itens)
        for ($i = 1; $i <= 5; $i++) {
            Equipamento::create([
                'categoria_id' => $catComp->id,
                'subcategoria_id' => $subNote->id,
                'tombo' => '700' . $i,
                'nome' => 'Notebook HP ProBook',
                'serial' => 'SN-HP-LAB-' . $i,
                'status' => 'Manutenção',
                'estoque_id' => $estoqueLab->id,
                'data_movimentacao' => now(),
            ]);
        }

        // 4. Itens Mesclados (Restante para completar 20 no total do sistema)
        // Criando 5 Insumos (Toners) em estoque
        for ($i = 1; $i <= 5; $i++) {
            Equipamento::create([
                'categoria_id' => $catSup->id,
                'subcategoria_id' => $subToner->id,
                'tombo' => null, // Insumo geralmente não tem tombo
                'nome' => 'Toner Kyocera TK-1175',
                'serial' => 'LOTE-TK-' . rand(100,999),
                'status' => 'Disponivel',
                'estoque_id' => $estoqueBase->id,
                'data_movimentacao' => now(),
            ]);
        }
    }
}