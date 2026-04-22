<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Criar a tabela principal de Rotas
        Schema::create('rotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('veiculo_id')->constrained('veiculos');
            $table->foreignId('estoque_origem_id')->constrained('estoques');
            $table->string('cidade_destino');
            $table->string('estado_destino');
            $table->date('data_saida');
            $table->date('previsao_chegada');
            $table->string('status')->default('Pendente'); 
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        // 2. Criar a tabela pivô de Carregamento (Muitas requisições em uma rota)
        // Colocamos aqui para garantir que ela só seja criada após a tabela 'rotas' existir
        Schema::create('rota_requisicao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rota_id')->constrained('rotas')->onDelete('cascade');
            $table->foreignId('requisicao_id')->constrained('requisicoes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Ordem inversa: apaga a tabela dependente primeiro
        Schema::dropIfExists('rota_requisicao');
        Schema::dropIfExists('rotas');
    }
};