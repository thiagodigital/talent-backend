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
        Schema::create('collaborator_profile_trait', function (Blueprint $table) {
            $table->id();
            $table->uuid('collaborator_id');
            $table->uuid('profile_trait_id');
            $table->unsignedTinyInteger('score'); // valores de 0 a 100
            $table->timestamps();

            $table->foreign('collaborator_id')->references('id')->on('collaborators')->onDelete('cascade');
            $table->foreign('profile_trait_id')->references('id')->on('profile_traits')->onDelete('cascade');

            $table->unique(['collaborator_id', 'profile_trait_id'], 'collab_trait_unique'); // garantir 1 traço por colaborador
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collaborator_profile_trait');
    }
};
