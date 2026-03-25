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
            ['nome' => 'MTE - Fortaleza', 'cidade' => 'Fortaleza', 'estado' => 'CE', 'cnpj' => '00.394.460/0080-90'],
            ['nome' => 'MTE - Porto Alegre', 'cidade' => 'Porto Alegre', 'estado' => 'RS', 'cnpj' => '00.394.460/0055-10'],
            ['nome' => 'MTE - João Pessoa', 'cidade' => 'João Pessoa', 'estado' => 'PB', 'cnpj' => '00.394.460/0090-11'],
            ['nome' => 'MTE - Pará', 'cidade' => 'Belém', 'estado' => 'PA', 'cnpj' => '00.394.460/0012-33'],
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
        $fms = Clientes::create([
            'nome' => 'Fundo Municipal de Saude',
            'tipo' => 'ministerio',
            'cnpj' => '00.394.445/0001-34',
            'contrato' => 'ZapLoc',
            'estado' => 'PB',
            'cidade' => 'João Pessoa',
            'endereco' => 'Esplanada dos Ministérios, Bloco L',
            'sla' => ['Atendimento' => 4, 'Insumos' => 12, 'Substituição' => 24, 'Tipo' => 'Original'],
        ]);

        $unidadesMEC = [
            ['nome' => 'Centro de Doenças Raras', 'cidade' => 'João Pessoa', 'estado' => 'PB', 'cnpj' => '00.394.445/0020-11'],
        ];

        foreach ($unidadesMEC as $u) {
            Clientes::create(array_merge($u, [
                'tipo' => 'unidade',
                'parent_id' => $fms->id,
                'contrato' => 'Moreia',
                'endereco' => 'Campus Universitário Principal',
                'sla' => ['Atendimento' => 6, 'Insumos' => 24, 'Substituição' => 48, 'Tipo' => 'Original']
            ]));
        }
    }
}