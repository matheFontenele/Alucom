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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            
            // Relacionamento Hierárquico
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->foreign('parent_id')->references('id')->on('clientes')->onDelete('cascade');
            
            // Dados Identificadores
            $table->string('nome');
            $table->enum('tipo', ['ministerio', 'unidade'])->default('unidade');
            $table->string('cnpj');
            
            // Localização e Contrato
            $table->string('contrato')->nullable();
            $table->string('estado', 2);
            $table->string('cidade');
            $table->string('endereco');
            
            // Especificações Técnicas
            $table->json('sla')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};