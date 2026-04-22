<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BiddingContract;
use App\Models\BiddingItem;

class BiddingSeeder extends Seeder
{
    public function run(): void
    {
        $contract = BiddingContract::create([
            'pregao_number' => '001/2026',
            'uasg_organ' => 'Conselho Regional de Psicologia - 8ª Região (CRP-PR)',
            'object' => 'Locação de equipamentos de informática (notebooks) para as(os) trabalhadoras(es) do CRP-PR...',
            'validity_months' => 12,
            'extension_years' => 10,
            'delivery_deadline' => 30,
            'accepts_used' => true,
            'requires_office' => true,
        ]);

        BiddingItem::create([
            'bidding_contract_id' => $contract->id,
            'item_description' => 'Notebooks e licenças (Lenovo V15 Gen 5)',
            'quantity' => 30,
            'min_cpu' => 'Intel Core i5 12ª/13ª ou AMD Ryzen 5',
            'min_ram' => 16,
            'min_storage' => 512,
            'os_required' => 'Windows 11 Pro',
        ]);

        $acessorios = [
            ['name' => 'Mouse USB Óptico', 'included' => true],
            ['name' => 'Teclado ABNT2', 'included' => true],
            ['name' => 'Cabo de Força Padrão NBR', 'included' => true],
            ['name' => 'Wi-Fi Integrado', 'included' => true],
            ['name' => 'Webcam HD', 'included' => true],
        ];

        foreach ($acessorios as $acessorio) {
            \App\Models\BiddingAccessory::create(array_merge($acessorio, [
                'bidding_contract_id' => $contract->id
            ]));
        }
    }
}
