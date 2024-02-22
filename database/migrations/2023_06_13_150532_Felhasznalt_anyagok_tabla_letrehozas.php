<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('felhasznalt_anyag', function (Blueprint $table){
            $table->unsignedBigInteger('Munka_ID');
            $table->unsignedBigInteger('Anyag_ID');
            $table->primary(['Munka_ID', 'Anyag_ID']);

            $table->foreign('Munka_ID')->references('Munka_ID')->on('munka');
            $table->foreign('Anyag_ID')->references('Anyag_ID')->on('anyag');
            $table->integer('Mennyiseg');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('felhasznalt_anyag');
    }
};


