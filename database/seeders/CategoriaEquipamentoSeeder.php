<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Database\Seeder;

class CategoriaEquipamentoSeeder extends Seeder
{
    public function run(): void
    {
        $estrutura = [
            'Computadores'       => ['Micro', 'Notebook', 'Thinkcentre'],
            'Impressoras'        => ['Multifuncionais', 'Térmicas'], // Sincronizado
            'Nobreaks'           => ['Estabilizadores', 'Transformadores'], // Sincronizado
            'Periféricos'        => ['Scanners', 'Leitores', 'Switches'], // Com acento
            'Suprimentos'        => ['Toners', 'Tintas', 'Cilindros'], // Sincronizado
            'Outros'             => [],
        ];

        foreach ($estrutura as $nomeCategoria => $subcategorias) {
            $categoria = Categoria::firstOrCreate(['nome' => $nomeCategoria]);

            foreach ($subcategorias as $nomeSub) {
                Subcategoria::firstOrCreate([
                    'categoria_id' => $categoria->id,
                    'nome' => $nomeSub
                ]);
            }
        }
    }
}
