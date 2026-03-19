<?php

namespace Database\Seeders;

use App\Models\Clientes;
use Illuminate\Database\Seeder;

class ClientesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ministério do Trabalho (Pai) + 4 Unidades
        $mte = Clientes::create([
            'nome' => 'Ministério do Trabalho e Emprego - MTE',
            'tipo' => 'ministerio',
            'cnpj' => '00.394.460/0001-41',
            'contrato' => 'IP',
            'estado' => 'DF',
            'cidade' => 'Brasília',
            'endereco' => 'Esplanada dos Ministérios, Bloco F',
            'sla' => ['Atendimento' => 4, 'Insumos' => 24, 'Substituição' => 48, 'Tipo' => 'Original'],
        ]);

        $unidadesMTE = [
            ['nome' => 'MTE - Gerência Fortaleza', 'cidade' => 'Fortaleza', 'estado' => 'CE', 'cnpj' => '00.394.460/0080-90'],
            ['nome' => 'MTE - Superintendência Porto Alegre', 'cidade' => 'Porto Alegre', 'estado' => 'RS', 'cnpj' => '00.394.460/0055-10'],
            ['nome' => 'MTE - Gerência Maracanaú', 'cidade' => 'Maracanaú', 'estado' => 'CE', 'cnpj' => '00.394.460/0090-11'],
            ['nome' => 'MTE - Unidade Móvel Pará', 'cidade' => 'Belém', 'estado' => 'PA', 'cnpj' => '00.394.460/0012-33'],
        ];

        foreach ($unidadesMTE as $u) {
            Clientes::create(array_merge($u, [
                'tipo' => 'unidade',
                'parent_id' => $mte->id,
                'contrato' => 'Alucom',
                'endereco' => 'Endereço Padrão da Unidade',
                'sla' => ['Atendimento' => 8, 'Insumos' => 48, 'Substituição' => 72, 'Tipo' => 'Compativel']
            ]));
        }

        // 2. Ministério da Educação (Pai) + 4 Unidades
        $mec = Clientes::create([
            'nome' => 'Ministério da Educação - MEC',
            'tipo' => 'ministerio',
            'cnpj' => '00.394.445/0001-34',
            'contrato' => 'ZapLoc',
            'estado' => 'DF',
            'cidade' => 'Brasília',
            'endereco' => 'Esplanada dos Ministérios, Bloco L',
            'sla' => ['Atendimento' => 4, 'Insumos' => 12, 'Substituição' => 24, 'Tipo' => 'Original'],
        ]);

        $unidadesMEC = [
            ['nome' => 'MEC - IFCE Fortaleza', 'cidade' => 'Fortaleza', 'estado' => 'CE', 'cnpj' => '00.394.445/0020-11'],
            ['nome' => 'MEC - UFSC Florianópolis', 'cidade' => 'Florianópolis', 'estado' => 'SC', 'cnpj' => '00.394.445/0030-22'],
            ['nome' => 'MEC - Unifesp SP', 'cidade' => 'São Paulo', 'estado' => 'SP', 'cnpj' => '00.394.445/0040-33'],
            ['nome' => 'MEC - Reitoria UFC', 'cidade' => 'Fortaleza', 'estado' => 'CE', 'cnpj' => '00.394.445/0050-44'],
        ];

        foreach ($unidadesMEC as $u) {
            Clientes::create(array_merge($u, [
                'tipo' => 'unidade',
                'parent_id' => $mec->id,
                'contrato' => 'Moreia',
                'endereco' => 'Campus Universitário Principal',
                'sla' => ['Atendimento' => 6, 'Insumos' => 24, 'Substituição' => 48, 'Tipo' => 'Original']
            ]));
        }
    }
}