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
        Schema::create('collaborator_evaluations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('collaborator_id');

            // Campos principais vindos do JSON
            $table->longText('summary'); // texto grande
            $table->longText('proficience'); 
            $table->longText('align'); 

            // JSONs estruturados
            $table->json('assets')->nullable(); // { "movies": [...], "books": [...] }
            $table->json('questions')->nullable(); // lista de 7 perguntas

            $table->unsignedTinyInteger('score'); // 0 a 100

            // Controle
            $table->timestamps();

            $table->foreign('collaborator_id')
                ->references('id')
                ->on('collaborators')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collaborator_evaluations');
    }
};
