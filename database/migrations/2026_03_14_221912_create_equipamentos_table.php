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
            $table->foreignId('categoria_id')->constrained();
            $table->foreignId('subcategoria_id')->nullable()->constrained();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes');
            $table->foreignId('estoque_id')->nullable()->constrained('estoques');
            $table->string('tombo', 5)->unique();
            $table->string('nome');
            $table->string('serial')->unique()->nullable();
            $table->string('situacao');
            $table->timestamp('data_movimentacao')->nullable();
            $table->timestamps();
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
