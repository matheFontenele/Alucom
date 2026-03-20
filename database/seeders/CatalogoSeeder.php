<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Catalogo;

class CatalogoSeeder extends Seeder
{
    public function run(): void
    {
        $itens = [
            // IMPRESSORAS
            [
                'nome' => 'Ecosys M3655idn',
                'fabricante' => 'Kyocera',
                'categoria' => 'Impressora',
                'tipo_papel' => 'A4',
                'voltagem' => '110v',
                'cor' => 'Preto',
            ],
            [
                'nome' => 'L42PRO Full',
                'fabricante' => 'Elgin',
                'categoria' => 'Impressora',
                'tipo_papel' => 'Etiqueta',
                'voltagem' => 'Bivolt',
                'cor' => 'Preto',
            ],
            // NOBREAKS
            [
                'nome' => 'Attiv 600VA',
                'fabricante' => 'Intelbras',
                'categoria' => 'Nobreak',
                'voltagem' => 'Bivolt',
                'descricao' => 'Nobreak para PDV e estações de trabalho',
            ],
            [
                'nome' => 'XNB 1440VA',
                'fabricante' => 'Intelbras',
                'categoria' => 'Nobreak',
                'voltagem' => '110v',
                'descricao' => 'Carga para servidores pequenos',
            ],
            // PERIFÉRICOS / OUTROS
            [
                'nome' => 'Leitor QuickScan QW2100',
                'fabricante' => 'Datalogic',
                'categoria' => 'Periférico',
                'descricao' => 'Leitor de código de barras USB com suporte',
            ],

            // TONERS (LASER)
            [
                'nome' => 'Toner TK-3192',
                'fabricante' => 'Kyocera',
                'categoria' => 'Suprimento',
                'tipo_papel' => null,
                'voltagem' => null,
                'cor' => 'Preto',
                'descricao' => 'Rendimento de 25.000 páginas. Compatível com M3655idn.',
            ],
            [
                'nome' => 'Toner HP 105A (W1105A)',
                'fabricante' => 'HP',
                'categoria' => 'Suprimento',
                'tipo_papel' => null,
                'voltagem' => null,
                'cor' => 'Preto',
                'descricao' => 'Compatível com HP Laser 107a, 107w, MFP 135a.',
            ],

            // BOLSAS DE TINTA (INKJET / RIPS)
            [
                'nome' => 'Bolsa de Tinta T941120',
                'fabricante' => 'Epson',
                'categoria' => 'Suprimento',
                'cor' => 'Preto',
                'descricao' => 'Tinta DURABrite Ultra. Compatível com WorkForce Pro WF-C5710.',
            ],
            [
                'nome' => 'Bolsa de Tinta T941220',
                'fabricante' => 'Epson',
                'categoria' => 'Suprimento',
                'cor' => 'Ciano',
                'descricao' => 'Tinta DURABrite Ultra. Compatível com WorkForce Pro WF-C5710.',
            ],
            [
                'nome' => 'Bolsa de Tinta T941320',
                'fabricante' => 'Epson',
                'categoria' => 'Suprimento',
                'cor' => 'Magenta',
                'descricao' => 'Tinta DURABrite Ultra. Compatível com WorkForce Pro WF-C5710.',
            ],
            [
                'nome' => 'Bolsa de Tinta T941420',
                'fabricante' => 'Epson',
                'categoria' => 'Suprimento',
                'cor' => 'Amarelo',
                'descricao' => 'Tinta DURABrite Ultra. Compatível com WorkForce Pro WF-C5710.',
            ],
        ];

        foreach ($itens as $item) {
            Catalogo::create($item);
        }
    }
}
