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
        Schema::create('bidding_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidding_contract_id')->constrained()->onDelete('cascade');
            $table->string('item_description');    // Ex: Item 1 - Notebooks
            $table->bigInteger('quantity');          // 30

            // Requisitos Mínimos (Para consulta no banco)
            $table->string('min_cpu');             // Ex: i5 12th / Ryzen 5
            $table->integer('min_ram');            // 16
            $table->integer('min_storage');        // 512
            $table->string('os_required');         // Windows 11 Pro

            // Modelo de Referência (O que você definiu: Lenovo V15)
            $table->string('reference_model')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidding_items');
    }
};
