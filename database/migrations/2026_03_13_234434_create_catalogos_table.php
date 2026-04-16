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
            $table->string('tipo')->default('equipamento'); // equipamento ou insumo
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->foreignId('subcategoria_id')->nullable()->constrained('subcategorias')->onDelete('set null');

            // Atributos técnicos dinâmicos
            $table->string('processador')->nullable(); // Computadores
            $table->string('memoria')->nullable();     // Computadores
            $table->string('geracao')->nullable();     // Computadores
            $table->string('voltagem')->nullable();    // Nobreaks
            $table->string('tipo_impressora')->nullable(); // Mono/Color
            $table->string('situacao_insumo')->nullable(); // Original/Compatível/Recondicionado
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
