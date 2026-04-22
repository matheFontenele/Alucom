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
        Schema::create('catalogo', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('fabricante');
            
            // Define se é 'equipamento' ou 'insumo'
            $table->string('tipo')->default('equipamento');

            // Relacionamento com Categorias e Subcategorias
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            // Recomendo adicionar subcategoria_id se você for usar a estrutura do seu Seeder
            $table->foreignId('subcategoria_id')->nullable()->constrained('subcategorias')->onDelete('set null');

            // --- Atributos Condicionais ---
            
            // Para Computadores (Micro, Notebook, Thinkcentre)
            $table->string('processador')->nullable();
            $table->string('memoria')->nullable();
            $table->string('geracao')->nullable();

            // Para Nobreaks (Estabilizadores, Transformadores)
            $table->string('voltagem')->nullable();

            // Para Impressoras (Multifuncionais, Térmicas, etc.)
            // Sugestão: 'Mono' ou 'Color'
            $table->string('tipo_impressora')->nullable(); 

            // --- Campos Gerais ---
            $table->string('tipo_papel')->nullable();
            $table->string('cor')->nullable();
            $table->text('descricao')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo');
    }
};