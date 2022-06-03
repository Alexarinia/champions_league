<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_match_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->nullOnDelete();
            $table->foreignId('game_match_id')->cascadeOnDelete();
            $table->boolean('host');
            $table->smallInteger('goals')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_match_team');
    }
};
