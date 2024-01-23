<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ugyfel', function (Blueprint $table){
            $table->id('Ugyfel_ID');
            $table->string('Nev');
            $table->string('Email');
            $table->string('Telefonszam');
            $table->string('Szamlazasi_Nev');
            $table->string('Szamlazasi_Cim');
            $table->string('Adoszam')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ugyfel');
    }
};
