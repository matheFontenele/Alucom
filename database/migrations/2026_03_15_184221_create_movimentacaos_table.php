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
        Schema::create('movimentacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipamento_id')->constrained('equipamentos');

            $table->foreignId('cliente_id')->nullable()->constrained('clientes');
            $table->foreignId('estoque_id')->nullable()->constrained('estoques');

            $table->string('tipo'); // Aluguel, Devolução, Substituição
            $table->timestamp('data_movimentacao');
            $table->text('observacao')->nullable(); // Para o alerta da substituição
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentacoes');
    }
};
