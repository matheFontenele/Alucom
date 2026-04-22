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
            ],
            [
                'nome' => 'Latitude 3440',
                'fabricante' => 'Dell',
                'categoria_nome' => 'Computadores',
                'subcategoria' => 'Notebook',
                'processador' => 'Intel Core i5',
                'memoria' => '8GB',
                'geracao' => '12ª Geração',
                'tipo' => 'equipamento',
            ],
            [
                'nome' => 'Precision 3581',
                'fabricante' => 'Dell',
                'categoria_nome' => 'Computadores',
                'subcategoria' => 'Workstation',
                'processador' => 'Intel Core i7',
                'memoria' => '32GB',
                'geracao' => '13ª Geração',
                'tipo' => 'equipamento',
            ],
            [
                'nome' => 'Vostro 3520',
                'fabricante' => 'Dell',
                'categoria_nome' => 'Computadores',
                'subcategoria' => 'Notebook',
                'processador' => 'Intel Core i3',
                'memoria' => '8GB',
                'geracao' => '12ª Geração',
                'tipo' => 'equipamento',
            ],
            [
                'nome' => 'ProBook 440 G9',
                'fabricante' => 'HP',
                'categoria_nome' => 'Computadores',
                'subcategoria' => 'Notebook',
                'processador' => 'Intel Core i5',
                'memoria' => '16GB',
                'geracao' => '12ª Geração',
                'tipo' => 'equipamento',
            ],
            [
                'nome' => 'EliteBook 640',
                'fabricante' => 'HP',
                'categoria_nome' => 'Computadores',
                'subcategoria' => 'Notebook',
                'processador' => 'Intel Core i7',
                'memoria' => '16GB',
                'geracao' => '13ª Geração',
                'tipo' => 'equipamento',
            ],
            [
                'nome' => 'ThinkPad E14 Gen 5',
                'fabricante' => 'Lenovo',
                'categoria_nome' => 'Computadores',
                'subcategoria' => 'Notebook',
                'processador' => 'AMD Ryzen 5',
                'memoria' => '16GB',
                'geracao' => '7000 Series',
                'tipo' => 'equipamento',
            ],

            // 13-18: Impressoras e Scanners
            [
                'nome' => 'LaserJet Pro M404dw',
                'fabricante' => 'HP',
                'categoria_nome' => 'Impressoras',
                'subcategoria' => 'Laser',
                'tipo_impressora' => 'Mono',
                'tipo_papel' => 'A4',
                'tipo' => 'equipamento',
            ],
            [
                'nome' => 'EcoTank L3250',
                'fabricante' => 'Epson',
                'categoria_nome' => 'Impressoras',
                'subcategoria' => 'Tanque de Tinta',
                'tipo_impressora' => 'Color',
                'tipo_papel' => 'A4',
                'tipo' => 'equipamento',
            ],
            [
                'nome' => 'Ecosys P3145dn',
                'fabricante' => 'Kyocera',
                'categoria_nome' => 'Impressoras',
                'subcategoria' => 'Laser',
                'tipo_impressora' => 'Mono',
                'tipo_papel' => 'A4',
                'tipo' => 'equipamento',
            ],
            [
                'nome' => 'ADS-2200',
                'fabricante' => 'Brother',
                'categoria_nome' => 'Impressoras',
                'subcategoria' => 'Scanner',
                'tipo' => 'equipamento',
            ],
            [
                'nome' => 'HL-L2360DW',
                'fabricante' => 'Brother',
                'categoria_nome' => 'Impressoras',
                'subcategoria' => 'Laser',
                'tipo_impressora' => 'Mono',
                'tipo_papel' => 'A4',
                'tipo' => 'equipamento',
            ],
            [
                'nome' => 'WorkForce ES-400',
                'fabricante' => 'Epson',
                'categoria_nome' => 'Impressoras',
                'subcategoria' => 'Scanner',
                'tipo' => 'equipamento',
            ],

            // 19-24: Monitores e Periféricos
            [
                'nome' => 'Monitor 223V5LHSW',
                'fabricante' => 'Philips',
                'categoria_nome' => 'Monitores',
                'subcategoria' => 'Monitor',
                'polegadas' => '21.5"',
                'tipo' => 'equipamento',
            ],
            [
                'nome' => 'Monitor P2422H',
                'fabricante' => 'Dell',
                'categoria_nome' => 'Monitores',
                'subcategoria' => 'Monitor',
                'polegadas' => '24"',
                'tipo' => 'equipamento',
            ],
            [
                'nome' => 'Teclado K120',
                'fabricante' => 'Logitech',
                'categoria_nome' => 'Periféricos',
                'subcategoria' => 'Teclado',
                'tipo' => 'equipamento',
            ],
            [
                'nome' => 'Mouse M90',
                'fabricante' => 'Logitech',
                'categoria_nome' => 'Periféricos',
                'subcategoria' => 'Mouse',
                'tipo' => 'equipamento',
            ],
            [
                'nome' => 'Headset LifeChat LX-3000',
                'fabricante' => 'Microsoft',
                'categoria_nome' => 'Periféricos',
                'subcategoria' => 'Fone',
                'tipo' => 'equipamento',
            ],
            [
                'nome' => 'Webcam C920s',
                'fabricante' => 'Logitech',
                'categoria_nome' => 'Periféricos',
                'subcategoria' => 'Webcam',
                'tipo' => 'equipamento',
            ],

            // 25-30: Energia e Insumos variados
            [
                'nome' => 'Nobreak Station II',
                'fabricante' => 'SMS',
                'categoria_nome' => 'Energia',
                'subcategoria' => 'Nobreaks',
                'voltagem' => '115V',
                'tipo' => 'equipamento',
            ],
            [
                'nome' => 'Toner CE285A (85A)',
                'fabricante' => 'HP',
                'categoria_nome' => 'Insumos',
                'subcategoria' => 'Tinta/Toner',
                'cor' => 'Preto',
                'tipo_insumo' => 'Original',
                'tipo' => 'insumo',
            ],
            [
                'nome' => 'Garrafa de Tinta T544120',
                'fabricante' => 'Epson',
                'categoria_nome' => 'Insumos',
                'subcategoria' => 'Tinta/Toner',
                'cor' => 'Preto',
                'tipo_insumo' => 'Original',
                'tipo' => 'insumo',
            ],
            [
                'nome' => 'Cilindro DR-2340',
                'fabricante' => 'Brother',
                'categoria_nome' => 'Insumos',
                'subcategoria' => 'Peças',
                'tipo_insumo' => 'Original',
                'tipo' => 'insumo',
            ],
            [
                'nome' => 'Kit de Manutenção MK-3172',
                'fabricante' => 'Kyocera',
                'categoria_nome' => 'Insumos',
                'subcategoria' => 'Peças',
                'tipo_insumo' => 'Original',
                'tipo' => 'insumo',
            ],
            [
                'nome' => 'Bateria Estacionária 12V',
                'fabricante' => 'Moura',
                'categoria_nome' => 'Energia',
                'subcategoria' => 'Bateria',
                'voltagem' => '12V',
                'tipo' => 'insumo',
            ],
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
