<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Catalogo;
use App\Models\Categoria;

class CatalogoSeeder extends Seeder
{
    public function run(): void
    {
        $itens = [
            [
                'nome' => 'ThinkCentre M70q',
                'fabricante' => 'Lenovo',
                'categoria_nome' => 'Computadores',
                'processador' => 'Intel Core i5',
                'memoria' => '16GB',
                'geracao' => '13ª Geração',
            ],
            [
                'nome' => 'Ecosys M3655idn',
                'fabricante' => 'Kyocera',
                'categoria_nome' => 'Impressoras',
                'tipo_impressora' => 'Mono',
                'voltagem' => '110v',
            ],
            [
                'nome' => 'Toner TK-3192',
                'fabricante' => 'Kyocera',
                'categoria_nome' => 'Suprimentos',
                'tipo' => 'insumo',
                'cor' => 'Preto',
                'situacao_insumo' => 'Original',
            ],
            [
                'nome' => 'Nobreak Manager III',
                'fabricante' => 'SMS',
                'categoria_nome' => 'Nobreaks',
                'voltagem' => 'Bivolt',
            ]
        ];

        foreach ($itens as $dados) {
            $catNome = $dados['categoria_nome'];
            unset($dados['categoria_nome']);

            $categoria = Categoria::firstOrCreate(['nome' => $catNome]);
            $dados['categoria_id'] = $categoria->id;

            Catalogo::create($dados);
        }
    }
}
