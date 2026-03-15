<?php

namespace Database\Seeders;

use App\Models\Equipamento;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Clientes;
use Illuminate\Database\Seeder;

class CadastroEquipamentosSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Localizar as Categorias/Subcategorias criadas pelo CategoriaEquipamentoSeeder
        $catComp = Categoria::where('nome', 'Computadores')->first();
        $subNote = Subcategoria::where('nome', 'Notebook')->first();
        $subMicro = Subcategoria::where('nome', 'Micro')->first();

        $catProt = Categoria::where('nome', 'Proteção e Energia')->first();
        $subNobreak = Subcategoria::where('nome', 'Nobreaks')->first();

        // 2. Localizar os Clientes criados pelo ClientesSeeder
        $mteFortaleza = Clientes::where('nome', 'like', '%Fortaleza%')->first();
        $mteCaxias = Clientes::where('nome', 'like', '%Caxias%')->first();

        // 3. Criar Equipamentos de Exemplo vinculados
        
        // Exemplo: Notebook em Fortaleza
        Equipamento::create([
            'categoria_id'    => $catComp->id,
            'subcategoria_id' => $subNote->id,
            'tombo'           => '50001',
            'nome'            => 'Notebook Dell Vostro',
            'serial'          => 'SN50001XYZ',
            'situacao'        => 'Alugado',
            'cliente_id'      => $mteFortaleza->id,
            'data_movimentacao' => now(),
        ]);

        // Exemplo: Micro em Caxias do Sul
        Equipamento::create([
            'categoria_id'    => $catComp->id,
            'subcategoria_id' => $subMicro->id,
            'tombo'           => '50002',
            'nome'            => 'Desktop Optiplex',
            'serial'          => 'SN50002ABC',
            'situacao'        => 'Alugado',
            'cliente_id'      => $mteCaxias->id,
            'data_movimentacao' => now(),
        ]);

        // Exemplo: Nobreak no ESTOQUE (cliente_id nulo)
        Equipamento::create([
            'categoria_id'    => $catProt->id,
            'subcategoria_id' => $subNobreak->id,
            'tombo'           => '70001',
            'nome'            => 'Nobreak APC 1500VA',
            'serial'          => 'SN70001PWR',
            'situacao'        => 'Disponivel',
            'cliente_id'      => null, // Representa o estoque
            'data_movimentacao' => now(),
        ]);
    }
}