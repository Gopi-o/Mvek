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
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('discipline')->default('Не указана');
            $table->foreignId('type_id')->constrained('tournament_types'); // Формат сетки
            $table->foreignId('owner_id')->constrained('users');
            $table->enum('status', ['draft', 'active', 'completed'])->default('draft');
            $table->integer('max_teams')->nullable();
            $table->integer('players_per_team')->default(1);
            $table->boolean('is_team_based')->default(false);
            $table->string('category')->default('gaming');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};
