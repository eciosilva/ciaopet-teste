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
        Schema::table('pets', function (Blueprint $table) {
            $table->foreignId('tutor_id')
                  ->nullable()
                  ->after('observacoes')
                  ->constrained('users')
                  ->onDelete('set null');
                  
            // Add index for better performance on queries
            $table->index(['tutor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropForeign(['tutor_id']);
            $table->dropIndex(['tutor_id']);
            $table->dropColumn('tutor_id');
        });
    }
};
