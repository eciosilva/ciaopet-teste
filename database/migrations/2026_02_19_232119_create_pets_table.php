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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            
            // Required fields
            $table->string('nome');
            $table->string('especie');
            
            // Optional fields
            $table->string('raca')->nullable();
            $table->enum('genero', ['Macho', 'Fêmea', 'Desconhecido'])->nullable();
            $table->date('data_nascimento')->nullable();
            $table->decimal('peso', 5, 2)->nullable()->comment('Peso em kg');
            $table->string('numero_microchip')->nullable()->unique();
            $table->text('observacoes')->nullable();
            
            // Timestamps and soft deletes
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better performance
            $table->index(['especie']);
            $table->index(['genero']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
