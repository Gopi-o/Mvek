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
        Schema::table('rounds', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
        });

        Schema::table('tournament_participants', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
        });

        Schema::table('tournament_participants', function (Blueprint $table) {
            $table->dropForeign(['participant_id']);
            $table->foreign('participant_id')->references('id')->on('participants')->onDelete('cascade');
        });

        Schema::table('match_participants', function (Blueprint $table) {
            $table->dropForeign(['match_id']);
            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
        });

        Schema::table('match_participants', function (Blueprint $table) {
            $table->dropForeign(['participant_id']);
            $table->foreign('participant_id')->references('id')->on('participants')->onDelete('cascade');
        });

        Schema::table('team_members', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->foreign('team_id')->references('id')->on('participants')->onDelete('cascade');
        });

        Schema::table('participants', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('rounds', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->foreign('tournament_id')->references('id')->on('tournaments');
        });

        Schema::table('tournament_participants', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->dropForeign(['participant_id']);
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->foreign('participant_id')->references('id')->on('participants');
        });

        Schema::table('match_participants', function (Blueprint $table) {
            $table->dropForeign(['match_id']);
            $table->dropForeign(['participant_id']);
            $table->foreign('match_id')->references('id')->on('matches');
            $table->foreign('participant_id')->references('id')->on('participants');
        });

        Schema::table('team_members', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->foreign('team_id')->references('id')->on('participants');
        });

        Schema::table('participants', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};
