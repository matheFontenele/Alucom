<?php

namespace App\Imports;

use App\Models\Clientes;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Para usar os nomes das colunas do Excel

class ClientesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Clientes([
            // 'coluna_no_banco' => $row['coluna_no_excel']
            'nome'     => $row['nome'],
            'cnpj'     => $row['cnpj'],
            'estado'   => $row['estado'],
            'cidade'   => $row['cidade'],
            'endereco' => $row['endereco'],
            'contrato' => $row['contrato'],
        ]);
    }
}