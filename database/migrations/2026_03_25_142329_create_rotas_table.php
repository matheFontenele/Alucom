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
        Schema::create('rotas', function (Blueprint $table) {
            $table->id();
            // Relaciona com Users (filtrando por quem é motorista no sistema)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('veiculo_id')->constrained('veiculos');
            $table->foreignId('estoque_origem_id')->constrained('estoques');

            $table->string('cidade_destino');
            $table->string('estado_destino');
            $table->date('data_saida');
            $table->date('previsao_chegada');

            $table->string('status')->default('Pendente'); // Pendente, Em Rota, Entregue
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        // Tabela para o "Carregamento" (Muitas requisições em uma rota)
        Schema::create('rota_requisicao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rota_id')->constrained('rotas')->onDelete('cascade');
            $table->foreignId('requisicao_id')->constrained('requisicoes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rotas');
    }
};
