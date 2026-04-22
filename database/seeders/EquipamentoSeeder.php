<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GuiaAdi;

class EquipamentoSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/equipamentos.csv');
        
        // Abre o arquivo para leitura
        if (($handle = fopen($file, "r")) !== FALSE) {
            $index = 0;
            
            while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                $index++;
                if ($index === 1) continue; // Pula o cabeçalho

                // LOG DE DEBUG: Isso vai mostrar se ele está lendo o modelo corretamente
                $modelo = $data[3] ?? 'Vazio';
                $this->command->info("Importando: $modelo");

                // Proteção: Se a marca/modelo estiver vazio, pula a linha
                if (empty($data[3])) continue;

                GuiaAdi::create([
                    'familia'      => $data[0] ?? null,
                    'fabricante'   => $data[1] ?? null,
                    'foto'         => $data[2] ?? null,
                    'marca_modelo' => $data[3] ?? null,
                    'toner'        => $data[4] ?? null,
                    'rendimento'   => $data[5] ?? null,
                    'ppm'          => $data[6] ?? null,
                    'papel'        => $data[7] ?? null,
                    'voltagem'     => $data[8] ?? null,
                    // Removemos as quebras de linha internas para não quebrar o layout
                    'funcoes'      => isset($data[9]) ? str_replace(["\r", "\n"], ' ', $data[9]) : null,
                    'resolucao'    => isset($data[10]) ? str_replace(["\r", "\n"], ' ', $data[10]) : null,
                    'memoria'      => $data[11] ?? null,
                    'hdd'          => $data[12] ?? null,
                    'duplex'       => $data[13] ?? null,
                    'cap_papel'    => $data[14] ?? null,
                    'pecas'        => $data[15] ?? null,
                    'cartao_sd'    => $data[16] ?? null,
                    'ndd'          => $data[17] ?? null,
                    'obs'          => isset($data[18]) ? trim($data[18]) : null,
                ]);
            }
            fclose($handle);
        }
    }
}