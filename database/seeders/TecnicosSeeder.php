<?php

namespace Database\Seeders;

use App\Models\Tecnicos;
use Illuminate\Database\Seeder;

class TecnicosSeeder extends Seeder
{
    public function run(): void
    {
        $tecnicos = [
            ['nome' => 'JF Impressoras', 'regiao' => 'Belém - PA', 'tipo' => 'Impressoras', 'preco_atendimento' => 150, 'contato' => '(91) 98888-7777'],
            ['nome' => 'TechSolutions', 'regiao' => 'Ananindeua - PA', 'tipo' => 'Informatica', 'preco_atendimento' => 120, 'contato' => 'suporte@tech.com'],
            ['nome' => 'Marcos Vinícius', 'regiao' => 'Castanhal - PA', 'tipo' => 'Informatica', 'preco_atendimento' => 200, 'contato' => '(91) 99999-0000'],
            ['nome' => 'Norte Copiadoras', 'regiao' => 'Manaus - AM', 'tipo' => 'Impressoras', 'preco_atendimento' => 180, 'contato' => 'vendas@norte.com'],
            ['nome' => 'Fortal Tech', 'regiao' => 'Fortaleza - CE', 'tipo' => 'Informatica', 'preco_atendimento' => 100, 'contato' => '(85) 3222-1111'],
            ['nome' => 'Ceará Suprimentos', 'regiao' => 'Fortaleza - CE', 'tipo' => 'Impressoras', 'preco_atendimento' => 130, 'contato' => '(85) 98765-4321'],
            ['nome' => 'Floripa Infor', 'regiao' => 'Florianópolis - SC', 'tipo' => 'Informatica', 'preco_atendimento' => 160, 'contato' => 'contato@floripa.in'],
            ['nome' => 'Sul Service', 'regiao' => 'Caxias do Sul - RS', 'tipo' => 'Impressoras', 'preco_atendimento' => 210, 'contato' => '(54) 3020-4050'],
            ['nome' => 'Brasília Cloud', 'regiao' => 'Brasília - DF', 'tipo' => 'Informatica', 'preco_atendimento' => 250, 'contato' => 'gov@bsbcloud.com'],
            ['nome' => 'Recife Digitech', 'regiao' => 'Recife - PE', 'tipo' => 'Impressoras', 'preco_atendimento' => 145, 'contato' => '(81) 91234-5678'],
        ];

        foreach ($tecnicos as $t) {
            Tecnicos::create($t);
        }
    }
}