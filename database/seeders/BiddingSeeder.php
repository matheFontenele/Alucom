<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BiddingContract;
use App\Models\BiddingItem;
use App\Models\BiddingAccessory;

class BiddingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Criar o Contrato consolidado (Sem extension_years que causava o erro)
        $contract = BiddingContract::create([
            'contract_number'     => '2021.08.02.01-19', // Número da ARP/Contrato
            'pregao_number'       => '001/2026',
            'uasg_organ'          => 'Conselho Regional de Psicologia - 8ª Região (CRP-PR)',
            'object'              => 'Locação de equipamentos de informática (notebooks) para as(os) trabalhadoras(es) do CRP-PR...',
            'max_monthly_billing' => 15000.00, // Teto mensal de faturamento (Exemplo)
            'validity_months'     => 12,
            'delivery_deadline'   => 30,
            'accepts_used'        => true,
            'requires_office'     => true,
            'start_date'          => now(),
            'end_date'            => now()->addMonths(12),
        ]);

        // 2. Criar o Item principal seguindo os novos campos de faturamento
        BiddingItem::create([
            'bidding_contract_id' => $contract->id,
            'lote'                => 'LOTE I',
            'item_type'           => 'TIPO I',
            'item_description'    => 'Notebooks e licenças (Lenovo V15 Gen 5) - Intel Core i5 12ª/13ª ou AMD Ryzen 5, 16GB RAM, 512GB SSD',
            'unit_price'          => 450.00,        // R$ Unit Mês (Valor fictício para o seeder)
            'contract_quantity'   => 30,            // Quantidade total contratada
            'delivered_quantity'  => 15,            // Quantidade entregue (para testar o cálculo de faturamento)
            'min_cpu'             => 'Intel Core i5',
            'min_ram'             => 16,
            'min_storage'         => 512,
            'os_required'         => 'Windows 11 Pro',
        ]);

        // 3. Criar Acessórios (Certifique-se de que a tabela e o model existem)
        $acessorios = [
            ['name' => 'Mouse USB Óptico', 'included' => true],
            ['name' => 'Teclado ABNT2', 'included' => true],
            ['name' => 'Cabo de Força Padrão NBR', 'included' => true],
            ['name' => 'Wi-Fi Integrado', 'included' => true],
            ['name' => 'Webcam HD', 'included' => true],
        ];

        foreach ($acessorios as $acessorio) {
            BiddingAccessory::create(array_merge($acessorio, [
                'bidding_contract_id' => $contract->id
            ]));
        }
    }
}
