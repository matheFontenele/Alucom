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
        // 1. Localizar Categorias e Subcategorias
        $catComp = Categoria::where('nome', 'Computadores')->first();
        $subNote = Subcategoria::where('nome', 'Notebook')->first();

        $catProt = Categoria::where('nome', 'Proteção e Energia')->first();
        $subNobreak = Subcategoria::where('nome', 'Nobreaks')->first();

        // 2. Localizar Clientes e Estoques
        $unidadeFortaleza = Clientes::where('nome', 'like', '%Fortaleza%')->first();
        $estoqueBase = Estoque::where('nome', 'Alucom Base')->first();
        $estoqueSC = Estoque::where('nome', 'Alucom SC')->first();
        // 3. Equipamento ALUGADO (Vinculado a Cliente, estoque_id nulo)
        Equipamento::create([
            'categoria_id'    => $catComp->id,
            'subcategoria_id' => $subNote->id,
            'tombo'           => '50010',
            'nome'            => 'Notebook Dell Latitude 3420',
            'serial'          => 'DELL50010X',
            'situacao'        => 'Alugado',
            'cliente_id'      => $unidadeFortaleza->id,
            'estoque_id'      => null,
            'data_movimentacao' => now(),
        ]);

        // 4. Equipamento EM ESTOQUE (cliente_id nulo, vinculado a um Estoque)
        Equipamento::create([
            'categoria_id'    => $catComp->id,
            'subcategoria_id' => $subNote->id,
            'tombo'           => '50011',
            'nome'            => 'Notebook Lenovo L14',
            'serial'          => 'LENO50011Y',
            'situacao'        => 'Disponivel',
            'cliente_id'      => null,
            'estoque_id'      => $estoqueBase->id, // Guardado no Almoxarifado
            'data_movimentacao' => now(),
        ]);

        // 5. Equipamento EM MANUTENÇÃO (No Laboratório)
        Equipamento::create([
            'categoria_id'    => $catProt->id,
            'subcategoria_id' => $subNobreak->id,
            'tombo'           => '70020',
            'nome'            => 'Nobreak SMS 1200VA',
            'serial'          => 'SMS70020Z',
            'situacao'        => 'Manutenção',
            'cliente_id'      => null,
            'estoque_id'      => $estoqueSC->id, // Guardado no Laboratório
            'data_movimentacao' => now(),
        ]);
    }
}
