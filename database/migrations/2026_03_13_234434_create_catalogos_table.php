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
            
            // Nova coluna para diferenciar Equipamento de Insumo
            $table->string('tipo')->default('equipamento'); 

            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->string('tipo_papel')->nullable();
            $table->string('voltagem')->nullable();
            $table->string('cor')->nullable();
            $table->string('descricao')->nullable();
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