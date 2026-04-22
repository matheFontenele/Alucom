<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bidding_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidding_contract_id')->constrained()->onDelete('cascade');

            // Categorização da Planilha
            $table->string('lote')->nullable();      // Ex: LOTE I
            $table->string('item_type')->nullable(); // Ex: TIPO II

            // Descrição e Valores
            $table->text('item_description');
            $table->decimal('unit_price', 10, 2)->default(0);      // R$ Unit Mês
            $table->integer('contracted_quantity')->default(0);    // Quantidade total em contrato
            $table->integer('delivered_quantity')->default(0);     // Quantidade entregue/faturando

            // Especificações Técnicas (Opcionais)
            $table->string('min_cpu')->nullable();
            $table->integer('min_ram')->nullable();
            $table->integer('min_storage')->nullable();
            $table->string('os_required')->nullable();

            // Referência de Faturamento (Upgrade de Item)
            $table->foreignId('billing_reference_id')->nullable()
                ->constrained('bidding_items')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bidding_items');
    }
};
