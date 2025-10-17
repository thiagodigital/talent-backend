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
        Schema::create('collaborator_skill_evaluations', function (Blueprint $table) {

            // 💡 CHAVE UUID: Deve usar foreignUuid para referenciar Collaborator
            $table->foreignUuid('collaborator_id')
                  ->constrained('collaborators')
                  ->onDelete('cascade');

            // 💡 CHAVE NUMÉRICA PADRÃO: Deve usar foreignId para referenciar ProfileEvaluation
            $table->foreignId('evaluation_id')
                  ->constrained('profile_evaluations')
                  ->onDelete('cascade');

            // 💡 DEFINIÇÃO DA CHAVE PRIMÁRIA COMPOSTA (APÓS a definição das colunas)
            $table->primary(['collaborator_id', 'evaluation_id'], 'collaborator_skill_primary');

            // Colunas da Pivot Table (Dados Extras)
            $table->string('type');
            $table->unsignedSmallInteger('value');
            $table->string('position')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collaborator_skill_evaluations');
    }
};
