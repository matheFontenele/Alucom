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
        Schema::create('equipamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained();
            $table->foreignId('subcategoria_id')->nullable()->constrained();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes');
            $table->foreignId('estoque_id')->nullable()->constrained('estoques');
            $table->foreignId('catalogo_id')->nullable()->constrained('catalogo')->onDelete('set null');

            // Identificadores
            $table->string('tipo')->default('equipamento'); // equipamento ou insumo
            $table->string('nome');
            $table->string('tombo')->nullable();
            $table->string('serial')->unique()->nullable();

            // Status e Rastreio
            $table->string('status'); // Alugado, Devolução, Disponivel, Manutenção, Reservado
            $table->string('situacao')->nullable(); // No Cliente, Em Rota, Aguardando Coleta, etc.

            // Campos que você pediu: Cor e Observações
            $table->string('cor')->nullable(); // Para Toners/Tintas
            $table->text('observacoes')->nullable(); // Notas gerais ou cor detalhada

            $table->timestamp('data_movimentacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipamentos');
    }
};
