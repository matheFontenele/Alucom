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
        if (!Schema::hasTable('guia_monitores')) {

            Schema::create('guia_monitores', function (Blueprint $table) {
                $table->id();
                $table->string('fabricante');
                $table->string('marca_modelo');
                $table->string('foto')->nullable();
                $table->string('polegadas');
                $table->text('obs')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guia_monitors');
    }
};
