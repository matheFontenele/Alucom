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
        Schema::create('movimentacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipamento_id')->constrained('equipamentos');
            $table->enum('tipo', ['Devolução', 'Aluguel', 'Manutenção', 'Liberação', 'Substituição', 'Reservado']);
            $table->string('situacao')->nullable();
            $table->string('origem');
            $table->string('destino');
            $table->dateTime('data_movimentacao');
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentacoes');
    }
};
