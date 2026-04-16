<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Catalogo;
use App\Models\Categoria;

class CatalogoSeeder extends Seeder
{
    public function run(): void
    {
        // Itens de exemplo baseados nas novas regras de atributos técnicos
        $itens = [
            [
                'nome' => 'ThinkCentre M70q',
                'fabricante' => 'Lenovo',
                'categoria_nome' => 'Computadores',
                'subcategoria' => 'Thinkcentre',
                'processador' => 'Intel Core i5',
                'memoria' => '16GB',
                'geracao' => '13ª Geração',
                'tipo' => 'equipamento',
            ],
            [
                'nome' => 'Ecosys M3655idn',
                'fabricante' => 'Kyocera',
                'categoria_nome' => 'Impressoras',
                'subcategoria' => 'Multifuncionais',
                'tipo_impressora' => 'Mono',
                'tipo_papel' => 'A4',
                'tipo' => 'equipamento',
            ],
            [
                'nome' => 'Nobreak Manager III Senoidal',
                'fabricante' => 'SMS',
                'categoria_nome' => 'Energia',
                'subcategoria' => 'Nobreaks',
                'voltagem' => 'Bivolt',
                'tipo' => 'equipamento',
            ],
            [
                'nome' => 'Monitor UltraSharp 27',
                'fabricante' => 'Dell',
                'categoria_nome' => 'Monitores',
                'subcategoria' => 'Monitor',
                'polegadas' => '27"',
                'tipo' => 'equipamento',
            ],
            [
                'nome' => 'Toner TK-3192',
                'fabricante' => 'Kyocera',
                'categoria_nome' => 'Insumos',
                'subcategoria' => 'Tinta/Toner',
                'cor' => 'Preto',
                'tipo_insumo' => 'Original',
                'tipo' => 'insumo',
            ],
            [
                'nome' => 'Papel A4 Report',
                'fabricante' => 'Suzano',
                'categoria_nome' => 'Insumos',
                'subcategoria' => 'Papel',
                'cor' => 'Não se aplica',
                'tipo_insumo' => 'Original',
                'tipo' => 'insumo',
            ]
        ];

        foreach ($itens as $dados) {
            // Extrai o nome da categoria para buscar/criar o ID
            $catNome = $dados['categoria_nome'];
            unset($dados['categoria_nome']);

            // Busca a categoria ou cria se não existir
            $categoria = Categoria::firstOrCreate(['nome' => $catNome]);
            $dados['categoria_id'] = $categoria->id;

            // Cria o registro no catálogo com todos os atributos técnicos
            Catalogo::create($dados);
        }
    }
}