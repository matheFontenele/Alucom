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
        Schema::create('bidding_accessories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidding_contract_id')->constrained()->onDelete('cascade');
            $table->string('name');                // Wi-Fi, Mouse, etc.
            $table->boolean('included')->default(false);
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidding_accessories');
    }
};
