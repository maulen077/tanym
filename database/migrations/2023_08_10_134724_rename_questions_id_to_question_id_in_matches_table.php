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
        Schema::table('question_id_in_matches', function (Blueprint $table) {
            Schema::table('matches', function (Blueprint $table) {
                $table->renameColumn('question_id', 'questions_id');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('question_id_in_matches', function (Blueprint $table) {
            $table->renameColumn('questions_id', 'question_id');
        });
    }
};
