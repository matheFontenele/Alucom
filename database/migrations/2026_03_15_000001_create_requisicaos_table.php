<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requisicoes', function (Blueprint $table) {
            $table->id(); 
            $table->string('oficio')->default('Sem Oficio');
            $table->string('solicitante'); 
            $table->date('data_solicitacao');
            $table->date('previsao_envio')->nullable();
            $table->enum('envio', ['Coleta', 'Rota', 'Transportadora']);
            $table->string('nfe')->default('Sem NF');

            // Relacionamentos
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('catalogo_id')->constrained('catalogo'); 
            $table->foreignId('estoque_id')->constrained('estoques');

            $table->string('estado');
            $table->string('cidade');

            $table->enum('etiqueta', ['Alucom', 'Moreia', 'IP', 'ZapLoc']);
            $table->integer('quantidade');
            $table->enum('tipo_solicitacao', ['Substituição', 'Novo']);
            $table->string('patrimonio_substituido')->nullable();
            $table->string('patrimonio_novo')->nullable();

            // Campos de Separação
            $table->integer('quantidade_separada')->nullable();
            $table->date('data_separacao')->nullable();
            $table->string('separado_por')->nullable(); 
            $table->boolean('baixa_sistema')->default(false);
            $table->text('observacao_separacao')->nullable();
            $table->string('situacao')->default('Pendente'); 

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisicoes');
    }
};