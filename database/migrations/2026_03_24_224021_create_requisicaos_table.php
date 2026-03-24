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
        Schema::create('requisicoes', function (Blueprint $table) {
            $table->id(); // ID único e automático
            $table->string('oficio')->default('Sem Oficio');
            $table->string('solicitante'); // Futuramente vinculado a User
            $table->date('data_solicitacao');
            $table->date('previsao_envio')->nullable();
            $table->enum('envio', ['Coleta', 'Rota', 'Transportadora']);
            $table->string('nfe')->default('Sem NF');

            // Relacionamentos
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('catalogo_id')->constrained('catalogo'); // Equipamento/Insumo

            // Dados automáticos via JS ou Model
            $table->string('estado');
            $table->string('cidade');

            $table->enum('etiqueta', ['Alucom', 'Moreia', 'IP', 'ZapLock']);
            $table->integer('quantidade');
            $table->enum('tipo_solicitacao', ['Substituição', 'Novo']);
            $table->string('patrimonio_substituido')->nullable(); // Ativo se for Substituição

            // Campos de Separação (Sua nova aba)
            $table->integer('quantidade_separada')->nullable();
            $table->date('data_separacao')->nullable();
            $table->string('separado_por')->nullable(); // Usuários com função estoque
            $table->boolean('baixa_sistema')->default(false);
            $table->text('observacao_separacao')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisicaos');
    }
};
