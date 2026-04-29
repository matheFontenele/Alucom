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
        Schema::create('guia_computadores', function (Blueprint $table) {
            $table->id();
            $table->string('fabricante');
            $table->string('marca_modelo');
            $table->string('foto')->nullable();
            $table->string('processador');
            $table->string('memoria');
            $table->string('armazenamento'); // Representa HD/SSD
            $table->string('geracao');
            $table->text('obs')->nullable();
            $table->timestamps();
        });;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guia_computadors');
    }
};
