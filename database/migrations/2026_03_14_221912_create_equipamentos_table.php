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

            // Relacionamentos
            $table->foreignId('categoria_id')->constrained()->onDelete('cascade');
            $table->foreignId('subcategoria_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->foreignId('estoque_id')->nullable()->constrained('estoques')->onDelete('set null');
            $table->foreignId('catalogo_id')->nullable()->constrained('catalogo')->onDelete('set null');

            // Identificadores e Classificação
            $table->string('tipo')->default('equipamento'); // equipamento ou insumo
            $table->string('nome');
            $table->string('tombo')->unique()->nullable();
            $table->string('serial')->nullable();

            // Status e Rastreio
            // Status: Alugado, Devolução, Disponivel, Manutenção, Reservado
            $table->string('status')->default('Disponivel');

            // Situação: No Cliente, Em Rota, Aguardando Coleta, Novo, Usado
            $table->string('situacao')->nullable();
            $table->string('condicao')->nullable()->default('Novo');

            // Atributos Específicos
            $table->string('cor')->nullable(); // Para Toners/Tintas (Preto, Ciano, etc.)
            $table->text('observacoes')->nullable();

            // Datas
            $table->timestamp('data_movimentacao')->nullable();
            $table->timestamps();

            // Índices para performance (Opcional, mas recomendado para PostgreSQL)
            $table->index(['tipo', 'status']);
            $table->index('tombo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipamentos');
    }
};
