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
        Schema::create('rota_requisicao', function (Blueprint $table) {
            $table->id();
            // Chave estrangeira para a Rota
            $table->foreignId('rota_id')->constrained('rotas')->onDelete('cascade');
            // Chave estrangeira para a Requisição
            $table->foreignId('requisicao_id')->constrained('requisicoes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rota_requisicao');
    }
};
