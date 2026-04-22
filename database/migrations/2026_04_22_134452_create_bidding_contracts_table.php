<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bidding_contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->nullable(); // Ex: 2021.08.02.01-19
            $table->string('pregao_number');               // Ex: 001/2026
            $table->string('uasg_organ');                  // Ex: AMT Caucaia
            $table->text('object');                        // Descrição do objeto

            // Controle Financeiro e Prazos
            $table->decimal('max_monthly_billing', 12, 2)->default(0); // Teto: R$ 7.432,50
            $table->integer('validity_months');                        // Vigência em meses
            $table->integer('delivery_deadline');                      // Dias para entrega

            // Datas de Vigência
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Configurações e Notas
            $table->boolean('accepts_used')->default(true);
            $table->boolean('requires_office')->default(true);
            $table->text('maintenance_notes')->nullable();
            $table->text('addendum_summary')->nullable(); // Resumo dos aditivos

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bidding_contracts');
    }
};
