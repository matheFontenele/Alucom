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
            // --- IMPRESSORAS & MULTIFUNCIONAIS ---
            [
                'nome' => 'Ecosys M3655idn',
                'fabricante' => 'Kyocera',
                'categoria_nome' => 'Impressoras',
                'tipo_papel' => 'A4',
                'voltagem' => '110v',
                'cor' => 'Preto',
                'descricao' => 'Multifuncional laser monocromática de alta performance (55 ppm).',
            ],
            [
                'nome' => 'Ecosys M6635cidn',
                'fabricante' => 'Kyocera',
                'categoria_nome' => 'Impressoras',
                'tipo_papel' => 'A4',
                'voltagem' => '110v',
                'cor' => 'Colorido',
                'descricao' => 'Multifuncional laser colorida para grupos de trabalho.',
            ],
            [
                'nome' => 'L42PRO Full',
                'fabricante' => 'Elgin',
                'categoria_nome' => 'Impressoras',
                'tipo_papel' => 'Etiqueta',
                'voltagem' => 'Bivolt',
                'cor' => 'Preto',
                'descricao' => 'Impressora térmica para etiquetas e códigos de barras.',
            ],
            [
                'nome' => 'EcoTank L3250',
                'fabricante' => 'Epson',
                'categoria_nome' => 'Impressoras',
                'tipo_papel' => 'A4',
                'voltagem' => 'Bivolt',
                'cor' => 'Colorido',
                'descricao' => 'Multifuncional tanque de tinta colorida com Wi-Fi.',
            ],

            // --- NOBREAKS & ESTABILIZADORES ---
            [
                'nome' => 'Attiv 600VA',
                'fabricante' => 'Intelbras',
                'categoria_nome' => 'Nobreaks',
                'voltagem' => 'Bivolt',
                'descricao' => 'Nobreak para PDV, roteadores e estações de trabalho simples.',
            ],
            [
                'nome' => 'DNB 1.5 kVA RT',
                'fabricante' => 'Intelbras',
                'categoria_nome' => 'Nobreaks',
                'voltagem' => '220v',
                'descricao' => 'Nobreak senoidal online de torre ou rack para servidores.',
            ],
            [
                'nome' => 'Manager III 1500VA',
                'fabricante' => 'SMS',
                'categoria_nome' => 'Nobreaks',
                'voltagem' => 'Bivolt',
                'descricao' => 'Nobreak com comunicação inteligente e 6 tomadas.',
            ],

            // --- SUPRIMENTOS (TONERS, TINTAS, CILINDROS) ---
            [
                'nome' => 'Toner TK-3192',
                'fabricante' => 'Kyocera',
                'categoria_nome' => 'Suprimentos',
                'cor' => 'Preto',
                'descricao' => 'Rendimento de 25.000 páginas. Compatível com M3655idn.',
            ],
            [
                'nome' => 'Toner TN-3472',
                'fabricante' => 'Brother',
                'categoria_nome' => 'Suprimentos',
                'cor' => 'Preto',
                'descricao' => 'Rendimento de 12.000 páginas. Compatível com L5652DN.',
            ],
            [
                'nome' => 'Bolsa de Tinta T504120',
                'fabricante' => 'Epson',
                'categoria_nome' => 'Suprimentos',
                'cor' => 'Preto',
                'descricao' => 'Refil de tinta preta 127ml para série EcoTank L4150/L4160.',
            ],
            [
                'nome' => 'Unidade de Cilindro DR-3440',
                'fabricante' => 'Brother',
                'categoria_nome' => 'Suprimentos',
                'cor' => 'Preto',
                'descricao' => 'Unidade de imagem fotocondutora (50.000 páginas).',
            ],

            // --- PERIFÉRICOS & OUTROS ---
            [
                'nome' => 'Leitor QuickScan QW2100',
                'fabricante' => 'Datalogic',
                'categoria_nome' => 'Periféricos',
                'descricao' => 'Leitor de código de barras imager 1D com interface USB.',
            ],
            [
                'nome' => 'Scanner ScanSnap iX1600',
                'fabricante' => 'Fujitsu',
                'categoria_nome' => 'Periféricos',
                'descricao' => 'Scanner de documentos duplex de alta velocidade com tela touch.',
            ],
            [
                'nome' => 'Switch 24 Portas Gigabit',
                'fabricante' => 'TP-Link',
                'categoria_nome' => 'Periféricos',
                'descricao' => 'Switch rackmount para infraestrutura de rede.',
            ],
        ];

        foreach ($itens as $dados) {
            // Busca a categoria pelo nome definido no array
            $categoria = Categoria::where('nome', $dados['categoria_nome'])->first();

            // Se a categoria não existir, cria uma para não quebrar o seeder
            if (!$categoria) {
                $categoria = Categoria::create(['nome' => $dados['categoria_nome']]);
            }

            // Remove o nome temporário e adiciona o ID real
            unset($dados['categoria_nome']);
            $dados['categoria_id'] = $categoria->id;

            Catalogo::create($dados);
        }
    }
}