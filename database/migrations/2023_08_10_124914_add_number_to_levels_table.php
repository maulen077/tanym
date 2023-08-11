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
        Schema::table('levels', function (Blueprint $table) {
            Schema::table('levels', function (Blueprint $table) {
                $table->integer('number')->default(1)->after('game_id');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            Schema::table('levels', function (Blueprint $table) {
                $table->dropColumn('number');
            });
        });
    }
};
