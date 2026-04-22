<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guia_adis', function (Blueprint $table) {
            $table->id();
            $table->string('fabricante'); // Kyocera, Epson...
            $table->string('marca_modelo'); // Nome completo
            $table->string('familia')->nullable(); // Multifuncional Laser, Mono, A3...
            $table->string('foto')->nullable(); // Caminho da imagem
            $table->string('toner')->nullable(); // Modelo do toner
            $table->string('rendimento')->nullable(); // Ex: 15.000 páginas
            $table->string('ppm')->nullable(); // Apenas números inteiros
            $table->string('papel')->nullable(); // A4, A3...
            $table->string('voltagem')->nullable(); // 110V, 220V...
            $table->json('funcoes')->nullable(); // Array: ['Impressão', 'Cópia', 'Scan']
            $table->string('resolucao')->nullable(); // 1200x1200dpi
            $table->string('memoria')->nullable(); // 512MB a 1.5GB
            $table->string('hdd')->nullable(); // 1GB ou Não Acompanha
            $table->string('duplex')->nullable(); // Sim ou Não
            $table->string('cap_papel')->nullable(); // 350/800
            $table->text('pecas')->nullable(); // Kits de manutenção
            $table->string('cartao_sd')->nullable(); // Aceita / Não Aceita
            $table->string('ndd')->nullable(); // Aceita solução embarcada / Não aceita
            $table->text('obs')->nullable(); // Textos longos de observação
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guia_adis');
    }
};