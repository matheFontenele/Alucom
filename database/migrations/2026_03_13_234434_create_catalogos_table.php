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
            $table->text('descricao')->nullable();
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->string('tipo')->nullable();
            $table->string('subcategoria')->nullable();
            $table->string('processador')->nullable();
            $table->string('memoria')->nullable();
            $table->string('geracao')->nullable();
            $table->string('tipo_impressora')->nullable();
            $table->string('tipo_papel')->nullable();
            $table->string('voltagem')->nullable();
            $table->string('polegadas')->nullable();
            $table->string('cor')->nullable();
            $table->string('tipo_insumo')->nullable();

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
