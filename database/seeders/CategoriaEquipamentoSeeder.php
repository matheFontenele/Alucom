<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Database\Seeder;

class CategoriaEquipamentoSeeder extends Seeder
{
    public function run(): void
    {
        // Definindo a estrutura baseada nas suas regras
        $estrutura = [
            'Computadores' => ['Micro', 'Notebook', 'Thinkcentre'],
            'Impressora' => ['Impressoras', 'Multifuncionais'],
            'Proteção e Energia' => ['Nobreaks', 'Estabilizadores', 'Transformadores'],
            'Perifericos' => ['Bandejas', 'Suportes', 'Scanners'],
            'Outros' => [], // Sem subcategorias
        ];

        foreach ($estrutura as $nomeCategoria => $subcategorias) {
            // Busca se já existe ou cria se for novo
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
