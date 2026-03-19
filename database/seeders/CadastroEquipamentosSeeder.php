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
        // 1. Preparação de Categorias e Subcategorias
        $catComp = Categoria::firstOrCreate(['nome' => 'Computadores']);
        $subNote = Subcategoria::firstOrCreate(['nome' => 'Notebook', 'categoria_id' => $catComp->id]);
        
        $catImp = Categoria::firstOrCreate(['nome' => 'Impressoras']);
        $subLaser = Subcategoria::firstOrCreate(['nome' => 'Laser Mono', 'categoria_id' => $catImp->id]);

        $catProt = Categoria::firstOrCreate(['nome' => 'Proteção e Energia']);
        $subNobreak = Subcategoria::firstOrCreate(['nome' => 'Nobreaks', 'categoria_id' => $catProt->id]);
        
        $catSup = Categoria::firstOrCreate(['nome' => 'Suprimentos']);
        $subToner = Subcategoria::firstOrCreate(['nome' => 'Toner', 'categoria_id' => $catSup->id]);

        // 2. Localização de Estoques e Clientes
        $estoqueBase = Estoque::where('nome', 'Alucom Base')->first();
        $estoqueSC = Estoque::where('nome', 'Alucom SC')->first();
        $estoqueLab = Estoque::where('nome', 'Laboratório Técnico')->first();

        // Pegando algumas unidades específicas para o vínculo
        $unidadeFortaleza = Clientes::where('nome', 'like', '%Gerência Fortaleza%')->first();
        $unidadeMEC_IFCE = Clientes::where('nome', 'like', '%IFCE Fortaleza%')->first();
        $unidadeRS = Clientes::where('nome', 'like', '%Porto Alegre%')->first();

        // ---------------------------------------------------------
        // 3. ITENS EM ESTOQUE (Disponíveis ou Manutenção)
        // ---------------------------------------------------------

        // Notebooks no Estoque SC
        for ($i = 1; $i <= 5; $i++) {
            Equipamento::create([
                'categoria_id' => $catComp->id,
                'subcategoria_id' => $subNote->id,
                'tombo' => '600' . $i,
                'nome' => 'Notebook Dell Latitude 3420',
                'serial' => 'SN-DELL-SC-' . $i,
                'status' => 'Disponivel',
                'estoque_id' => $estoqueSC->id,
                'cliente_id' => null,
                'data_movimentacao' => now(),
            ]);
        }

        // Itens em Manutenção no Laboratório
        for ($i = 1; $i <= 3; $i++) {
            Equipamento::create([
                'categoria_id' => $catProt->id,
                'subcategoria_id' => $subNobreak->id,
                'tombo' => '700' . $i,
                'nome' => 'Nobreak SMS 1200VA',
                'serial' => 'SMS-LAB-' . $i,
                'status' => 'Manutenção',
                'estoque_id' => $estoqueLab->id,
                'cliente_id' => null,
                'data_movimentacao' => now(),
            ]);
        }

        // Suprimentos (Insumos) na Base
        for ($i = 1; $i <= 10; $i++) {
            Equipamento::create([
                'categoria_id' => $catSup->id,
                'subcategoria_id' => $subToner->id,
                'tombo' => 'INS-' . rand(1000, 9999),
                'nome' => 'Toner Kyocera TK-1175',
                'serial' => 'LOTE-TK-' . rand(100, 999),
                'status' => 'Disponivel',
                'estoque_id' => $estoqueBase->id,
                'cliente_id' => null,
                'data_movimentacao' => now(),
            ]);
        }

        // ---------------------------------------------------------
        // 4. ITENS ALUGADOS (Vinculados a Clientes)
        // ---------------------------------------------------------

        // Equipamentos no MTE Fortaleza
        for ($i = 1; $i <= 5; $i++) {
            Equipamento::create([
                'categoria_id' => $catComp->id,
                'subcategoria_id' => $subNote->id,
                'tombo' => '500' . $i,
                'nome' => 'Notebook Lenovo L14',
                'serial' => 'SN-LENO-MTE-' . $i,
                'status' => 'Alugado',
                'estoque_id' => null,
                'cliente_id' => $unidadeFortaleza->id,
                'data_movimentacao' => now(),
            ]);
        }

        // Impressoras no MEC IFCE
        for ($i = 1; $i <= 4; $i++) {
            Equipamento::create([
                'categoria_id' => $catImp->id,
                'subcategoria_id' => $subLaser->id,
                'tombo' => '900' . $i,
                'nome' => 'Impressora Kyocera M2040dn',
                'serial' => 'KYO-MEC-' . $i,
                'status' => 'Alugado',
                'estoque_id' => null,
                'cliente_id' => $unidadeMEC_IFCE->id,
                'data_movimentacao' => now(),
            ]);
        }

        // Itens no MTE Porto Alegre
        Equipamento::create([
            'categoria_id' => $catComp->id,
            'subcategoria_id' => $subNote->id,
            'tombo' => '5501',
            'nome' => 'Notebook HP ProBook',
            'serial' => 'SN-HP-RS-01',
            'status' => 'Alugado',
            'estoque_id' => null,
            'cliente_id' => $unidadeRS->id,
            'data_movimentacao' => now(),
        ]);
    }
}