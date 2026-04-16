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
            $table->string('nome'); // Modelo
            $table->string('fabricante');
            $table->text('descricao')->nullable();
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->string('subcategoria')->nullable();

            // Atributos Específicos
            $table->string('tipo_papel')->nullable(); // A3, A4...
            $table->string('tipo_impressao')->nullable(); // Mono, Color
            $table->string('voltagem')->nullable();
            $table->string('processador')->nullable();
            $table->string('geracao')->nullable();
            $table->string('memoria')->nullable();
            $table->string('polegadas')->nullable();
            $table->string('cor')->nullable(); // Ciano, Magenta...
            $table->string('tipo_insumo')->nullable(); // Original, Compatível...

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
