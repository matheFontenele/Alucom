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
            $table->string('nome'); // Ex: Ecosys M3655idn
            $table->string('fabricante'); // Ex: Kyocera
            $table->string('categoria'); // Impressora, Nobreak, etc.

            // Campos específicos
            $table->string('tipo_papel')->nullable(); // A3, A4
            $table->string('voltagem')->nullable(); // Bivolt, 110v, 220v
            $table->string('cor')->nullable(); // Preto, Ciano, etc.
            $table->string('descricao')->nullable(); // Para Periféricos/Outros

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
