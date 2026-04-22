<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bidding_contracts', function (Blueprint $table) {
            $table->id();
            $table->string('pregao_number');       // Ex: 001/2026
            $table->string('uasg_organ');         // Ex: CRP-PR
            $table->text('object');               // Locação de notebooks...

            // Prazos
            $table->integer('validity_months');    // 12 meses
            $table->integer('extension_years');   // 10 anos (conforme lei 14.133)
            $table->integer('delivery_deadline'); // 30 dias (o prazo que você aceitou)

            // Regras de Negócio (Flags)
            $table->boolean('accepts_used')->default(true);
            $table->boolean('requires_office')->default(true);
            $table->boolean('requires_bivolt')->default(false);

            $table->text('maintenance_notes')->nullable(); // Detalhes sobre peças originais
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidding_contracts');
    }
};
