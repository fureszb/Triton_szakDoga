<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('munka', function (Blueprint $table){
            $table->id('Munka_ID');
            $table->unsignedBigInteger('Megrendeles_ID');
            $table->unsignedBigInteger('Szerelo_ID');
            $table->unsignedBigInteger('Szolgaltatas_ID');
            $table->text('Leiras');
            $table->dateTime('Munkakezdes_Idopontja');
            $table->dateTime('Munkabefejezes_Idopontja');
            $table->timestamps();

            $table->foreign('Megrendeles_ID')->references('Megrendeles_ID')->on('megrendeles');
            $table->foreign('Szerelo_ID')->references('Szerelo_ID')->on('szerelo');
            $table->foreign('Szolgaltatas_ID')->references('Szolgaltatas_ID')->on('szolgaltatas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('munka');
    }
};


